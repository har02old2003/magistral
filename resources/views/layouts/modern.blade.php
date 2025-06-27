<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Farmacia Magistral')</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            --sidebar-gradient: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
            --card-shadow: 0 10px 30px rgba(0,0,0,0.1);
            --hover-transform: translateY(-5px);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        /* Sidebar Moderno */
        .modern-sidebar {
            background: var(--sidebar-gradient);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1000;
            box-shadow: 5px 0 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-brand h3 {
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .sidebar-brand small {
            color: rgba(255,255,255,0.8);
            font-size: 0.85rem;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-item {
            margin: 0.2rem 1rem;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.85) !important;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
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
        
        .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        
        /* Contenido Principal */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }
        
        /* Header Moderno */
        .page-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }
        
        .page-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            margin-top: 0.5rem;
        }
        
        /* Cards Modernos */
        .modern-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .modern-card:hover {
            transform: var(--hover-transform);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
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
            background: var(--primary-gradient);
        }
        
        .stat-card:hover {
            transform: var(--hover-transform);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .stat-card.primary::before { background: var(--primary-gradient); }
        .stat-card.success::before { background: var(--success-gradient); }
        .stat-card.warning::before { background: var(--warning-gradient); }
        .stat-card.info::before { background: var(--info-gradient); }
        .stat-card.danger::before { background: var(--danger-gradient); }
        
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
        
        .stat-icon {
            font-size: 3rem;
            opacity: 0.8;
            margin-bottom: 1rem;
        }
        
        /* Botones Modernos */
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
        
        .btn-primary-modern {
            background: var(--primary-gradient);
            color: white;
        }
        
        .btn-success-modern {
            background: var(--success-gradient);
            color: white;
        }
        
        .btn-warning-modern {
            background: var(--warning-gradient);
            color: white;
        }
        
        .btn-info-modern {
            background: var(--info-gradient);
            color: white;
        }
        
        .btn-danger-modern {
            background: var(--danger-gradient);
            color: white;
        }
        
        /* Tablas Modernas */
        .modern-table {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .modern-table .table {
            margin: 0;
        }
        
        .modern-table .table thead th {
            background: var(--dark-gradient);
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
        
        /* Badges Modernos */
        .badge-modern {
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
        }
        
        /* Alertas Modernas */
        .alert-modern {
            border-radius: 15px;
            border: none;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        /* Animaciones */
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }
        
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .modern-sidebar {
                width: 0;
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .stat-card {
                margin-bottom: 1rem;
            }
        }
        
        /* Efectos de Loading */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>
    
    @yield('extra-css')
</head>
<body>
    <!-- Sidebar -->
    <nav class="modern-sidebar">
        <div class="sidebar-brand">
            <h3><i class="bi bi-hospital"></i> Farmacia</h3>
            <small>{{ auth()->user()->name ?? 'Usuario' }}</small>
            <small class="d-block">{{ auth()->user()->role ?? 'Empleado' }}</small>
        </div>
        
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="/dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}" href="/productos">
                    <i class="bi bi-capsule"></i> Productos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}" href="/ventas">
                    <i class="bi bi-cart-check"></i> Ventas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}" href="/clientes">
                    <i class="bi bi-people"></i> Clientes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('marcas.*') ? 'active' : '' }}" href="/marcas">
                    <i class="bi bi-tags"></i> Marcas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}" href="/categorias">
                    <i class="bi bi-grid"></i> Categorías
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('proveedores.*') ? 'active' : '' }}" href="/proveedores">
                    <i class="bi bi-truck"></i> Proveedores
                </a>
            </li>
            @if(auth()->user()->role === 'administrador')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="/usuarios">
                    <i class="bi bi-person-gear"></i> Usuarios
                </a>
            </li>
            @endif
            
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link w-100 border-0 bg-transparent text-start">
                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                    </button>
                </form>
            </li>
        </ul>
    </nav>
    
    <!-- Contenido Principal -->
    <main class="main-content">
        @yield('content')
    </main>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        // Inicializar AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
        
        // Agregar efectos hover dinámicos
        document.addEventListener('DOMContentLoaded', function() {
            // Efecto parallax suave en cards
            const cards = document.querySelectorAll('.stat-card, .modern-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.3s ease';
                });
            });
            
            // Efecto de typing en títulos (opcional)
            const titles = document.querySelectorAll('.page-title');
            titles.forEach(title => {
                title.style.opacity = '0';
                setTimeout(() => {
                    title.style.opacity = '1';
                    title.style.transition = 'opacity 0.5s ease';
                }, 100);
            });
        });
    </script>
    
    @yield('extra-js')
</body>
</html> 