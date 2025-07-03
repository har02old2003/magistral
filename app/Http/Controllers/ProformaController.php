<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proforma;
use App\Models\DetalleProforma;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class ProformaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Proforma::with(['cliente', 'usuario'])
                           ->orderBy('created_at', 'desc');

            // Filtros
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->filled('cliente_id')) {
                $query->where('cliente_id', $request->cliente_id);
            }

            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha_proforma', '>=', $request->fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha_proforma', '<=', $request->fecha_hasta);
            }

            $proformas = $query->paginate(15);

            $clientes = Cliente::where('activo', true)->orderBy('nombres')->get();

            // Estadísticas
            $estadisticas = [
                'total' => Proforma::count(),
                'pendientes' => Proforma::where('estado', 'pendiente')->count(),
                'aceptadas' => Proforma::where('estado', 'aceptado')->count(),
                'convertidas' => Proforma::where('estado', 'convertido')->count(),
                'monto_mes' => Proforma::whereMonth('created_at', date('m'))->sum('total')
            ];

            return view('proformas.index', compact('proformas', 'clientes', 'estadisticas'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar proformas: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $clientes = Cliente::where('activo', true)->orderBy('nombres')->get();
            $productos = Producto::activo()->with(['categoria', 'marca'])->orderBy('nombre')->get();

            return view('proformas.create', compact('clientes', 'productos'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'fecha_proforma' => 'required|date',
                'fecha_vencimiento' => 'required|date|after:fecha_proforma',
                'descuento' => 'nullable|numeric|min:0|max:99999.99',
                'observaciones' => 'nullable|string|max:1000',
                'condiciones_pago' => 'nullable|string|max:500',
                'tiempo_entrega' => 'nullable|string|max:100',
                'productos' => 'required|array|min:1',
                'productos.*.id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'productos.*.precio' => 'required|numeric|min:0.01',
                'productos.*.descuento' => 'nullable|numeric|min:0'
            ], [
                'cliente_id.required' => 'Debe seleccionar un cliente.',
                'fecha_proforma.required' => 'La fecha de la proforma es obligatoria.',
                'fecha_vencimiento.required' => 'La fecha de vencimiento es obligatoria.',
                'fecha_vencimiento.after' => 'La fecha de vencimiento debe ser posterior a la fecha de la proforma.',
                'productos.required' => 'Debe agregar al menos un producto.',
                'productos.min' => 'Debe agregar al menos un producto.'
            ]);

            DB::beginTransaction();

            // Calcular totales
            $subtotal = 0;
            foreach ($request->productos as $producto) {
                $descuentoUnitario = $producto['descuento'] ?? 0;
                $precioConDescuento = $producto['precio'] - $descuentoUnitario;
                $subtotal += $producto['cantidad'] * $precioConDescuento;
            }

            $descuentoGeneral = $validated['descuento'] ?? 0;
            $subtotalConDescuento = $subtotal - $descuentoGeneral;
            $igv = $subtotalConDescuento * 0.18;
            $total = $subtotalConDescuento + $igv;

            // Crear proforma
            $proforma = Proforma::create([
                'numero_proforma' => Proforma::generarNumero(),
                'cliente_id' => $validated['cliente_id'],
                'fecha_proforma' => $validated['fecha_proforma'],
                'fecha_vencimiento' => $validated['fecha_vencimiento'],
                'estado' => Proforma::ESTADO_PENDIENTE,
                'subtotal' => $subtotal,
                'descuento' => $descuentoGeneral,
                'igv' => $igv,
                'total' => $total,
                'observaciones' => $validated['observaciones'],
                'condiciones_pago' => $validated['condiciones_pago'],
                'tiempo_entrega' => $validated['tiempo_entrega'],
                'usuario_id' => Auth::id()
            ]);

            // Crear detalles de la proforma
            foreach ($request->productos as $producto) {
                DetalleProforma::create([
                    'proforma_id' => $proforma->id,
                    'producto_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio'],
                    'descuento_unitario' => $producto['descuento'] ?? 0
                ]);
            }

            DB::commit();

            return redirect()->route('proformas.show', $proforma)
                           ->with('success', 'Proforma creada exitosamente.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear proforma: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Proforma $proforma)
    {
        try {
            $proforma->load(['cliente', 'usuario', 'detalles.producto.categoria', 'detalles.producto.marca']);

            return view('proformas.show', compact('proforma'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar proforma: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proforma $proforma)
    {
        try {
            $validated = $request->validate([
                'estado' => 'required|in:pendiente,enviado,aceptado,rechazado,vencido',
                'observaciones' => 'nullable|string|max:1000'
            ]);

            $proforma->update($validated);

            return back()->with('success', 'Proforma actualizada correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al actualizar proforma: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proforma $proforma)
    {
        try {
            if ($proforma->estado == 'convertido') {
                return back()->with('error', 'No se puede eliminar una proforma que ya fue convertida a venta.');
            }

            $proforma->delete();

            return redirect()->route('proformas.index')
                           ->with('success', 'Proforma eliminada correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al eliminar proforma: ' . $e->getMessage());
        }
    }

    /**
     * Convertir proforma a venta
     */
    public function convertirAVenta(Proforma $proforma)
    {
        try {
            if ($proforma->estado != 'aceptado') {
                return back()->with('error', 'Solo se pueden convertir proformas aceptadas.');
            }

            if ($proforma->esta_vencida) {
                return back()->with('error', 'No se puede convertir una proforma vencida.');
            }

            // Verificar stock disponible
            foreach ($proforma->detalles as $detalle) {
                if ($detalle->producto->stock_actual < $detalle->cantidad) {
                    return back()->with('error', "Stock insuficiente para el producto: {$detalle->producto->nombre}");
                }
            }

            DB::beginTransaction();

            // Aquí se integraría con VentaController para crear la venta
            // Por ahora solo cambiamos el estado de la proforma
            $proforma->update(['estado' => 'convertido']);

            DB::commit();

            return back()->with('success', 'Proforma convertida a venta exitosamente.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al convertir proforma: ' . $e->getMessage());
        }
    }

    /**
     * Enviar proforma por email
     */
    public function enviarEmail(Proforma $proforma)
    {
        try {
            // Aquí se implementaría el envío por email
            $proforma->update(['estado' => 'enviado']);

            return back()->with('success', 'Proforma enviada por email correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al enviar proforma: ' . $e->getMessage());
        }
    }

    /**
     * Generar PDF de la proforma
     */
    public function generarPDF(Proforma $proforma)
    {
        try {
            $proforma->load(['cliente', 'detalles.producto']);

            // Aquí se implementaría la generación del PDF
            // Por ahora retornamos una vista para PDF
            return view('proformas.pdf', compact('proforma'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas de proformas
     */
    public function estadisticas()
    {
        try {
            $estadisticas = [
                'mes_actual' => [
                    'total' => Proforma::whereMonth('created_at', date('m'))->count(),
                    'monto' => Proforma::whereMonth('created_at', date('m'))->sum('total'),
                    'convertidas' => Proforma::whereMonth('created_at', date('m'))->where('estado', 'convertido')->count(),
                    'tasa_conversion' => 0
                ],
                'estados' => [
                    'pendientes' => Proforma::where('estado', 'pendiente')->count(),
                    'enviadas' => Proforma::where('estado', 'enviado')->count(),
                    'aceptadas' => Proforma::where('estado', 'aceptado')->count(),
                    'rechazadas' => Proforma::where('estado', 'rechazado')->count()
                ]
            ];

            // Calcular tasa de conversión
            $totalMes = $estadisticas['mes_actual']['total'];
            if ($totalMes > 0) {
                $estadisticas['mes_actual']['tasa_conversion'] = 
                    round(($estadisticas['mes_actual']['convertidas'] / $totalMes) * 100, 2);
            }

            return response()->json($estadisticas);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 💾 CREAR PROFORMA VÍA AJAX
     */
    public function storeAjax(Request $request)
    {
        try {
            $validated = $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'fecha_proforma' => 'required|date',
                'fecha_vencimiento' => 'required|date|after:fecha_proforma',
                'descuento' => 'nullable|numeric|min:0|max:99999.99',
                'observaciones' => 'nullable|string|max:1000',
                'condiciones_pago' => 'nullable|string|max:500',
                'tiempo_entrega' => 'nullable|string|max:100',
                'productos' => 'required|array|min:1',
                'productos.*.id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'productos.*.precio' => 'required|numeric|min:0.01',
                'productos.*.descuento' => 'nullable|numeric|min:0'
            ], [
                'cliente_id.required' => 'Debe seleccionar un cliente.',
                'fecha_proforma.required' => 'La fecha de la proforma es obligatoria.',
                'fecha_vencimiento.required' => 'La fecha de vencimiento es obligatoria.',
                'fecha_vencimiento.after' => 'La fecha de vencimiento debe ser posterior a la fecha de la proforma.',
                'productos.required' => 'Debe agregar al menos un producto.',
                'productos.min' => 'Debe agregar al menos un producto.'
            ]);

            DB::beginTransaction();

            // Calcular totales
            $subtotal = 0;
            foreach ($request->productos as $producto) {
                $descuentoUnitario = $producto['descuento'] ?? 0;
                $precioConDescuento = $producto['precio'] - $descuentoUnitario;
                $subtotal += $producto['cantidad'] * $precioConDescuento;
            }

            $descuentoGeneral = $validated['descuento'] ?? 0;
            $subtotalConDescuento = $subtotal - $descuentoGeneral;
            $igv = $subtotalConDescuento * 0.18;
            $total = $subtotalConDescuento + $igv;

            // Crear proforma
            $proforma = Proforma::create([
                'numero_proforma' => Proforma::generarNumero(),
                'cliente_id' => $validated['cliente_id'],
                'fecha_proforma' => $validated['fecha_proforma'],
                'fecha_vencimiento' => $validated['fecha_vencimiento'],
                'estado' => Proforma::ESTADO_PENDIENTE ?? 'pendiente',
                'subtotal' => $subtotal,
                'descuento' => $descuentoGeneral,
                'igv' => $igv,
                'total' => $total,
                'observaciones' => $validated['observaciones'],
                'condiciones_pago' => $validated['condiciones_pago'],
                'tiempo_entrega' => $validated['tiempo_entrega'],
                'usuario_id' => Auth::id()
            ]);

            // Crear detalles de la proforma
            foreach ($request->productos as $producto) {
                DetalleProforma::create([
                    'proforma_id' => $proforma->id,
                    'producto_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio'],
                    'descuento_unitario' => $producto['descuento'] ?? 0
                ]);
            }

            DB::commit();

            // Cargar relaciones para la respuesta
            $proforma->load(['cliente', 'usuario', 'detalles.producto']);

            return response()->json([
                'success' => true,
                'message' => 'Proforma creada exitosamente',
                'proforma' => [
                    'id' => $proforma->id,
                    'numero_proforma' => $proforma->numero_proforma,
                    'cliente' => $proforma->cliente->nombres . ' ' . $proforma->cliente->apellidos,
                    'fecha_proforma' => $proforma->fecha_proforma,
                    'fecha_vencimiento' => $proforma->fecha_vencimiento,
                    'total' => $proforma->total,
                    'estado' => $proforma->estado
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Datos de proforma inválidos',
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear proforma: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 📝 ACTUALIZAR PROFORMA VÍA AJAX
     */
    public function updateAjax(Request $request, Proforma $proforma)
    {
        try {
            $validated = $request->validate([
                'estado' => 'required|in:pendiente,enviado,aceptado,rechazado,vencido',
                'observaciones' => 'nullable|string|max:1000'
            ]);

            $proforma->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Proforma actualizada correctamente',
                'proforma' => $proforma->fresh(['cliente', 'usuario'])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar proforma: ' . $e->getMessage()
            ], 500);
        }
    }
}
 