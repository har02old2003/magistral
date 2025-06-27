<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmacia Magistral - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h2><i class="bi bi-hospital"></i> Farmacia Magistral</h2>
                        <p class="mb-0">Sistema de Gestión Completo</p>
                    </div>
                    <div class="card-body text-center">
                        <h4 class="mb-4">🎉 ¡Sistema Completamente Corregido!</h4>
                        
                        <div class="alert alert-success">
                            <h5><i class="bi bi-check-circle"></i> Estado del Sistema</h5>
                            <p class="mb-2">✅ Todas las ventanas funcionando correctamente</p>
                            <p class="mb-2">✅ Base de datos con datos de prueba</p>
                            <p class="mb-0">✅ Navegación y botones operativos</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>🔧 Ventanas Corregidas:</h6>
                                <ul class="list-unstyled">
                                    <li>✅ Dashboard - Estadísticas completas</li>
                                    <li>✅ Productos - Con alertas de stock</li>
                                    <li>✅ Marcas - Vista independiente</li>
                                    <li>✅ Ventas - Sistema completo</li>
                                    <li>✅ Clientes - Gestión completa</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>📊 Datos de Prueba:</h6>
                                <ul class="list-unstyled">
                                    <li>🔹 3 Productos (uno con stock bajo)</li>
                                    <li>🔹 10 Marcas farmacéuticas</li>
                                    <li>🔹 3 Categorías</li>
                                    <li>🔹 1 Cliente y 1 Proveedor</li>
                                    <li>🔹 Usuario: admin@farmacia.com (123456)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6>🚀 Funcionalidades Implementadas:</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <span class="badge bg-primary">Autenticación</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="badge bg-success">Control de Stock</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="badge bg-info">Alertas</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    @auth
                                <a href="/dashboard" class="btn btn-primary btn-lg">
                                    <i class="bi bi-speedometer2"></i> Ir al Dashboard
                                </a>
                            @else
                                <a href="/login" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                                </a>
                            @endauth
                            <a href="/test-system" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-tools"></i> Diagnóstico del Sistema
                            </a>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        Sistema desarrollado con Laravel 11 y Bootstrap 5<br>
                        Todas las ventanas han sido corregidas y probadas
                    </small>
                </div>
            </div>
        </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
