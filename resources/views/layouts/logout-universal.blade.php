{{-- Modal Universal de Cerrar Sesión --}}
{{-- Incluir en cualquier vista con @include('layouts.logout-universal') --}}

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
                <div class="progress mb-3" id="logout-progress" style="display: none;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" style="width: 100%"></div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-danger btn-lg px-4 ms-3" onclick="ejecutarCerrarSesionUniversal()">
                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos para el modal de cerrar sesión */
    #modalCerrarSesion .modal-content {
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        animation: modalSlideIn 0.3s ease-out;
    }
    
    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    #modalCerrarSesion .btn-danger {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        border: none;
        transition: all 0.3s ease;
    }

    #modalCerrarSesion .btn-danger:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(238, 90, 82, 0.4);
    }

    #modalCerrarSesion .btn-outline-secondary {
        border: 2px solid #6c757d;
        transition: all 0.3s ease;
    }

    #modalCerrarSesion .btn-outline-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108, 117, 125, 0.2);
    }
    
    /* Estilos para botones de cerrar sesión en sidebars */
    .logout-link {
        background: rgba(255,107,107,0.2) !important;
        border: 1px solid rgba(255,107,107,0.3) !important;
        transition: all 0.3s ease;
    }

    .logout-link:hover {
        background: rgba(255,107,107,0.4) !important;
        color: white !important;
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(255,107,107,0.3);
    }
    
    .logout-link i {
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateX(0); }
        50% { transform: translateX(3px); }
    }
</style>

<script>
// Script Universal para Cerrar Sesión
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔐 Modal de cerrar sesión cargado exitosamente');
    
    // Configurar CSRF token para AJAX si no existe
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }
});

// Función para mostrar el modal de confirmación
function mostrarModalCerrarSesion() {
    console.log('🚪 Mostrando modal de cerrar sesión');
    const modal = new bootstrap.Modal(document.getElementById('modalCerrarSesion'), {
        backdrop: 'static',
        keyboard: false
    });
    modal.show();
}

// Función para ejecutar el cierre de sesión de forma universal
function ejecutarCerrarSesionUniversal() {
    console.log('⏳ Ejecutando cierre de sesión...');
    
    // Mostrar loading en el botón
    const btnCerrar = document.querySelector('#modalCerrarSesion .btn-danger');
    const btnCancelar = document.querySelector('#modalCerrarSesion .btn-outline-secondary');
    const progress = document.getElementById('logout-progress');
    
    const originalText = btnCerrar.innerHTML;
    btnCerrar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Cerrando sesión...';
    btnCerrar.disabled = true;
    btnCancelar.disabled = true;
    progress.style.display = 'block';

    // Lista de posibles IDs de formularios de logout
    const logoutFormIds = [
        'logout-form',
        'logout-form-dashboard', 
        'logout-form-usuarios',
        'logout-form-ventas',
        'logout-form-clientes',
        'logout-form-categorias',
        'logout-form-proveedores',
        'logout-form-marcas',
        'logout-form-productos'
    ];
    
    // Buscar formulario existente
    let formFound = false;
    for (const formId of logoutFormIds) {
        const form = document.getElementById(formId);
        if (form) {
            console.log(`✅ Formulario encontrado: ${formId}`);
            setTimeout(() => {
                form.submit();
            }, 1500);
            formFound = true;
            break;
        }
    }
    
    // Si no encuentra ningún formulario, crear uno dinámicamente
    if (!formFound) {
        console.log('🔧 Creando formulario de logout dinámicamente');
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("logout") }}';
        form.style.display = 'none';
        
        // Agregar token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }
        
        document.body.appendChild(form);
        
        setTimeout(() => {
            console.log('📤 Enviando formulario de logout');
            form.submit();
        }, 1500);
    }
}

// Función legacy para compatibilidad con vistas existentes
function confirmarCerrarSesion() {
    mostrarModalCerrarSesion();
}

// Función para agregar botón de cerrar sesión dinámicamente a cualquier sidebar
function agregarBotonCerrarSesion(sidebarSelector = 'nav ul') {
    const sidebar = document.querySelector(sidebarSelector);
    if (sidebar && !document.querySelector('.logout-link')) {
        const logoutItem = document.createElement('li');
        logoutItem.className = 'nav-item mt-4';
        logoutItem.style.cssText = 'border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;';
        
        logoutItem.innerHTML = `
            <form action="{{ route('logout') }}" method="POST" id="logout-form-dynamic" style="display: none;">
                @csrf
            </form>
            <a class="nav-link logout-link" href="#" onclick="mostrarModalCerrarSesion()">
                <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
            </a>
        `;
        
        sidebar.appendChild(logoutItem);
        console.log('➕ Botón de cerrar sesión agregado dinámicamente');
    }
}

// Auto-agregar botón si no existe (opcional)
// Descomentar la siguiente línea para agregar automáticamente el botón
// setTimeout(() => agregarBotonCerrarSesion(), 1000);
</script> 