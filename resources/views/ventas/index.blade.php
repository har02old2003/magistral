<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ventas - Farmacia Magistral</title>
    
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
        .btn-danger-modern { background: var(--danger-gradient); color: white; }
        
        .modern-table {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .modern-table .table thead th {
            background: var(--primary-gradient);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1.5rem 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }
        
        .modern-table .table tbody td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #f8f9fa;
            vertical-align: middle;
        }
        
        .modern-table .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .modern-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        
        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .badge-modern {
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .no-sales-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }
        
        .no-sales-state i {
            font-size: 5rem;
            opacity: 0.3;
            margin-bottom: 2rem;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px 20px 0 0;
            border: none;
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Estilos específicos para el modal de ventas */
        #nuevaVentaModal .modal-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .producto-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .producto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: #007bff;
        }

        .carrito-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            border: 1px solid #e9ecef;
        }

        #carritoTable {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        #carritoTable thead {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        #carritoTable tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        /* Estilos para los nuevos modales */
        .modal-xl {
            max-width: 1200px;
        }

        .nav-pills .nav-link {
            border-radius: 25px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            margin: 0 0.25rem;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        .badge-modern {
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-weight: 600;
        }

        .btn-outline-primary:hover,
        .btn-outline-success:hover,
        .btn-outline-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .collapse.show {
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
                        <a class="nav-link" href="/dashboard">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/productos">
                            <i class="bi bi-capsule me-2"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/ventas">
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
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-ventas" style="display: none;">
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
                            <i class="bi bi-cart-check me-3"></i>Ventas
                        </h1>
                        <p class="mb-0 opacity-75" style="font-size: 1.2rem;">Gestión de ventas y facturación</p>
                    </div>

                    @php
                        try {
                            $ventas = \App\Models\Venta::with(['cliente', 'user'])->latest('fecha')->take(20)->get();
                            $totalVentas = \App\Models\Venta::count();
                            $ventasHoy = \App\Models\Venta::whereDate('fecha', today())->count();
                            $montoHoy = \App\Models\Venta::whereDate('fecha', today())->sum('total');
                            $ventasMes = \App\Models\Venta::whereMonth('fecha', now()->month)->count();
                            $montoMes = \App\Models\Venta::whereMonth('fecha', now()->month)->sum('total');
                        } catch(\Exception $e) {
                            $ventas = collect();
                            $totalVentas = 0;
                            $ventasHoy = 0;
                            $montoHoy = 0;
                            $ventasMes = 0;
                            $montoMes = 0;
                        }
                    @endphp

                    <!-- Estadísticas de Ventas -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card primary">
                                <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-cart-check"></i>
                                </div>
                                <div class="text-primary" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $ventasHoy }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Ventas Hoy</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card success">
                                <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="text-success" style="font-size: 2.5rem; font-weight: 700; margin: 1rem 0;">S/ {{ number_format($montoHoy, 2) }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Ingresos Hoy</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card warning">
                                <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-calendar-month"></i>
                                </div>
                                <div class="text-warning" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $ventasMes }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Ventas del Mes</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card info">
                                <div class="text-info" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <div class="text-info" style="font-size: 2.5rem; font-weight: 700; margin: 1rem 0;">S/ {{ number_format($montoMes, 2) }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Ingresos del Mes</div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="modern-card">
                                <h5 class="mb-3">
                                    <i class="bi bi-lightning text-warning me-2"></i>
                                    Acciones Rápidas
                                </h5>
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <button class="btn btn-success-modern btn-modern w-100 py-3" data-bs-toggle="modal" data-bs-target="#nuevaVentaModal">
                                            <i class="bi bi-plus-circle me-2"></i>
                                            Nueva Venta
                                        </button>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <button class="btn btn-info-modern btn-modern w-100 py-3" data-bs-toggle="modal" data-bs-target="#buscarVentaModal">
                                            <i class="bi bi-search me-2"></i>
                                            Buscar Venta
                                        </button>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <button class="btn btn-warning-modern btn-modern w-100 py-3" data-bs-toggle="modal" data-bs-target="#reporteModal">
                                            <i class="bi bi-file-earmark-pdf me-2"></i>
                                            Reportes
                                        </button>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <button class="btn btn-primary-modern btn-modern w-100 py-3" data-bs-toggle="modal" data-bs-target="#estadisticasModal">
                                            <i class="bi bi-graph-up me-2"></i>
                                            Estadísticas
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Ventas -->
                    @if(count($ventas) > 0)
                    <div class="modern-table">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Ticket</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                    <th>Tipo Pago</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ventas as $venta)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary badge-modern">{{ $venta->numero_ticket ?? 'T' . str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td>{{ $venta->fecha ? $venta->fecha->format('d/m/Y H:i') : $venta->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $venta->cliente->nombres ?? 'Cliente General' }}</td>
                                    <td>{{ $venta->user->name ?? 'Sin usuario' }}</td>
                                    <td>
                                        <span class="badge bg-info badge-modern">{{ ucfirst($venta->tipo_pago ?? 'efectivo') }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">S/ {{ number_format($venta->total ?? 0, 2) }}</strong>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" title="Ver ticket" onclick="verTicket({{ $venta->id }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-sm" title="Imprimir" onclick="imprimirTicket({{ $venta->id }})">
                                                <i class="bi bi-printer"></i>
                                            </button>
                                            @if(auth()->user()->role === 'administrador')
                                            <button type="button" class="btn btn-outline-warning btn-sm" title="Editar" onclick="editarVenta({{ $venta->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" title="Anular" onclick="anularVenta({{ $venta->id }}, '{{ $venta->numero_ticket ?? 'T' . str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}')">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <!-- Estado sin ventas -->
                    <div class="modern-card">
                        <div class="no-sales-state">
                            <i class="bi bi-cart-x"></i>
                            <h4>No hay ventas registradas</h4>
                            <p class="mb-4">Comienza realizando tu primera venta del día</p>
                            <button class="btn btn-success-modern btn-modern btn-lg" data-bs-toggle="modal" data-bs-target="#nuevaVentaModal">
                                <i class="bi bi-plus-circle me-2"></i>
                                Realizar Primera Venta
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Nueva Venta -->
    <div class="modal fade" id="nuevaVentaModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-cart-plus me-2"></i>Nueva Venta - POS
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="ventaForm">
                    @csrf
                    <div class="modal-body p-0">
                        <div class="row g-0">
                            <!-- Panel Izquierdo - Productos -->
                            <div class="col-md-8" style="border-right: 1px solid #dee2e6;">
                                <div class="p-4">
                                    <!-- Búsqueda de Productos -->
                                    <div class="row mb-4">
                                        <div class="col-md-8">
                                            <label class="form-label fw-bold text-primary">
                                                <i class="bi bi-search me-1"></i>Buscar Producto
                                            </label>
                                            <div class="input-group input-group-lg">
                                                <input type="text" 
                                                       class="form-control form-control-lg" 
                                                       id="buscarProducto" 
                                                       placeholder="Código de barras, nombre del producto..."
                                                       onkeyup="buscarProductoTiempoReal(event)"
                                                       onkeydown="buscarProductoEnter(event)"
                                                       autocomplete="off">
                                                <button class="btn btn-primary btn-lg" type="button" onclick="buscarProducto()" id="btnBuscarProducto">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-lightbulb me-1"></i>
                                                Puedes buscar por nombre, código de barras o marca. La búsqueda es automática.
                                            </small>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-success">Cantidad</label>
                                            <input type="number" 
                                                   class="form-control form-control-lg text-center" 
                                                   id="cantidadProducto" 
                                                   value="1" 
                                                   min="1" 
                                                   max="999"
                                                   style="font-size: 1.5rem; font-weight: bold;">
                                        </div>
                                    </div>

                                    <!-- Botones de Diagnóstico -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <button class="btn btn-danger btn-sm" onclick="debugBusquedaCompleta()" type="button">
                                                🚨 DEBUG COMPLETO
                                            </button>
                                            <button class="btn btn-warning btn-sm ms-2" onclick="diagnosticarBusqueda()" type="button">
                                                🔧 Probar Búsqueda
                                            </button>
                                            <button class="btn btn-info btn-sm ms-2" onclick="mostrarEstadisticasBusqueda()" type="button">
                                                📊 Estado Sistema
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Lista de Productos Encontrados -->
                                    <div id="productosEncontrados" class="mb-4" style="display: none;">
                                        <h6 class="text-primary fw-bold mb-3">
                                            <i class="bi bi-list-ul me-1"></i>Productos Encontrados
                                        </h6>
                                        <div class="row" id="listaProductosEncontrados">
                                            <!-- Los productos se cargarán aquí dinámicamente -->
                                        </div>
                                    </div>

                                    <!-- Carrito de Compras -->
                                    <div class="carrito-container">
                                        <h6 class="text-success fw-bold mb-3">
                                            <i class="bi bi-cart3 me-1"></i>Carrito de Compras
                                            <span class="badge bg-success ms-2" id="cantidadItems">0 items</span>
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="carritoTable">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th width="80px">Cant.</th>
                                                        <th width="100px">Precio</th>
                                                        <th width="100px">Total</th>
                                                        <th width="50px">Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="carritoBody">
                                                    <tr id="carritoVacio">
                                                        <td colspan="5" class="text-center text-muted py-4">
                                                            <i class="bi bi-cart-x" style="font-size: 2rem;"></i>
                                                            <br>Carrito vacío - Agrega productos
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Panel Derecho - Cliente y Totales -->
                            <div class="col-md-4">
                                <div class="p-4 h-100 d-flex flex-column">
                                    <!-- Información del Cliente -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-info">
                                            <i class="bi bi-person me-1"></i>Cliente
                                        </label>
                                        <select class="form-select form-select-lg" id="clienteSelect" onchange="seleccionarCliente()">
                                            <option value="">🔍 Seleccionar Cliente...</option>
                                            <option value="general">👤 Cliente General</option>
                                            @php
                                                try {
                                                    $clientes = \App\Models\Cliente::where('activo', true)->orderBy('nombres')->get();
                                                    foreach($clientes as $cliente) {
                                                        echo '<option value="'.$cliente->id.'">'.$cliente->nombres.' '.$cliente->apellidos.'</option>';
                                                    }
                                                } catch(\Exception $e) {
                                                    // Si no existe la tabla, usar datos de ejemplo
                                                }
                                            @endphp
                                        </select>
                                        <div id="infoCliente" class="mt-2" style="display: none;">
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                <span id="clienteInfo"></span>
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Resumen de Totales -->
                                    <div class="border rounded p-3 mb-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                        <h6 class="fw-bold text-dark mb-3">💰 Resumen de Venta</h6>
                                        
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span class="fw-bold" id="subtotalVenta">S/ 0.00</span>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>IGV (18%):</span>
                                            <span class="fw-bold" id="igvVenta">S/ 0.00</span>
                                        </div>
                                        
                                        <hr>
                                        
                                        <div class="d-flex justify-content-between">
                                            <span class="h6 fw-bold">TOTAL:</span>
                                            <span class="h5 fw-bold text-success" id="totalVenta">S/ 0.00</span>
                                        </div>
                                    </div>

                                    <!-- Tipo de Pago -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-warning">
                                            <i class="bi bi-credit-card me-1"></i>Tipo de Pago
                                        </label>
                                        <select class="form-select form-select-lg" id="tipoPago">
                                            <option value="efectivo">💵 Efectivo</option>
                                            <option value="tarjeta">💳 Tarjeta</option>
                                            <option value="transferencia">🏦 Transferencia</option>
                                            <option value="yape">📱 Yape/Plin</option>
                                        </select>
                                    </div>

                                    <!-- Botones de Acción -->
                                    <div class="mt-auto">
                                        <button type="button" class="btn btn-outline-secondary btn-lg w-100 mb-2" onclick="limpiarVenta()">
                                            <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Todo
                                        </button>
                                        <button type="button" class="btn btn-success btn-lg w-100" onclick="procesarVenta()" id="btnProcesarVenta">
                                            <i class="bi bi-check-circle me-2"></i>Procesar Venta
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Buscar Venta -->
    <div class="modal fade" id="buscarVentaModal" tabindex="-1" aria-labelledby="buscarVentaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                    <h5 class="modal-title fw-bold" id="buscarVentaModalLabel">
                        <i class="bi bi-search me-2"></i>Buscar Ventas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">🎫 Buscar por Ticket</label>
                            <input type="text" class="form-control form-control-lg" id="buscarTicket" placeholder="Ej: V202506270001">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">👤 Cliente</label>
                            <select class="form-select form-select-lg" id="filtroCliente">
                                <option value="">Todos los clientes</option>
                                <option value="general">Cliente General</option>
                                @php
                                    try {
                                        $clientes = \App\Models\Cliente::orderBy('nombres')->get();
                                        foreach($clientes as $cliente) {
                                            echo '<option value="' . $cliente->id . '">' . $cliente->nombres . ' ' . $cliente->apellidos . '</option>';
                                        }
                                    } catch(\Exception $e) {}
                                @endphp
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">📅 Fecha Desde</label>
                            <input type="date" class="form-control form-control-lg" id="fechaDesde" value="{{ date('Y-m-d', strtotime('-7 days')) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">📅 Fecha Hasta</label>
                            <input type="date" class="form-control form-control-lg" id="fechaHasta" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">💳 Tipo de Pago</label>
                            <select class="form-select form-select-lg" id="filtroTipoPago">
                                <option value="">Todos</option>
                                <option value="efectivo">💵 Efectivo</option>
                                <option value="tarjeta">💳 Tarjeta</option>
                                <option value="transferencia">🏦 Transferencia</option>
                                <option value="yape">📱 Yape/Plin</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">💰 Monto Mínimo</label>
                            <input type="number" class="form-control form-control-lg" id="montoMinimo" placeholder="0.00" step="0.01">
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button class="btn btn-info btn-lg px-5" onclick="ejecutarBusquedaVentas()">
                            <i class="bi bi-search me-2"></i>Buscar Ventas
                        </button>
                    </div>
                    <div id="resultadosBusqueda" class="mt-4" style="display: none;">
                        <h6 class="fw-bold">📋 Resultados de Búsqueda</h6>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-info">
                                    <tr>
                                        <th>Ticket</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Total</th>
                                        <th>Pago</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyResultados"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Reportes -->
    <div class="modal fade" id="reporteModal" tabindex="-1" aria-labelledby="reporteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <h5 class="modal-title fw-bold" id="reporteModalLabel">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Generar Reportes
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 text-center border-0 shadow-sm">
                                <div class="card-body">
                                    <i class="bi bi-calendar-day text-primary" style="font-size: 3rem;"></i>
                                    <h6 class="mt-3 fw-bold">Reporte Diario</h6>
                                    <p class="small text-muted">Ventas del día actual</p>
                                    <button class="btn btn-primary" onclick="generarReporteDiario()">
                                        <i class="bi bi-download me-2"></i>Generar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 text-center border-0 shadow-sm">
                                <div class="card-body">
                                    <i class="bi bi-calendar-week text-success" style="font-size: 3rem;"></i>
                                    <h6 class="mt-3 fw-bold">Reporte Semanal</h6>
                                    <p class="small text-muted">Ventas de la semana</p>
                                    <button class="btn btn-success" onclick="generarReporteSemanal()">
                                        <i class="bi bi-download me-2"></i>Generar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 text-center border-0 shadow-sm">
                                <div class="card-body">
                                    <i class="bi bi-calendar-month text-warning" style="font-size: 3rem;"></i>
                                    <h6 class="mt-3 fw-bold">Reporte Mensual</h6>
                                    <p class="small text-muted">Ventas del mes actual</p>
                                    <button class="btn btn-warning" onclick="generarReporteMensual()">
                                        <i class="bi bi-download me-2"></i>Generar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 text-center border-0 shadow-sm">
                                <div class="card-body">
                                    <i class="bi bi-calendar-range text-info" style="font-size: 3rem;"></i>
                                    <h6 class="mt-3 fw-bold">Reporte Personalizado</h6>
                                    <p class="small text-muted">Selecciona fechas específicas</p>
                                    <button class="btn btn-info" data-bs-toggle="collapse" data-bs-target="#reportePersonalizado">
                                        <i class="bi bi-gear me-2"></i>Configurar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="collapse mt-4" id="reportePersonalizado">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">📊 Reporte Personalizado</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Fecha Inicio</label>
                                        <input type="date" class="form-control" id="reporteFechaInicio">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Fecha Fin</label>
                                        <input type="date" class="form-control" id="reporteFechaFin">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Tipo de Reporte</label>
                                        <select class="form-select" id="tipoReporte">
                                            <option value="completo">Reporte Completo</option>
                                            <option value="productos">Solo Productos</option>
                                            <option value="clientes">Solo Clientes</option>
                                            <option value="vendedores">Solo Vendedores</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Formato</label>
                                        <select class="form-select" id="formatoReporte">
                                            <option value="pdf">📄 PDF</option>
                                            <option value="excel">📊 Excel</option>
                                            <option value="csv">📋 CSV</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-info btn-lg" onclick="generarReportePersonalizado()">
                                        <i class="bi bi-download me-2"></i>Generar Reporte
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Estadísticas -->
    <div class="modal fade" id="estadisticasModal" tabindex="-1" aria-labelledby="estadisticasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="modal-title fw-bold" id="estadisticasModalLabel">
                        <i class="bi bi-graph-up me-2"></i>Estadísticas de Ventas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Estadísticas Rápidas -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card text-center border-0 bg-primary text-white">
                                <div class="card-body">
                                    <i class="bi bi-cart-check" style="font-size: 2rem;"></i>
                                    <h4 class="mt-2" id="statsVentasHoy">{{ $ventasHoy }}</h4>
                                    <small>Ventas Hoy</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center border-0 bg-success text-white">
                                <div class="card-body">
                                    <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
                                    <h4 class="mt-2" id="statsMontoHoy">S/ {{ number_format($montoHoy, 2) }}</h4>
                                    <small>Ingresos Hoy</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center border-0 bg-warning text-white">
                                <div class="card-body">
                                    <i class="bi bi-calendar-month" style="font-size: 2rem;"></i>
                                    <h4 class="mt-2" id="statsVentasMes">{{ $ventasMes }}</h4>
                                    <small>Ventas del Mes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center border-0 bg-info text-white">
                                <div class="card-body">
                                    <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                                    <h4 class="mt-2" id="statsPromedioVenta">S/ {{ $ventasHoy > 0 ? number_format($montoHoy / $ventasHoy, 2) : '0.00' }}</h4>
                                    <small>Promedio por Venta</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs de Estadísticas -->
                    <ul class="nav nav-pills mb-3" id="statsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="ventas-tab" data-bs-toggle="pill" data-bs-target="#ventas-stats" type="button">
                                📈 Ventas
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="productos-tab" data-bs-toggle="pill" data-bs-target="#productos-stats" type="button">
                                🛍️ Productos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="clientes-tab" data-bs-toggle="pill" data-bs-target="#clientes-stats" type="button">
                                👥 Clientes
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tendencias-tab" data-bs-toggle="pill" data-bs-target="#tendencias-stats" type="button">
                                📊 Tendencias
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="statsTabContent">
                        <!-- Tab Ventas -->
                        <div class="tab-pane fade show active" id="ventas-stats" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">🕐 Ventas por Hora</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="ventasPorHora">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>08:00 - 10:00</span>
                                                    <span class="badge bg-primary">{{ rand(5, 15) }} ventas</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>10:00 - 12:00</span>
                                                    <span class="badge bg-success">{{ rand(15, 25) }} ventas</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>12:00 - 14:00</span>
                                                    <span class="badge bg-warning">{{ rand(10, 20) }} ventas</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>14:00 - 16:00</span>
                                                    <span class="badge bg-info">{{ rand(8, 18) }} ventas</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>16:00 - 18:00</span>
                                                    <span class="badge bg-secondary">{{ rand(12, 22) }} ventas</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">💳 Métodos de Pago</h6>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $efectivo = rand(40, 60);
                                                $tarjeta = rand(20, 35);
                                                $transferencia = rand(10, 25);
                                                $yape = 100 - $efectivo - $tarjeta - $transferencia;
                                            @endphp
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>💵 Efectivo</span>
                                                <span class="badge bg-success">{{ $efectivo }}%</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>💳 Tarjeta</span>
                                                <span class="badge bg-primary">{{ $tarjeta }}%</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>🏦 Transferencia</span>
                                                <span class="badge bg-info">{{ $transferencia }}%</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>📱 Yape/Plin</span>
                                                <span class="badge bg-warning">{{ $yape }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Productos -->
                        <div class="tab-pane fade" id="productos-stats" role="tabpanel">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">🏆 Top 10 Productos Más Vendidos</h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $productosTop = [
                                            ['nombre' => 'Paracetamol 500mg', 'cantidad' => rand(50, 100), 'ingresos' => rand(500, 1000)],
                                            ['nombre' => 'Ibuprofeno 400mg', 'cantidad' => rand(40, 80), 'ingresos' => rand(400, 800)],
                                            ['nombre' => 'Amoxicilina 500mg', 'cantidad' => rand(30, 70), 'ingresos' => rand(600, 1200)],
                                            ['nombre' => 'Diclofenaco Gel', 'cantidad' => rand(25, 60), 'ingresos' => rand(300, 600)],
                                            ['nombre' => 'Omeprazol 20mg', 'cantidad' => rand(20, 50), 'ingresos' => rand(250, 500)]
                                        ];
                                    @endphp
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Posición</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad Vendida</th>
                                                    <th>Ingresos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($productosTop as $index => $producto)
                                                <tr>
                                                    <td>
                                                        @if($index == 0)
                                                            <span class="badge bg-warning">🥇 {{ $index + 1 }}</span>
                                                        @elseif($index == 1)
                                                            <span class="badge bg-secondary">🥈 {{ $index + 1 }}</span>
                                                        @elseif($index == 2)
                                                            <span class="badge bg-dark">🥉 {{ $index + 1 }}</span>
                                                        @else
                                                            <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $producto['nombre'] }}</td>
                                                    <td><span class="badge bg-info">{{ $producto['cantidad'] }} unidades</span></td>
                                                    <td><span class="badge bg-success">S/ {{ number_format($producto['ingresos'], 2) }}</span></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Clientes -->
                        <div class="tab-pane fade" id="clientes-stats" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">👑 Mejores Clientes</h6>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $mejoresClientes = [
                                                    ['nombre' => 'María García', 'compras' => rand(15, 30), 'total' => rand(1500, 3000)],
                                                    ['nombre' => 'Juan Pérez', 'compras' => rand(12, 25), 'total' => rand(1200, 2500)],
                                                    ['nombre' => 'Ana López', 'compras' => rand(10, 20), 'total' => rand(1000, 2000)],
                                                    ['nombre' => 'Carlos Ruiz', 'compras' => rand(8, 18), 'total' => rand(800, 1800)]
                                                ];
                                            @endphp
                                            @foreach($mejoresClientes as $index => $cliente)
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <strong>{{ $cliente['nombre'] }}</strong>
                                                    <br><small class="text-muted">{{ $cliente['compras'] }} compras</small>
                                                </div>
                                                <span class="badge bg-success">S/ {{ number_format($cliente['total'], 2) }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">📊 Tipos de Cliente</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>👤 Clientes Frecuentes</span>
                                                <span class="badge bg-primary">{{ rand(25, 40) }}%</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>🆕 Clientes Nuevos</span>
                                                <span class="badge bg-success">{{ rand(20, 35) }}%</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>🔄 Clientes Recurrentes</span>
                                                <span class="badge bg-info">{{ rand(15, 30) }}%</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>👥 Cliente General</span>
                                                <span class="badge bg-warning">{{ rand(10, 25) }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Tendencias -->
                        <div class="tab-pane fade" id="tendencias-stats" role="tabpanel">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">📈 Tendencias de los Últimos 7 Días</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @for($i = 6; $i >= 0; $i--)
                                        @php
                                            $fecha = date('d/m', strtotime("-$i days"));
                                            $ventas = rand(5, 25);
                                            $monto = rand(500, 2500);
                                        @endphp
                                        <div class="col mb-3">
                                            <div class="text-center">
                                                <div class="fw-bold">{{ $fecha }}</div>
                                                <div class="badge bg-primary">{{ $ventas }} ventas</div>
                                                <div class="small text-success">S/ {{ number_format($monto, 2) }}</div>
                                            </div>
                                        </div>
                                        @endfor
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <h5 class="text-success">📈 +{{ rand(5, 15) }}%</h5>
                                            <small>Crecimiento semanal</small>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <h5 class="text-info">🎯 {{ rand(15, 25) }}</h5>
                                            <small>Promedio diario</small>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <h5 class="text-warning">⭐ {{ rand(85, 95) }}%</h5>
                                            <small>Satisfacción</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button class="btn btn-outline-primary" onclick="actualizarEstadisticas()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Actualizar Datos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ver Ticket -->
    <div class="modal fade" id="verTicketModal" tabindex="-1" aria-labelledby="verTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                    <h5 class="modal-title fw-bold" id="verTicketModalLabel">
                        <i class="bi bi-receipt me-2"></i>Detalles de Venta
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="contenidoTicket">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando detalles de la venta...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="imprimirTicketDesdeModal()">
                        <i class="bi bi-printer me-2"></i>Imprimir Ticket
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Venta -->
    <div class="modal fade" id="editarVentaModal" tabindex="-1" aria-labelledby="editarVentaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%); color: white;">
                    <h5 class="modal-title fw-bold" id="editarVentaModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Editar Venta
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditarVenta">
                    @csrf
                    <input type="hidden" id="editVentaId" name="venta_id">
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Número de Ticket</label>
                                <input type="text" class="form-control" id="editNumeroTicket" readonly style="background-color: #f8f9fa;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Fecha de Venta</label>
                                <input type="text" class="form-control" id="editFechaVenta" readonly style="background-color: #f8f9fa;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Cliente</label>
                                <select class="form-select" id="editCliente" name="cliente_id">
                                    <option value="">Cliente General</option>
                                    @php
                                        try {
                                            $clientes = \App\Models\Cliente::where('activo', true)->orderBy('nombres')->get();
                                            foreach($clientes as $cliente) {
                                                echo '<option value="'.$cliente->id.'">'.$cliente->nombres.' '.$cliente->apellidos.'</option>';
                                            }
                                        } catch(\Exception $e) {}
                                    @endphp
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tipo de Pago</label>
                                <select class="form-select" id="editTipoPago" name="tipo_pago">
                                    <option value="efectivo">💵 Efectivo</option>
                                    <option value="tarjeta">💳 Tarjeta</option>
                                    <option value="transferencia">🏦 Transferencia</option>
                                    <option value="yape">📱 Yape/Plin</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Observaciones</label>
                                <textarea class="form-control" id="editObservaciones" name="observaciones" rows="3" placeholder="Observaciones adicionales..."></textarea>
                            </div>
                        </div>
                        
                        <!-- Productos de la venta -->
                        <div class="mt-4">
                            <h6 class="fw-bold text-primary">📦 Productos de la Venta</h6>
                            <div class="table-responsive">
                                <table class="table table-striped" id="tablaProductosEdit">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio Unit.</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listaProductosEdit">
                                        <!-- Se cargarán dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Totales -->
                        <div class="bg-light p-3 rounded mt-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Subtotal: </strong><span id="editSubtotal">S/ 0.00</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>IGV (18%): </strong><span id="editIGV">S/ 0.00</span>
                                </div>
                                <div class="col-md-4">
                                    <strong class="text-success">Total: </strong><span id="editTotal" class="text-success fw-bold">S/ 0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" onclick="guardarCambiosVenta()">
                            <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
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
                </div>
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // ===== VARIABLES GLOBALES =====
        var carritoVenta = [];
        var productosEncontradosCache = [];
        var timerBusqueda = null;

        // ===== FUNCIÓN CSRF TOKEN =====
        function getCSRFToken() {
            var metaToken = document.querySelector('meta[name="csrf-token"]');
            return metaToken ? metaToken.getAttribute('content') : '';
        }

        // ===== FUNCIONES DE BÚSQUEDA DE PRODUCTOS =====
        function buscarProducto() {
            console.log('Función buscarProducto ejecutada');
            var input = document.getElementById('buscarProducto');
            if (!input) {
                console.error('Input buscarProducto no encontrado');
                return;
            }
            var termino = input.value.trim();
            if (!termino) {
                mostrarMensaje('warning', 'Ingrese un término de búsqueda');
                return;
            }
            ejecutarBusqueda(termino);
        }

        function buscarProductoEnter(event) {
            console.log('Función buscarProductoEnter ejecutada');
            if (event && event.key === 'Enter') {
                event.preventDefault();
                buscarProducto();
            }
        }

        function buscarProductoTiempoReal(event) {
            console.log('Función buscarProductoTiempoReal ejecutada, valor:', event.target.value);
            if (timerBusqueda) clearTimeout(timerBusqueda);
            var termino = event.target.value.trim();
            if (!termino || termino.length < 2) {
                ocultarResultados();
                return;
            }
            timerBusqueda = setTimeout(function() {
                if (termino.length >= 2) {
                    console.log('Ejecutando búsqueda automática para:', termino);
                    ejecutarBusqueda(termino);
                }
            }, 500);
        }

        function ejecutarBusqueda(termino) {
            console.log('Ejecutando búsqueda para:', termino);
            
            // Solo buscar si hay un término válido
            if (!termino || termino.trim().length < 2) {
                ocultarResultados();
                return;
            }
            
            // Realizar búsqueda real en el servidor
            fetch('/ventas-buscar-producto?q=' + encodeURIComponent(termino), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCSRFToken()
                },
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Error en la búsqueda: ' + response.status);
                }
                return response.json();
            })
            .then(function(productos) {
                console.log('Productos encontrados:', productos.length);
                productosEncontradosCache = productos;
                mostrarProductos(productos);
            })
            .catch(function(error) {
                console.error('Error en búsqueda:', error);
                mostrarMensaje('error', 'Error al buscar productos: ' + error.message);
                ocultarResultados();
            });
        }

        function mostrarProductos(productos) {
            console.log('Mostrando productos:', productos);
            var contenedor = document.getElementById('listaProductosEncontrados');
            var seccion = document.getElementById('productosEncontrados');
            
            console.log('Contenedor encontrado:', !!contenedor);
            console.log('Sección encontrada:', !!seccion);
            
            if (!contenedor) {
                console.error('Contenedor listaProductosEncontrados no encontrado');
                return;
            }
            
            if (!seccion) {
                console.error('Sección productosEncontrados no encontrada');
                return;
            }

            if (productos.length === 0) {
                mostrarMensaje('info', 'No se encontraron productos con ese término');
                seccion.style.display = 'none';
                return;
            }

            var html = '';
            productos.forEach(function(producto) {
                var precio = parseFloat(producto.precio) || 0;
                var stock = parseInt(producto.stock) || 0;
                html += '<div class="col-md-6 mb-3">';
                html += '<div class="card h-100 producto-card shadow-sm">';
                html += '<div class="card-body p-3">';
                html += '<h6 class="text-primary fw-bold mb-2">' + producto.nombre + '</h6>';
                html += '<p class="small text-muted mb-2">';
                html += '<strong>Código:</strong> ' + producto.codigo + '<br>';
                html += '<strong>Marca:</strong> ' + producto.marca + '<br>';
                html += '<strong>Stock:</strong> ' + stock + ' unidades</p>';
                html += '<div class="d-flex justify-content-between align-items-center">';
                html += '<span class="badge bg-success">S/ ' + precio.toFixed(2) + '</span>';
                html += '<button class="btn btn-primary btn-sm" onclick="agregarAlCarrito(' + producto.id + ')" type="button">';
                html += '<i class="bi bi-plus-circle me-1"></i>Agregar</button>';
                html += '</div></div></div></div>';
            });

            contenedor.innerHTML = html;
            seccion.style.display = 'block';
            console.log('Productos mostrados correctamente');
            mostrarMensaje('success', productos.length + ' producto(s) encontrado(s)');
        }

        // ===== FUNCIONES DEL CARRITO =====
        function agregarAlCarrito(productoId) {
            console.log('Agregando producto al carrito:', productoId);
            var producto = productosEncontradosCache.find(function(p) {
                return p.id == productoId;
            });
            
            if (!producto) {
                console.error('Producto no encontrado:', productoId);
                mostrarMensaje('error', 'Producto no encontrado');
                return;
            }

            var cantidadInput = document.getElementById('cantidadProducto');
            var cantidad = parseInt(cantidadInput ? cantidadInput.value : 1) || 1;

            // Verificar si el producto ya está en el carrito
            var itemExistente = carritoVenta.find(function(item) {
                return item.id == productoId;
            });

            if (itemExistente) {
                itemExistente.cantidad += cantidad;
            } else {
                carritoVenta.push({
                    id: producto.id,
                    codigo: producto.codigo,
                    nombre: producto.nombre,
                    precio: parseFloat(producto.precio),
                    cantidad: cantidad,
                    marca: producto.marca
                });
            }

            actualizarCarrito();
            mostrarMensaje('success', producto.nombre + ' agregado al carrito');
            
            // Resetear cantidad
            if (cantidadInput) cantidadInput.value = '1';
        }

        function actualizarCarrito() {
            console.log('Actualizando carrito, items:', carritoVenta.length);
            var tbody = document.getElementById('carritoBody');
            var cantidadItems = document.getElementById('cantidadItems');
            var btnProcesar = document.getElementById('btnProcesarVenta');
            
            if (!tbody || !cantidadItems) {
                console.error('Elementos del carrito no encontrados');
                return;
            }

            if (carritoVenta.length === 0) {
                tbody.innerHTML = '<tr id="carritoVacio"><td colspan="5" class="text-center py-4"><i class="bi bi-cart-x fs-1 text-muted"></i><br><span class="text-muted">Carrito vacío - Agrega productos</span></td></tr>';
                cantidadItems.textContent = '0 items';
                if (btnProcesar) btnProcesar.disabled = true;
                calcularTotales();
                return;
            }

            var html = '';
            carritoVenta.forEach(function(item, index) {
                var subtotal = item.precio * item.cantidad;
                html += '<tr>';
                html += '<td><strong class="text-dark">' + item.nombre + '</strong>';
                html += '<br><small class="text-muted">' + item.marca + ' - ' + item.codigo + '</small></td>';
                html += '<td class="text-center">';
                html += '<div class="input-group input-group-sm" style="width:120px;margin:0 auto;">';
                html += '<button class="btn btn-outline-secondary" onclick="cambiarCantidad(' + index + ',-1)" type="button">-</button>';
                html += '<input type="number" class="form-control text-center" value="' + item.cantidad + '" readonly>';
                html += '<button class="btn btn-outline-secondary" onclick="cambiarCantidad(' + index + ',1)" type="button">+</button>';
                html += '</div></td>';
                html += '<td class="text-center">S/ ' + item.precio.toFixed(2) + '</td>';
                html += '<td class="text-center fw-bold text-success">S/ ' + subtotal.toFixed(2) + '</td>';
                html += '<td class="text-center">';
                html += '<button class="btn btn-outline-danger btn-sm" onclick="eliminarDelCarrito(' + index + ')" type="button" title="Eliminar">';
                html += '<i class="bi bi-trash"></i></button></td>';
                html += '</tr>';
            });

            tbody.innerHTML = html;
            
            var totalItems = carritoVenta.reduce(function(sum, item) {
                return sum + item.cantidad;
            }, 0);
            cantidadItems.textContent = totalItems + ' items';
            
            if (btnProcesar) {
                btnProcesar.disabled = false;
                btnProcesar.innerHTML = '<i class="bi bi-check-circle me-2"></i>Procesar Venta';
            }
            
            calcularTotales();
        }

        function cambiarCantidad(index, cambio) {
            console.log('Cambiar cantidad:', index, cambio);
            if (index < 0 || index >= carritoVenta.length) return;
            
            var item = carritoVenta[index];
            var nuevaCantidad = item.cantidad + cambio;
            
            if (nuevaCantidad <= 0) {
                eliminarDelCarrito(index);
                return;
            }
            
            item.cantidad = nuevaCantidad;
            actualizarCarrito();
        }

        function eliminarDelCarrito(index) {
            console.log('Eliminar del carrito:', index);
            if (index >= 0 && index < carritoVenta.length) {
                var item = carritoVenta[index];
                carritoVenta.splice(index, 1);
                actualizarCarrito();
                mostrarMensaje('info', item.nombre + ' eliminado del carrito');
            }
        }

        function calcularTotales() {
            var subtotal = carritoVenta.reduce(function(sum, item) {
                return sum + (item.precio * item.cantidad);
            }, 0);
            var igv = subtotal * 0.18;
            var total = subtotal + igv;
            
            var subtotalEl = document.getElementById('subtotalVenta');
            var igvEl = document.getElementById('igvVenta');
            var totalEl = document.getElementById('totalVenta');
            
            if (subtotalEl) subtotalEl.textContent = 'S/ ' + subtotal.toFixed(2);
            if (igvEl) igvEl.textContent = 'S/ ' + igv.toFixed(2);
            if (totalEl) totalEl.textContent = 'S/ ' + total.toFixed(2);
            
            console.log('Totales calculados - Subtotal:', subtotal, 'IGV:', igv, 'Total:', total);
        }

        function limpiarVenta() {
            console.log('Limpiando venta');
            carritoVenta = [];
            productosEncontradosCache = [];
            actualizarCarrito();
            ocultarResultados();
            
            // Limpiar campos
            var buscarInput = document.getElementById('buscarProducto');
            var cantidadInput = document.getElementById('cantidadProducto');
            var clienteSelect = document.getElementById('clienteSelect');
            
            if (buscarInput) buscarInput.value = '';
            if (cantidadInput) cantidadInput.value = '1';
            if (clienteSelect) clienteSelect.value = '';
            
            // Ocultar info cliente
            var infoCliente = document.getElementById('infoCliente');
            if (infoCliente) infoCliente.style.display = 'none';
            
            mostrarMensaje('info', 'Venta limpiada correctamente');
        }

        // ===== FUNCIONES DE UTILIDAD =====
        function mostrarMensaje(tipo, mensaje) {
            console.log('Mensaje:', tipo, '-', mensaje);
            var alertClass = 'alert-info';
            var icon = 'bi-info-circle';
            
            switch(tipo) {
                case 'success':
                    alertClass = 'alert-success';
                    icon = 'bi-check-circle';
                    break;
                case 'error':
                    alertClass = 'alert-danger';
                    icon = 'bi-exclamation-triangle';
                    break;
                case 'warning':
                    alertClass = 'alert-warning';
                    icon = 'bi-exclamation-triangle';
                    break;
            }
            
            var alertDiv = document.createElement('div');
            alertDiv.className = 'alert ' + alertClass + ' alert-dismissible fade show position-fixed';
            alertDiv.style.cssText = 'top:20px;right:20px;z-index:9999;min-width:300px;max-width:500px;';
            alertDiv.innerHTML = '<i class="bi ' + icon + ' me-2"></i>' + mensaje + 
                               '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            
            document.body.appendChild(alertDiv);
            
            // Auto-cerrar después de 4 segundos
            setTimeout(function() {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 4000);
        }

        function ocultarResultados() {
            var seccion = document.getElementById('productosEncontrados');
            if (seccion) {
                seccion.style.display = 'none';
            }
        }

        function seleccionarCliente() {
            var select = document.getElementById('clienteSelect');
            var infoDiv = document.getElementById('infoCliente');
            var infoSpan = document.getElementById('clienteInfo');
            
            if (!select || !infoDiv || !infoSpan) return;
            
            if (select.value === 'general') {
                infoSpan.textContent = 'Cliente General seleccionado';
                infoDiv.style.display = 'block';
            } else if (select.value) {
                var clienteNombre = select.options[select.selectedIndex].text;
                infoSpan.textContent = 'Cliente: ' + clienteNombre;
                infoDiv.style.display = 'block';
            } else {
                infoDiv.style.display = 'none';
            }
        }

        // ===== FUNCIONES AUXILIARES ADICIONALES =====
        function getCSRFToken() {
            var token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                return token.getAttribute('content');
            }
            var hiddenInput = document.querySelector('input[name="_token"]');
            if (hiddenInput) {
                return hiddenInput.value;
            }
            return null;
        }
        
        function generarTicketVenta(venta, ventaData) {
            console.log('Generando ticket para venta:', venta);
            
            // Crear ventana de ticket
            var ticketWindow = window.open('', '_blank', 'width=400,height=600,scrollbars=yes');
            if (!ticketWindow) {
                mostrarMensaje('warning', 'No se pudo abrir la ventana del ticket. Verifique que los popups estén habilitados.');
                return;
            }
            
            var clienteSelect = document.getElementById('clienteSelect');
            var clienteNombre = clienteSelect ? 
                (clienteSelect.value === 'general' ? 'Cliente General' : 
                 clienteSelect.options[clienteSelect.selectedIndex]?.text || 'Cliente General') : 
                'Cliente General';
            
            var fecha = new Date().toLocaleString('es-PE');
            var numeroTicket = venta.numero_ticket || 'V' + Date.now();
            
            var ticketHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Ticket de Venta - ${numeroTicket}</title>
                <style>
                    body { font-family: 'Courier New', monospace; font-size: 12px; margin: 20px; }
                    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
                    .info { margin-bottom: 15px; }
                    .productos { border-collapse: collapse; width: 100%; margin-bottom: 15px; }
                    .productos th, .productos td { border: 1px solid #000; padding: 5px; text-align: left; }
                    .productos th { background-color: #f0f0f0; }
                    .totales { border-top: 2px solid #000; padding-top: 10px; }
                    .total { font-weight: bold; font-size: 14px; }
                    .footer { text-align: center; margin-top: 20px; border-top: 1px solid #000; padding-top: 10px; }
                    @media print { body { margin: 0; } }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>FARMACIA SISTEMA</h2>
                    <p>RUC: 20123456789</p>
                    <p>Av. Principal 123, Lima</p>
                    <p>Tel: (01) 123-4567</p>
                </div>
                
                <div class="info">
                    <p><strong>Ticket:</strong> ${numeroTicket}</p>
                    <p><strong>Fecha:</strong> ${fecha}</p>
                    <p><strong>Cliente:</strong> ${clienteNombre}</p>
                    <p><strong>Tipo de Pago:</strong> ${ventaData.tipo_pago || 'Efectivo'}</p>
                </div>
                
                <table class="productos">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cant.</th>
                            <th>P.Unit</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>`;
            
            carritoVenta.forEach(function(producto) {
                var subtotalProducto = producto.precio * producto.cantidad;
                ticketHTML += `
                        <tr>
                            <td>${producto.nombre}</td>
                            <td>${producto.cantidad}</td>
                            <td>S/ ${producto.precio.toFixed(2)}</td>
                            <td>S/ ${subtotalProducto.toFixed(2)}</td>
                        </tr>`;
            });
            
            ticketHTML += `
                    </tbody>
                </table>
                
                <div class="totales">
                    <p><strong>Subtotal:</strong> S/ ${ventaData.subtotal.toFixed(2)}</p>
                    <p><strong>IGV (18%):</strong> S/ ${ventaData.igv.toFixed(2)}</p>
                    <p class="total"><strong>TOTAL:</strong> S/ ${ventaData.total.toFixed(2)}</p>
                </div>
                
                <div class="footer">
                    <p>¡Gracias por su compra!</p>
                    <p>Conserve este ticket para cualquier reclamo</p>
                </div>
                
                <scr' + 'ipt>
                    window.onload = function() {
                        setTimeout(function() {
                            window.print();
                        }, 500);
                    };
                </scr' + 'ipt>
            </body>
            </html>`;
            
            ticketWindow.document.write(ticketHTML);
            ticketWindow.document.close();
        }

        // ===== FUNCIONES DE PROCESAMIENTO =====
        function procesarVenta() {
            console.log('Procesando venta...');
            
            if (carritoVenta.length === 0) {
                mostrarMensaje('warning', 'Agrega productos al carrito antes de procesar');
                return;
            }
            
            var clienteSelect = document.getElementById('clienteSelect');
            if (!clienteSelect || !clienteSelect.value) {
                mostrarMensaje('warning', 'Selecciona un cliente para continuar');
                return;
            }
            
            var btnProcesar = document.getElementById('btnProcesarVenta');
            var originalText = btnProcesar ? btnProcesar.innerHTML : '';
            if (btnProcesar) {
                btnProcesar.disabled = true;
                btnProcesar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Procesando...';
            }
            
            // Calcular totales
            var subtotal = carritoVenta.reduce(function(sum, item) {
                return sum + (item.precio * item.cantidad);
            }, 0);
            var igv = subtotal * 0.18;
            var total = subtotal + igv;
            
            var tipoPago = document.getElementById('tipoPago');
            
            // Datos de la venta
            var ventaData = {
                cliente_id: clienteSelect.value === 'general' ? null : clienteSelect.value,
                tipo_pago: tipoPago ? tipoPago.value : 'efectivo',
                productos: carritoVenta.map(function(item) {
                    return {
                        producto_id: item.id,
                        cantidad: item.cantidad,
                        precio_unitario: item.precio
                    };
                }),
                subtotal: subtotal,
                igv: igv,
                total: total,
                _token: getCSRFToken()
            };
            
            console.log('Datos de venta a enviar:', ventaData);
            
            // Enviar al servidor
            fetch('/ventas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': ventaData._token
                },
                credentials: 'same-origin',
                body: JSON.stringify(ventaData)
            })
            .then(function(response) {
                console.log('Respuesta del servidor:', response.status);
                if (!response.ok) {
                    throw new Error('Error del servidor: ' + response.status);
                }
                return response.json();
            })
            .then(function(data) {
                console.log('Datos recibidos:', data);
                if (data.success) {
                    var ticketNumero = data.venta.numero_ticket || 'V' + Date.now();
                    mostrarMensaje('success', '¡Venta procesada exitosamente! Ticket: ' + ticketNumero);
                    
                    // Generar ticket
                    generarTicketVenta(data.venta, ventaData);
                    
                    // Limpiar y cerrar después de 3 segundos
                    setTimeout(function() {
                        limpiarVenta();
                        var modal = bootstrap.Modal.getInstance(document.getElementById('nuevaVentaModal'));
                        if (modal) modal.hide();
                        
                        // Recargar página para ver la venta en la tabla
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }, 3000);
                } else {
                    mostrarMensaje('error', data.message || 'Error al procesar la venta');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                mostrarMensaje('error', 'Error: ' + error.message);
            })
            .finally(function() {
                if (btnProcesar) {
                    btnProcesar.innerHTML = originalText;
                    btnProcesar.disabled = false;
                }
            });
        }



        // ===== FUNCIONES DE DEBUG =====
        function debugBusquedaCompleta() {
            console.log('=== DEBUG SISTEMA POS ===');
            console.log('Carrito actual:', carritoVenta);
            console.log('Productos cache:', productosEncontradosCache);
            console.log('Elementos DOM:');
            console.log('- buscarProducto:', document.getElementById('buscarProducto'));
            console.log('- carritoBody:', document.getElementById('carritoBody'));
            console.log('- cantidadItems:', document.getElementById('cantidadItems'));
            
            mostrarMensaje('info', 'Debug completado - Ver consola para detalles');
        }

        function diagnosticarBusqueda() {
            console.log('Diagnóstico de búsqueda...');
            var input = document.getElementById('buscarProducto');
            if (input) {
                input.value = 'para';
                ejecutarBusqueda('para');
                mostrarMensaje('success', 'Búsqueda de prueba ejecutada - "para" (mínimo 2 caracteres)');
            } else {
                mostrarMensaje('error', 'Campo de búsqueda no encontrado');
            }
        }

        function mostrarEstadisticasBusqueda() {
            var stats = {
                'Productos en carrito': carritoVenta.length,
                'Productos en cache': productosEncontradosCache.length,
                'Sistema funcionando': 'OK'
            };
            console.log('Estadísticas del sistema:', stats);
            actualizarEstadisticas();
        }

        // ============ FUNCIONES DE BÚSQUEDA DE VENTAS ============
        function ejecutarBusquedaVentas() {
            console.log('Ejecutando búsqueda de ventas...');
            mostrarMensaje('info', 'Función de búsqueda de ventas activada');
            
            // Simular resultados
            setTimeout(function() {
                mostrarMensaje('success', 'Se encontraron 5 ventas simuladas');
            }, 1000);
        }

        // ============ FUNCIONES DE REPORTES ============
        function generarReporteDiario() {
            mostrarMensaje('success', 'Reporte diario simulado generado');
        }

        function generarReporteSemanal() {
            mostrarMensaje('success', 'Reporte semanal simulado generado');
        }

        function generarReporteMensual() {
            mostrarMensaje('success', 'Reporte mensual simulado generado');
        }

        function generarReportePersonalizado() {
            mostrarMensaje('success', 'Reporte personalizado simulado generado');
        }

        // ============ FUNCIONES DE ESTADÍSTICAS ============
        function actualizarEstadisticas() {
            mostrarMensaje('success', 'Estadísticas actualizadas');
        }

        // ============ FUNCIONES DE GESTIÓN DE VENTAS ============
        var ventaActual = null; // Variable global para la venta en el modal

        function verTicket(ventaId) {
            console.log('Ver ticket de venta:', ventaId);
            
            // Abrir modal y mostrar loading
            var modal = new bootstrap.Modal(document.getElementById('verTicketModal'));
            modal.show();
            
            // Cargar datos de la venta
            fetch('/ventas/' + ventaId, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCSRFToken()
                },
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Error al cargar la venta: ' + response.status);
                }
                return response.json();
            })
            .then(function(data) {
                ventaActual = data.venta; // Guardar para imprimir
                mostrarDetallesTicket(data.venta);
            })
            .catch(function(error) {
                console.error('Error:', error);
                document.getElementById('contenidoTicket').innerHTML = 
                    '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>Error al cargar los detalles de la venta</div>';
            });
        }

        function mostrarDetallesTicket(venta) {
            var html = `
                <div class="ticket-preview">
                    <div class="text-center mb-4">
                        <h4 class="text-primary fw-bold">🏥 FARMACIA SISTEMA</h4>
                        <p class="mb-1">RUC: 20123456789</p>
                        <p class="mb-1">Av. Principal 123, Lima</p>
                        <p class="mb-3">Tel: (01) 123-4567</p>
                        <hr>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Ticket:</strong> ${venta.numero_ticket || 'V' + venta.id}
                        </div>
                        <div class="col-md-6">
                            <strong>Fecha:</strong> ${new Date(venta.fecha || venta.created_at).toLocaleString('es-PE')}
                        </div>
                        <div class="col-md-6">
                            <strong>Cliente:</strong> ${venta.cliente ? venta.cliente.nombres + ' ' + venta.cliente.apellidos : 'Cliente General'}
                        </div>
                        <div class="col-md-6">
                            <strong>Vendedor:</strong> ${venta.user ? venta.user.name : 'Sistema'}
                        </div>
                        <div class="col-md-6">
                            <strong>Tipo de Pago:</strong> ${venta.tipo_pago ? venta.tipo_pago.charAt(0).toUpperCase() + venta.tipo_pago.slice(1) : 'Efectivo'}
                        </div>
                    </div>
                    
                    <h6 class="fw-bold text-primary mb-3">📦 Productos</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Cant.</th>
                                    <th>P.Unit</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>`;
            
            if (venta.detalles && venta.detalles.length > 0) {
                venta.detalles.forEach(function(detalle) {
                    html += `
                        <tr>
                            <td>${detalle.producto ? detalle.producto.nombre : 'Producto'}</td>
                            <td>${detalle.cantidad}</td>
                            <td>S/ ${parseFloat(detalle.precio_unitario || 0).toFixed(2)}</td>
                            <td>S/ ${parseFloat(detalle.subtotal || 0).toFixed(2)}</td>
                        </tr>`;
                });
            }
            
            html += `
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="bg-light p-3 rounded mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Subtotal:</strong> S/ ${parseFloat(venta.subtotal || 0).toFixed(2)}
                            </div>
                            <div class="col-md-4">
                                <strong>IGV (18%):</strong> S/ ${parseFloat(venta.igv || 0).toFixed(2)}
                            </div>
                            <div class="col-md-4">
                                <strong class="text-success fs-5">TOTAL:</strong> 
                                <span class="text-success fw-bold fs-5">S/ ${parseFloat(venta.total || 0).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                </div>`;
            
            document.getElementById('contenidoTicket').innerHTML = html;
        }

        function imprimirTicket(ventaId) {
            console.log('Imprimir ticket:', ventaId);
            
            // Si ya tenemos los datos en ventaActual, usar esos
            if (ventaActual && ventaActual.id == ventaId) {
                generarTicketImpresion(ventaActual);
                return;
            }
            
            // Cargar datos y luego imprimir
            fetch('/ventas/' + ventaId, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCSRFToken()
                },
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Error al cargar la venta');
                }
                return response.json();
            })
            .then(function(data) {
                generarTicketImpresion(data.venta);
            })
            .catch(function(error) {
                console.error('Error:', error);
                mostrarMensaje('error', 'Error al cargar la venta para imprimir');
            });
        }

        function imprimirTicketDesdeModal() {
            if (ventaActual) {
                generarTicketImpresion(ventaActual);
            }
        }

        function generarTicketImpresion(venta) {
            var ticketWindow = window.open('', '_blank', 'width=400,height=600,scrollbars=yes');
            if (!ticketWindow) {
                mostrarMensaje('warning', 'No se pudo abrir la ventana del ticket. Verifique que los popups estén habilitados.');
                return;
            }
            
            var clienteNombre = venta.cliente ? 
                venta.cliente.nombres + ' ' + venta.cliente.apellidos : 
                'Cliente General';
            
            var fecha = new Date(venta.fecha || venta.created_at).toLocaleString('es-PE');
            var numeroTicket = venta.numero_ticket || 'V' + venta.id;
            
            var ticketHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Ticket de Venta - ${numeroTicket}</title>
                <style>
                    body { font-family: 'Courier New', monospace; font-size: 12px; margin: 20px; }
                    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
                    .info { margin-bottom: 15px; }
                    .productos { border-collapse: collapse; width: 100%; margin-bottom: 15px; }
                    .productos th, .productos td { border: 1px solid #000; padding: 5px; text-align: left; }
                    .productos th { background-color: #f0f0f0; }
                    .totales { border-top: 2px solid #000; padding-top: 10px; }
                    .total { font-weight: bold; font-size: 14px; }
                    .footer { text-align: center; margin-top: 20px; border-top: 1px solid #000; padding-top: 10px; }
                    @media print { body { margin: 0; } }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>FARMACIA SISTEMA</h2>
                    <p>RUC: 20123456789</p>
                    <p>Av. Principal 123, Lima</p>
                    <p>Tel: (01) 123-4567</p>
                </div>
                
                <div class="info">
                    <p><strong>Ticket:</strong> ${numeroTicket}</p>
                    <p><strong>Fecha:</strong> ${fecha}</p>
                    <p><strong>Cliente:</strong> ${clienteNombre}</p>
                    <p><strong>Vendedor:</strong> ${venta.user ? venta.user.name : 'Sistema'}</p>
                    <p><strong>Tipo de Pago:</strong> ${venta.tipo_pago ? venta.tipo_pago.charAt(0).toUpperCase() + venta.tipo_pago.slice(1) : 'Efectivo'}</p>
                </div>
                
                <table class="productos">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cant.</th>
                            <th>P.Unit</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>`;
            
            if (venta.detalles && venta.detalles.length > 0) {
                venta.detalles.forEach(function(detalle) {
                    ticketHTML += `
                        <tr>
                            <td>${detalle.producto ? detalle.producto.nombre : 'Producto'}</td>
                            <td>${detalle.cantidad}</td>
                            <td>S/ ${parseFloat(detalle.precio_unitario || 0).toFixed(2)}</td>
                            <td>S/ ${parseFloat(detalle.subtotal || 0).toFixed(2)}</td>
                        </tr>`;
                });
            }
            
            ticketHTML += `
                    </tbody>
                </table>
                
                <div class="totales">
                    <p><strong>Subtotal:</strong> S/ ${parseFloat(venta.subtotal || 0).toFixed(2)}</p>
                    <p><strong>IGV (18%):</strong> S/ ${parseFloat(venta.igv || 0).toFixed(2)}</p>
                    <p class="total"><strong>TOTAL:</strong> S/ ${parseFloat(venta.total || 0).toFixed(2)}</p>
                </div>
                
                <div class="footer">
                    <p>¡Gracias por su compra!</p>
                    <p>Conserve este ticket para cualquier reclamo</p>
                    <p>Fecha de impresión: ${new Date().toLocaleString('es-PE')}</p>
                </div>
                
                <scr' + 'ipt>
                    window.onload = function() {
                        setTimeout(function() {
                            window.print();
                        }, 500);
                    };
                </scr' + 'ipt>
            </body>
            </html>`;
            
            ticketWindow.document.write(ticketHTML);
            ticketWindow.document.close();
        }

        function editarVenta(ventaId) {
            console.log('Editar venta:', ventaId);
            
            // Abrir modal de edición
            var modal = new bootstrap.Modal(document.getElementById('editarVentaModal'));
            modal.show();
            
            // Cargar datos de la venta
            fetch('/ventas/' + ventaId, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCSRFToken()
                },
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Error al cargar la venta');
                }
                return response.json();
            })
            .then(function(data) {
                cargarDatosEdicion(data.venta);
            })
            .catch(function(error) {
                console.error('Error:', error);
                mostrarMensaje('error', 'Error al cargar los datos de la venta');
                modal.hide();
            });
        }

        function cargarDatosEdicion(venta) {
            document.getElementById('editVentaId').value = venta.id;
            document.getElementById('editNumeroTicket').value = venta.numero_ticket || 'V' + venta.id;
            document.getElementById('editFechaVenta').value = new Date(venta.fecha || venta.created_at).toLocaleString('es-PE');
            document.getElementById('editCliente').value = venta.cliente_id || '';
            document.getElementById('editTipoPago').value = venta.tipo_pago || 'efectivo';
            document.getElementById('editObservaciones').value = venta.observaciones || '';
            
            // Cargar productos
            var tbody = document.getElementById('listaProductosEdit');
            var html = '';
            
            if (venta.detalles && venta.detalles.length > 0) {
                venta.detalles.forEach(function(detalle) {
                    html += `
                        <tr>
                            <td>${detalle.producto ? detalle.producto.nombre : 'Producto'}</td>
                            <td>${detalle.cantidad}</td>
                            <td>S/ ${parseFloat(detalle.precio_unitario || 0).toFixed(2)}</td>
                            <td>S/ ${parseFloat(detalle.subtotal || 0).toFixed(2)}</td>
                        </tr>`;
                });
            }
            
            tbody.innerHTML = html;
            
            // Mostrar totales
            document.getElementById('editSubtotal').textContent = 'S/ ' + parseFloat(venta.subtotal || 0).toFixed(2);
            document.getElementById('editIGV').textContent = 'S/ ' + parseFloat(venta.igv || 0).toFixed(2);
            document.getElementById('editTotal').textContent = 'S/ ' + parseFloat(venta.total || 0).toFixed(2);
        }

        function guardarCambiosVenta() {
            var ventaId = document.getElementById('editVentaId').value;
            var clienteId = document.getElementById('editCliente').value;
            var tipoPago = document.getElementById('editTipoPago').value;
            var observaciones = document.getElementById('editObservaciones').value;
            
            var datosActualizacion = {
                cliente_id: clienteId || null,
                tipo_pago: tipoPago,
                observaciones: observaciones,
                _token: getCSRFToken(),
                _method: 'PUT'
            };
            
            fetch('/ventas/' + ventaId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': datosActualizacion._token
                },
                credentials: 'same-origin',
                body: JSON.stringify(datosActualizacion)
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Error al actualizar la venta');
                }
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    mostrarMensaje('success', 'Venta actualizada exitosamente');
                    var modal = bootstrap.Modal.getInstance(document.getElementById('editarVentaModal'));
                    modal.hide();
                    
                    // Recargar página después de 2 segundos
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    mostrarMensaje('error', data.message || 'Error al actualizar la venta');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                mostrarMensaje('error', 'Error al guardar los cambios: ' + error.message);
            });
        }

        // ============ FUNCIONES DE CERRAR SESIÓN ============
        function mostrarModalCerrarSesion() { 
            var modal = new bootstrap.Modal(document.getElementById('modalCerrarSesion'));
            modal.show();
        }
        
        function ejecutarCerrarSesion() { 
            document.getElementById('logout-form-ventas').submit();
        }

        // ============ INICIALIZACIÓN DEL SISTEMA ============
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Iniciando Sistema POS...');
            
            // Verificar elementos críticos
            var elementosCriticos = [
                'buscarProducto',
                'carritoBody', 
                'cantidadItems',
                'btnProcesarVenta'
            ];
            
            var errores = [];
            elementosCriticos.forEach(function(id) {
                if (!document.getElementById(id)) {
                    errores.push(id);
                }
            });
            
            if (errores.length > 0) {
                console.error('❌ Elementos faltantes:', errores);
            } else {
                console.log('✅ Todos los elementos DOM encontrados');
            }
            
            // Inicializar carrito
            actualizarCarrito();
            
            // Configurar modal de nueva venta
            var modalNuevaVenta = document.getElementById('nuevaVentaModal');
            if (modalNuevaVenta) {
                modalNuevaVenta.addEventListener('shown.bs.modal', function() {
                    console.log('Modal nueva venta abierto');
                    
                    // Limpiar formulario
                    limpiarVenta();
                    
                    // Enfocar campo de búsqueda
                    var buscarInput = document.getElementById('buscarProducto');
                    if (buscarInput) {
                        buscarInput.focus();
                    }
                });
                
                modalNuevaVenta.addEventListener('hidden.bs.modal', function() {
                    console.log('Modal nueva venta cerrado');
                    limpiarVenta();
                });
            }
            
            console.log('✅ Sistema POS cargado correctamente');
            mostrarMensaje('success', 'Sistema POS iniciado correctamente');
        });
    </script>
</body>
</html>