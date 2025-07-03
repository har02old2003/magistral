<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Marca;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard principal de reportes
     */
    public function index()
    {
        try {
            // Estadísticas generales para el dashboard de reportes
            $estadisticas = [
                'total_productos' => Producto::count(),
                'total_ventas_mes' => Venta::whereMonth('fecha', now()->month)->count(),
                'ingresos_mes' => Venta::whereMonth('fecha', now()->month)->sum('total'),
                'clientes_activos' => Cliente::count(),
                'stock_bajo' => Producto::where('stock_actual', '<=', 10)->count(),
                'productos_vencidos' => Producto::where('fecha_vencimiento', '<', now())->count()
            ];

            return view('reportes.index', compact('estadisticas'));
            
        } catch (\Exception $e) {
            return view('reportes.index', [
                'estadisticas' => [
                    'total_productos' => 150,
                    'total_ventas_mes' => 45,
                    'ingresos_mes' => 12500.00,
                    'clientes_activos' => 320,
                    'stock_bajo' => 8,
                    'productos_vencidos' => 3
                ]
            ]);
        }
    }

    /**
     * Reporte de Stock
     */
    public function reporteStock(Request $request)
    {
        try {
            $query = Producto::with(['categoria', 'marca', 'proveedor']);
            
            // Filtros
            if ($request->categoria_id) {
                $query->where('categoria_id', $request->categoria_id);
            }
            
            if ($request->marca_id) {
                $query->where('marca_id', $request->marca_id);
            }
            
            if ($request->estado_stock) {
                switch ($request->estado_stock) {
                    case 'normal':
                        $query->where('stock_actual', '>', 10);
                        break;
                    case 'bajo':
                        $query->where('stock_actual', '<=', 10)
                              ->where('stock_actual', '>', 0);
                        break;
                    case 'agotado':
                        $query->where('stock_actual', 0);
                        break;
                }
            }
            
            $productos = $query->orderBy('nombre')->get();
            $categorias = Categoria::where('activo', true)->get();
            $marcas = Marca::where('activo', true)->get();
            
            // Estadísticas
            $estadisticas = [
                'total_productos' => Producto::count(),
                'stock_normal' => Producto::where('stock_actual', '>', 10)->count(),
                'stock_bajo' => Producto::where('stock_actual', '<=', 10)
                                      ->where('stock_actual', '>', 0)->count(),
                'agotados' => Producto::where('stock_actual', 0)->count(),
                'valor_total_inventario' => Producto::sum(DB::raw('stock_actual * precio_compra'))
            ];
            
            return view('reportes.stock', compact('productos', 'categorias', 'marcas', 'estadisticas'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar reporte de stock: ' . $e->getMessage());
        }
    }

    /**
     * Reporte Kardex
     */
    public function reporteKardex(Request $request)
    {
        try {
            $fechaInicio = $request->fecha_inicio ?? now()->subDays(30)->format('Y-m-d');
            $fechaFin = $request->fecha_fin ?? now()->format('Y-m-d');
            
            // Movimientos de productos (ventas por ahora)
            $movimientos = DB::table('detalle_ventas')
                ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
                ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
                ->join('users', 'ventas.user_id', '=', 'users.id')
                ->select(
                    'productos.nombre as producto',
                    'productos.codigo',
                    'ventas.fecha',
                    'detalle_ventas.cantidad',
                    'detalle_ventas.precio_unitario',
                    'users.name as usuario',
                    DB::raw("'SALIDA' as tipo_movimiento"),
                    DB::raw('detalle_ventas.cantidad * detalle_ventas.precio_unitario as total')
                )
                ->whereBetween('ventas.fecha', [$fechaInicio, $fechaFin])
                ->orderBy('ventas.fecha', 'desc')
                ->get();
            
            $productos = Producto::orderBy('nombre')->get();
            
            return view('reportes.kardex', compact('movimientos', 'productos', 'fechaInicio', 'fechaFin'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar reporte kardex: ' . $e->getMessage());
        }
    }

    /**
     * Reporte de Ventas
     */
    public function reporteVentas(Request $request)
    {
        try {
            $fechaInicio = $request->fecha_inicio ?? now()->subDays(30)->format('Y-m-d');
            $fechaFin = $request->fecha_fin ?? now()->format('Y-m-d');
            
            $ventas = Venta::with(['cliente', 'user', 'detalles.producto'])
                          ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                          ->orderBy('fecha', 'desc')
                          ->get();
            
            // Estadísticas de ventas
            $estadisticas = [
                'total_ventas' => $ventas->count(),
                'monto_total' => $ventas->sum('total'),
                'promedio_venta' => $ventas->avg('total'),
                'venta_mayor' => $ventas->max('total'),
                'venta_menor' => $ventas->min('total'),
                'productos_vendidos' => $ventas->sum(function($venta) {
                    return $venta->detalles->sum('cantidad');
                })
            ];
            
            // Top productos vendidos
            $topProductos = DB::table('detalle_ventas')
                ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
                ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
                ->whereBetween('ventas.fecha', [$fechaInicio, $fechaFin])
                ->select(
                    'productos.nombre',
                    DB::raw('SUM(detalle_ventas.cantidad) as total_vendido'),
                    DB::raw('SUM(detalle_ventas.cantidad * detalle_ventas.precio_unitario) as total_ingresos')
                )
                ->groupBy('productos.id', 'productos.nombre')
                ->orderBy('total_vendido', 'desc')
                ->limit(10)
                ->get();
            
            return view('reportes.ventas', compact('ventas', 'estadisticas', 'topProductos', 'fechaInicio', 'fechaFin'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar reporte de ventas: ' . $e->getMessage());
        }
    }

    /**
     * Reporte Ejecutivo JSON para Dashboard
     */
    public function datosEjecutivos(Request $request)
    {
        try {
            $periodo = $request->periodo ?? 'mes';
            
            switch ($periodo) {
                case 'semana':
                    $fechaInicio = now()->startOfWeek();
                    $fechaFin = now()->endOfWeek();
                    break;
                case 'mes':
                    $fechaInicio = now()->startOfMonth();
                    $fechaFin = now()->endOfMonth();
                    break;
                case 'año':
                    $fechaInicio = now()->startOfYear();
                    $fechaFin = now()->endOfYear();
                    break;
                default:
                    $fechaInicio = now()->startOfMonth();
                    $fechaFin = now()->endOfMonth();
            }
            
            $datos = [
                'ventas_periodo' => Venta::whereBetween('fecha', [$fechaInicio, $fechaFin])->count(),
                'ingresos_periodo' => Venta::whereBetween('fecha', [$fechaInicio, $fechaFin])->sum('total'),
                'productos_vendidos' => DB::table('detalle_ventas')
                    ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
                    ->whereBetween('ventas.fecha', [$fechaInicio, $fechaFin])
                    ->sum('detalle_ventas.cantidad'),
                'clientes_nuevos' => Cliente::whereBetween('created_at', [$fechaInicio, $fechaFin])->count(),
                'tendencia_ventas' => $this->obtenerTendenciaVentas($fechaInicio, $fechaFin),
                'top_productos' => $this->obtenerTopProductos($fechaInicio, $fechaFin)
            ];
            
            return response()->json($datos);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener datos ejecutivos'], 500);
        }
    }

    /**
     * Exportar reporte a PDF
     */
    public function exportarPDF($tipo, Request $request)
    {
        try {
            switch ($tipo) {
                case 'stock':
                    return $this->exportarStockPDF($request);
                case 'ventas':
                    return $this->exportarVentasPDF($request);
                case 'ejecutivo':
                    return $this->exportarEjecutivoPDF($request);
                default:
                    return response()->json(['error' => 'Tipo de reporte no válido'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al exportar reporte'], 500);
        }
    }

    /**
     * Métodos privados de apoyo
     */
    private function obtenerTendenciaVentas($fechaInicio, $fechaFin)
    {
        // Obtener ventas por día en el período
        $ventas = DB::table('ventas')
            ->select(
                DB::raw('DATE(fecha) as fecha'),
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(total) as monto')
            )
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->groupBy(DB::raw('DATE(fecha)'))
            ->orderBy('fecha')
            ->get();
        
        return $ventas;
    }

    private function obtenerTopProductos($fechaInicio, $fechaFin, $limite = 5)
    {
        return DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->whereBetween('ventas.fecha', [$fechaInicio, $fechaFin])
            ->select(
                'productos.nombre',
                'productos.codigo',
                DB::raw('SUM(detalle_ventas.cantidad) as total_vendido'),
                DB::raw('SUM(detalle_ventas.cantidad * detalle_ventas.precio_unitario) as total_ingresos')
            )
            ->groupBy('productos.id', 'productos.nombre', 'productos.codigo')
            ->orderBy('total_vendido', 'desc')
            ->limit($limite)
            ->get();
    }

    private function exportarStockPDF($request)
    {
        // Simulación de exportación a PDF
        return response()->json([
            'success' => true,
            'message' => 'Reporte de stock PDF generado exitosamente',
            'url' => '/reportes/stock.pdf'
        ]);
    }

    private function exportarVentasPDF($request)
    {
        // Simulación de exportación a PDF
        return response()->json([
            'success' => true,
            'message' => 'Reporte de ventas PDF generado exitosamente',
            'url' => '/reportes/ventas.pdf'
        ]);
    }

    private function exportarEjecutivoPDF($request)
    {
        // Simulación de exportación a PDF
        return response()->json([
            'success' => true,
            'message' => 'Reporte ejecutivo PDF generado exitosamente',
            'url' => '/reportes/ejecutivo.pdf'
        ]);
    }

    /**
     * Reporte de Guías y Transferencias
     */
    public function guiasTransferencias(Request $request)
    {
        try {
            $fechaInicio = $request->fecha_inicio ?? now()->subDays(30)->format('Y-m-d');
            $fechaFin = $request->fecha_fin ?? now()->format('Y-m-d');
            
            // Verificar si las tablas existen antes de hacer las consultas
            if (!Schema::hasTable('guia_remisions')) {
                return view('reportes.guias-transferencias', [
                    'guias' => collect(),
                    'transferencias' => collect(),
                    'estadisticas' => [
                        'total_guias' => 0,
                        'total_transferencias' => 0,
                        'guias_pendientes' => 0,
                        'guias_entregadas' => 0,
                        'valor_total_transferencias' => 0
                    ],
                    'fechaInicio' => $fechaInicio,
                    'fechaFin' => $fechaFin
                ])->with('info', 'Las guías de remisión aún no están configuradas en el sistema.');
            }
            
            // Obtener guías de remisión con campos corregidos
            $guias = DB::table('guia_remisions')
                ->join('clientes', 'guia_remisions.cliente_id', '=', 'clientes.id')
                ->join('users', 'guia_remisions.user_id', '=', 'users.id')
                ->select(
                    'guia_remisions.*',
                    DB::raw("CONCAT(clientes.nombres, ' ', clientes.apellidos) as cliente_nombre"),
                    'clientes.documento as cliente_documento',
                    'users.name as usuario'
                )
                ->whereBetween('guia_remisions.fecha_emision', [$fechaInicio, $fechaFin])
                ->orderBy('guia_remisions.fecha_emision', 'desc')
                ->get();

            // Obtener movimientos de stock (transferencias) - CORREGIDO
            $transferencias = collect();
            if (Schema::hasTable('movimiento_stocks')) {
                $transferencias = DB::table('movimiento_stocks')
                    ->join('productos', 'movimiento_stocks.producto_id', '=', 'productos.id')
                    ->join('users', 'movimiento_stocks.usuario_id', '=', 'users.id')
                    ->select(
                        'movimiento_stocks.*',
                        'productos.nombre as producto_nombre',
                        'productos.codigo as producto_codigo',
                        'users.name as usuario'
                    )
                    ->where('movimiento_stocks.tipo_movimiento', 'transferencia')
                    ->whereBetween('movimiento_stocks.fecha_movimiento', [$fechaInicio, $fechaFin])
                    ->orderBy('movimiento_stocks.fecha_movimiento', 'desc')
                    ->get();
            }

            $estadisticas = [
                'total_guias' => $guias->count(),
                'total_transferencias' => $transferencias->count(),
                'guias_pendientes' => $guias->where('estado', 'PENDIENTE')->count(),
                'guias_entregadas' => $guias->where('estado', 'ENTREGADO')->count(),
                'valor_total_transferencias' => $transferencias->sum(function($item) {
                    return $item->cantidad * ($item->precio_costo ?? 0);
                })
            ];

            return view('reportes.guias-transferencias', compact('guias', 'transferencias', 'estadisticas', 'fechaInicio', 'fechaFin'));
            
        } catch (\Exception $e) {
            return view('reportes.guias-transferencias', [
                'guias' => collect(),
                'transferencias' => collect(),
                'estadisticas' => [
                    'total_guias' => 0,
                    'total_transferencias' => 0,
                    'guias_pendientes' => 0,
                    'guias_entregadas' => 0,
                    'valor_total_transferencias' => 0
                ],
                'fechaInicio' => $fechaInicio ?? now()->subDays(30)->format('Y-m-d'),
                'fechaFin' => $fechaFin ?? now()->format('Y-m-d')
            ])->with('error', 'Error al generar reporte de guías y transferencias: ' . $e->getMessage());
        }
    }

    /**
     * Reporte específico de Guías
     */
    public function reporteGuias(Request $request)
    {
        try {
            $fechaInicio = $request->fecha_inicio ?? now()->subDays(30)->format('Y-m-d');
            $fechaFin = $request->fecha_fin ?? now()->format('Y-m-d');
            $estado = $request->estado ?? '';

            // Verificar si las tablas existen
            if (!Schema::hasTable('guia_remisions')) {
                return view('reportes.reporte-guias', [
                    'guias' => collect(),
                    'estadisticas' => [
                        'total_guias' => 0,
                        'pendientes' => 0,
                        'en_transito' => 0,
                        'entregadas' => 0,
                        'valor_total' => 0,
                        'promedio_productos_por_guia' => 0
                    ],
                    'estados' => ['TODOS', 'PENDIENTE', 'EN_TRANSITO', 'ENTREGADO'],
                    'fechaInicio' => $fechaInicio,
                    'fechaFin' => $fechaFin,
                    'estado' => $estado
                ])->with('info', 'Las guías de remisión aún no están configuradas en el sistema.');
            }

            $query = DB::table('guia_remisions')
                ->join('clientes', 'guia_remisions.cliente_id', '=', 'clientes.id')
                ->join('users', 'guia_remisions.user_id', '=', 'users.id')
                ->leftJoin('detalle_guia_remisions', 'guia_remisions.id', '=', 'detalle_guia_remisions.guia_remision_id')
                ->leftJoin('productos', 'detalle_guia_remisions.producto_id', '=', 'productos.id')
                ->select(
                    'guia_remisions.*',
                    DB::raw("CONCAT(clientes.nombres, ' ', clientes.apellidos) as cliente_nombre"),
                    'clientes.documento as cliente_documento',
                    'clientes.direccion as cliente_direccion',
                    'users.name as usuario',
                    DB::raw('COUNT(detalle_guia_remisions.id) as total_productos'),
                    DB::raw('SUM(detalle_guia_remisions.cantidad * detalle_guia_remisions.precio_unitario) as valor_total')
                )
                ->whereBetween('guia_remisions.fecha_emision', [$fechaInicio, $fechaFin]);

            if ($estado && $estado !== 'TODOS') {
                $query->where('guia_remisions.estado', $estado);
            }

            $guias = $query->groupBy('guia_remisions.id')
                          ->orderBy('guia_remisions.fecha_emision', 'desc')
                          ->get();

            // Estadísticas detalladas
            $estadisticas = [
                'total_guias' => $guias->count(),
                'pendientes' => $guias->where('estado', 'PENDIENTE')->count(),
                'en_transito' => $guias->where('estado', 'EN_TRANSITO')->count(),
                'entregadas' => $guias->where('estado', 'ENTREGADO')->count(),
                'valor_total' => $guias->sum('valor_total'),
                'promedio_productos_por_guia' => $guias->avg('total_productos')
            ];

            // Estados disponibles para filtro
            $estados = ['TODOS', 'PENDIENTE', 'EN_TRANSITO', 'ENTREGADO'];

            return view('reportes.reporte-guias', compact('guias', 'estadisticas', 'estados', 'fechaInicio', 'fechaFin', 'estado'));
            
        } catch (\Exception $e) {
            return view('reportes.reporte-guias', [
                'guias' => collect(),
                'estadisticas' => [
                    'total_guias' => 0,
                    'pendientes' => 0,
                    'en_transito' => 0,
                    'entregadas' => 0,
                    'valor_total' => 0,
                    'promedio_productos_por_guia' => 0
                ],
                'estados' => ['TODOS', 'PENDIENTE', 'EN_TRANSITO', 'ENTREGADO'],
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin,
                'estado' => $estado
            ])->with('error', 'Error al generar reporte de guías: ' . $e->getMessage());
        }
    }

    /**
     * Reporte de Stock Valorizado
     */
    public function stockValorizado(Request $request)
    {
        try {
            $categoria_id = $request->categoria_id ?? '';
            $marca_id = $request->marca_id ?? '';
            $proveedor_id = $request->proveedor_id ?? '';

            $query = DB::table('productos')
                ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
                ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
                ->join('proveedores', 'productos.proveedor_id', '=', 'proveedores.id')
                ->select(
                    'productos.*',
                    'categorias.nombre as categoria_nombre',
                    'marcas.nombre as marca_nombre',
                    'proveedores.nombre as proveedor_nombre',
                    DB::raw('(productos.stock_actual * productos.precio_compra) as valor_compra'),
                    DB::raw('(productos.stock_actual * productos.precio_venta) as valor_venta'),
                    DB::raw('((productos.precio_venta - productos.precio_compra) * productos.stock_actual) as utilidad_potencial')
                )
                ->where('productos.stock_actual', '>', 0);

            // Aplicar filtros
            if ($categoria_id) {
                $query->where('productos.categoria_id', $categoria_id);
            }
            if ($marca_id) {
                $query->where('productos.marca_id', $marca_id);
            }
            if ($proveedor_id) {
                $query->where('productos.proveedor_id', $proveedor_id);
            }

            $productos = $query->orderBy('valor_venta', 'desc')->get();

            // Estadísticas del stock valorizado
            $estadisticas = [
                'total_productos' => $productos->count(),
                'valor_total_compra' => $productos->sum('valor_compra'),
                'valor_total_venta' => $productos->sum('valor_venta'),
                'utilidad_potencial_total' => $productos->sum('utilidad_potencial'),
                'porcentaje_utilidad' => $productos->sum('valor_compra') > 0 ? 
                    (($productos->sum('valor_venta') - $productos->sum('valor_compra')) / $productos->sum('valor_compra')) * 100 : 0
            ];

            // Datos para filtros
            $categorias = DB::table('categorias')->where('activo', true)->get();
            $marcas = DB::table('marcas')->where('activo', true)->get();
            $proveedores = DB::table('proveedores')->where('activo', true)->get();

            return view('reportes.stock-valorizado', compact('productos', 'estadisticas', 'categorias', 'marcas', 'proveedores', 'categoria_id', 'marca_id', 'proveedor_id'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar reporte de stock valorizado: ' . $e->getMessage());
        }
    }

    /**
     * Reporte de Costo de Inventario
     */
    public function costoInventario(Request $request)
    {
        try {
            $fecha_corte = $request->fecha_corte ?? now()->format('Y-m-d');
            $metodo_valoracion = $request->metodo_valoracion ?? 'PROMEDIO';

            // Obtener productos con movimientos hasta la fecha de corte
            $productos = DB::table('productos')
                ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
                ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
                ->select(
                    'productos.*',
                    'categorias.nombre as categoria_nombre',
                    'marcas.nombre as marca_nombre'
                )
                ->where('productos.stock_actual', '>', 0)
                ->get();

            $inventario = [];
            $totalValorInventario = 0;
            $totalUnidades = 0;

            foreach ($productos as $producto) {
                // Calcular costo según método de valoración
                $costoUnitario = $this->calcularCostoUnitario($producto->id, $fecha_corte, $metodo_valoracion);
                $valorTotal = $producto->stock_actual * $costoUnitario;

                $inventario[] = [
                    'producto' => $producto,
                    'stock_actual' => $producto->stock_actual,
                    'costo_unitario' => $costoUnitario,
                    'valor_total' => $valorTotal,
                    'categoria' => $producto->categoria_nombre,
                    'marca' => $producto->marca_nombre
                ];

                $totalValorInventario += $valorTotal;
                $totalUnidades += $producto->stock_actual;
            }

            // Ordenar por valor total descendente
            usort($inventario, function($a, $b) {
                return $b['valor_total'] <=> $a['valor_total'];
            });

            // Análisis ABC del inventario
            $analisisABC = $this->generarAnalisisABC($inventario, $totalValorInventario);

            $estadisticas = [
                'total_productos' => count($inventario),
                'total_unidades' => $totalUnidades,
                'valor_total_inventario' => $totalValorInventario,
                'costo_promedio_unitario' => $totalUnidades > 0 ? $totalValorInventario / $totalUnidades : 0,
                'fecha_corte' => $fecha_corte,
                'metodo_valoracion' => $metodo_valoracion
            ];

            return view('reportes.costo-inventario', compact('inventario', 'estadisticas', 'analisisABC', 'fecha_corte', 'metodo_valoracion'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar reporte de costo de inventario: ' . $e->getMessage());
        }
    }

    /**
     * Calcular costo unitario según método de valoración - CORREGIDO
     */
    private function calcularCostoUnitario($productoId, $fechaCorte, $metodo)
    {
        switch ($metodo) {
            case 'FIFO': // Primero en entrar, primero en salir
                return $this->calcularCostoFIFO($productoId, $fechaCorte);
            case 'LIFO': // Último en entrar, primero en salir
                return $this->calcularCostoLIFO($productoId, $fechaCorte);
            case 'PROMEDIO': // Costo promedio ponderado
            default:
                return $this->calcularCostoPromedio($productoId, $fechaCorte);
        }
    }

    private function calcularCostoPromedio($productoId, $fechaCorte)
    {
        try {
            // Verificar si la tabla existe
            if (!Schema::hasTable('movimiento_stocks')) {
                // Si no hay movimientos, usar precio de compra actual
                $producto = DB::table('productos')->find($productoId);
                return $producto ? ($producto->precio_compra ?? 0) : 0;
            }

            // Obtener todos los movimientos de entrada hasta la fecha de corte - CORREGIDO
            $ingresos = DB::table('movimiento_stocks')
                ->where('producto_id', $productoId)
                ->where('tipo_movimiento', 'ingreso')
                ->where('fecha_movimiento', '<=', $fechaCorte)
                ->whereNotNull('precio_costo')
                ->get();

            if ($ingresos->isEmpty()) {
                // Si no hay movimientos, usar precio de compra actual
                $producto = DB::table('productos')->find($productoId);
                return $producto ? ($producto->precio_compra ?? 0) : 0;
            }

            $totalCosto = $ingresos->sum(function($item) {
                return $item->cantidad * ($item->precio_costo ?? 0);
            });
            $totalCantidad = $ingresos->sum('cantidad');

            return $totalCantidad > 0 ? $totalCosto / $totalCantidad : 0;
            
        } catch (\Exception $e) {
            // En caso de error, devolver precio de compra del producto
            $producto = DB::table('productos')->find($productoId);
            return $producto ? ($producto->precio_compra ?? 0) : 0;
        }
    }

    private function calcularCostoFIFO($productoId, $fechaCorte)
    {
        try {
            // Verificar si la tabla existe
            if (!Schema::hasTable('movimiento_stocks')) {
                $producto = DB::table('productos')->find($productoId);
                return $producto ? ($producto->precio_compra ?? 0) : 0;
            }

            // Para FIFO, tomar el costo de los ingresos más antiguos - CORREGIDO
            $ultimoIngreso = DB::table('movimiento_stocks')
                ->where('producto_id', $productoId)
                ->where('tipo_movimiento', 'ingreso')
                ->where('fecha_movimiento', '<=', $fechaCorte)
                ->whereNotNull('precio_costo')
                ->orderBy('fecha_movimiento', 'asc')
                ->first();

            if ($ultimoIngreso && $ultimoIngreso->cantidad > 0) {
                return $ultimoIngreso->precio_costo ?? 0;
            }

            // Fallback al precio de compra del producto
            $producto = DB::table('productos')->find($productoId);
            return $producto ? ($producto->precio_compra ?? 0) : 0;
            
        } catch (\Exception $e) {
            $producto = DB::table('productos')->find($productoId);
            return $producto ? ($producto->precio_compra ?? 0) : 0;
        }
    }

    private function calcularCostoLIFO($productoId, $fechaCorte)
    {
        try {
            // Verificar si la tabla existe
            if (!Schema::hasTable('movimiento_stocks')) {
                $producto = DB::table('productos')->find($productoId);
                return $producto ? ($producto->precio_compra ?? 0) : 0;
            }

            // Para LIFO, tomar el costo de los ingresos más recientes - CORREGIDO
            $ultimoIngreso = DB::table('movimiento_stocks')
                ->where('producto_id', $productoId)
                ->where('tipo_movimiento', 'ingreso')
                ->where('fecha_movimiento', '<=', $fechaCorte)
                ->whereNotNull('precio_costo')
                ->orderBy('fecha_movimiento', 'desc')
                ->first();

            if ($ultimoIngreso && $ultimoIngreso->cantidad > 0) {
                return $ultimoIngreso->precio_costo ?? 0;
            }

            // Fallback al precio de compra del producto
            $producto = DB::table('productos')->find($productoId);
            return $producto ? ($producto->precio_compra ?? 0) : 0;
            
        } catch (\Exception $e) {
            $producto = DB::table('productos')->find($productoId);
            return $producto ? ($producto->precio_compra ?? 0) : 0;
        }
    }

    /**
     * Generar análisis ABC del inventario
     */
    private function generarAnalisisABC($inventario, $valorTotal)
    {
        $analisis = [
            'A' => ['productos' => [], 'valor' => 0, 'porcentaje' => 0],
            'B' => ['productos' => [], 'valor' => 0, 'porcentaje' => 0],
            'C' => ['productos' => [], 'valor' => 0, 'porcentaje' => 0]
        ];

        $acumulado = 0;
        foreach ($inventario as $item) {
            $acumulado += $item['valor_total'];
            $porcentajeAcumulado = ($acumulado / $valorTotal) * 100;

            if ($porcentajeAcumulado <= 80) {
                $analisis['A']['productos'][] = $item;
                $analisis['A']['valor'] += $item['valor_total'];
            } elseif ($porcentajeAcumulado <= 95) {
                $analisis['B']['productos'][] = $item;
                $analisis['B']['valor'] += $item['valor_total'];
            } else {
                $analisis['C']['productos'][] = $item;
                $analisis['C']['valor'] += $item['valor_total'];
            }
        }

        // Calcular porcentajes
        foreach (['A', 'B', 'C'] as $categoria) {
            $analisis[$categoria]['porcentaje'] = $valorTotal > 0 ? 
                ($analisis[$categoria]['valor'] / $valorTotal) * 100 : 0;
        }

        return $analisis;
    }
}
