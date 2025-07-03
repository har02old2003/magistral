<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class PedidoController extends Controller
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
            $query = Pedido::with(['cliente', 'proveedor', 'usuario'])
                          ->activo()
                          ->orderBy('created_at', 'desc');

            // Filtros
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->filled('tipo')) {
                $query->where('tipo_pedido', $request->tipo);
            }

            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha_pedido', '>=', $request->fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha_pedido', '<=', $request->fecha_hasta);
            }

            $pedidos = $query->paginate(15);

            $clientes = Cliente::where('activo', true)->orderBy('nombres')->get();
            $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();

            // Estadísticas rápidas
            $estadisticas = [
                'total' => Pedido::activo()->count(),
                'pendientes' => Pedido::activo()->estado('pendiente')->count(),
                'confirmados' => Pedido::activo()->estado('confirmado')->count(),
                'entregados' => Pedido::activo()->estado('entregado')->count(),
                'delivery_hoy' => Pedido::activo()->tipo('delivery')->whereDate('fecha_entrega_estimada', today())->count()
            ];

            return view('pedidos.index', compact('pedidos', 'clientes', 'proveedores', 'estadisticas'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar pedidos: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $clientes = Cliente::where('activo', true)->orderBy('nombres')->get();
            $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();
            $productos = Producto::activo()->with(['categoria', 'marca'])->orderBy('nombre')->get();

            return view('pedidos.create', compact('clientes', 'proveedores', 'productos'));

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
                'tipo_pedido' => 'required|in:compra,venta,delivery',
                'cliente_id' => 'nullable|exists:clientes,id',
                'proveedor_id' => 'nullable|exists:proveedores,id',
                'fecha_pedido' => 'required|date',
                'fecha_entrega_estimada' => 'required|date|after_or_equal:fecha_pedido',
                'direccion_entrega' => 'nullable|string|max:500',
                'telefono_contacto' => 'nullable|string|max:20',
                'observaciones' => 'nullable|string|max:1000',
                'productos' => 'required|array|min:1',
                'productos.*.id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'productos.*.precio' => 'required|numeric|min:0.01'
            ], [
                'tipo_pedido.required' => 'El tipo de pedido es obligatorio.',
                'fecha_pedido.required' => 'La fecha del pedido es obligatoria.',
                'fecha_entrega_estimada.required' => 'La fecha de entrega estimada es obligatoria.',
                'fecha_entrega_estimada.after_or_equal' => 'La fecha de entrega debe ser igual o posterior a la fecha del pedido.',
                'productos.required' => 'Debe agregar al menos un producto al pedido.',
                'productos.min' => 'Debe agregar al menos un producto al pedido.'
            ]);

            DB::beginTransaction();

            // Validar que se proporcione cliente o proveedor según el tipo
            if ($validated['tipo_pedido'] == 'compra' && !$request->filled('proveedor_id')) {
                throw new Exception('Debe seleccionar un proveedor para pedidos de compra.');
            }

            if (in_array($validated['tipo_pedido'], ['venta', 'delivery']) && !$request->filled('cliente_id')) {
                throw new Exception('Debe seleccionar un cliente para pedidos de venta/delivery.');
            }

            // Calcular totales
            $subtotal = 0;
            foreach ($request->productos as $producto) {
                $subtotal += $producto['cantidad'] * $producto['precio'];
            }

            $igv = $subtotal * 0.18;
            $total = $subtotal + $igv;

            // Crear pedido
            $pedido = Pedido::create([
                'numero_pedido' => Pedido::generarNumero($validated['tipo_pedido']),
                'cliente_id' => $validated['cliente_id'],
                'proveedor_id' => $validated['proveedor_id'],
                'fecha_pedido' => $validated['fecha_pedido'],
                'fecha_entrega_estimada' => $validated['fecha_entrega_estimada'],
                'estado' => Pedido::ESTADO_PENDIENTE,
                'tipo_pedido' => $validated['tipo_pedido'],
                'subtotal' => $subtotal,
                'igv' => $igv,
                'total' => $total,
                'observaciones' => $validated['observaciones'],
                'direccion_entrega' => $validated['direccion_entrega'],
                'telefono_contacto' => $validated['telefono_contacto'],
                'usuario_id' => Auth::id(),
                'activo' => true
            ]);

            // Crear detalles del pedido
            foreach ($request->productos as $producto) {
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio'],
                    'subtotal' => $producto['cantidad'] * $producto['precio']
                ]);
            }

            DB::commit();

            return redirect()->route('pedidos.show', $pedido)
                           ->with('success', 'Pedido creado exitosamente.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear pedido: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        try {
            $pedido->load(['cliente', 'proveedor', 'usuario', 'detalles.producto']);

            return view('pedidos.show', compact('pedido'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar pedido: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        try {
            $validated = $request->validate([
                'estado' => 'required|in:pendiente,confirmado,preparando,en_camino,entregado,cancelado',
                'fecha_entrega_real' => 'nullable|date',
                'observaciones' => 'nullable|string|max:1000'
            ]);

            $pedido->update($validated);

            return back()->with('success', 'Estado del pedido actualizado correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al actualizar pedido: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido)
    {
        try {
            if ($pedido->estado == 'entregado') {
                return back()->with('error', 'No se puede eliminar un pedido que ya fue entregado.');
            }

            $pedido->update(['activo' => false]);

            return redirect()->route('pedidos.index')
                           ->with('success', 'Pedido eliminado correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al eliminar pedido: ' . $e->getMessage());
        }
    }

    /**
     * Confirmar pedido
     */
    public function confirmar(Pedido $pedido)
    {
        try {
            if ($pedido->estado != 'pendiente') {
                return back()->with('error', 'Solo se pueden confirmar pedidos pendientes.');
            }

            $pedido->update(['estado' => 'confirmado']);

            return back()->with('success', 'Pedido confirmado correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al confirmar pedido: ' . $e->getMessage());
        }
    }

    /**
     * Convertir pedido a venta
     */
    public function convertirAVenta(Pedido $pedido)
    {
        try {
            if ($pedido->tipo_pedido != 'venta' || $pedido->estado != 'confirmado') {
                return back()->with('error', 'Solo se pueden convertir pedidos de venta confirmados.');
            }

            // Verificar stock disponible
            foreach ($pedido->detalles as $detalle) {
                if ($detalle->producto->stock_actual < $detalle->cantidad) {
                    return back()->with('error', "Stock insuficiente para el producto: {$detalle->producto->nombre}");
                }
            }

            DB::beginTransaction();

            // Crear la venta (aquí se integraría con VentaController)
            // Por ahora solo cambiamos el estado del pedido
            $pedido->update(['estado' => 'entregado']);

            DB::commit();

            return back()->with('success', 'Pedido convertido a venta exitosamente.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al convertir pedido: ' . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas de pedidos
     */
    public function estadisticas()
    {
        try {
            $estadisticas = [
                'hoy' => [
                    'total' => Pedido::whereDate('created_at', today())->count(),
                    'pendientes' => Pedido::whereDate('created_at', today())->estado('pendiente')->count(),
                    'delivery' => Pedido::whereDate('created_at', today())->tipo('delivery')->count()
                ],
                'mes' => [
                    'total' => Pedido::whereMonth('created_at', date('m'))->count(),
                    'monto' => Pedido::whereMonth('created_at', date('m'))->sum('total'),
                    'entregados' => Pedido::whereMonth('created_at', date('m'))->estado('entregado')->count()
                ]
            ];

            return response()->json($estadisticas);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 