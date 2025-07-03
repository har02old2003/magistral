<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\Venta;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class DeliveryController extends Controller
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
            $query = Delivery::with(['venta', 'pedido', 'cliente', 'repartidor', 'usuario'])
                           ->orderBy('fecha_programada', 'desc');

            // Filtros
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->filled('repartidor_id')) {
                $query->where('repartidor_id', $request->repartidor_id);
            }

            if ($request->filled('fecha')) {
                $query->whereDate('fecha_programada', $request->fecha);
            }

            if ($request->filled('cliente_id')) {
                $query->where('cliente_id', $request->cliente_id);
            }

            $deliveries = $query->paginate(15);

            $repartidores = User::where('role', 'repartidor')->orWhere('role', 'empleado')->orderBy('name')->get();
            $clientes = Cliente::where('activo', true)->orderBy('nombres')->get();

            // Estadísticas
            $estadisticas = [
                'hoy' => [
                    'total' => Delivery::whereDate('fecha_programada', today())->count(),
                    'entregados' => Delivery::whereDate('fecha_programada', today())->where('estado', 'entregado')->count(),
                    'pendientes' => Delivery::whereDate('fecha_programada', today())->where('estado', 'programado')->count(),
                    'en_ruta' => Delivery::whereDate('fecha_programada', today())->where('estado', 'en_ruta')->count()
                ],
                'mes' => [
                    'total' => Delivery::whereMonth('created_at', date('m'))->count(),
                    'ingresos' => Delivery::whereMonth('created_at', date('m'))->sum('costo_delivery')
                ]
            ];

            return view('delivery.index', compact('deliveries', 'repartidores', 'clientes', 'estadisticas'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar deliveries: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $clientes = Cliente::where('activo', true)->orderBy('nombres')->get();
            $repartidores = User::where('role', 'repartidor')->orWhere('role', 'empleado')->orderBy('name')->get();

            $venta = null;
            $pedido = null;

            // Si viene desde una venta o pedido
            if ($request->filled('venta_id')) {
                $venta = Venta::with(['cliente'])->findOrFail($request->venta_id);
            }

            if ($request->filled('pedido_id')) {
                $pedido = Pedido::with(['cliente'])->findOrFail($request->pedido_id);
            }

            return view('delivery.create', compact('clientes', 'repartidores', 'venta', 'pedido'));

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
                'venta_id' => 'nullable|exists:ventas,id',
                'pedido_id' => 'nullable|exists:pedidos,id',
                'cliente_id' => 'required|exists:clientes,id',
                'repartidor_id' => 'nullable|exists:users,id',
                'fecha_programada' => 'required|date',
                'direccion_entrega' => 'required|string|max:500',
                'referencia_direccion' => 'nullable|string|max:500',
                'telefono_contacto' => 'required|string|max:20',
                'costo_delivery' => 'required|numeric|min:0',
                'metodo_pago_delivery' => 'required|in:efectivo,tarjeta,transferencia',
                'observaciones' => 'nullable|string|max:1000',
                'latitud' => 'nullable|numeric|between:-90,90',
                'longitud' => 'nullable|numeric|between:-180,180'
            ], [
                'cliente_id.required' => 'Debe seleccionar un cliente.',
                'fecha_programada.required' => 'La fecha programada es obligatoria.',
                'direccion_entrega.required' => 'La dirección de entrega es obligatoria.',
                'telefono_contacto.required' => 'El teléfono de contacto es obligatorio.',
                'costo_delivery.required' => 'El costo del delivery es obligatorio.',
                'costo_delivery.min' => 'El costo del delivery debe ser mayor o igual a 0.',
                'metodo_pago_delivery.required' => 'El método de pago es obligatorio.'
            ]);

            $delivery = Delivery::create([
                'codigo_delivery' => Delivery::generarCodigo(),
                'venta_id' => $validated['venta_id'],
                'pedido_id' => $validated['pedido_id'],
                'cliente_id' => $validated['cliente_id'],
                'repartidor_id' => $validated['repartidor_id'],
                'fecha_programada' => $validated['fecha_programada'],
                'direccion_entrega' => $validated['direccion_entrega'],
                'referencia_direccion' => $validated['referencia_direccion'],
                'telefono_contacto' => $validated['telefono_contacto'],
                'costo_delivery' => $validated['costo_delivery'],
                'metodo_pago_delivery' => $validated['metodo_pago_delivery'],
                'estado' => $validated['repartidor_id'] ? Delivery::ESTADO_ASIGNADO : Delivery::ESTADO_PROGRAMADO,
                'observaciones' => $validated['observaciones'],
                'latitud' => $validated['latitud'],
                'longitud' => $validated['longitud'],
                'usuario_id' => Auth::id()
            ]);

            return redirect()->route('delivery.show', $delivery)
                           ->with('success', 'Delivery creado exitosamente.');

        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Error al crear delivery: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Delivery $delivery)
    {
        try {
            $delivery->load(['venta', 'pedido', 'cliente', 'repartidor', 'usuario']);

            return view('delivery.show', compact('delivery'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar delivery: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Delivery $delivery)
    {
        try {
            $validated = $request->validate([
                'repartidor_id' => 'nullable|exists:users,id',
                'estado' => 'required|in:programado,asignado,en_ruta,entregado,no_entregado,cancelado',
                'fecha_entrega' => 'nullable|date',
                'hora_salida' => 'nullable|date_format:H:i',
                'hora_entrega' => 'nullable|date_format:H:i|after:hora_salida',
                'observaciones' => 'nullable|string|max:1000'
            ]);

            $delivery->update($validated);

            return back()->with('success', 'Delivery actualizado correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al actualizar delivery: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery)
    {
        try {
            if (in_array($delivery->estado, ['entregado', 'en_ruta'])) {
                return back()->with('error', 'No se puede eliminar un delivery entregado o en ruta.');
            }

            $delivery->update(['estado' => 'cancelado']);

            return redirect()->route('delivery.index')
                           ->with('success', 'Delivery cancelado correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al cancelar delivery: ' . $e->getMessage());
        }
    }

    /**
     * Asignar repartidor
     */
    public function asignarRepartidor(Request $request, Delivery $delivery)
    {
        try {
            $validated = $request->validate([
                'repartidor_id' => 'required|exists:users,id'
            ]);

            if (!in_array($delivery->estado, ['programado', 'asignado'])) {
                return back()->with('error', 'Solo se pueden asignar deliveries programados.');
            }

            $delivery->update([
                'repartidor_id' => $validated['repartidor_id'],
                'estado' => 'asignado'
            ]);

            return back()->with('success', 'Repartidor asignado correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al asignar repartidor: ' . $e->getMessage());
        }
    }

    /**
     * Marcar como en ruta
     */
    public function enRuta(Delivery $delivery)
    {
        try {
            if ($delivery->estado != 'asignado') {
                return back()->with('error', 'Solo se pueden poner en ruta deliveries asignados.');
            }

            $delivery->update([
                'estado' => 'en_ruta',
                'hora_salida' => now()->format('H:i')
            ]);

            return back()->with('success', 'Delivery marcado como en ruta.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al marcar como en ruta: ' . $e->getMessage());
        }
    }

    /**
     * Marcar como entregado
     */
    public function entregar(Request $request, Delivery $delivery)
    {
        try {
            if ($delivery->estado != 'en_ruta') {
                return back()->with('error', 'Solo se pueden entregar deliveries en ruta.');
            }

            $validated = $request->validate([
                'foto_entrega' => 'nullable|image|max:2048',
                'firma_cliente' => 'nullable|string',
                'observaciones_entrega' => 'nullable|string|max:500'
            ]);

            $updates = [
                'estado' => 'entregado',
                'fecha_entrega' => now(),
                'hora_entrega' => now()->format('H:i')
            ];

            if ($request->hasFile('foto_entrega')) {
                $path = $request->file('foto_entrega')->store('delivery/fotos', 'public');
                $updates['foto_entrega'] = $path;
            }

            if (isset($validated['firma_cliente'])) {
                $updates['firma_cliente'] = $validated['firma_cliente'];
            }

            if (isset($validated['observaciones_entrega'])) {
                $updates['observaciones'] = $delivery->observaciones . "\n\nEntrega: " . $validated['observaciones_entrega'];
            }

            $delivery->update($updates);

            return back()->with('success', 'Delivery marcado como entregado.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al marcar como entregado: ' . $e->getMessage());
        }
    }

    /**
     * Vista para repartidores
     */
    public function misDeliveries(Request $request)
    {
        try {
            $fecha = $request->get('fecha', today()->toDateString());

            $deliveries = Delivery::with(['venta', 'pedido', 'cliente'])
                                ->where('repartidor_id', Auth::id())
                                ->whereDate('fecha_programada', $fecha)
                                ->orderBy('hora_salida')
                                ->get();

            $estadisticas = [
                'total' => $deliveries->count(),
                'pendientes' => $deliveries->where('estado', 'asignado')->count(),
                'en_ruta' => $deliveries->where('estado', 'en_ruta')->count(),
                'entregados' => $deliveries->where('estado', 'entregado')->count()
            ];

            return view('delivery.mis-deliveries', compact('deliveries', 'estadisticas', 'fecha'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar mis deliveries: ' . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas de delivery
     */
    public function estadisticas(Request $request)
    {
        try {
            $periodo = $request->get('periodo', 'mes');
            $fechaDesde = match($periodo) {
                'dia' => now()->startOfDay(),
                'semana' => now()->startOfWeek(),
                'mes' => now()->startOfMonth(),
                default => now()->startOfMonth()
            };

            $estadisticas = [
                'total' => Delivery::where('created_at', '>=', $fechaDesde)->count(),
                'entregados' => Delivery::where('created_at', '>=', $fechaDesde)->where('estado', 'entregado')->count(),
                'cancelados' => Delivery::where('created_at', '>=', $fechaDesde)->where('estado', 'cancelado')->count(),
                'ingresos' => Delivery::where('created_at', '>=', $fechaDesde)->where('estado', 'entregado')->sum('costo_delivery'),
                'tiempo_promedio' => 0
            ];

            // Calcular tiempo promedio de entrega
            $entregados = Delivery::where('created_at', '>=', $fechaDesde)
                               ->where('estado', 'entregado')
                               ->whereNotNull('hora_salida')
                               ->whereNotNull('hora_entrega')
                               ->get();

            if ($entregados->count() > 0) {
                $tiempoTotal = $entregados->sum('duracion_entrega');
                $estadisticas['tiempo_promedio'] = round($tiempoTotal / $entregados->count(), 2);
            }

            return response()->json($estadisticas);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 