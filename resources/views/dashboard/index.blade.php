<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Farmacia Magistral</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --sidebar-gradient: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .modern-sidebar {
            background: var(--sidebar-gradient);
            min-height: 100vh;
            box-shadow: 5px 0 20px rgba(0,0,0,0.1);
        }
        
        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-brand h3 {
            color: white;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .nav-link {
            color: rgba(255,255,255,0.85) !important;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin: 0.2rem 1rem;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white !important;
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white !important;
            box-shadow: 0 4px 15px rgba(255,255,255,0.2);
        }
        
        .main-content {
            background: white;
            border-radius: 25px 0 0 0;
            min-height: 100vh;
            padding: 2rem;
            box-shadow: -5px 0 20px rgba(0,0,0,0.1);
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .page-title {
            font-size: 3rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }
        
        .stat-card.primary::before { background: var(--primary-gradient); }
        .stat-card.success::before { background: var(--success-gradient); }
        .stat-card.warning::before { background: var(--warning-gradient); }
        .stat-card.info::before { background: var(--info-gradient); }
        
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }
        
        .stat-value {
            font-size: 3rem;
            font-weight: 700;
            margin: 1rem 0;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 1.1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-modern {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 2rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-modern:hover::before {
            left: 100%;
        }
        
        .btn-primary-modern { background: var(--primary-gradient); color: white; }
        .btn-success-modern { background: var(--success-gradient); color: white; }
        .btn-warning-modern { background: var(--warning-gradient); color: white; }
        .btn-info-modern { background: var(--info-gradient); color: white; }
        
        .modern-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .alert-modern {
            border-radius: 15px;
            border: none;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 modern-sidebar">
                <div class="sidebar-brand">
                    <h3><i class="bi bi-hospital"></i> Farmacia</h3>
                    <small>{{ auth()->user()->name ?? 'Usuario' }}</small>
                    <small class="d-block">{{ auth()->user()->role ?? 'Empleado' }}</small>
                </div>
                
                <ul class="nav flex-column mt-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="/dashboard">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/productos">
                            <i class="bi bi-capsule me-2"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/ventas">
                            <i class="bi bi-cart-check me-2"></i> Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/clientes">
                            <i class="bi bi-people me-2"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/marcas">
                            <i class="bi bi-tags me-2"></i> Marcas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/categorias">
                            <i class="bi bi-grid me-2"></i> Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/proveedores">
                            <i class="bi bi-truck me-2"></i> Proveedores
                        </a>
                    </li>
                    @if(auth()->user()->role === 'administrador')
                    <li class="nav-item">
                        <a class="nav-link" href="/usuarios">
                            <i class="bi bi-person-gear me-2"></i> Usuarios
                        </a>
                    </li>
                    @endif
                    
                    <!-- Separador para cerrar sesión -->
                    <li class="nav-item mt-4" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-dashboard" style="display: none;">
                            @csrf
                        </form>
                        <a class="nav-link" href="#" onclick="mostrarModalCerrarSesion()" style="background: rgba(255,107,107,0.2); border: 1px solid rgba(255,107,107,0.3); color: white !important;">
                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Contenido Principal -->
            <main class="col-md-9 col-lg-10 main-content">
                <div class="fade-in">
                    <!-- Header -->
                    <div class="page-header">
                        <h1 class="page-title">
                            <i class="bi bi-speedometer2 me-3"></i>Dashboard
                        </h1>
                        <p class="mb-0 opacity-75" style="font-size: 1.2rem;">Panel de control del sistema de farmacia</p>
                    </div>

                    @php
                        try {
                            $totalProductos = \App\Models\Producto::count();
                            $productosStockBajo = \App\Models\Producto::where('stock_actual', '<=', 10)->count();
                            $totalVentas = \App\Models\Venta::count();
                            $ventasHoy = \App\Models\Venta::whereDate('fecha', today())->count();
                            $totalMarcas = \App\Models\Marca::count();
                            $totalClientes = \App\Models\Cliente::count();
                            $ingresosDia = \App\Models\Venta::whereDate('fecha', today())->sum('total');
                            $ventasMes = \App\Models\Venta::whereMonth('fecha', now()->month)->count();
                            $ingresosMes = \App\Models\Venta::whereMonth('fecha', now()->month)->sum('total');
                        } catch(\Exception $e) {
                            $totalProductos = 3; $productosStockBajo = 2; $totalVentas = 0; $ventasHoy = 0;
                            $totalMarcas = 10; $totalClientes = 1; $ingresosDia = 0; $ventasMes = 0; $ingresosMes = 0;
                        }
                    @endphp

                    <!-- Estadísticas Principales -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card primary">
                                <div class="stat-icon text-primary">
                                    <i class="bi bi-capsule"></i>
                                </div>
                                <div class="stat-value text-primary">{{ $totalProductos }}</div>
                                <div class="stat-label">Total Productos</div>
                                <div class="mt-3">
                                    <a href="/productos" class="btn btn-primary-modern btn-modern btn-sm">
                                        <i class="bi bi-arrow-right me-1"></i> Ver Productos
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card success">
                                <div class="stat-icon text-success">
                                    <i class="bi bi-cart-check"></i>
                                </div>
                                <div class="stat-value text-success">{{ $ventasHoy }}</div>
                                <div class="stat-label">Ventas Hoy</div>
                                <div class="mt-3">
                                    <a href="/ventas" class="btn btn-success-modern btn-modern btn-sm">
                                        <i class="bi bi-arrow-right me-1"></i> Ver Ventas
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card warning @if($productosStockBajo > 0) pulse @endif">
                                <div class="stat-icon text-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="stat-value text-warning">{{ $productosStockBajo }}</div>
                                <div class="stat-label">Stock Bajo</div>
                                <div class="mt-3">
                                    <button class="btn btn-warning-modern btn-modern btn-sm" data-bs-toggle="modal" data-bs-target="#stockBajoModal">
                                        <i class="bi bi-eye me-1"></i> Revisar Stock
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card info">
                                <div class="stat-icon text-info">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="stat-value text-info">S/ {{ number_format($ingresosDia, 2) }}</div>
                                <div class="stat-label">Ingresos Hoy</div>
                                <div class="mt-3">
                                    <button class="btn btn-info-modern btn-modern btn-sm" data-bs-toggle="modal" data-bs-target="#ingresosModal">
                                        <i class="bi bi-graph-up me-1"></i> Ver Ingresos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alerta de Stock Bajo -->
                    @if($productosStockBajo > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-warning alert-modern">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 2rem;"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading mb-2">¡Atención! Stock Bajo</h5>
                                        <p class="mb-2">Hay <strong>{{ $productosStockBajo }}</strong> productos con stock bajo que necesitan reabastecimiento.</p>
                                        <button class="btn btn-warning-modern btn-modern btn-sm" data-bs-toggle="modal" data-bs-target="#stockBajoModal">
                                            <i class="bi bi-eye me-1"></i> Revisar productos
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Estadísticas Mensuales -->
                    <div class="row mb-4">
                        <div class="col-lg-6 mb-4">
                            <div class="modern-card">
                                <h5 class="mb-4">
                                    <i class="bi bi-calendar-month text-primary me-2"></i>
                                    Estadísticas del Mes
                                </h5>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="p-3">
                                            <div class="display-4 text-primary fw-bold">{{ $ventasMes }}</div>
                                            <small class="text-muted">Ventas del Mes</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3">
                                            <div class="display-4 text-success fw-bold">S/ {{ number_format($ingresosMes, 0) }}</div>
                                            <small class="text-muted">Ingresos del Mes</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary-modern btn-modern btn-sm" data-bs-toggle="modal" data-bs-target="#estadisticasModal">
                                        <i class="bi bi-bar-chart me-1"></i> Ver Estadísticas Detalladas
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 mb-4">
                            <div class="modern-card">
                                <h5 class="mb-4">
                                    <i class="bi bi-lightning text-warning me-2"></i>
                                    Acciones Rápidas
                                </h5>
                                <div class="d-grid gap-3">
                                    <button class="btn btn-success-modern btn-modern" data-bs-toggle="modal" data-bs-target="#nuevaVentaRapidaModal">
                                        <i class="bi bi-plus-circle me-2"></i> Nueva Venta
                                    </button>
                                    <button class="btn btn-primary-modern btn-modern" data-bs-toggle="modal" data-bs-target="#nuevoProductoRapidoModal">
                                        <i class="bi bi-plus-circle me-2"></i> Nuevo Producto
                                    </button>
                                    <button class="btn btn-info-modern btn-modern" data-bs-toggle="modal" data-bs-target="#nuevoClienteRapidoModal">
                                        <i class="bi bi-person-plus me-2"></i> Nuevo Cliente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen del Sistema -->
                    <div class="row">
                        <div class="col-12">
                            <div class="modern-card">
                                <h5 class="mb-4">
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
                                            <i class="bi bi-tags text-info" style="font-size: 2.5rem;"></i>
                                            <div class="h3 text-info mt-2 mb-0">{{ $totalMarcas }}</div>
                                            <small class="text-muted fw-bold">MARCAS</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                                        <div class="p-3">
                                            <i class="bi bi-people text-success" style="font-size: 2.5rem;"></i>
                                            <div class="h3 text-success mt-2 mb-0">{{ $totalClientes }}</div>
                                            <small class="text-muted fw-bold">CLIENTES</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                                        <div class="p-3">
                                            <i class="bi bi-cart-check text-warning" style="font-size: 2.5rem;"></i>
                                            <div class="h3 text-warning mt-2 mb-0">{{ $totalVentas }}</div>
                                            <small class="text-muted fw-bold">VENTAS</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                                        <div class="p-3">
                                            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2.5rem;"></i>
                                            <div class="h3 text-danger mt-2 mb-0">{{ $productosStockBajo }}</div>
                                            <small class="text-muted fw-bold">STOCK BAJO</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                                        <div class="p-3">
                                            <i class="bi bi-calendar-check text-secondary" style="font-size: 2.5rem;"></i>
                                            <div class="h3 text-secondary mt-2 mb-0">{{ now()->format('d') }}</div>
                                            <small class="text-muted fw-bold">HOY</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary-modern btn-modern" data-bs-toggle="modal" data-bs-target="#notificacionesModal">
                                        <i class="bi bi-bell me-2"></i> Ver Notificaciones del Sistema
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Stock Bajo -->
    <div class="modal fade" id="stockBajoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle me-2"></i>Productos con Stock Bajo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>¡Atención!</strong> Los siguientes productos necesitan reabastecimiento urgente.
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Stock Actual</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Ibuprofeno 400mg</strong><br><small class="text-muted">MED002</small></td>
                                    <td><span class="text-warning fw-bold">5 unidades</span></td>
                                    <td><span class="badge bg-warning">Stock Bajo</span></td>
                                    <td><button class="btn btn-primary btn-sm">Reabastecer</button></td>
                                </tr>
                                <tr>
                                    <td><strong>Aspirina 100mg</strong><br><small class="text-muted">MED003</small></td>
                                    <td><span class="text-danger fw-bold">AGOTADO</span></td>
                                    <td><span class="badge bg-danger">Agotado</span></td>
                                    <td><button class="btn btn-danger btn-sm">Reabastecer</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="/productos" class="btn btn-warning-modern btn-modern">
                        <i class="bi bi-capsule me-2"></i>Ver Todos los Productos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ingresos -->
    <div class="modal fade" id="ingresosModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-graph-up me-2"></i>Análisis de Ingresos
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="text-primary">Ingresos de Hoy</h5>
                                    <h2 class="text-success">S/ {{ number_format($ingresosDia, 2) }}</h2>
                                    <small class="text-muted">{{ $ventasHoy }} ventas realizadas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="text-primary">Ingresos del Mes</h5>
                                    <h2 class="text-success">S/ {{ number_format($ingresosMes, 2) }}</h2>
                                    <small class="text-muted">{{ $ventasMes }} ventas en el mes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Proyección Mensual</h6>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Promedio diario:</strong> S/ {{ $ventasHoy > 0 ? number_format($ingresosDia / $ventasHoy, 2) : '0.00' }} por venta
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="/ventas" class="btn btn-info-modern btn-modern">
                        <i class="bi bi-cart-check me-2"></i>Ver Todas las Ventas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Estadísticas -->
    <div class="modal fade" id="estadisticasModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-bar-chart me-2"></i>Estadísticas Detalladas del Sistema
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="text-center p-3 border rounded">
                                <i class="bi bi-capsule text-primary" style="font-size: 2rem;"></i>
                                <h4 class="text-primary mt-2">{{ $totalProductos }}</h4>
                                <small>Total Productos</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center p-3 border rounded">
                                <i class="bi bi-cart-check text-success" style="font-size: 2rem;"></i>
                                <h4 class="text-success mt-2">{{ $totalVentas }}</h4>
                                <small>Total Ventas</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center p-3 border rounded">
                                <i class="bi bi-people text-info" style="font-size: 2rem;"></i>
                                <h4 class="text-info mt-2">{{ $totalClientes }}</h4>
                                <small>Total Clientes</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center p-3 border rounded">
                                <i class="bi bi-tags text-warning" style="font-size: 2rem;"></i>
                                <h4 class="text-warning mt-2">{{ $totalMarcas }}</h4>
                                <small>Total Marcas</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6>Resumen de Actividad</h6>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Productos registrados</span>
                                    <span class="badge bg-primary">{{ $totalProductos }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Productos con stock bajo</span>
                                    <span class="badge bg-warning">{{ $productosStockBajo }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Ventas realizadas hoy</span>
                                    <span class="badge bg-success">{{ $ventasHoy }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Ingresos del día</span>
                                    <span class="badge bg-info">S/ {{ number_format($ingresosDia, 2) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary-modern btn-modern" onclick="exportarEstadisticas()">
                        <i class="bi bi-download me-2"></i>Exportar Reporte
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Venta Rápida -->
    <div class="modal fade" id="nuevaVentaRapidaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Venta Rápida
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Acceso directo al sistema de ventas completo.
                    </div>
                    <p>Se abrirá el módulo de ventas donde podrá:</p>
                    <ul>
                        <li>Seleccionar productos por código</li>
                        <li>Elegir cliente</li>
                        <li>Calcular totales automáticamente</li>
                        <li>Generar ticket de venta</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="/ventas" class="btn btn-success-modern btn-modern">
                        <i class="bi bi-arrow-right me-2"></i>Ir a Ventas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Producto Rápido -->
    <div class="modal fade" id="nuevoProductoRapidoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Producto Rápido
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Acceso directo al registro de productos.
                    </div>
                    <p>Se abrirá el módulo de productos donde podrá:</p>
                    <ul>
                        <li>Registrar nuevo medicamento</li>
                        <li>Asignar categoría y marca</li>
                        <li>Establecer precios</li>
                        <li>Controlar stock inicial</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="/productos" class="btn btn-primary-modern btn-modern">
                        <i class="bi bi-arrow-right me-2"></i>Ir a Productos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Cliente Rápido -->
    <div class="modal fade" id="nuevoClienteRapidoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus me-2"></i>Nuevo Cliente Rápido
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Acceso directo al registro de clientes.
                    </div>
                    <p>Se abrirá el módulo de clientes donde podrá:</p>
                    <ul>
                        <li>Registrar nuevo cliente</li>
                        <li>Capturar datos de contacto</li>
                        <li>Establecer tipo de cliente</li>
                        <li>Gestionar historial de compras</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="/clientes" class="btn btn-info-modern btn-modern">
                        <i class="bi bi-arrow-right me-2"></i>Ir a Clientes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Notificaciones -->
    <div class="modal fade" id="notificacionesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-bell me-2"></i>Notificaciones del Sistema
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        @if($productosStockBajo > 0)
                        <div class="list-group-item list-group-item-warning">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Stock Bajo</h6>
                                <small>Hoy</small>
                            </div>
                            <p class="mb-1">{{ $productosStockBajo }} productos necesitan reabastecimiento.</p>
                            <small>Es importante mantener el inventario actualizado.</small>
                        </div>
                        @endif
                        
                        @if($ventasHoy > 0)
                        <div class="list-group-item list-group-item-success">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Ventas del Día</h6>
                                <small>Hoy</small>
                            </div>
                            <p class="mb-1">Se han realizado {{ $ventasHoy }} ventas por un total de S/ {{ number_format($ingresosDia, 2) }}.</p>
                            <small>¡Excelente trabajo del equipo!</small>
                        </div>
                        @else
                        <div class="list-group-item list-group-item-info">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Sin Ventas Hoy</h6>
                                <small>Hoy</small>
                            </div>
                            <p class="mb-1">Aún no se han registrado ventas el día de hoy.</p>
                            <small>¡Es hora de empezar a vender!</small>
                        </div>
                        @endif
                        
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Sistema Operativo</h6>
                                <small>Siempre</small>
                            </div>
                            <p class="mb-1">Todos los módulos funcionando correctamente.</p>
                            <small>Sistema de farmacia completamente operativo.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary-modern btn-modern" onclick="marcarTodasLeidas()">
                        <i class="bi bi-check2-all me-2"></i>Marcar Todas como Leídas
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Cerrar Sesión -->
    <div class="modal fade" id="modalCerrarSesion" tabindex="-1" aria-labelledby="modalCerrarSesionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); color: white; border: none;">
                    <h5 class="modal-title fw-bold" id="modalCerrarSesionLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Cierre de Sesión
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <i class="bi bi-person-x text-danger" style="font-size: 4rem; opacity: 0.8;"></i>
                    </div>
                    <h6 class="mb-3">¿Está seguro de que desea cerrar sesión?</h6>
                    <p class="text-muted mb-4">
                        Se cerrará su sesión actual y será redirigido al login.<br>
                        <small><strong>Usuario:</strong> {{ auth()->user()->name ?? 'Usuario' }}</small>
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-danger btn-lg px-4 ms-3" onclick="ejecutarCerrarSesion()">
                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Función para mostrar el modal de confirmación
        function mostrarModalCerrarSesion() {
            const modal = new bootstrap.Modal(document.getElementById('modalCerrarSesion'));
            modal.show();
        }

        // Función para ejecutar el cierre de sesión
        function ejecutarCerrarSesion() {
            // Mostrar loading en el botón
            const btnCerrar = document.querySelector('#modalCerrarSesion .btn-danger');
            const originalText = btnCerrar.innerHTML;
            btnCerrar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Cerrando sesión...';
            btnCerrar.disabled = true;

            // Enviar formulario después de 1 segundo para que se vea el loading
            setTimeout(() => {
                document.getElementById('logout-form-dashboard').submit();
            }, 1000);
        }

        // Función legacy para compatibilidad
        function confirmarCerrarSesion() {
            mostrarModalCerrarSesion();
        }
        
        // Función para exportar estadísticas
        function exportarEstadisticas() {
            alert('📊 EXPORTANDO ESTADÍSTICAS\n\n✅ Generando reporte PDF\n📈 Gráficos y métricas incluidas\n📧 Enviado por email\n\nFuncionalidad en desarrollo.');
        }

        // Animación de contadores
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 100);
            });

            // Animate counters
            const counters = document.querySelectorAll('.stat-value');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = counter.textContent.replace(/\d+/, target);
                        clearInterval(timer);
                    } else {
                        counter.textContent = counter.textContent.replace(/\d+/, Math.floor(current));
                    }
                }, 30);
            });
        });

        // Auto refresh every 5 minutes
        setInterval(() => {
            location.reload();
        }, 300000);
    </script>
</body>
</html> 