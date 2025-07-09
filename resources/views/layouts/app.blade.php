<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Farmacia Magistral') - Farmacia Magistral</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), #1e40af);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar .logo {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar .logo h4 {
            margin: 0;
            font-weight: bold;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border: none;
        }

        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #1e40af;
            border-color: #1e40af;
        }

        .table th {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
        }

        .badge {
            font-weight: 500;
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }

        .modal-backdrop {
            background-color: rgba(0,0,0,0.5);
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="logo text-center">
            <img src="/logo-farmacia.png" alt="Farmacia Magistral" style="max-width: 90px; margin-bottom: 0.5rem;">
            <h4 class="mt-2">Farmacia Magistral</h4>
            <small class="text-light">Sistema de Gestión</small>
        </div>
        
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}" href="{{ route('productos.index') }}">
                    <i class="bi bi-capsule"></i>
                    Productos
                    @if(auth()->user()->esAdministrador())
                        <span class="position-relative">
                            <span id="productos-notificacion" class="notification-badge d-none">0</span>
                        </span>
                    @endif
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#ventasSubmenu" aria-expanded="false">
                    <i class="bi bi-cart3"></i>
                    Ventas
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ request()->routeIs('ventas.*') ? 'show' : '' }}" id="ventasSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('ventas.index') ? 'active' : '' }}" href="{{ route('ventas.index') }}">
                                <i class="bi bi-list-ul"></i>
                                Historial
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('ventas.directa') ? 'active' : '' }}" href="{{ route('ventas.directa') }}">
                                <i class="bi bi-lightning"></i>
                                Venta Directa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('ventas.create') ? 'active' : '' }}" href="{{ route('ventas.create') }}">
                                <i class="bi bi-plus-square"></i>
                                Venta Manual
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                    <i class="bi bi-people"></i>
                    Clientes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('laboratorio.*') ? 'active' : '' }}" href="{{ route('laboratorio.index') }}">
                    <i class="bi bi-flask"></i>
                    Laboratorio
                </a>
            </li>
            
            @if(auth()->user()->esAdministrador())
            <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
            <li class="nav-item">
                <small class="text-uppercase text-light px-3" style="font-size: 0.75rem; opacity: 0.7;">Administración</small>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}" href="{{ route('categorias.index') }}">
                    <i class="bi bi-tags"></i>
                    Categorías
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('marcas.*') ? 'active' : '' }}" href="{{ route('marcas.index') }}">
                    <i class="bi bi-bookmark"></i>
                    Marcas
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('proveedores.*') ? 'active' : '' }}" href="{{ route('proveedores.index') }}">
                    <i class="bi bi-truck"></i>
                    Proveedores
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                    <i class="bi bi-person-gear"></i>
                    Usuarios
                </a>
            </li>
            @endif
        </ul>
        
        <div class="mt-auto p-3">
            <div class="text-center">
                <small class="text-light d-block">{{ auth()->user()->name }}</small>
                <small class="text-light opacity-75">{{ ucfirst(auth()->user()->role) }}</small>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <button class="btn btn-link d-md-none" type="button" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bell position-relative me-2">
                                <span id="total-notificaciones" class="notification-badge d-none">0</span>
                            </i>
                            Notificaciones
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="width: 350px;" id="notificaciones-dropdown">
                            <li><h6 class="dropdown-header">Notificaciones</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li id="sin-notificaciones"><span class="dropdown-item-text text-muted">No hay notificaciones</span></li>
                        </ul>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS (solo una vez, sin jQuery para Bootstrap 5) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Toggle sidebar en móviles
            $('#sidebarToggle').click(function() {
                $('#sidebar').toggleClass('show');
            });

            // CSRF token para peticiones AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Cargar notificaciones
            cargarNotificaciones();
            
            // Actualizar notificaciones cada 5 minutos
            setInterval(cargarNotificaciones, 300000);
        });

        function cargarNotificaciones() {
            $.get('{{ route('dashboard.notificaciones') }}', function(data) {
                const totalNotificaciones = data.total_notificaciones;
                const badge = $('#total-notificaciones');
                const dropdown = $('#notificaciones-dropdown');
                
                if (totalNotificaciones > 0) {
                    badge.text(totalNotificaciones).removeClass('d-none');
                    
                    let html = '<li><h6 class="dropdown-header">Notificaciones (' + totalNotificaciones + ')</h6></li>';
                    html += '<li><hr class="dropdown-divider"></li>';
                    
                    if (data.stock_bajo.length > 0) {
                        html += '<li><h6 class="dropdown-header text-warning"><i class="bi bi-exclamation-triangle"></i> Stock Bajo</h6></li>';
                        data.stock_bajo.forEach(function(producto) {
                            html += '<li><span class="dropdown-item-text small">' + producto.nombre + ' (Stock: ' + producto.stock_actual + ')</span></li>';
                        });
                    }
                    
                    if (data.proximos_vencer.length > 0) {
                        html += '<li><h6 class="dropdown-header text-danger"><i class="bi bi-calendar-x"></i> Próximos a Vencer</h6></li>';
                        data.proximos_vencer.forEach(function(producto) {
                            html += '<li><span class="dropdown-item-text small">' + producto.nombre + ' (' + producto.fecha_vencimiento + ')</span></li>';
                        });
                    }
                    
                    dropdown.html(html);
                } else {
                    badge.addClass('d-none');
                    dropdown.html('<li><h6 class="dropdown-header">Notificaciones</h6></li><li><hr class="dropdown-divider"></li><li><span class="dropdown-item-text text-muted">No hay notificaciones</span></li>');
                }
            });
        }

        // Función global para mostrar mensajes
        function mostrarMensaje(mensaje, tipo = 'success') {
            const alert = $('<div class="alert alert-' + tipo + ' alert-dismissible fade show animate-fade-in" role="alert">' +
                '<i class="bi bi-' + (tipo === 'success' ? 'check-circle' : 'exclamation-triangle') + ' me-2"></i>' +
                mensaje +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                '</div>');
            
            $('.container-fluid').first().prepend(alert);
            
            setTimeout(function() {
                alert.alert('close');
            }, 5000);
        }
    </script>
    
    @stack('scripts')
</body>
</html> 