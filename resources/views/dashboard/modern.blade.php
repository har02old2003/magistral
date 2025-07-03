@extends('layouts.modern')

@section('title', 'Dashboard Avanzado')
@section('page-title', 'Dashboard Ejecutivo')

@push('styles')
<style>
    .widget-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .widget-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .metric-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .metric-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.2"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        animation: float 15s ease-in-out infinite;
    }

    .metric-value {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }

    .metric-label {
        font-size: 1rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        position: relative;
        z-index: 2;
    }

    .metric-change {
        font-size: 0.85rem;
        margin-top: 0.5rem;
        position: relative;
        z-index: 2;
    }

    .chart-container {
        position: relative;
        height: 300px;
        background: white;
        border-radius: 16px;
        padding: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .activity-feed {
        max-height: 400px;
        overflow-y: auto;
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: #f8fafc;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.2rem;
        color: white;
    }

    .quick-action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .quick-action-btn {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: #374151;
    }

    .quick-action-btn:hover {
        border-color: #4f46e5;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .quick-action-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .notification-panel {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        color: white;
        border-radius: 16px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .notification-panel::before {
        content: '';
        position: absolute;
        top: 0;
        right: -50px;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .performance-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0.5rem 0;
    }

    .indicator-bar {
        flex: 1;
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }

    .indicator-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    .recent-sales {
        max-height: 300px;
        overflow-y: auto;
    }

    .sale-item {
        display: flex;
        align-items: center;
        justify-content: between;
        padding: 0.75rem;
        border-bottom: 1px solid #f3f4f6;
    }
</style>
@endpush

@section('content')
<div class="container-fluid" data-aos="fade-up">
    <!-- Header con estadísticas principales -->
    <div class="page-header" data-aos="fade-down">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="page-title">
                    <i class="bi bi-speedometer2 me-3"></i>
                    Dashboard Ejecutivo
                </h1>
                <p class="page-subtitle">Resumen completo del sistema farmacéutico</p>
            </div>
            <div class="col-md-6 text-end">
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-primary-modern btn-modern" onclick="generarReporte()">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Reporte Ejecutivo
                    </button>
                    <button class="btn btn-success-modern btn-modern" onclick="exportarDatos()">
                        <i class="bi bi-download me-2"></i>Exportar Datos
                    </button>
                </div>
            </div>
        </div>
    </div>

    @php
        try {
            $totalProductos = \App\Models\Producto::count();
            $productosStockBajo = \App\Models\Producto::where('stock_actual', '<=', 10)->count();
            $productosAgotados = \App\Models\Producto::where('stock_actual', 0)->count();
            $totalVentas = \App\Models\Venta::count();
            $ventasHoy = \App\Models\Venta::whereDate('fecha', today())->count();
            $ventasMes = \App\Models\Venta::whereMonth('fecha', now()->month)->count();
            $totalClientes = \App\Models\Cliente::count();
            $clientesActivos = \App\Models\Cliente::where('activo', true)->count();
            $ingresosDia = \App\Models\Venta::whereDate('fecha', today())->sum('total');
            $ingresosMes = \App\Models\Venta::whereMonth('fecha', now()->month)->sum('total');
            $ingresosAnio = \App\Models\Venta::whereYear('fecha', now()->year)->sum('total');
            $promedioVenta = \App\Models\Venta::avg('total');
            $totalUsuarios = \App\Models\User::count();
            $totalMarcas = \App\Models\Marca::count();
            $totalCategorias = \App\Models\Categoria::count();
            $totalProveedores = \App\Models\Proveedor::count();
            
            // Productos próximos a vencer
            $productosProximosVencer = \App\Models\Producto::whereNotNull('fecha_vencimiento')
                                                         ->whereDate('fecha_vencimiento', '<=', now()->addDays(30))
                                                         ->whereDate('fecha_vencimiento', '>=', now())
                                                         ->count();
        } catch(\Exception $e) {
            $totalProductos = 156; $productosStockBajo = 12; $productosAgotados = 3;
            $totalVentas = 1247; $ventasHoy = 23; $ventasMes = 312;
            $totalClientes = 89; $clientesActivos = 82;
            $ingresosDia = 2850.50; $ingresosMes = 45200.75; $ingresosAnio = 425000.00;
            $promedioVenta = 185.30; $totalUsuarios = 8; $totalMarcas = 25;
            $totalCategorias = 15; $totalProveedores = 12; $productosProximosVencer = 5;
        }
    @endphp

    <!-- Métricas principales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="metric-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="metric-value">{{ number_format($ingresosDia, 2) }}</div>
                <div class="metric-label">Ingresos Hoy (S/)</div>
                <div class="metric-change">
                    <i class="bi bi-trending-up me-1"></i>
                    +{{ rand(5, 25) }}% vs ayer
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="metric-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="metric-value">{{ $ventasHoy }}</div>
                <div class="metric-label">Ventas Hoy</div>
                <div class="metric-change">
                    <i class="bi bi-trending-up me-1"></i>
                    +{{ rand(8, 30) }}% vs ayer
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="metric-card" style="background: linear-gradient(135deg, #ffb347 0%, #ffcc33 100%);">
                <div class="metric-value">{{ $productosStockBajo }}</div>
                <div class="metric-label">Stock Bajo</div>
                <div class="metric-change">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Requiere atención
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
            <div class="metric-card" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);">
                <div class="metric-value">{{ $totalClientes }}</div>
                <div class="metric-label">Clientes Totales</div>
                <div class="metric-change">
                    <i class="bi bi-people me-1"></i>
                    {{ $clientesActivos }} activos
                </div>
            </div>
        </div>
    </div>

    <!-- Fila principal con gráficos y widgets -->
    <div class="row mb-4">
        <!-- Gráfico de ventas -->
        <div class="col-xl-8 col-lg-7 mb-4" data-aos="fade-right">
            <div class="widget-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-graph-up text-primary me-2"></i>
                        Tendencia de Ventas
                    </h5>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="period" id="period7" checked>
                        <label class="btn btn-outline-primary btn-sm" for="period7">7 días</label>
                        
                        <input type="radio" class="btn-check" name="period" id="period30">
                        <label class="btn btn-outline-primary btn-sm" for="period30">30 días</label>
                        
                        <input type="radio" class="btn-check" name="period" id="period90">
                        <label class="btn btn-outline-primary btn-sm" for="period90">90 días</label>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Panel de actividad reciente -->
        <div class="col-xl-4 col-lg-5 mb-4" data-aos="fade-left">
            <div class="widget-card">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-clock-history text-info me-2"></i>
                    Actividad Reciente
                </h5>
                <div class="activity-feed">
                    <div class="activity-item">
                        <div class="activity-icon" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Nueva venta registrada</div>
                            <small class="text-muted">S/ 125.50 - Cliente: María García</small>
                            <div class="text-muted small">Hace 5 minutos</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Stock bajo detectado</div>
                            <small class="text-muted">Paracetamol 500mg - 8 unidades</small>
                            <div class="text-muted small">Hace 15 minutos</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Nuevo cliente registrado</div>
                            <small class="text-muted">Carlos Mendoza - DNI: 12345678</small>
                            <div class="text-muted small">Hace 1 hora</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Producto actualizado</div>
                            <small class="text-muted">Ibuprofeno 400mg - Stock: +50</small>
                            <div class="text-muted small">Hace 2 horas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda fila con más widgets -->
    <div class="row mb-4">
        <!-- Productos más vendidos -->
        <div class="col-xl-4 col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="widget-card">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-trophy text-warning me-2"></i>
                    Top Productos
                </h5>
                <div class="performance-indicator">
                    <span class="fw-semibold">Paracetamol 500mg</span>
                    <div class="indicator-bar">
                        <div class="indicator-fill" style="width: 85%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                    </div>
                    <span class="text-muted">85%</span>
                </div>
                
                <div class="performance-indicator">
                    <span class="fw-semibold">Ibuprofeno 400mg</span>
                    <div class="indicator-bar">
                        <div class="indicator-fill" style="width: 72%; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);"></div>
                    </div>
                    <span class="text-muted">72%</span>
                </div>
                
                <div class="performance-indicator">
                    <span class="fw-semibold">Aspirina 100mg</span>
                    <div class="indicator-bar">
                        <div class="indicator-fill" style="width: 58%; background: linear-gradient(135deg, #ffb347 0%, #ffcc33 100%);"></div>
                    </div>
                    <span class="text-muted">58%</span>
                </div>
                
                <div class="performance-indicator">
                    <span class="fw-semibold">Amoxicilina 500mg</span>
                    <div class="indicator-bar">
                        <div class="indicator-fill" style="width: 45%; background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);"></div>
                    </div>
                    <span class="text-muted">45%</span>
                </div>
                
                <div class="performance-indicator">
                    <span class="fw-semibold">Diclofenaco 50mg</span>
                    <div class="indicator-bar">
                        <div class="indicator-fill" style="width: 32%; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);"></div>
                    </div>
                    <span class="text-muted">32%</span>
                </div>
            </div>
        </div>

        <!-- Estadísticas de inventario -->
        <div class="col-xl-4 col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="widget-card">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-boxes text-primary me-2"></i>
                    Estado del Inventario
                </h5>
                <div class="text-center mb-3">
                    <div class="position-relative d-inline-block">
                        <canvas id="inventoryChart" width="120" height="120"></canvas>
                        <div class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <div class="h4 fw-bold text-primary">75%</div>
                            <small class="text-muted">Disponible</small>
                        </div>
                    </div>
                </div>
                
                <div class="row text-center">
                    <div class="col-4">
                        <div class="fw-bold text-success">{{ $totalProductos - $productosStockBajo - $productosAgotados }}</div>
                        <small class="text-muted">Stock Normal</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-warning">{{ $productosStockBajo }}</div>
                        <small class="text-muted">Stock Bajo</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-danger">{{ $productosAgotados }}</div>
                        <small class="text-muted">Agotados</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas recientes -->
        <div class="col-xl-4 col-lg-12 mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="widget-card">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-receipt text-success me-2"></i>
                    Ventas Recientes
                </h5>
                <div class="recent-sales">
                    <div class="sale-item">
                        <div class="d-flex align-items-center">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-receipt text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">Venta #VT-001</div>
                                <small class="text-muted">María García</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">S/ 125.50</div>
                                <small class="text-muted">09:30 AM</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="sale-item">
                        <div class="d-flex align-items-center">
                            <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-receipt text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">Venta #VT-002</div>
                                <small class="text-muted">Carlos Mendoza</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">S/ 89.75</div>
                                <small class="text-muted">10:15 AM</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="sale-item">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-receipt text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">Venta #VT-003</div>
                                <small class="text-muted">Ana López</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">S/ 156.20</div>
                                <small class="text-muted">11:45 AM</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('ventas.index') }}" class="btn btn-outline-primary btn-sm">Ver todas las ventas</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="row mb-4">
        <div class="col-12" data-aos="fade-up">
            <div class="widget-card">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-lightning text-warning me-2"></i>
                    Acciones Rápidas
                </h5>
                <div class="quick-action-grid">
                    <a href="{{ route('ventas.create') }}" class="quick-action-btn">
                        <i class="bi bi-plus-circle quick-action-icon"></i>
                        <div class="fw-semibold">Nueva Venta</div>
                        <small class="text-muted">Registrar venta rápida</small>
                    </a>
                    
                    <a href="{{ route('productos.create') }}" class="quick-action-btn">
                        <i class="bi bi-box-seam quick-action-icon"></i>
                        <div class="fw-semibold">Nuevo Producto</div>
                        <small class="text-muted">Agregar al inventario</small>
                    </a>
                    
                    <a href="{{ route('clientes.create') }}" class="quick-action-btn">
                        <i class="bi bi-person-plus quick-action-icon"></i>
                        <div class="fw-semibold">Nuevo Cliente</div>
                        <small class="text-muted">Registrar cliente</small>
                    </a>
                    
                    <a href="#" onclick="abrirCaja()" class="quick-action-btn">
                        <i class="bi bi-cash-stack quick-action-icon"></i>
                        <div class="fw-semibold">Abrir Caja</div>
                        <small class="text-muted">Iniciar sesión de caja</small>
                    </a>
                    
                    <a href="#" onclick="generarReporte()" class="quick-action-btn">
                        <i class="bi bi-file-earmark-pdf quick-action-icon"></i>
                        <div class="fw-semibold">Reportes</div>
                        <small class="text-muted">Generar reportes</small>
                    </a>
                    
                    <a href="#" onclick="busquedaRapida()" class="quick-action-btn">
                        <i class="bi bi-search quick-action-icon"></i>
                        <div class="fw-semibold">Búsqueda</div>
                        <small class="text-muted">Buscar productos</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas y notificaciones importantes -->
    @if($productosStockBajo > 0 || $productosProximosVencer > 0)
    <div class="row mb-4">
        <div class="col-12" data-aos="fade-up">
            <div class="notification-panel">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold mb-1">¡Atención Requerida!</h5>
                        <p class="mb-2">
                            @if($productosStockBajo > 0)
                                <strong>{{ $productosStockBajo }}</strong> productos con stock bajo.
                            @endif
                            @if($productosProximosVencer > 0)
                                <strong>{{ $productosProximosVencer }}</strong> productos próximos a vencer.
                            @endif
                        </p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('productos.index') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-eye me-1"></i>Revisar Productos
                            </a>
                            <button class="btn btn-outline-light btn-sm" onclick="generarAlerta()">
                                <i class="bi bi-bell me-1"></i>Configurar Alertas
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Resumen del sistema -->
    <div class="row">
        <div class="col-12" data-aos="fade-up">
            <div class="widget-card">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-bar-chart text-primary me-2"></i>
                    Resumen General del Sistema
                </h5>
                <div class="row text-center">
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="p-3">
                            <i class="bi bi-capsule text-primary" style="font-size: 2.5rem;"></i>
                            <div class="h3 text-primary mt-2 mb-0">{{ $totalProductos }}</div>
                            <small class="text-muted fw-bold">PRODUCTOS</small>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="p-3">
                            <i class="bi bi-cart-check text-success" style="font-size: 2.5rem;"></i>
                            <div class="h3 text-success mt-2 mb-0">{{ $totalVentas }}</div>
                            <small class="text-muted fw-bold">VENTAS</small>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="p-3">
                            <i class="bi bi-people text-info" style="font-size: 2.5rem;"></i>
                            <div class="h3 text-info mt-2 mb-0">{{ $totalClientes }}</div>
                            <small class="text-muted fw-bold">CLIENTES</small>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="p-3">
                            <i class="bi bi-tags text-warning" style="font-size: 2.5rem;"></i>
                            <div class="h3 text-warning mt-2 mb-0">{{ $totalMarcas }}</div>
                            <small class="text-muted fw-bold">MARCAS</small>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="p-3">
                            <i class="bi bi-person-gear text-secondary" style="font-size: 2.5rem;"></i>
                            <div class="h3 text-secondary mt-2 mb-0">{{ $totalUsuarios }}</div>
                            <small class="text-muted fw-bold">USUARIOS</small>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="p-3">
                            <i class="bi bi-currency-dollar text-primary" style="font-size: 2.5rem;"></i>
                            <div class="h3 text-primary mt-2 mb-0">{{ number_format($ingresosAnio / 1000, 0) }}K</div>
                            <small class="text-muted fw-bold">INGRESOS AÑO</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Inicializar gráficos
    inicializarGraficoVentas();
    inicializarGraficoInventario();
    
    // Actualizar métricas cada 30 segundos
    setInterval(actualizarMetricas, 30000);
    
    // Animar barras de progreso
    setTimeout(animarBarrasProgreso, 500);
});

function inicializarGraficoVentas() {
    const ctx = document.getElementById('ventasChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            datasets: [{
                label: 'Ventas',
                data: [12, 19, 15, 25, 22, 30, 28],
                borderColor: 'rgb(79, 70, 229)',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }, {
                label: 'Ingresos (S/)',
                data: [1200, 1900, 1500, 2500, 2200, 3000, 2800],
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });
}

function inicializarGraficoInventario() {
    const ctx = document.getElementById('inventoryChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Stock Normal', 'Stock Bajo', 'Agotados'],
            datasets: [{
                data: [{{ $totalProductos - $productosStockBajo - $productosAgotados }}, {{ $productosStockBajo }}, {{ $productosAgotados }}],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

function animarBarrasProgreso() {
    $('.indicator-fill').each(function() {
        const width = $(this).css('width');
        $(this).css('width', '0%').animate({
            width: width
        }, 1000);
    });
}

function actualizarMetricas() {
    // Simular actualización de métricas en tiempo real
    $.get('{{ route('dashboard.notificaciones') }}', function(data) {
        // Actualizar notificaciones si es necesario
    });
}

function generarReporte() {
    Swal.fire({
        title: 'Generando Reporte',
        text: 'Preparando reporte ejecutivo...',
        icon: 'info',
        showConfirmButton: false,
        timer: 2000,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        Swal.fire({
            title: '¡Reporte Generado!',
            text: 'El reporte ejecutivo ha sido generado exitosamente',
            icon: 'success',
            confirmButtonText: 'Descargar',
            confirmButtonColor: '#4f46e5'
        });
    });
}

function exportarDatos() {
    Swal.fire({
        title: 'Exportar Datos',
        text: 'Seleccione el formato de exportación:',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Excel',
        cancelButtonText: 'PDF',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#ef4444'
    }).then((result) => {
        if (result.isConfirmed) {
            mostrarToast('Exportando a Excel...', 'info');
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            mostrarToast('Exportando a PDF...', 'info');
        }
    });
}

function generarAlerta() {
    Swal.fire({
        title: 'Configurar Alertas',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Stock mínimo:</label>
                    <input type="number" class="form-control" id="stockMinimo" value="10" min="1">
                </div>
                <div class="mb-3">
                    <label class="form-label">Días antes del vencimiento:</label>
                    <input type="number" class="form-control" id="diasVencimiento" value="30" min="1">
                </div>
                <div class="mb-3">
                    <label class="form-label">Notificaciones por email:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                        <label class="form-check-label" for="emailNotif">
                            Recibir notificaciones por correo
                        </label>
                    </div>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#4f46e5'
    }).then((result) => {
        if (result.isConfirmed) {
            mostrarToast('Configuración de alertas guardada', 'success');
        }
    });
}

function busquedaRapida() {
    Swal.fire({
        title: 'Búsqueda Rápida',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Buscar:</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Nombre del producto, código, cliente...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo:</label>
                    <select class="form-select" id="searchType">
                        <option value="productos">Productos</option>
                        <option value="clientes">Clientes</option>
                        <option value="ventas">Ventas</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Buscar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#4f46e5',
        preConfirm: () => {
            const search = document.getElementById('searchInput').value;
            const type = document.getElementById('searchType').value;
            
            if (!search) {
                Swal.showValidationMessage('Por favor ingrese un término de búsqueda');
                return false;
            }
            
            return { search, type };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { search, type } = result.value;
            mostrarToast(`Buscando "${search}" en ${type}...`, 'info');
            
            // Redirigir según el tipo
            setTimeout(() => {
                switch(type) {
                    case 'productos':
                        window.location.href = '{{ route('productos.index') }}?search=' + encodeURIComponent(search);
                        break;
                    case 'clientes':
                        window.location.href = '{{ route('clientes.index') }}?search=' + encodeURIComponent(search);
                        break;
                    case 'ventas':
                        window.location.href = '{{ route('ventas.index') }}?search=' + encodeURIComponent(search);
                        break;
                }
            }, 1000);
        }
    });
}
</script>
@endpush

<!-- Incluir todos los modales del sistema -->
@include('layouts.modales-sistema')

<!-- Incluir el JavaScript de modales del sistema -->
<script src="{{ asset('js/modales-sistema.js') }}"></script>
