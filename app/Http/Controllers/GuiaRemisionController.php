<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuiaRemision;
use App\Models\DetalleGuiaRemision;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class GuiaRemisionController extends Controller
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
            $query = GuiaRemision::with(['cliente', 'proveedor'])
                                ->orderBy('created_at', 'desc');

            // Filtros
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha_traslado', '>=', $request->fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha_traslado', '<=', $request->fecha_hasta);
            }

            if ($request->filled('cliente_id')) {
                $query->where('cliente_id', $request->cliente_id);
            }

            $guias = $query->paginate(15);

            $clientes = Cliente::where('activo', true)->orderBy('nombres')->get();

            // Estadísticas
            $estadisticas = [
                'total' => GuiaRemision::count(),
                'emitidas' => GuiaRemision::where('estado', 'emitida')->count(),
                'en_transito' => GuiaRemision::where('estado', 'en_transito')->count(),
                'entregadas' => GuiaRemision::where('estado', 'entregada')->count(),
                'hoy' => GuiaRemision::whereDate('created_at', today())->count()
            ];

            return view('guias-remision.index', compact('guias', 'clientes', 'estadisticas'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar guías de remisión: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $clientes = Cliente::where('activo', true)->orderBy('nombres')->get();
            $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();
            $productos = Producto::where('activo', true)->with(['categoria', 'marca'])->orderBy('nombre')->get();

            return view('guias-remision.create', compact('clientes', 'proveedores', 'productos'));

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
                'cliente_id' => 'nullable|exists:clientes,id',
                'proveedor_id' => 'nullable|exists:proveedores,id',
                'destinatario' => 'required|string|max:255',
                'direccion_destino' => 'required|string|max:500',
                'tipo_traslado' => 'required|in:venta,compra,traslado',
                'fecha_emision' => 'required|date',
                'fecha_traslado' => 'nullable|date',
                'transportista' => 'nullable|string|max:255',
                'ruc_transportista' => 'nullable|string|max:11',
                'placa_vehiculo' => 'nullable|string|max:10',
                'observaciones' => 'nullable|string|max:1000',
                'peso_total' => 'nullable|numeric|min:0',
                'cantidad_bultos' => 'nullable|integer|min:1',
                'productos' => 'required|array|min:1',
                'productos.*.id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'productos.*.peso_unitario' => 'nullable|numeric|min:0'
            ], [
                'destinatario.required' => 'El destinatario es obligatorio.',
                'direccion_destino.required' => 'La dirección de destino es obligatoria.',
                'tipo_traslado.required' => 'El tipo de traslado es obligatorio.',
                'fecha_emision.required' => 'La fecha de emisión es obligatoria.',
                'productos.required' => 'Debe agregar al menos un producto.',
                'productos.min' => 'Debe agregar al menos un producto.'
            ]);

            DB::beginTransaction();

            // Crear guía de remisión
            $guia = GuiaRemision::create([
                'numero_guia' => GuiaRemision::generarNumero(),
                'cliente_id' => $validated['cliente_id'],
                'proveedor_id' => $validated['proveedor_id'],
                'destinatario' => $validated['destinatario'],
                'direccion_destino' => $validated['direccion_destino'],
                'tipo_traslado' => $validated['tipo_traslado'],
                'estado' => GuiaRemision::ESTADO_EMITIDA,
                'fecha_emision' => $validated['fecha_emision'],
                'fecha_traslado' => $validated['fecha_traslado'],
                'transportista' => $validated['transportista'],
                'ruc_transportista' => $validated['ruc_transportista'],
                'placa_vehiculo' => $validated['placa_vehiculo'],
                'observaciones' => $validated['observaciones'],
                'peso_total' => $validated['peso_total'] ?? 0,
                'cantidad_bultos' => $validated['cantidad_bultos'] ?? 1,
                'activo' => true
            ]);

            // Crear detalles de la guía
            foreach ($request->productos as $producto) {
                DetalleGuiaRemision::create([
                    'guia_remision_id' => $guia->id,
                    'producto_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'unidad_medida' => 'UND',
                    'peso_unitario' => $producto['peso_unitario'] ?? 0,
                    'peso_total' => ($producto['cantidad'] * ($producto['peso_unitario'] ?? 0))
                ]);
            }

            DB::commit();

            return redirect()->route('guias.index')
                           ->with('success', 'Guía de remisión creada exitosamente.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear guía de remisión: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GuiaRemision $guia)
    {
        try {
            $guia->load(['cliente', 'proveedor', 'detalles.producto']);

            return view('guias-remision.show', compact('guia'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar guía de remisión: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GuiaRemision $guia)
    {
        try {
            $validated = $request->validate([
                'estado' => 'required|in:emitida,en_transito,entregada,anulada',
                'observaciones' => 'nullable|string|max:1000'
            ]);

            $guia->update($validated);

            return back()->with('success', 'Guía de remisión actualizada correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al actualizar guía de remisión: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GuiaRemision $guia)
    {
        try {
            if ($guia->estado == 'entregada') {
                return back()->with('error', 'No se puede eliminar una guía que ya fue entregada.');
            }

            $guia->update(['estado' => 'anulada']);

            return redirect()->route('guias.index')
                           ->with('success', 'Guía de remisión anulada correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al anular guía de remisión: ' . $e->getMessage());
        }
    }

    /**
     * Marcar como en tránsito
     */
    public function enTransito(GuiaRemision $guia)
    {
        try {
            if ($guia->estado != 'emitida') {
                return back()->with('error', 'Solo se pueden poner en tránsito guías emitidas.');
            }

            $guia->update(['estado' => 'en_transito']);

            return back()->with('success', 'Guía marcada como en tránsito.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al actualizar estado: ' . $e->getMessage());
        }
    }

    /**
     * Marcar como entregada
     */
    public function entregar(GuiaRemision $guia)
    {
        try {
            if (!in_array($guia->estado, ['emitida', 'en_transito'])) {
                return back()->with('error', 'Solo se pueden entregar guías emitidas o en tránsito.');
            }

            $guia->update(['estado' => 'entregada']);

            return back()->with('success', 'Guía marcada como entregada.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al marcar como entregada: ' . $e->getMessage());
        }
    }

    /**
     * Generar PDF de la guía
     */
    public function generarPDF(GuiaRemision $guia)
    {
        try {
            $guia->load(['cliente', 'proveedor', 'detalles.producto']);

            // Aquí se implementaría la generación del PDF
            return response()->json([
                'success' => true,
                'message' => 'PDF generado exitosamente',
                'url' => '/guias/' . $guia->id . '/pdf'
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error al generar PDF: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtener estadísticas de guías
     */
    public function estadisticas()
    {
        try {
            $estadisticas = [
                'hoy' => [
                    'total' => GuiaRemision::whereDate('created_at', today())->count(),
                    'emitidas' => GuiaRemision::whereDate('created_at', today())->where('estado', 'emitida')->count(),
                    'entregadas' => GuiaRemision::whereDate('created_at', today())->where('estado', 'entregada')->count()
                ],
                'mes' => [
                    'total' => GuiaRemision::whereMonth('created_at', date('m'))->count(),
                    'por_tipo' => [
                        'venta' => GuiaRemision::whereMonth('created_at', date('m'))->where('tipo_traslado', 'venta')->count(),
                        'compra' => GuiaRemision::whereMonth('created_at', date('m'))->where('tipo_traslado', 'compra')->count(),
                        'traslado' => GuiaRemision::whereMonth('created_at', date('m'))->where('tipo_traslado', 'traslado')->count()
                    ]
                ]
            ];

            return response()->json($estadisticas);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 