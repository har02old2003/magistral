// Modal de Cerrar Sesión Universal
// Funciona en todas las vistas del sistema

document.addEventListener('DOMContentLoaded', function() {
    // Crear el modal si no existe
    if (!document.getElementById('modalCerrarSesion')) {
        crearModalCerrarSesion();
    }
    
    // Agregar evento a todos los botones de cerrar sesión
    document.querySelectorAll('[onclick="mostrarModalCerrarSesion()"], [onclick="confirmarCerrarSesion()"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            mostrarModalCerrarSesion();
        });
    });
});

// Función para crear el modal dinámicamente
function crearModalCerrarSesion() {
    const modalHTML = `
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
                        <small><strong>Usuario:</strong> <span id="current-user-name">Usuario</span></small>
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
    </div>`;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Agregar estilos del modal
    const estilos = `
    <style>
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
    </style>`;
    
    document.head.insertAdjacentHTML('beforeend', estilos);
}

// Función para mostrar el modal de confirmación
function mostrarModalCerrarSesion() {
    // Actualizar nombre del usuario si está disponible
    const userNameSpan = document.getElementById('current-user-name');
    if (userNameSpan && window.currentUserName) {
        userNameSpan.textContent = window.currentUserName;
    }
    
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

    // Buscar el formulario de logout en cualquier vista
    const logoutForms = [
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
    
    let formFound = false;
    for (const formId of logoutForms) {
        const form = document.getElementById(formId);
        if (form) {
            setTimeout(() => {
                form.submit();
            }, 1000);
            formFound = true;
            break;
        }
    }
    
    // Si no encuentra ningún formulario, crear uno dinámicamente
    if (!formFound) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/logout';
        
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
            form.submit();
        }, 1000);
    }
}

// Función legacy para compatibilidad
function confirmarCerrarSesion() {
    mostrarModalCerrarSesion();
} 