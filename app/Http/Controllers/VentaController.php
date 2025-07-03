<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ventas = Venta::with(['cliente', 'user', 'detalles.producto'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Estadísticas para la vista
        $ventasHoy = Venta::whereDate('fecha', today())->count();
        $montoHoy = Venta::whereDate('fecha', today())->sum('total');
        $ventasMes = Venta::whereMonth('fecha', now()->month)->count();
        $promedioVenta = Venta::avg('total');

        // Obtener vendedores para filtros
        $vendedores = \App\Models\User::whereHas('ventas')->get();
        
        // Obtener clientes activos para el modal de nueva venta
        $clientes = Cliente::where('activo', true)->orderBy('nombres')->get();
        
        $productos = \App\Models\Producto::where('activo', true)
            ->where('stock_actual', '>', 0)
            ->orderBy('nombre')
            ->get();
        
        return view('ventas.index', compact(
            'ventas', 
            'ventasHoy', 
            'montoHoy', 
            'ventasMes', 
            'promedioVenta',
            'vendedores',
            'clientes',
            'productos'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::where('activo', true)->orderBy('nombres')->get();
        $productos = Producto::with(['categoria', 'marca'])
                            ->where('activo', true)
                            ->where('stock_actual', '>', 0)
                            ->orderBy('nombre')
                            ->get();
        
        return view('ventas.create', compact('clientes', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Si es una petición AJAX, usar método específico
        if ($request->ajax() || $request->expectsJson()) {
            return $this->storeAjax($request);
        }

        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'tipo_pago' => 'required|in:efectivo,tarjeta,transferencia',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'observaciones' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Crear la venta
            $venta = new Venta();
            $venta->numero_ticket = 'T' . str_pad(Venta::count() + 1, 6, '0', STR_PAD_LEFT);
            $venta->fecha = Carbon::now();
            $venta->cliente_id = $request->cliente_id;
            $venta->user_id = Auth::id();
            $venta->tipo_pago = $request->tipo_pago ?? 'efectivo';
            $venta->observaciones = $request->observaciones;
            
            $subtotal = 0;

            // Calcular subtotal primero
            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['producto_id']);
                
                // Verificar stock disponible
                if ($producto->stock_actual < $item['cantidad']) {
                    return back()->with('error', "Stock insuficiente para el producto {$producto->nombre}. Stock disponible: {$producto->stock_actual}");
                }
                
                $subtotal += $producto->precio_venta * $item['cantidad'];
            }

            // Calcular totales
            $venta->subtotal = $subtotal;
            $venta->igv = $subtotal * 0.18;
            $venta->total = $venta->subtotal + $venta->igv;
            $venta->save();

            // Procesar cada producto en la venta
            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['producto_id']);

                // Crear detalle de venta
                $detalle = new DetalleVenta();
                $detalle->venta_id = $venta->id;
                $detalle->producto_id = $producto->id;
                $detalle->cantidad = $item['cantidad'];
                $detalle->precio_unitario = $item['precio_unitario'] ?? $producto->precio_venta;
                $detalle->subtotal = $detalle->precio_unitario * $item['cantidad'];
                $detalle->lote = $item['lote'] ?? $producto->lote ?? 'SIN_LOTE';
                $detalle->fecha_vencimiento = $item['fecha_vencimiento'] ?? $producto->fecha_vencimiento ?? now()->addYear();
                $detalle->save();

                // Actualizar stock del producto
                $producto->stock_actual -= $item['cantidad'];
                $producto->save();
            }

            DB::commit();

            return redirect()->route('ventas.index')->with('success', 'Venta registrada exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * 💰 PROCESAR VENTA VÍA AJAX - MÉTODO ESPECÍFICO PARA POS
     */
    public function storeAjax(Request $request)
    {
        try {
            $request->validate([
                'cliente_id' => 'nullable|exists:clientes,id',
                'tipo_pago' => 'required|in:efectivo,tarjeta,transferencia',
                'productos' => 'required|array|min:1',
                'productos.*.producto_id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'subtotal' => 'required|numeric|min:0',
                'igv' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0'
            ]);

            DB::beginTransaction();

            // Verificar stock de todos los productos antes de procesar
            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['producto_id']);
                if ($producto->stock_actual < $item['cantidad']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stock_actual}"
                    ], 400);
                }
            }

            // Crear la venta
            $numeroVenta = 'V' . date('Ymd') . str_pad(Venta::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            $venta = new Venta();
            $venta->numero_ticket = $numeroVenta;
            $venta->fecha = Carbon::now();
            $venta->cliente_id = $request->cliente_id;
            $venta->user_id = Auth::id();
            $venta->tipo_pago = $request->tipo_pago;
            $venta->subtotal = $request->subtotal;
            $venta->igv = $request->igv;
            $venta->total = $request->total;
            $venta->estado = 'completada';
            $venta->observaciones = $request->observaciones;
            $venta->save();

            // Procesar cada producto en la venta
            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['producto_id']);

                // Crear detalle de venta
                $detalle = new DetalleVenta();
                $detalle->venta_id = $venta->id;
                $detalle->producto_id = $producto->id;
                $detalle->cantidad = $item['cantidad'];
                $detalle->precio_unitario = $item['precio_unitario'] ?? $producto->precio_venta;
                $detalle->subtotal = $detalle->precio_unitario * $item['cantidad'];
                $detalle->lote = $item['lote'] ?? $producto->lote ?? 'SIN_LOTE';
                $detalle->fecha_vencimiento = $item['fecha_vencimiento'] ?? $producto->fecha_vencimiento ?? now()->addYear();
                $detalle->save();

                // Actualizar stock del producto
                $producto->stock_actual -= $item['cantidad'];
                $producto->save();
            }

            DB::commit();

            // Cargar relaciones para la respuesta
            $venta->load(['cliente', 'user', 'detalles.producto']);

            return response()->json([
                'success' => true,
                'message' => 'Venta procesada exitosamente',
                'venta' => $venta
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Datos de venta inválidos',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error al procesar venta AJAX: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        // Cargar relaciones necesarias
        $venta->load(['cliente', 'user', 'detalles.producto']);
        
        // Si es petición AJAX, devolver JSON
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'venta' => $venta
            ]);
        }
        
        // Vista normal para web
        return view('ventas.show', compact('venta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        // Cargar relaciones necesarias
        $venta->load(['cliente', 'user', 'detalles.producto']);
        $detalles = $venta->detalles;
        // Si es petición AJAX, devolver JSON
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'venta' => $venta
            ]);
        }
        // Vista normal para web
        $clientes = Cliente::where('activo', true)->orderBy('nombres')->get();
        $productos = Producto::with(['categoria', 'marca'])
                            ->where('activo', true)
                            ->orderBy('nombre')
                            ->get();
        return view('ventas.edit', compact('venta', 'clientes', 'productos', 'detalles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venta $venta)
    {
        // Si es petición AJAX, usar validación JSON
        if ($request->ajax() || $request->expectsJson()) {
            $request->validate([
                'tipo_pago' => 'required|in:efectivo,tarjeta,transferencia',
                'estado' => 'required|in:completada,cancelada,pendiente',
                'observaciones' => 'nullable|string|max:500'
            ]);

            try {
                DB::beginTransaction();

                $venta->tipo_pago = $request->tipo_pago;
                $venta->estado = $request->estado;
                $venta->observaciones = $request->observaciones;
                $venta->save();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Venta actualizada exitosamente',
                    'venta' => $venta->load(['cliente', 'user', 'detalles.producto'])
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la venta: ' . $e->getMessage()
                ], 500);
            }
        }

        // Validación normal para formularios web
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'tipo_pago' => 'required|in:efectivo,tarjeta,transferencia',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'observaciones' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Actualizar venta básica
            $venta->cliente_id = $request->cliente_id;
            $venta->tipo_pago = $request->tipo_pago;
            $venta->observaciones = $request->observaciones;
            
            $subtotal = 0;

            // Restaurar stock de productos originales
            foreach ($venta->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->stock_actual += $detalle->cantidad;
                    $producto->save();
                }
            }

            // Eliminar detalles existentes
            $venta->detalles()->delete();

            // Procesar nuevos productos
            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['producto_id']);
                
                // Verificar stock disponible
                if ($producto->stock_actual < $item['cantidad']) {
                    return back()->with('error', "Stock insuficiente para el producto {$producto->nombre}. Stock disponible: {$producto->stock_actual}");
                }
                
                $subtotal += $producto->precio_venta * $item['cantidad'];

                // Crear nuevo detalle
                $detalle = new DetalleVenta();
                $detalle->venta_id = $venta->id;
                $detalle->producto_id = $producto->id;
                $detalle->cantidad = $item['cantidad'];
                $detalle->precio_unitario = $producto->precio_venta;
                $detalle->subtotal = $producto->precio_venta * $item['cantidad'];
                $detalle->lote = $item['lote'] ?? $producto->lote ?? 'SIN_LOTE';
                $detalle->fecha_vencimiento = $item['fecha_vencimiento'] ?? $producto->fecha_vencimiento ?? now()->addYear();
                $detalle->save();

                // Actualizar stock del producto
                $producto->stock_actual -= $item['cantidad'];
                $producto->save();
            }

            // Recalcular totales
            $venta->subtotal = $subtotal;
            $venta->igv = $subtotal * 0.18;
            $venta->total = $venta->subtotal + $venta->igv;
            $venta->save();

            DB::commit();

            return redirect()->route('ventas.index')->with('success', 'Venta actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        DB::beginTransaction();
        try {
            // Restaurar stock de los productos vendidos
            foreach ($venta->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->stock_actual += $detalle->cantidad;
                    $producto->save();
                }
            }

            // Cambiar estado a cancelada en lugar de eliminar físicamente
            $venta->estado = 'cancelada';
            $venta->save();

            DB::commit();

            // Si es petición AJAX, devolver JSON
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Venta anulada exitosamente'
                ]);
            }

            return redirect()->route('ventas.index')->with('success', 'Venta cancelada exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Si es petición AJAX, devolver error JSON
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al anular la venta: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Generar ticket de venta
     */
    public function ticket(Venta $venta)
    {
        $venta->load(['cliente', 'user', 'detalles.producto']);
        return view('ventas.ticket', compact('venta'));
    }

    /**
     * Buscar producto por código
     */
    public function buscarProducto(Request $request)
    {
        $termino = $request->get('q');
        
        $productos = Producto::with(['categoria', 'marca'])
                            ->where('activo', true)
                            ->where('stock_actual', '>', 0)
                            ->where(function($query) use ($termino) {
                                $query->where('nombre', 'like', "%{$termino}%")
                                      ->orWhere('codigo', 'like', "%{$termino}%");
                            })
                            ->limit(10)
                            ->get();

        return response()->json($productos->map(function($producto) {
            return [
                'id' => $producto->id,
                'codigo' => $producto->codigo,
                'nombre' => $producto->nombre,
                'marca' => $producto->marca->nombre ?? '',
                'precio_venta' => $producto->precio_venta,
                'stock_actual' => $producto->stock_actual,
                'categoria' => $producto->categoria->nombre ?? '',
                'lote' => $producto->lote ?? 'LOTE001',
                'fecha_vencimiento' => $producto->fecha_vencimiento ?? '2025-12-31'
            ];
        }));
    }

    /**
     * Reportes de ventas
     */
    public function reportes()
    {
        // Solo administrador puede ver reportes
        if (Auth::user()->role !== 'administrador') {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        return view('ventas.reportes');
    }

    /**
     * Datos para reportes
     */
    public function datosReportes(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth());

        $ventas = Venta::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->with(['cliente', 'user', 'detalles.producto'])
            ->get();

        $resumen = [
            'total_ventas' => $ventas->count(),
            'monto_total' => $ventas->sum('total'),
            'promedio_venta' => $ventas->avg('total'),
            'productos_vendidos' => $ventas->sum(function($venta) {
                return $venta->detalles->sum('cantidad');
            })
        ];

        return response()->json([
            'ventas' => $ventas,
            'resumen' => $resumen
        ]);
    }

    /**
     * Exportar ventas a CSV
     */
    public function exportar()
    {
        $ventas = Venta::with(['cliente', 'user', 'detalles.producto'])->orderBy('fecha', 'desc')->get();
        
        $filename = 'ventas_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($ventas) {
            $file = fopen('php://output', 'w');
            
            // Encabezados del CSV
            fputcsv($file, [
                'ID',
                'Número Ticket',
                'Fecha Venta',
                'Cliente',
                'Vendedor',
                'Tipo Pago',
                'Subtotal',
                'IGV',
                'Total',
                'Estado',
                'Observaciones'
            ]);

            // Datos
            foreach ($ventas as $venta) {
                fputcsv($file, [
                    $venta->id,
                    $venta->numero_ticket,
                    $venta->fecha->format('Y-m-d H:i:s'),
                    $venta->cliente ? $venta->cliente->nombre_completo : 'Cliente General',
                    $venta->user->name,
                    ucfirst($venta->tipo_pago),
                    $venta->subtotal,
                    $venta->igv,
                    $venta->total,
                    ucfirst($venta->estado),
                    $venta->observaciones ?? ''
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * 🔍 BUSCAR CLIENTE AJAX
     */
    public function buscarClienteAjax(Request $request)
    {
        try {
            $termino = $request->get('q', '');
            
            if (empty($termino)) {
                return response()->json([
                    'success' => true,
                    'clientes' => []
                ]);
            }
            
            $clientes = Cliente::where('activo', true)
                ->where(function($query) use ($termino) {
                    $query->where('nombres', 'like', "%{$termino}%")
                          ->orWhere('apellidos', 'like', "%{$termino}%")
                          ->orWhere('dni', 'like', "%{$termino}%")
                          ->orWhere('telefono', 'like', "%{$termino}%");
                })
                ->limit(10)
                ->get();
            
            return response()->json([
                'success' => true,
                'clientes' => $clientes->map(function($cliente) {
                    return [
                        'id' => $cliente->id,
                        'nombres' => $cliente->nombres,
                        'apellidos' => $cliente->apellidos,
                        'nombre_completo' => $cliente->nombres . ' ' . $cliente->apellidos,
                        'dni' => $cliente->dni,
                        'telefono' => $cliente->telefono,
                        'email' => $cliente->email
                    ];
                })
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar clientes: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * 📊 CALCULAR TOTALES AJAX
     */
    public function calcularTotalesAjax(Request $request)
    {
        try {
            $request->validate([
                'productos' => 'required|array|min:1',
                'productos.*.producto_id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'descuento' => 'nullable|numeric|min:0|max:100'
            ]);
            
            $subtotal = 0;
            $productosCalculados = [];
            
            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['producto_id']);
                
                $precioUnitario = $producto->precio_venta;
                $cantidad = $item['cantidad'];
                $subtotalProducto = $precioUnitario * $cantidad;
                
                $subtotal += $subtotalProducto;
                
                $productosCalculados[] = [
                    'producto_id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'precio_unitario' => $precioUnitario,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotalProducto,
                    'stock_disponible' => $producto->stock_actual
                ];
            }
            
            // Aplicar descuento si existe
            $descuento = $request->descuento ?? 0;
            $montoDescuento = ($subtotal * $descuento) / 100;
            $subtotalConDescuento = $subtotal - $montoDescuento;
            
            // Calcular IGV (18%)
            $igv = $subtotalConDescuento * 0.18;
            $total = $subtotalConDescuento + $igv;
            
            return response()->json([
                'success' => true,
                'calculos' => [
                    'subtotal' => round($subtotal, 2),
                    'descuento_porcentaje' => $descuento,
                    'monto_descuento' => round($montoDescuento, 2),
                    'subtotal_con_descuento' => round($subtotalConDescuento, 2),
                    'igv' => round($igv, 2),
                    'total' => round($total, 2)
                ],
                'productos' => $productosCalculados
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular totales: ' . $e->getMessage()
            ], 500);
        }
    }

    public function directa()
    {
        $clientes = \App\Models\Cliente::where('activo', true)->orderBy('nombres')->get();
        $productos = \App\Models\Producto::with(['categoria', 'marca'])
            ->where('activo', true)
            ->where('stock_actual', '>', 0)
            ->orderBy('nombre')
            ->get();
        return view('ventas.directa', compact('clientes', 'productos'));
    }
}
