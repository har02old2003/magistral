<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Demo - Modal Cerrar Sesión</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .demo-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
        }
        
        .btn-demo {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .btn-demo:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(238, 90, 82, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="demo-card">
        <h1 class="text-primary mb-4">
            <i class="bi bi-gear-fill"></i> Demo Modal Cerrar Sesión
        </h1>
        
        <p class="text-muted mb-4">
            Este es el nuevo <strong>modal moderno</strong> para cerrar sesión.<br>
            ¡Ya no más mensajes básicos!
        </p>
        
        <div class="row text-center mb-4">
            <div class="col-md-6 mb-3">
                <div class="p-3 border rounded">
                    <h6 class="text-danger">❌ ANTES</h6>
                    <small class="text-muted">confirm() básico</small>
                    <br>
                    <button class="btn btn-outline-secondary btn-sm mt-2" onclick="demoAntes()">
                        Ver Demo Viejo
                    </button>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="p-3 border rounded">
                    <h6 class="text-success">✅ AHORA</h6>
                    <small class="text-muted">Modal moderno</small>
                    <br>
                    <button class="btn btn-outline-success btn-sm mt-2" onclick="mostrarModalCerrarSesion()">
                        Ver Demo Nuevo
                    </button>
                </div>
            </div>
        </div>
        
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>
            <strong>¡Implementado en todas las vistas!</strong><br>
            Dashboard, Productos, Marcas, Usuarios, etc.
        </div>
        
        <button class="btn btn-demo" onclick="mostrarModalCerrarSesion()">
            <i class="bi bi-box-arrow-right me-2"></i>
            Probar Modal Moderno
        </button>
        
        <div class="mt-4">
            <small class="text-muted">
                <strong>Usuario actual:</strong> {{ auth()->user()->name ?? 'Demo User' }}<br>
                <strong>Rol:</strong> {{ auth()->user()->role ?? 'Demo' }}
            </small>
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
                        <small><strong>Usuario:</strong> {{ auth()->user()->name ?? 'Demo User' }}</small>
                    </p>
                    <div class="progress mb-3" id="logout-progress" style="display: none; height: 8px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" style="width: 100%"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-danger btn-lg px-4 ms-3" onclick="ejecutarCerrarSesionDemo()">
                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Función para mostrar el modal MODERNO
        function mostrarModalCerrarSesion() {
            const modal = new bootstrap.Modal(document.getElementById('modalCerrarSesion'), {
                backdrop: 'static',
                keyboard: false
            });
            modal.show();
        }

        // Función para ejecutar el cierre de sesión (demo)
        function ejecutarCerrarSesionDemo() {
            const btnCerrar = document.querySelector('#modalCerrarSesion .btn-danger');
            const btnCancelar = document.querySelector('#modalCerrarSesion .btn-outline-secondary');
            const progress = document.getElementById('logout-progress');
            
            const originalText = btnCerrar.innerHTML;
            btnCerrar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Cerrando sesión...';
            btnCerrar.disabled = true;
            btnCancelar.disabled = true;
            progress.style.display = 'block';

            // Simular cierre de sesión
            setTimeout(() => {
                alert('🎉 ¡Demo completado!\n\nEn el sistema real, aquí se cerraría la sesión y redirigiría al login.');
                
                // Restaurar botón
                btnCerrar.innerHTML = originalText;
                btnCerrar.disabled = false;
                btnCancelar.disabled = false;
                progress.style.display = 'none';
                
                // Cerrar modal
                bootstrap.Modal.getInstance(document.getElementById('modalCerrarSesion')).hide();
            }, 2000);
        }

        // Función para mostrar el método VIEJO
        function demoAntes() {
            if (confirm('¿Está seguro de que desea cerrar sesión?')) {
                alert('Este era el método anterior... ¡Muy básico! 😕');
            }
        }

        // Estilos adicionales para el modal
        const estilos = `
        <style>
            .modal-content {
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

            .btn-danger {
                background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
                border: none;
                transition: all 0.3s ease;
            }

            .btn-danger:hover:not(:disabled) {
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
            
            .progress-bar {
                background: linear-gradient(90deg, #ff6b6b, #ee5a52, #ff6b6b);
                background-size: 200% 100%;
                animation: progressGlow 1s ease-in-out infinite;
            }
            
            @keyframes progressGlow {
                0% { background-position: 200% 0; }
                100% { background-position: -200% 0; }
            }
        </style>`;
        
        document.head.insertAdjacentHTML('beforeend', estilos);
    </script>
</body>
</html> 