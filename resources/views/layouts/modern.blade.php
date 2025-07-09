<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Farmacia Magistral')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- ========== ESTILOS UNIFICADOS DE FARMACIA ========== -->
    <style>
        :root {
            /* Colores principales de farmacia */
            --pharmaSys-primary: #00a651;      /* Verde médico principal */
            --pharmaSys-secondary: #0066cc;    /* Azul médico */
            --pharmaSys-success: #28a745;      /* Verde éxito */
            --pharmaSys-danger: #dc3545;       /* Rojo emergencia */
            --pharmaSys-warning: #ffc107;      /* Amarillo alerta */
            --pharmaSys-info: #17a2b8;         /* Azul información */
            --pharmaSys-light: #f8f9fa;        /* Gris claro */
            --pharmaSys-dark: #343a40;         /* Gris oscuro */
            --pharmaSys-white: #ffffff;        /* Blanco puro */
            
            /* Gradientes temáticos */
            --pharmaSys-gradient-primary: linear-gradient(135deg, #00a651 0%, #28a745 100%);
            --pharmaSys-gradient-secondary: linear-gradient(135deg, #0066cc 0%, #17a2b8 100%);
            --pharmaSys-gradient-success: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            --pharmaSys-gradient-danger: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
            --pharmaSys-gradient-warning: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            --pharmaSys-gradient-info: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
            
            /* Sombras médicas */
            --pharmaSys-shadow-sm: 0 2px 8px rgba(0, 166, 81, 0.1);
            --pharmaSys-shadow-md: 0 4px 15px rgba(0, 166, 81, 0.15);
            --pharmaSys-shadow-lg: 0 8px 25px rgba(0, 166, 81, 0.2);
            --pharmaSys-shadow-xl: 0 15px 35px rgba(0, 166, 81, 0.25);
            
            /* Tipografía médica */
            --pharmaSys-font-family: 'Figtree', 'Segoe UI', 'Roboto', sans-serif;
            --pharmaSys-font-size-xs: 0.75rem;
            --pharmaSys-font-size-sm: 0.875rem;
            --pharmaSys-font-size-base: 1rem;
            --pharmaSys-font-size-lg: 1.125rem;
            --pharmaSys-font-size-xl: 1.25rem;
            --pharmaSys-font-size-2xl: 1.5rem;
            --pharmaSys-font-size-3xl: 1.875rem;
            
            /* Espaciados */
            --pharmaSys-border-radius: 12px;
            --pharmaSys-border-radius-lg: 16px;
            --pharmaSys-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --sidebar-width: 250px;
        }

        /* ========== RESET Y BASE ========== */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: var(--pharmaSys-font-family);
            background: linear-gradient(120deg, #f8f9fa 0%, #e9ecef 100%);
            color: var(--pharmaSys-dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ========== COMPONENTES PRINCIPALES ========== */
        
        /* Sidebar médico */
        .sidebar-modern {
            background: var(--pharmaSys-gradient-primary);
            border-radius: 0 var(--pharmaSys-border-radius-lg) var(--pharmaSys-border-radius-lg) 0;
            box-shadow: var(--pharmaSys-shadow-lg);
            border-right: 3px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-modern .nav-link {
            color: rgba(255, 255, 255, 0.9);
            border-radius: var(--pharmaSys-border-radius);
            margin: 0.25rem 0.5rem;
            padding: 0.75rem 1rem;
            transition: var(--pharmaSys-transition);
            position: relative;
            overflow: hidden;
        }

        .sidebar-modern .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: var(--pharmaSys-white);
            transform: translateX(5px);
            box-shadow: var(--pharmaSys-shadow-md);
        }

        .sidebar-modern .nav-link.active {
            background: var(--pharmaSys-white);
            color: var(--pharmaSys-primary);
            font-weight: 600;
            box-shadow: var(--pharmaSys-shadow-md);
        }

        .sidebar-modern .nav-link i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 1.125rem;
        }

        /* Logo de farmacia */
        .sidebar-modern .navbar-brand {
            color: var(--pharmaSys-white);
            font-weight: 700;
            font-size: 1.5rem;
            text-decoration: none;
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .sidebar-modern .navbar-brand:hover {
            color: var(--pharmaSys-white);
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }

        /* Contenido principal */
        .content-modern {
            background: var(--pharmaSys-white);
            padding: 2rem 24px 2rem 24px;
            min-height: 100vh;
            width: 100vw;
            max-width: none;
            margin-left: 280px;
        }

        /* ========== TARJETAS Y MODALES ========== */
        
        .modern-card {
            background: var(--pharmaSys-white);
            border-radius: var(--pharmaSys-border-radius);
            border: 1px solid rgba(0, 166, 81, 0.1);
            box-shadow: var(--pharmaSys-shadow-sm);
            padding: 1.5rem;
            transition: var(--pharmaSys-transition);
            position: relative;
            overflow: hidden;
        }

        .modern-card:hover {
            box-shadow: var(--pharmaSys-shadow-md);
            transform: translateY(-2px);
            border-color: rgba(0, 166, 81, 0.2);
        }

        .modern-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--pharmaSys-gradient-primary);
        }

        /* Tarjetas de estadísticas */
        .stat-card {
            background: var(--pharmaSys-white);
            border-radius: var(--pharmaSys-border-radius);
            padding: 2rem 1.5rem;
            text-align: center;
            transition: var(--pharmaSys-transition);
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
            box-shadow: var(--pharmaSys-shadow-sm);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--pharmaSys-shadow-lg);
        }

        .stat-card.primary {
            border-left: 4px solid var(--pharmaSys-primary);
            background: linear-gradient(135deg, rgba(0, 166, 81, 0.05) 0%, rgba(0, 166, 81, 0.01) 100%);
        }

        .stat-card.secondary {
            border-left: 4px solid var(--pharmaSys-secondary);
            background: linear-gradient(135deg, rgba(0, 102, 204, 0.05) 0%, rgba(0, 102, 204, 0.01) 100%);
        }

        .stat-card.success {
            border-left: 4px solid var(--pharmaSys-success);
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.01) 100%);
        }

        .stat-card.danger {
            border-left: 4px solid var(--pharmaSys-danger);
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.05) 0%, rgba(220, 53, 69, 0.01) 100%);
        }

        .stat-card.warning {
            border-left: 4px solid var(--pharmaSys-warning);
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.05) 0%, rgba(255, 193, 7, 0.01) 100%);
        }

        .stat-card.info {
            border-left: 4px solid var(--pharmaSys-info);
            background: linear-gradient(135deg, rgba(23, 162, 184, 0.05) 0%, rgba(23, 162, 184, 0.01) 100%);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .stat-icon.primary { color: var(--pharmaSys-primary); }
        .stat-icon.secondary { color: var(--pharmaSys-secondary); }
        .stat-icon.success { color: var(--pharmaSys-success); }
        .stat-icon.danger { color: var(--pharmaSys-danger); }
        .stat-icon.warning { color: var(--pharmaSys-warning); }
        .stat-icon.info { color: var(--pharmaSys-info); }

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

        /* ========== BOTONES MÉDICOS ========== */
        
        .btn-modern {
            border-radius: var(--pharmaSys-border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: var(--pharmaSys-font-size-sm);
            transition: var(--pharmaSys-transition);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--pharmaSys-shadow-md);
        }

        .btn-modern:active {
            transform: translateY(0);
        }

        .btn-success-modern {
            background: var(--pharmaSys-gradient-success);
            color: var(--pharmaSys-white);
        }

        .btn-success-modern:hover {
            background: var(--pharmaSys-gradient-success);
            filter: brightness(1.1);
            color: var(--pharmaSys-white);
        }

        .btn-primary-modern {
            background: var(--pharmaSys-gradient-primary);
            color: var(--pharmaSys-white);
        }

        .btn-primary-modern:hover {
            background: var(--pharmaSys-gradient-primary);
            filter: brightness(1.1);
            color: var(--pharmaSys-white);
        }

        .btn-warning-modern {
            background: var(--pharmaSys-gradient-warning);
            color: var(--pharmaSys-dark);
        }

        .btn-warning-modern:hover {
            background: var(--pharmaSys-gradient-warning);
            filter: brightness(1.1);
            color: var(--pharmaSys-dark);
        }

        .btn-danger-modern {
            background: var(--pharmaSys-gradient-danger);
            color: var(--pharmaSys-white);
        }

        .btn-danger-modern:hover {
            background: var(--pharmaSys-gradient-danger);
            filter: brightness(1.1);
            color: var(--pharmaSys-white);
        }

        .btn-info-modern {
            background: var(--pharmaSys-gradient-info);
            color: var(--pharmaSys-white);
        }

        .btn-info-modern:hover {
            background: var(--pharmaSys-gradient-info);
            filter: brightness(1.1);
            color: var(--pharmaSys-white);
        }

        /* ========== TABLAS MÉDICAS ========== */
        
        .modern-table {
            background: var(--pharmaSys-white);
            border-radius: var(--pharmaSys-border-radius);
            overflow: hidden;
            box-shadow: var(--pharmaSys-shadow-sm);
            border: 1px solid rgba(0, 166, 81, 0.1);
        }

        .modern-table .table {
            margin: 0;
        }

        .modern-table .table thead th {
            background: var(--pharmaSys-gradient-primary);
            color: var(--pharmaSys-white);
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: var(--pharmaSys-font-size-sm);
        }

        .modern-table .table tbody tr {
            transition: var(--pharmaSys-transition);
        }

        .modern-table .table tbody tr:hover {
            background: rgba(0, 166, 81, 0.05);
            transform: scale(1.01);
        }

        .modern-table .table tbody td {
            padding: 1rem;
            border-color: rgba(0, 166, 81, 0.1);
            vertical-align: middle;
        }

        /* ========== FORMULARIOS MÉDICOS ========== */
        
        .form-control {
            border-radius: var(--pharmaSys-border-radius);
            border: 2px solid rgba(0, 166, 81, 0.1);
            padding: 0.75rem 1rem;
            transition: var(--pharmaSys-transition);
            font-size: var(--pharmaSys-font-size-base);
        }

        .form-control:focus {
            border-color: var(--pharmaSys-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 166, 81, 0.25);
        }

        .form-select {
            border-radius: var(--pharmaSys-border-radius);
            border: 2px solid rgba(0, 166, 81, 0.1);
            padding: 0.75rem 1rem;
            transition: var(--pharmaSys-transition);
        }

        .form-select:focus {
            border-color: var(--pharmaSys-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 166, 81, 0.25);
        }

        /* ========== BADGES MÉDICOS ========== */
        
        .badge-modern {
            border-radius: var(--pharmaSys-border-radius);
            padding: 0.5rem 0.75rem;
            font-weight: 600;
            font-size: var(--pharmaSys-font-size-xs);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ========== MODALES MÉDICOS ========== */
        
        .modal-content {
            border-radius: var(--pharmaSys-border-radius-lg);
            border: none;
            box-shadow: var(--pharmaSys-shadow-xl);
        }

        .modal-header {
            background: var(--pharmaSys-gradient-primary);
            color: var(--pharmaSys-white);
            border-radius: var(--pharmaSys-border-radius-lg) var(--pharmaSys-border-radius-lg) 0 0;
            border-bottom: none;
            padding: 1.5rem 2rem;
        }

        .modal-header .modal-title {
            font-weight: 700;
            font-size: var(--pharmaSys-font-size-xl);
        }

        .modal-header .btn-close {
            filter: invert(1);
            opacity: 0.8;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            background: rgba(0, 166, 81, 0.02);
            border-top: 1px solid rgba(0, 166, 81, 0.1);
            border-radius: 0 0 var(--pharmaSys-border-radius-lg) var(--pharmaSys-border-radius-lg);
        }

        /* ========== EFECTOS ESPECIALES ========== */
        
        /* Efecto de carga de farmacia */
        @keyframes pharmaSys-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.05); }
        }

        .pharmaSys-loading {
            animation: pharmaSys-pulse 2s infinite;
        }

        /* Efecto de entrada suave */
        @keyframes pharmaSys-fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .pharmaSys-fade-in {
            animation: pharmaSys-fadeIn 0.6s ease-out;
        }

        /* ========== HEADER MÉDICO ========== */
        
        .header-modern {
            background: var(--pharmaSys-white);
            border-radius: var(--pharmaSys-border-radius);
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--pharmaSys-shadow-sm);
            border-left: 4px solid var(--pharmaSys-primary);
        }

        .header-modern h1 {
            color: var(--pharmaSys-primary);
            font-weight: 700;
            margin: 0;
            font-size: var(--pharmaSys-font-size-3xl);
        }

        .header-modern p {
            color: #6c757d;
            margin: 0;
            font-size: var(--pharmaSys-font-size-lg);
        }

        /* ========== DROPDOWN MÉDICO ========== */
        
        .dropdown-modern {
            position: relative;
        }

        .dropdown-modern .dropdown-toggle {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.9);
            font-size: var(--pharmaSys-font-size-base);
            display: flex;
            align-items: center;
            width: 100%;
            text-align: left;
            transition: var(--pharmaSys-transition);
        }

        .dropdown-modern .dropdown-toggle:hover {
            color: var(--pharmaSys-white);
            background: rgba(255, 255, 255, 0.1);
        }

        .dropdown-modern .dropdown-toggle::after {
            margin-left: auto;
            transition: var(--pharmaSys-transition);
        }

        .dropdown-modern.show .dropdown-toggle::after {
            transform: rotate(90deg);
        }

        .dropdown-modern .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: var(--pharmaSys-border-radius);
            box-shadow: var(--pharmaSys-shadow-md);
            margin-left: 1rem;
            width: calc(100% - 1rem);
            overflow: hidden;
        }

        .dropdown-modern .dropdown-item {
            color: var(--pharmaSys-dark);
            padding: 0.75rem 1rem;
            border-radius: var(--pharmaSys-border-radius);
            margin: 0.25rem;
            transition: var(--pharmaSys-transition);
            font-size: var(--pharmaSys-font-size-sm);
        }

        .dropdown-modern .dropdown-item:hover {
            background: var(--pharmaSys-primary);
            color: var(--pharmaSys-white);
            transform: translateX(5px);
        }

        /* ========== RESPONSIVE MÉDICO ========== */
        
        @media (max-width: 768px) {
            .content-modern {
                margin: 0.5rem;
                padding: 1rem;
                border-radius: var(--pharmaSys-border-radius);
            }

            .stat-card {
                margin-bottom: 1rem;
            }

            .modern-card {
                padding: 1rem;
            }

            .sidebar-modern {
                border-radius: 0;
            }

            .header-modern {
                padding: 1rem;
                text-align: center;
            }

            .header-modern h1 {
                font-size: var(--pharmaSys-font-size-2xl);
            }

            .btn-modern {
                padding: 0.5rem 1rem;
                font-size: var(--pharmaSys-font-size-xs);
            }
        }

        /* ========== UTILIDADES FARMACIA ========== */
        
        .text-pharmaSys-primary { color: var(--pharmaSys-primary) !important; }
        .text-pharmaSys-secondary { color: var(--pharmaSys-secondary) !important; }
        .bg-pharmaSys-primary { background-color: var(--pharmaSys-primary) !important; }
        .bg-pharmaSys-secondary { background-color: var(--pharmaSys-secondary) !important; }
        .border-pharmaSys-primary { border-color: var(--pharmaSys-primary) !important; }
        .border-pharmaSys-secondary { border-color: var(--pharmaSys-secondary) !important; }

        /* ========== BOTÓN FLOTANTE ========== */
        
        .floating-action {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            background: var(--pharmaSys-gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pharmaSys-white);
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: var(--pharmaSys-shadow-lg);
            transition: var(--pharmaSys-transition);
            z-index: 1000;
        }

        .floating-action:hover {
            transform: scale(1.1);
            box-shadow: var(--pharmaSys-shadow-xl);
        }

        /* ========== TOAST CONTAINER ========== */
        
        .toast-container {
            position: fixed;
            top: 2rem;
            right: 2rem;
            z-index: 1050;
        }

        /* Estado sin datos */
        .no-data-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }
        
        .no-data-state i {
            font-size: 5rem;
            opacity: 0.3;
            margin-bottom: 2rem;
            color: var(--pharmaSys-primary);
        }

        .no-data-state h4 {
            color: var(--pharmaSys-dark);
            margin-bottom: 1rem;
        }

        .no-data-state p {
            font-size: var(--pharmaSys-font-size-lg);
            margin-bottom: 2rem;
        }

        /* ========== BOTÓN FLOTANTE ========== */
        
        .floating-action {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            background: var(--pharmaSys-gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pharmaSys-white);
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: var(--pharmaSys-shadow-lg);
            transition: var(--pharmaSys-transition);
            z-index: 1000;
        }

        .floating-action:hover {
            transform: scale(1.1);
            box-shadow: var(--pharmaSys-shadow-xl);
        }

        /* ========== TOAST CONTAINER ========== */
        
        .toast-container {
            position: fixed;
            top: 2rem;
            right: 2rem;
            z-index: 1050;
        }

        /* ========== DROPDOWN MEJORAS ========== */
        
        .dropdown-modern .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            border-radius: var(--pharmaSys-border-radius);
            margin: 0.25rem 0.5rem;
            transition: var(--pharmaSys-transition);
        }

        .dropdown-modern .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: var(--pharmaSys-white);
            transform: translateX(5px);
        }

        .dropdown-modern .nav-link i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.75rem;
        }

        .main-content, .container-fluid {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content > .container-fluid > .row,
        .main-content > .container-fluid > #proveedoresContainer,
        .main-content > .container-fluid > #marcasContainer,
        .main-content > .container-fluid > #categoriasContainer {
            flex: 1 1 auto;
            min-height: 60vh;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar de Farmacia -->
        <nav class="sidebar-modern" style="width: 280px; height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000;">
            <!-- Logo de Farmacia -->
            <a class="navbar-brand d-flex align-items-center text-decoration-none" href="{{ route('dashboard') }}">
                <i class="bi bi-hospital me-2"></i>
                <div>
                    <div class="fw-bold">Farmacia Magistral</div>
                    <small class="opacity-75">Farmacia Magistral</small>
                </div>
            </a>

            <!-- Navegación Principal -->
            <div class="px-3 py-2" style="overflow-y: auto; height: calc(100vh - 120px);">
                <!-- Dashboard -->
                <div class="nav-section mb-3">
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2 px-2" style="font-size: 0.7rem; letter-spacing: 1px;">Principal</h6>
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                </div>

                <!-- Almacén -->
                <div class="nav-section mb-3">
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2 px-2" style="font-size: 0.7rem; letter-spacing: 1px;">Almacén</h6>
                    <a class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}" href="{{ route('productos.index') }}">
                        <i class="bi bi-boxes"></i>
                        Productos
                        @php
                            try {
                                $stockBajo = \App\Models\Producto::where('stock_actual', '<=', 10)->count();
                            } catch(\Exception $e) {
                                $stockBajo = 0;
                            }
                        @endphp
                        @if($stockBajo > 0)
                            <span class="badge bg-warning ms-auto">{{ $stockBajo }}</span>
                        @endif
                    </a>
                </div>

                <!-- VENTAS -->
                <div class="nav-section mb-3">
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2 px-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                        <i class="bi bi-cart me-1"></i>VENTAS
                    </h6>
                    
                    <!-- Ventas Dropdown -->
                    <div class="dropdown-modern">
                        <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-cart-check"></i>
                            Ventas
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('ventas.index') }}">
                                <i class="bi bi-clock-history me-2"></i>Historial
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="nuevaVenta()">
                                <i class="bi bi-plus-circle me-2"></i>Venta Manual
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="nuevaVenta()">
                                <i class="bi bi-lightning me-2"></i>Venta Directa
                            </a></li>
                        </ul>
                    </div>
                    
                    <!-- Cajas Dropdown -->
                    <div class="dropdown-modern">
                        <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-credit-card"></i>
                            Cajas
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="nuevoCaja()">
                                <i class="bi bi-arrow-down me-2"></i>Registrar egreso
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="nuevoCaja()">
                                <i class="bi bi-unlock me-2"></i>Aperturar
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="nuevoCaja()">
                                <i class="bi bi-check-circle me-2"></i>Validaciones
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="nuevoCaja()">
                                <i class="bi bi-lock me-2"></i>Cerrar
                            </a></li>
                        </ul>
                    </div>
                    
                    <!-- Proforma Dropdown -->
                    <div class="dropdown-modern">
                        <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-file-earmark-text"></i>
                            Proforma
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('proformas.index') }}">
                                <i class="bi bi-clock-history me-2"></i>Historial
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="nuevaProforma()">
                                <i class="bi bi-plus-circle me-2"></i>Proforma
                            </a></li>
                        </ul>
                    </div>
                </div>

                <!-- PEDIDOS -->
                <div class="nav-section mb-3">
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2 px-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                        <i class="bi bi-clipboard me-1"></i>PEDIDOS
                    </h6>
                    
                    <!-- Pedidos Dropdown -->
                    <div class="dropdown-modern">
                        <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-clipboard-plus"></i>
                            Pedidos
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('pedidos.index') }}">
                                <i class="bi bi-clock-history me-2"></i>Historial
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="nuevoPedido()">
                                <i class="bi bi-plus-circle me-2"></i>Nuevo Pedido
                            </a></li>
                        </ul>
                    </div>
                    
                    <!-- Reportes Dropdown -->
                    <div class="dropdown-modern">
                        <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            Reportes
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('reportes.stock') }}">
                                <i class="bi bi-boxes me-2"></i>Stock
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('reportes.kardex') }}">
                                <i class="bi bi-file-earmark-spreadsheet me-2"></i>Kardex
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('reportes.stock-valorizado') }}">
                                <i class="bi bi-graph-up-arrow me-2"></i>Stock Valorizado
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('reportes.costo-inventario') }}">
                                <i class="bi bi-calculator me-2"></i>Costo Inventario
                            </a></li>
                        </ul>
                    </div>
                    
                    <!-- Delivery Dropdown -->
                    <div class="dropdown-modern">
                        <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-truck"></i>
                            Delivery
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('delivery.index') }}">
                                <i class="bi bi-clock-history me-2"></i>Historial
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="nuevoDelivery()">
                                <i class="bi bi-plus-circle me-2"></i>Nuevo Delivery
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('guias.index') }}">
                                <i class="bi bi-file-earmark-ruled me-2"></i>Guías de Remisión
                            </a></li>
                        </ul>
                    </div>
                </div>

                <!-- CLIENTES -->
                <div class="nav-section mb-3">
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2 px-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                        <i class="bi bi-people me-1"></i>CLIENTES
                    </h6>
                    
                    <!-- Clientes Dropdown -->
                    <div class="dropdown-modern">
                        <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-people"></i>
                            Clientes
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('clientes.index') }}">
                                <i class="bi bi-clock-history me-2"></i>Historial
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="nuevoCliente()">
                                <i class="bi bi-person-plus me-2"></i>Nuevo Cliente
                            </a></li>
                        </ul>
                    </div>
                    
                    <!-- Historia Clínica Dropdown -->
                    <div class="dropdown-modern">
                        <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-journal-medical"></i>
                            Historia Clínica
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('historia-clinica.index') }}">
                                <i class="bi bi-clock-history me-2"></i>Historial
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="nuevaHistoriaClinica()">
                                <i class="bi bi-plus-circle me-2"></i>Nueva Historia
                            </a></li>
                        </ul>
                    </div>
                </div>

                <!-- LABORATORIO -->
                <div class="nav-section mb-3">
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2 px-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                        <i class="bi bi-flask me-1"></i>LABORATORIO
                    </h6>
                    <a class="nav-link {{ request()->routeIs('laboratorio.*') ? 'active' : '' }}" href="{{ route('laboratorio.index') }}">
                        <i class="bi bi-flask"></i>
                        Laboratorio
                    </a>
                </div>

                @if(auth()->user()->role === 'administrador')
                <!-- ADMINISTRACIÓN -->
                <div class="nav-section mb-3">
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2 px-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                        <i class="bi bi-gear me-1"></i>ADMINISTRACIÓN
                    </h6>
                    
                    <a class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}" href="{{ route('categorias.index') }}">
                        <i class="bi bi-tags"></i>
                        Categorías
                    </a>
                    <a class="nav-link {{ request()->routeIs('marcas.*') ? 'active' : '' }}" href="{{ route('marcas.index') }}">
                        <i class="bi bi-bookmark"></i>
                        Marcas
                    </a>
                    <a class="nav-link {{ request()->routeIs('proveedores.*') ? 'active' : '' }}" href="{{ route('proveedores.index') }}">
                        <i class="bi bi-truck"></i>
                        Proveedores
                    </a>
                    <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                        <i class="bi bi-person-gear"></i>
                        Usuarios
                    </a>
                    
                    <!-- Contabilidad Dropdown -->
                    <div class="dropdown-modern">
                        <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-calculator"></i>
                            Contabilidad
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('contabilidad.index') }}">
                                <i class="bi bi-journal-bookmark me-2"></i>Asientos Contables
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('contabilidad.libro-diario') }}">
                                <i class="bi bi-book me-2"></i>Libro Diario
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('contabilidad.estado-resultados') }}">
                                <i class="bi bi-graph-up me-2"></i>Estado de Resultados
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="nuevoAsientoContable()">
                                <i class="bi bi-plus-circle me-2"></i>Nuevo Asiento
                            </a></li>
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Logout -->
                <div class="nav-section mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                    <a class="nav-link" href="#" onclick="mostrarModalCerrarSesion()">
                        <i class="bi bi-box-arrow-right"></i>
                        Cerrar Sesión
                    </a>
                </div>
            </div>
        </nav>

        <!-- Contenido Principal -->
        <div class="content-modern pharmaSys-fade-in" style="margin-left: 280px;">
            <!-- Header de la página -->
            @hasSection('header')
                <div class="header-modern">
                    @yield('header')
                </div>
            @endif

            <!-- Contenido de la página -->
            @yield('content')
        </div>
    </div>

    <!-- Botón flotante de acción rápida -->
    <div class="floating-action" onclick="accionRapida()">
        <i class="bi bi-plus"></i>
    </div>

    <!-- Container para toasts -->
    <div class="toast-container" id="toast-container"></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
    @include('layouts.modales-sistema')
    <script src="{{ asset('js/modales-sistema.js') }}"></script>
</body>
</html>
