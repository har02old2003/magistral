/**
 * Script Universal para Cerrar Sesión - Farmacia Magistral
 * Agrega automáticamente el modal moderno a todas las vistas
 * 
 * INSTRUCCIÓN: Incluir este script en las vistas que no tienen el modal moderno
 * <script src="{{ asset('fix_logout_all.js') }}"></script>
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔐 Inicializando sistema universal de cerrar sesión...');
    
    // Verificar si ya existe el modal moderno
    if (!document.getElementById('modalCerrarSesion')) {
        console.log('📝 Creando modal moderno de cerrar sesión...');
        crearModalCerrarSesionModerno();
    }
    
    // Verificar si ya existe el botón en el sidebar
    if (!document.querySelector('.logout-link')) {
        console.log('🔴 Agregando botón de cerrar sesión al sidebar...');
        agregarBotonCerrarSesion();
    }
    
    // Reemplazar todas las funciones antiguas
    window.confirmarCerrarSesion = mostrarModalCerrarSesion;
    
    console.log('✅ Sistema de cerrar sesión unificado correctamente!');
});

// Función para crear el modal moderno dinámicamente
function crearModalCerrarSesionModerno() {
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
                        <small><strong>Usuario:</strong> <span id="current-user">Admin</span></small>
                    </p>
                    <div class="progress mb-3" id="logout-progress" style="display: none; height: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             style="width: 100%; background: linear-gradient(90deg, #ff6b6b, #ee5a52, #ff6b6b); background-size: 200% 100%; animation: progressGlow 1.5s ease-in-out infinite;">
                        </div>
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
    </div>`;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Agregar estilos del modal
    if (!document.getElementById('logout-modal-styles')) {
        const estilos = `
        <style id="logout-modal-styles">
            #modalCerrarSesion .modal-content {
                box-shadow: 0 20px 40px rgba(0,0,0,0.15);
                animation: modalSlideIn 0.4s ease-out;
            }
            
            @keyframes modalSlideIn {
                from {
                    transform: translateY(-30px) scale(0.95);
                    opacity: 0;
                }
                to {
                    transform: translateY(0) scale(1);
                    opacity: 1;
                }
            }

            #modalCerrarSesion .btn-danger {
                background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
                border: none;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            #modalCerrarSesion .btn-danger:hover:not(:disabled) {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(238, 90, 82, 0.4);
            }

            #modalCerrarSesion .btn-danger:active {
                transform: scale(0.98);
            }

            #modalCerrarSesion .btn-outline-secondary {
                border: 2px solid #6c757d;
                transition: all 0.3s ease;
            }

            #modalCerrarSesion .btn-outline-secondary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(108, 117, 125, 0.2);
            }
            
            .logout-link {
                background: rgba(255,107,107,0.2) !important;
                border: 1px solid rgba(255,107,107,0.3) !important;
                transition: all 0.3s ease !important;
                position: relative;
                overflow: hidden;
            }

            .logout-link:hover {
                background: rgba(255,107,107,0.4) !important;
                color: white !important;
                transform: translateX(5px);
                box-shadow: 0 4px 15px rgba(255,107,107,0.3);
            }
            
            .logout-link::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: left 0.5s;
            }
            
            .logout-link:hover::before {
                left: 100%;
            }
            
            .logout-link i {
                animation: bounce 2s infinite;
            }
            
            @keyframes bounce {
                0%, 100% { transform: translateX(0); }
                50% { transform: translateX(3px); }
            }
            
            @keyframes progressGlow {
                0% { background-position: 200% 0; }
                100% { background-position: -200% 0; }
            }
        </style>`;
        
        document.head.insertAdjacentHTML('beforeend', estilos);
    }
}

// Función para agregar botón a sidebar si no existe
function agregarBotonCerrarSesion() {
    const sidebars = [
        'nav ul', 
        '.modern-sidebar ul', 
        '.sidebar ul',
        'nav.modern-sidebar ul',
        'nav.col-md-3 ul',
        'nav.col-md-2 ul'
    ];
    
    let sidebarEncontrado = false;
    
    for (const selector of sidebars) {
        const sidebar = document.querySelector(selector);
        if (sidebar && !sidebar.querySelector('.logout-link')) {
            const logoutItem = document.createElement('li');
            logoutItem.className = 'nav-item mt-4';
            logoutItem.style.cssText = 'border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;';
            
            logoutItem.innerHTML = `
                <form action="/logout" method="POST" id="logout-form-universal" style="display: none;">
                    <input type="hidden" name="_token" value="${getCSRFToken()}">
                </form>
                <a class="nav-link logout-link" href="#" onclick="mostrarModalCerrarSesion()">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                </a>
            `;
            
            sidebar.appendChild(logoutItem);
            console.log('✅ Botón agregado al sidebar:', selector);
            sidebarEncontrado = true;
            break;
        }
    }
    
    if (!sidebarEncontrado) {
        console.log('⚠️ No se encontró sidebar compatible');
    }
}

// Función para obtener CSRF token
function getCSRFToken() {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (csrfMeta) {
        return csrfMeta.getAttribute('content');
    }
    
    // Si no existe, crear uno
    const meta = document.createElement('meta');
    meta.name = 'csrf-token';
    meta.content = 'default-token'; // Se reemplazará por Laravel
    document.head.appendChild(meta);
    
    return 'default-token';
}

// Función para mostrar el modal moderno
function mostrarModalCerrarSesion() {
    console.log('🚪 Mostrando modal moderno de cerrar sesión');
    
    // Actualizar nombre del usuario si está disponible
    const currentUserSpan = document.getElementById('current-user');
    if (currentUserSpan) {
        // Intentar obtener el nombre del usuario desde elementos comunes
        const userElements = [
            document.querySelector('.user-info .fw-semibold'),
            document.querySelector('.sidebar-brand small'),
            document.querySelector('[data-user-name]')
        ];
        
        for (const element of userElements) {
            if (element && element.textContent.trim()) {
                currentUserSpan.textContent = element.textContent.trim();
                break;
            }
        }
    }
    
    const modal = new bootstrap.Modal(document.getElementById('modalCerrarSesion'), {
        backdrop: 'static',
        keyboard: false
    });
    modal.show();
}

// Función para ejecutar el cierre de sesión universal
function ejecutarCerrarSesionUniversal() {
    console.log('⏳ Ejecutando cierre de sesión universal...');
    
    const btnCerrar = document.querySelector('#modalCerrarSesion .btn-danger');
    const btnCancelar = document.querySelector('#modalCerrarSesion .btn-outline-secondary');
    const progress = document.getElementById('logout-progress');
    
    // Mostrar estado de loading
    const originalText = btnCerrar.innerHTML;
    btnCerrar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Cerrando sesión...';
    btnCerrar.disabled = true;
    btnCancelar.disabled = true;
    progress.style.display = 'block';

    // Lista de formularios de logout posibles
    const logoutFormIds = [
        'logout-form',
        'logout-form-universal',
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
    
    // Si no encuentra formulario, crear uno dinámico
    if (!formFound) {
        console.log('🔧 Creando formulario dinámico de logout');
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/logout';
        form.style.display = 'none';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = getCSRFToken();
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        
        setTimeout(() => {
            console.log('📤 Enviando formulario dinámico');
            form.submit();
        }, 1500);
    }
}

// Función legacy para compatibilidad total
window.confirmarCerrarSesion = mostrarModalCerrarSesion;
window.mostrarModalCerrarSesion = mostrarModalCerrarSesion;
window.ejecutarCerrarSesion = ejecutarCerrarSesionUniversal;

console.log('🎯 Script universal de cerrar sesión cargado exitosamente!'); 