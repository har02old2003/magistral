<!-- Sidebar Moderno -->
<nav class="col-md-2 d-md-block modern-sidebar animate__animated animate__slideInLeft" style="min-height: 100vh;">
    <div class="sidebar-sticky">
        <div class="text-center text-white p-4 sidebar-header">
            <div class="logo-container mb-3">
                <i class="bi bi-hospital logo-icon"></i>
            </div>
            <h4 class="fw-bold mb-1">Farmacia Magistral</h4>
            <small class="text-light opacity-75">Sistema de Gestión</small>
        </div>
        
        <div class="user-info p-3 mb-3">
            <div class="d-flex align-items-center">
                <div class="avatar me-3">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div>
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <small class="role-badge">{{ ucfirst(auth()->user()->role) }}</small>
                </div>
            </div>
        </div>
        
        <ul class="nav flex-column px-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}" href="{{ route('productos.index') }}">
                    <i class="bi bi-capsule me-3"></i>
                    <span>Productos</span>
                    @if(auth()->user()->esEmpleado())
                    <small class="ms-auto text-warning">Solo ver</small>
                    @endif
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}" href="{{ route('ventas.index') }}">
                    <i class="bi bi-cart3 me-3"></i>
                    <span>Ventas</span>
                    <span class="notification-dot"></span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                    <i class="bi bi-people me-3"></i>
                    <span>Clientes</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('laboratorio.*') ? 'active' : '' }}" href="{{ route('laboratorio.index') }}">
                    <i class="bi bi-flask me-3"></i>
                    <span>Laboratorio</span>
                </a>
            </li>
            
            @if(auth()->user()->esAdministrador())
            <li class="nav-divider"></li>
            <li class="nav-header">
                <small class="text-uppercase">Administración</small>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}" href="{{ route('categorias.index') }}">
                    <i class="bi bi-tags me-3"></i>
                    <span>Categorías</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('marcas.*') ? 'active' : '' }}" href="{{ route('marcas.index') }}">
                    <i class="bi bi-bookmark me-3"></i>
                    <span>Marcas</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('proveedores.*') ? 'active' : '' }}" href="{{ route('proveedores.index') }}">
                    <i class="bi bi-truck me-3"></i>
                    <span>Proveedores</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                    <i class="bi bi-person-gear me-3"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            @endif
            
            <!-- Separador para cerrar sesión -->
            <li class="nav-divider mt-4"></li>
            
            <!-- Botón de Cerrar Sesión -->
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                    @csrf
                </form>
                <a class="nav-link logout-link" href="#" onclick="mostrarModalCerrarSesion()">
                    <i class="bi bi-box-arrow-right me-3"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

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

<style>
    /* Sidebar Moderno */
    .modern-sidebar {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        box-shadow: 4px 0 20px rgba(0,0,0,0.1);
        border-radius: 0 20px 20px 0;
        border: none;
    }

    .sidebar-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .logo-icon {
        font-size: 3rem;
        color: #fff;
        background: rgba(255,255,255,0.2);
        padding: 20px;
        border-radius: 50%;
        display: inline-block;
    }

    .user-info {
        background: rgba(255,255,255,0.1);
        border-radius: 15px;
        color: white;
    }

    .avatar i {
        font-size: 2.5rem;
        color: #fff;
    }

    .role-badge {
        background: rgba(255,255,255,0.2);
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.7rem;
    }

    .nav-link {
        color: rgba(255,255,255,0.8) !important;
        padding: 12px 15px;
        margin: 5px 0;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        position: relative;
    }

    .nav-link:hover {
        background: rgba(255,255,255,0.15);
        color: white !important;
        transform: translateX(5px);
    }

    .nav-link.active {
        background: rgba(255,255,255,0.2);
        color: white !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    /* Estilo especial para el botón de cerrar sesión */
    .logout-link {
        background: rgba(255,107,107,0.2) !important;
        border: 1px solid rgba(255,107,107,0.3);
    }

    .logout-link:hover {
        background: rgba(255,107,107,0.4) !important;
        color: white !important;
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(255,107,107,0.3);
    }

    .nav-divider {
        height: 1px;
        background: rgba(255,255,255,0.1);
        margin: 20px 0;
    }

    .nav-header {
        padding: 10px 15px;
        color: rgba(255,255,255,0.6);
    }

    .notification-dot {
        width: 8px;
        height: 8px;
        background: #ff4757;
        border-radius: 50%;
        position: absolute;
        top: 8px;
        right: 8px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Estilos para el modal de cerrar sesión */
    .modal-content {
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(238, 90, 82, 0.4);
    }

    .btn-outline-secondary {
        border: 2px solid #6c757d;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108, 117, 125, 0.2);
    }
</style>

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
            document.getElementById('logout-form').submit();
        }, 1000);
    }

    // Función legacy para compatibilidad (algunas vistas aún la usan)
    function confirmarCerrarSesion() {
        mostrarModalCerrarSesion();
    }
</script> 