<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalProductos = Producto::count();
        $totalClientes = Cliente::count();
        $totalVentas = Venta::count();
        $ventasHoy = Venta::whereDate('fecha', today())->count();
        
        // Productos con stock bajo
        $productosStockBajo = Producto::where('stock_actual', '<=', 10)
                                    ->where('stock_actual', '>', 0)
                                    ->count();
        
        // Productos próximos a vencer
        $productosProximosVencer = Producto::whereNotNull('fecha_vencimiento')
                                          ->whereDate('fecha_vencimiento', '<=', now()->addDays(30))
                                          ->whereDate('fecha_vencimiento', '>=', now())
                                          ->count();
        
        // Ventas del mes actual
        $ventasMes = Venta::whereMonth('fecha', now()->month)
                         ->whereYear('fecha', now()->year)
                         ->count();
        
        // Ingresos del día
        $ingresosDia = Venta::whereDate('fecha', today())->sum('total');
        
        // Ingresos del mes
        $ingresosMes = Venta::whereMonth('fecha', now()->month)
                           ->whereYear('fecha', now()->year)
                           ->sum('total');

        // Productos más vendidos (últimos 7 días)
        $productosMasVendidos = Producto::with(['categoria', 'marca'])
            ->select('productos.*')
            ->selectRaw('COALESCE(SUM(detalle_ventas.cantidad), 0) as total_vendido')
            ->leftJoin('detalle_ventas', 'productos.id', '=', 'detalle_ventas.producto_id')
            ->leftJoin('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->where(function($query) {
                $query->whereDate('ventas.fecha', '>=', now()->subDays(7))
                      ->orWhereNull('ventas.fecha');
            })
            ->groupBy('productos.id', 'productos.codigo', 'productos.nombre', 'productos.categoria_id', 'productos.marca_id', 'productos.stock_actual', 'productos.precio_compra', 'productos.precio_venta', 'productos.fecha_vencimiento', 'productos.lote', 'productos.proveedor_id', 'productos.descripcion', 'productos.activo', 'productos.created_at', 'productos.updated_at')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        // Últimas ventas
        $ultimasVentas = Venta::with(['cliente', 'user'])
                             ->orderBy('fecha', 'desc')
                             ->limit(5)
                             ->get();

        return view('dashboard.index', compact(
            'totalProductos',
            'totalClientes', 
            'totalVentas',
            'ventasHoy',
            'productosStockBajo',
            'productosProximosVencer',
            'ventasMes',
            'ingresosDia',
            'ingresosMes',
            'productosMasVendidos',
            'ultimasVentas'
        ));
    }

    public function notificaciones()
    {
        // Productos con stock bajo - devolver como colección
        $stockBajo = Producto::where('stock_actual', '<=', 10)
                            ->where('stock_actual', '>', 0)
                            ->with(['categoria', 'marca'])
                            ->get();
        
        // Productos próximos a vencer - devolver como colección
        $proximosVencer = Producto::whereNotNull('fecha_vencimiento')
                                 ->whereDate('fecha_vencimiento', '<=', now()->addDays(30))
                                 ->whereDate('fecha_vencimiento', '>=', now())
                                 ->with(['categoria', 'marca'])
                                 ->get();

        return response()->json([
            'stock_bajo' => $stockBajo,
            'proximos_vencer' => $proximosVencer,
            'total_notificaciones' => $stockBajo->count() + $proximosVencer->count()
        ]);
    }

    public function stockBajo()
    {
        $productos = Producto::where('stock_actual', '<=', 10)
                            ->where('stock_actual', '>', 0)
                            ->with(['categoria', 'marca'])
                            ->orderBy('stock_actual', 'asc')
                            ->get();

        return response()->json($productos);
    }

    public function proximosVencer()
    {
        $productos = Producto::whereNotNull('fecha_vencimiento')
                            ->whereDate('fecha_vencimiento', '<=', now()->addDays(30))
                            ->whereDate('fecha_vencimiento', '>=', now())
                            ->with(['categoria', 'marca'])
                            ->orderBy('fecha_vencimiento', 'asc')
                            ->get();

        return response()->json($productos);
    }
}
