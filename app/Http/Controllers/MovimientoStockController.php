<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientoStock;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class MovimientoStockController extends Controller
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
            $query = MovimientoStock::with(['producto.categoria', 'producto.marca', 'proveedor', 'usuario'])
                                  ->orderBy('fecha_movimiento', 'desc');

            // Filtros
            if ($request->filled('tipo')) {
                $query->where('tipo_movimiento', $request->tipo);
            }

            if ($request->filled('producto_id')) {
                $query->where('producto_id', $request->producto_id);
            }

            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha_movimiento', '>=', $request->fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha_movimiento', '<=', $request->fecha_hasta);
            }

            $movimientos = $query->paginate(20);

            $productos = Producto::activo()->orderBy('nombre')->get();
            $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();

            // Estadísticas
            $estadisticas = [
                'ingresos_hoy' => MovimientoStock::tipo('ingreso')->whereDate('fecha_movimiento', today())->sum('cantidad'),
                'egresos_hoy' => MovimientoStock::tipo('egreso')->whereDate('fecha_movimiento', today())->sum('cantidad'),
                'movimientos_mes' => MovimientoStock::whereMonth('fecha_movimiento', date('m'))->count(),
                'valor_ingresos_mes' => MovimientoStock::tipo('ingreso')->whereMonth('fecha_movimiento', date('m'))->sum('costo_total')
            ];

            return view('movimientos.index', compact('movimientos', 'productos', 'proveedores', 'estadisticas'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar movimientos: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'producto_id' => 'required|exists:productos,id',
                'tipo_movimiento' => 'required|in:ingreso,egreso,ajuste,transferencia,devolucion',
                'cantidad' => 'required|integer|min:1',
                'precio_unitario' => 'required|numeric|min:0.01',
                'motivo' => 'required|string',
                'documento_referencia' => 'nullable|string|max:100',
                'fecha_movimiento' => 'required|date',
                'fecha_vencimiento' => 'nullable|date|after:today',
                'lote' => 'nullable|string|max:50',
                'proveedor_id' => 'nullable|exists:proveedores,id',
                'observaciones' => 'nullable|string|max:1000'
            ]);

            DB::beginTransaction();

            $producto = Producto::findOrFail($validated['producto_id']);
            $stockAnterior = $producto->stock_actual;

            // Calcular nuevo stock según el tipo de movimiento
            $nuevoStock = $stockAnterior;
            switch ($validated['tipo_movimiento']) {
                case 'ingreso':
                case 'devolucion':
                    $nuevoStock += $validated['cantidad'];
                    break;
                case 'egreso':
                case 'transferencia':
                    if ($stockAnterior < $validated['cantidad']) {
                        throw new Exception('Stock insuficiente. Stock actual: ' . $stockAnterior);
                    }
                    $nuevoStock -= $validated['cantidad'];
                    break;
                case 'ajuste':
                    $nuevoStock = $validated['cantidad'];
                    break;
            }

            // Crear movimiento de stock
            MovimientoStock::create([
                'producto_id' => $validated['producto_id'],
                'tipo_movimiento' => $validated['tipo_movimiento'],
                'cantidad' => $validated['cantidad'],
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $nuevoStock,
                'precio_unitario' => $validated['precio_unitario'],
                'costo_total' => $validated['cantidad'] * $validated['precio_unitario'],
                'motivo' => $validated['motivo'],
                'documento_referencia' => $validated['documento_referencia'],
                'fecha_movimiento' => $validated['fecha_movimiento'],
                'fecha_vencimiento' => $validated['fecha_vencimiento'],
                'lote' => $validated['lote'],
                'proveedor_id' => $validated['proveedor_id'],
                'observaciones' => $validated['observaciones'],
                'usuario_id' => Auth::id()
            ]);

            // Actualizar stock del producto
            $producto->update(['stock_actual' => $nuevoStock]);

            DB::commit();

            return redirect()->route('movimientos.index')
                           ->with('success', 'Movimiento de stock registrado exitosamente.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar movimiento: ' . $e->getMessage());
        }
    }

    /**
     * Obtener kardex de producto
     */
    public function kardex(Request $request, Producto $producto)
    {
        try {
            $fechaDesde = $request->get('fecha_desde', now()->subDays(30)->toDateString());
            $fechaHasta = $request->get('fecha_hasta', now()->toDateString());

            $movimientos = MovimientoStock::where('producto_id', $producto->id)
                                        ->whereBetween('fecha_movimiento', [$fechaDesde, $fechaHasta])
                                        ->orderBy('fecha_movimiento', 'asc')
                                        ->with(['usuario', 'proveedor'])
                                        ->get();

            return view('movimientos.kardex', compact('producto', 'movimientos', 'fechaDesde', 'fechaHasta'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar kardex: ' . $e->getMessage());
        }
    }

    /**
     * Reporte de ingresos
     */
    public function reporteIngresos(Request $request)
    {
        try {
            $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->toDateString());
            $fechaHasta = $request->get('fecha_hasta', now()->toDateString());

            $ingresos = MovimientoStock::with(['producto.categoria', 'proveedor'])
                                     ->tipo('ingreso')
                                     ->whereBetween('fecha_movimiento', [$fechaDesde, $fechaHasta])
                                     ->orderBy('fecha_movimiento', 'desc')
                                     ->get();

            $resumen = [
                'total_cantidad' => $ingresos->sum('cantidad'),
                'total_costo' => $ingresos->sum('costo_total'),
                'por_categoria' => $ingresos->groupBy('producto.categoria.nombre')
                                          ->map(function($items) {
                                              return [
                                                  'cantidad' => $items->sum('cantidad'),
                                                  'costo' => $items->sum('costo_total')
                                              ];
                                          }),
                'por_proveedor' => $ingresos->whereNotNull('proveedor')
                                          ->groupBy('proveedor.nombre')
                                          ->map(function($items) {
                                              return [
                                                  'cantidad' => $items->sum('cantidad'),
                                                  'costo' => $items->sum('costo_total')
                                              ];
                                          })
            ];

            return view('movimientos.reporte-ingresos', compact('ingresos', 'resumen', 'fechaDesde', 'fechaHasta'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al generar reporte: ' . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas de movimientos
     */
    public function estadisticas(Request $request)
    {
        try {
            $periodo = $request->get('periodo', 'mes');

            $fechaDesde = match($periodo) {
                'dia' => now()->startOfDay(),
                'semana' => now()->startOfWeek(),
                'mes' => now()->startOfMonth(),
                'año' => now()->startOfYear(),
                default => now()->startOfMonth()
            };

            $estadisticas = [
                'ingresos' => [
                    'cantidad' => MovimientoStock::tipo('ingreso')->where('fecha_movimiento', '>=', $fechaDesde)->sum('cantidad'),
                    'valor' => MovimientoStock::tipo('ingreso')->where('fecha_movimiento', '>=', $fechaDesde)->sum('costo_total')
                ],
                'egresos' => [
                    'cantidad' => MovimientoStock::tipo('egreso')->where('fecha_movimiento', '>=', $fechaDesde)->sum('cantidad'),
                    'valor' => MovimientoStock::tipo('egreso')->where('fecha_movimiento', '>=', $fechaDesde)->sum('costo_total')
                ],
                'ajustes' => [
                    'cantidad' => MovimientoStock::tipo('ajuste')->where('fecha_movimiento', '>=', $fechaDesde)->count()
                ],
                'transferencias' => [
                    'cantidad' => MovimientoStock::tipo('transferencia')->where('fecha_movimiento', '>=', $fechaDesde)->sum('cantidad')
                ]
            ];

            return response()->json($estadisticas);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 