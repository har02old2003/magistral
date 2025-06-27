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
        
        return view('ventas.index', compact(
            'ventas', 
            'ventasHoy', 
            'montoHoy', 
            'ventasMes', 
            'promedioVenta',
            'vendedores',
            'clientes'
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
                $detalle->precio_unitario = $producto->precio_venta;
                $detalle->subtotal = $producto->precio_venta * $item['cantidad'];
                $detalle->lote = $producto->lote ?? 'SIN_LOTE';
                $detalle->fecha_vencimiento = $producto->fecha_vencimiento ?? now()->addYear();
                $detalle->lote = $producto->lote ?? 'SIN_LOTE';
                $detalle->fecha_vencimiento = $producto->fecha_vencimiento ?? now()->addYear();
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
            $venta->save();

            // Procesar cada producto en la venta
            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['producto_id']);

                // Crear detalle de venta
                $detalle = new DetalleVenta();
                $detalle->venta_id = $venta->id;
                $detalle->producto_id = $producto->id;
                $detalle->cantidad = $item['cantidad'];
                $detalle->precio_unitario = $producto->precio_venta;
                $detalle->subtotal = $producto->precio_venta * $item['cantidad'];
                $detalle->lote = $producto->lote ?? 'SIN_LOTE';
                $detalle->fecha_vencimiento = $producto->fecha_vencimiento ?? now()->addYear();
                $detalle->lote = $producto->lote ?? 'SIN_LOTE';
                $detalle->fecha_vencimiento = $producto->fecha_vencimiento ?? now()->addYear();
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
                'numero_venta' => $numeroVenta,
                'venta' => [
                    'id' => $venta->id,
                    'numero_ticket' => $venta->numero_ticket,
                    'fecha' => $venta->fecha->format('Y-m-d H:i:s'),
                    'cliente' => $venta->cliente ? $venta->cliente->nombres . ' ' . $venta->cliente->apellidos : 'Cliente General',
                    'vendedor' => $venta->user->name,
                    'tipo_pago' => $venta->tipo_pago,
                    'subtotal' => $venta->subtotal,
                    'igv' => $venta->igv,
                    'total' => $venta->total,
                    'productos' => $venta->detalles->map(function($detalle) {
                        return [
                            'nombre' => $detalle->producto->nombre,
                            'codigo' => $detalle->producto->codigo,
                            'cantidad' => $detalle->cantidad,
                            'precio' => $detalle->precio_unitario,
                            'subtotal' => $detalle->subtotal
                        ];
                    })
                ]
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
        $venta->load(['cliente', 'user', 'detalles.producto']);
        
        // Si es una petición AJAX, devolver JSON
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'venta' => $venta
            ]);
        }
        
        return view('ventas.show', compact('venta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        // Solo administrador puede editar ventas
        if (Auth::user()->role !== 'administrador') {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // Solo permitir editar ventas del mismo día
        if (!Carbon::parse($venta->fecha)->isToday()) {
            return redirect()->back()->with('error', 'Solo se pueden editar ventas del día actual.');
        }

        $clientes = Cliente::where('activo', true)->orderBy('nombres')->get();
        return view('ventas.edit', compact('venta', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venta $venta)
    {
        // Solo administrador puede actualizar ventas
        if (Auth::user()->role !== 'administrador') {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para realizar esta acción.'
                ], 403);
            }
            return back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // Solo permitir editar ventas del mismo día
        if (!Carbon::parse($venta->fecha)->isToday()) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden editar ventas del día actual.'
                ], 400);
            }
            return back()->with('error', 'Solo se pueden editar ventas del día actual.');
        }

        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'tipo_pago' => 'required|in:efectivo,tarjeta,transferencia,yape',
            'observaciones' => 'nullable|string|max:500'
        ]);

        $venta->update($request->only(['cliente_id', 'tipo_pago', 'observaciones']));

        // Si es una petición AJAX, devolver JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Venta actualizada exitosamente',
                'venta' => $venta->fresh(['cliente', 'user', 'detalles.producto'])
            ]);
        }

        return redirect()->route('ventas.index')->with('success', 'Venta actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        // Solo administrador puede eliminar ventas
        if (Auth::user()->role !== 'administrador') {
            return back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // Solo permitir cancelar ventas del mismo día
        if (!Carbon::parse($venta->fecha)->isToday()) {
            return back()->with('error', 'Solo se pueden cancelar ventas del día actual.');
        }

        DB::beginTransaction();
        try {
            // Restaurar stock de productos
            foreach ($venta->detalles as $detalle) {
                $producto = $detalle->producto;
                $producto->stock_actual += $detalle->cantidad;
                $producto->save();
            }

            // Eliminar la venta y sus detalles
            $venta->detalles()->delete();
            $venta->delete();

            DB::commit();

            return redirect()->route('ventas.index')->with('success', 'Venta cancelada exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
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
                'precio' => $producto->precio_venta,
                'stock' => $producto->stock_actual,
                'categoria' => $producto->categoria->nombre ?? ''
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
}
