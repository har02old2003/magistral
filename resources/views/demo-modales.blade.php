@extends('layouts.modern')

@section('title', 'Demo Modales CRUD - Farmacia Magistral')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="bi bi-window-stack me-2"></i>
                        Demo Sistema de Modales CRUD
                    </h3>
                    <p class="text-muted mb-0">Prueba todas las ventanas emergentes funcionales del sistema</p>
                </div>
                
                <div class="card-body">
                    <!-- Sección Ventas -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-success mb-3">
                                <i class="bi bi-cart-check me-2"></i>Módulo Ventas
                            </h4>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-success w-100 btn-lg" onclick="abrirModalAgregar('Ventas')">
                                <i class="bi bi-plus-circle me-2"></i>
                                Nueva Venta
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-info w-100 btn-lg" onclick="editarVenta(1)">
                                <i class="bi bi-pencil me-2"></i>
                                Editar Venta
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-primary w-100 btn-lg" onclick="verVenta(1)">
                                <i class="bi bi-eye me-2"></i>
                                Ver Venta
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-danger w-100 btn-lg" onclick="eliminarVenta(1, 'V001')">
                                <i class="bi bi-trash me-2"></i>
                                Eliminar Venta
                            </button>
                        </div>
                    </div>

                    <!-- Sección Cajas -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-purple mb-3">
                                <i class="bi bi-cash-stack me-2"></i>Módulo Cajas
                            </h4>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-purple w-100 btn-lg" onclick="abrirModalAgregar('Cajas')">
                                <i class="bi bi-plus-circle me-2"></i>
                                Nuevo Movimiento
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-warning w-100 btn-lg" onclick="editarCaja(1)">
                                <i class="bi bi-pencil me-2"></i>
                                Editar Movimiento
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-info w-100 btn-lg" onclick="verCaja(1)">
                                <i class="bi bi-eye me-2"></i>
                                Ver Movimiento
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-danger w-100 btn-lg" onclick="eliminarCaja(1, 'MOV001')">
                                <i class="bi bi-trash me-2"></i>
                                Eliminar Movimiento
                            </button>
                        </div>
                    </div>

                    <!-- Sección Proformas -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-warning mb-3">
                                <i class="bi bi-file-earmark-text me-2"></i>Módulo Proformas
                            </h4>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-warning w-100 btn-lg" onclick="abrirModalAgregar('Proformas')">
                                <i class="bi bi-plus-circle me-2"></i>
                                Nueva Proforma
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-info w-100 btn-lg" onclick="editarProforma(1)">
                                <i class="bi bi-pencil me-2"></i>
                                Editar Proforma
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-primary w-100 btn-lg" onclick="verProforma(1)">
                                <i class="bi bi-eye me-2"></i>
                                Ver Proforma
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-danger w-100 btn-lg" onclick="eliminarProforma(1, 'PRO001')">
                                <i class="bi bi-trash me-2"></i>
                                Eliminar Proforma
                            </button>
                        </div>
                    </div>

                    <!-- Sección Terceros -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-teal mb-3">
                                <i class="bi bi-people me-2"></i>Módulo Terceros
                            </h4>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-teal w-100 btn-lg" onclick="abrirModalAgregar('Terceros')">
                                <i class="bi bi-person-plus me-2"></i>
                                Nuevo Cliente/Proveedor
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-info w-100 btn-lg" onclick="editarTercero(1)">
                                <i class="bi bi-pencil me-2"></i>
                                Editar Tercero
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-primary w-100 btn-lg" onclick="verTercero(1)">
                                <i class="bi bi-eye me-2"></i>
                                Ver Tercero
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-danger w-100 btn-lg" onclick="eliminarTercero(1, 'Juan Pérez')">
                                <i class="bi bi-trash me-2"></i>
                                Eliminar Tercero
                            </button>
                        </div>
                    </div>

                    <!-- Sección Utilidades -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-dark mb-3">
                                <i class="bi bi-tools me-2"></i>Utilidades y Extras
                            </h4>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn btn-dark w-100 btn-lg" data-bs-toggle="modal" data-bs-target="#modalBusquedaAvanzada">
                                <i class="bi bi-search me-2"></i>
                                Búsqueda Avanzada
                            </button>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn btn-secondary w-100 btn-lg" onclick="exportarDatos('ventas', 'excel')">
                                <i class="bi bi-download me-2"></i>
                                Exportar a Excel
                            </button>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn btn-outline-primary w-100 btn-lg" onclick="mostrarNotificacion('¡Sistema funcionando!', 'Todos los modales están operativos', 'success')">
                                <i class="bi bi-bell me-2"></i>
                                Test Notificación
                            </button>
                        </div>
                    </div>

                    <!-- Información del Sistema -->
                    <div class="alert alert-info">
                        <h5 class="alert-heading">
                            <i class="bi bi-info-circle me-2"></i>
                            ℹ️ Información del Sistema
                        </h5>
                        <hr>
                        <ul class="mb-0">
                            <li><strong>Modales Implementados:</strong> 4 módulos principales (Ventas, Cajas, Proformas, Terceros)</li>
                            <li><strong>Operaciones CRUD:</strong> Crear, Leer, Actualizar, Eliminar para cada módulo</li>
                            <li><strong>Validación:</strong> Frontend y backend con mensajes de error</li>
                            <li><strong>Notificaciones:</strong> SweetAlert2 para confirmaciones y alertas</li>
                            <li><strong>Búsqueda Avanzada:</strong> Filtros por fecha, texto y estado</li>
                            <li><strong>Exportación:</strong> Preparado para Excel y PDF</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-purple {
    background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
    color: white;
    border: none;
}

.btn-purple:hover {
    background: linear-gradient(135deg, #5a359c 0%, #d1266b 100%);
    color: white;
}

.btn-teal {
    background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
    color: white;
    border: none;
}

.btn-teal:hover {
    background: linear-gradient(135deg, #1ba47e 0%, #138496 100%);
    color: white;
}

.text-purple {
    color: #6f42c1 !important;
}

.text-teal {
    color: #20c997 !important;
}

.modern-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-bottom: none;
}

.btn-lg {
    padding: 1rem 1.5rem;
    font-size: 1.1rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-lg:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
</style>
@endsection 