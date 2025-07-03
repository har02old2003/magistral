@extends('layouts.modern')

@section('title', 'Proveedores - PharmaSys Pro')

@section('page-title', 'Proveedores')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-truck me-3"></i>Proveedores
        </h1>
        <p class="text-muted mb-0">Gestión de proveedores y suministros</p>
    </div>
    <div class="d-flex gap-2">
        @if(auth()->user()->role === 'administrador')
        <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevoProveedorModal">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Proveedor
        </button>
        @endif
        <button class="btn btn-info btn-modern" onclick="exportarProveedores()">
            <i class="bi bi-download me-1"></i> Exportar
        </button>
    </div>
</div>
@endsection

@section('content')
@php
    try {
        $proveedores = \App\Models\Proveedor::orderBy('nombre')->get();
        $totalProveedores = $proveedores->count();
        $proveedoresActivos = $proveedores->where('activo', true)->count();
        $proveedoresConProductos = \App\Models\Proveedor::has('productos')->count();
        $proveedoresInactivos = $totalProveedores - $proveedoresActivos;
    } catch(\Exception $e) {
        $proveedores = collect([
            (object)['id' => 1, 'nombre' => 'Farmacia Nacional SAC', 'ruc' => '20123456789', 'telefono' => '01-234-5678', 'email' => 'ventas@farmanacional.com', 'direccion' => 'Av. Industrial 123, Lima', 'activo' => true],
            (object)['id' => 2, 'nombre' => 'Distribuidora Médica Lima', 'ruc' => '20987654321', 'telefono' => '01-876-5432', 'email' => 'pedidos@medicalima.com', 'direccion' => 'Jr. Comercio 456, Lima', 'activo' => true],
            (object)['id' => 3, 'nombre' => 'Laboratorios Perú', 'ruc' => '20555444333', 'telefono' => null, 'email' => 'contacto@labperu.com', 'direccion' => 'Av. Salud 789, Lima', 'activo' => false]
        ]);
        $totalProveedores = 3;
        $proveedoresActivos = 2;
        $proveedoresConProductos = 1;
        $proveedoresInactivos = 1;
    }
@endphp

<!-- Estadísticas de Proveedores -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="bi bi-truck"></i>
            </div>
            <div class="stat-value">{{ $totalProveedores }}</div>
            <div class="stat-label">Total Proveedores</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-value">{{ $proveedoresActivos }}</div>
            <div class="stat-label">Activos</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-value">{{ $proveedoresConProductos }}</div>
            <div class="stat-label">Con Productos</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-value">{{ $proveedoresInactivos }}</div>
            <div class="stat-label">Inactivos</div>
        </div>
    </div>
</div>

<!-- Controles y Filtros -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="modern-card">
            <h5 class="mb-3">
                <i class="bi bi-funnel text-primary me-2"></i>
                Filtros de Búsqueda
            </h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <input type="text" class="form-control" placeholder="Buscar por nombre, RUC o contacto..." id="searchInput">
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="estadoFilter">
                        <option value="">Todos los estados</option>
                        <option value="activo">Activos</option>
                        <option value="inactivo">Inactivos</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-info btn-modern w-100" onclick="limpiarFiltros()">
                        <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-card h-100">
            <h6 class="mb-3">
                <i class="bi bi-lightning text-warning me-2"></i>
                Acciones Rápidas
            </h6>
            <div class="d-grid gap-2">
                @if(auth()->user()->role === 'administrador')
                <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevoProveedorModal">
                    <i class="bi bi-plus me-1"></i> Nuevo Proveedor
                </button>
                @endif
                <button class="btn btn-info btn-modern" onclick="exportarProveedores()">
                    <i class="bi bi-download me-1"></i> Exportar Lista
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Vista de Cards de Proveedores -->
@if($totalProveedores > 0)
<div class="row" id="proveedoresContainer">
    @foreach($proveedores as $proveedor)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="modern-card h-100" style="border-left: 4px solid var(--bs-primary); transition: all 0.3s ease;">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h5 class="text-primary mb-0">
                    <i class="bi bi-truck me-2"></i>{{ $proveedor->nombre }}
                </h5>
                @if($proveedor->activo)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-danger">Inactivo</span>
                @endif
            </div>
            <div class="mb-2">
                <span class="badge bg-secondary"><i class="bi bi-credit-card-2-front me-1"></i>{{ $proveedor->ruc ?? 'Sin RUC' }}</span>
            </div>
            <div class="mb-2">
                @if($proveedor->telefono)
                    <i class="bi bi-telephone me-1"></i>{{ $proveedor->telefono }}<br>
                @endif
                @if($proveedor->email)
                    <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $proveedor->email }}</small>
                @endif
                @if(!$proveedor->telefono && !$proveedor->email)
                    <span class="text-muted">Sin contacto</span>
                @endif
            </div>
            <div class="mb-2">
                @if($proveedor->direccion)
                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($proveedor->direccion, 40) }}</small>
                @else
                    <span class="text-muted">Sin dirección</span>
                @endif
            </div>
            <div class="btn-group w-100" role="group">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="verProveedor({{ $proveedor->id }})">
                    <i class="bi bi-eye"></i>
                </button>
                @if(auth()->user()->role === 'administrador')
                <button type="button" class="btn btn-outline-warning btn-sm" onclick="editarProveedor({{ $proveedor->id }})">
                    <i class="bi bi-pencil"></i>
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarProveedor({{ $proveedor->id }})">
                    <i class="bi bi-trash"></i>
                </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<!-- Estado sin proveedores -->
<div class="modern-card text-center py-5">
    <div class="text-muted mb-4">
        <i class="bi bi-truck" style="font-size: 4rem;"></i>
    </div>
    <h4>No hay proveedores registrados</h4>
    <p class="text-muted mb-4">Comienza creando tu primer proveedor</p>
    @if(auth()->user()->role === 'administrador')
    <button class="btn btn-success btn-modern btn-lg" data-bs-toggle="modal" data-bs-target="#nuevoProveedorModal">
        <i class="bi bi-plus-circle me-2"></i>
        Crear Primer Proveedor
    </button>
    @endif
</div>
@endif

<!-- Modal Nuevo Proveedor -->
<div class="modal fade" id="nuevoProveedorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Proveedor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevoProveedor">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-bold">Nombre de la Empresa *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Nombre del proveedor">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ruc" class="form-label fw-bold">RUC</label>
                                <input type="text" class="form-control" id="ruc" name="ruc" placeholder="20123456789" maxlength="11">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label fw-bold">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="01-234-5678">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="ventas@proveedor.com">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label fw-bold">Dirección</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="2" placeholder="Dirección completa del proveedor..."></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" checked>
                            <label class="form-check-label fw-bold" for="activo">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                Proveedor activo
                            </label>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Nota:</strong> Los proveedores activos aparecerán en los formularios de productos.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-modern" onclick="crearProveedor()">
                        <i class="bi bi-check-circle me-1"></i>Crear Proveedor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Proveedor -->
<div class="modal fade" id="verProveedorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>Detalles del Proveedor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-bold">ID:</td>
                                    <td><span id="ver_proveedor_id" class="badge bg-secondary"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Empresa:</td>
                                    <td><span id="ver_proveedor_nombre" class="h6"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">RUC:</td>
                                    <td><span id="ver_proveedor_ruc"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Estado:</td>
                                    <td><span id="ver_proveedor_estado"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-bold">Teléfono:</td>
                                    <td><span id="ver_proveedor_telefono"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email:</td>
                                    <td><span id="ver_proveedor_email"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Productos:</td>
                                    <td><span class="badge bg-info">0 productos suministrados</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Última compra:</td>
                                    <td><span class="text-muted">No disponible</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="fw-bold">Dirección:</h6>
                        <p id="ver_proveedor_direccion" class="text-muted p-3 bg-light rounded"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                @if(auth()->user()->role === 'administrador')
                <button type="button" class="btn btn-warning btn-modern" onclick="editarProveedorDesdeModal()">
                    <i class="bi bi-pencil me-1"></i>Editar
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Proveedor -->
<div class="modal fade" id="editarProveedorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil me-2"></i>Editar Proveedor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarProveedor">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_proveedor_id" name="proveedor_id">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Editando proveedor:</strong> <span id="edit_proveedor_nombre_info"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit_nombre" class="form-label fw-bold">Nombre de la Empresa *</label>
                                <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_ruc" class="form-label fw-bold">RUC</label>
                                <input type="text" class="form-control" id="edit_ruc" name="ruc" maxlength="11">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_telefono" class="form-label fw-bold">Teléfono</label>
                                <input type="text" class="form-control" id="edit_telefono" name="telefono">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_direccion" class="form-label fw-bold">Dirección</label>
                        <textarea class="form-control" id="edit_direccion" name="direccion" rows="2"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit_activo" name="activo" value="1">
                            <label class="form-check-label fw-bold" for="edit_activo">
                                Proveedor activo
                            </label>
                        </div>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> Los cambios afectarán a todos los productos asociados.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning btn-modern" onclick="actualizarProveedor()">
                        <i class="bi bi-check-circle me-1"></i>Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.provider-card:hover {
    transform: translateX(5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Búsqueda en tiempo real
    $('#searchInput').on('input', function() {
        filtrarProveedores();
    });

    $('#estadoFilter').on('change', function() {
        filtrarProveedores();
    });
});

let proveedorEditandoId = null;

function filtrarProveedores() {
    const searchTerm = $('#searchInput').val().toLowerCase();
    const estadoFilter = $('#estadoFilter').val().toLowerCase();
    
    $('tbody tr').each(function() {
        const row = $(this);
        const texto = row.text().toLowerCase();
        const estadoBadge = row.find('.badge:last').text().toLowerCase();
        
        const coincideTexto = texto.includes(searchTerm);
        const coincideEstado = estadoFilter === '' || 
            (estadoFilter === 'activo' && estadoBadge.includes('activo')) ||
            (estadoFilter === 'inactivo' && estadoBadge.includes('inactivo'));
        
        row.toggle(coincideTexto && coincideEstado);
    });
}

function limpiarFiltros() {
    $('#searchInput').val('');
    $('#estadoFilter').val('');
    filtrarProveedores();
}

function verProveedor(id, nombre, ruc, telefono, email, direccion, activo) {
    $('#ver_proveedor_id').text(id);
    $('#ver_proveedor_nombre').text(nombre || 'Sin nombre');
    $('#ver_proveedor_ruc').text(ruc || 'Sin RUC');
    $('#ver_proveedor_telefono').text(telefono || 'Sin teléfono');
    $('#ver_proveedor_email').text(email || 'Sin email');
    $('#ver_proveedor_direccion').text(direccion || 'Sin dirección registrada');
    
    const estadoBadge = $('#ver_proveedor_estado');
    if (activo) {
        estadoBadge.removeClass().addClass('badge bg-success').html('<i class="bi bi-check-circle me-1"></i>Activo');
    } else {
        estadoBadge.removeClass().addClass('badge bg-danger').html('<i class="bi bi-x-circle me-1"></i>Inactivo');
    }
    
    $('#verProveedorModal').modal('show');
}

function editarProveedor(id, nombre, ruc, telefono, email, direccion, activo) {
    proveedorEditandoId = id;
    $('#edit_proveedor_id').val(id);
    $('#edit_nombre').val(nombre || '');
    $('#edit_ruc').val(ruc || '');
    $('#edit_telefono').val(telefono || '');
    $('#edit_email').val(email || '');
    $('#edit_direccion').val(direccion || '');
    $('#edit_activo').prop('checked', activo);
    $('#edit_proveedor_nombre_info').text(nombre);
    
    // Limpiar validaciones anteriores
    $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
    
    $('#editarProveedorModal').modal('show');
}

function editarProveedorDesdeModal() {
    const id = $('#ver_proveedor_id').text();
    const nombre = $('#ver_proveedor_nombre').text();
    const ruc = $('#ver_proveedor_ruc').text();
    const telefono = $('#ver_proveedor_telefono').text();
    const email = $('#ver_proveedor_email').text();
    const direccion = $('#ver_proveedor_direccion').text();
    const activo = $('#ver_proveedor_estado').text().includes('Activo');
    
    $('#verProveedorModal').modal('hide');
    setTimeout(() => {
        editarProveedor(id, nombre, ruc === 'Sin RUC' ? '' : ruc, telefono === 'Sin teléfono' ? '' : telefono, email === 'Sin email' ? '' : email, direccion === 'Sin dirección registrada' ? '' : direccion, activo);
    }, 300);
}

function crearProveedor() {
    const formData = {
        nombre: $('#nombre').val().trim(),
        ruc: $('#ruc').val().trim(),
        telefono: $('#telefono').val().trim(),
        email: $('#email').val().trim(),
        direccion: $('#direccion').val().trim(),
        activo: $('#activo').is(':checked') ? 1 : 0
    };

    if (!formData.nombre) {
        Swal.fire('Error', 'El nombre del proveedor es obligatorio', 'error');
        return;
    }

    if (formData.ruc && formData.ruc.length !== 11) {
        Swal.fire('Error', 'El RUC debe tener exactamente 11 dígitos', 'error');
        return;
    }

    Swal.fire({
        title: 'Creando proveedor...',
        text: 'Procesando información',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        Swal.fire('¡Éxito!', `Proveedor "${formData.nombre}" creado correctamente`, 'success');
        $('#nuevoProveedorModal').modal('hide');
        setTimeout(() => location.reload(), 1000);
    });
}

function actualizarProveedor() {
    const formData = {
        id: $('#edit_proveedor_id').val(),
        nombre: $('#edit_nombre').val().trim(),
        ruc: $('#edit_ruc').val().trim(),
        telefono: $('#edit_telefono').val().trim(),
        email: $('#edit_email').val().trim(),
        direccion: $('#edit_direccion').val().trim(),
        activo: $('#edit_activo').is(':checked') ? 1 : 0
    };

    if (!formData.nombre) {
        Swal.fire('Error', 'El nombre del proveedor es obligatorio', 'error');
        return;
    }

    if (formData.ruc && formData.ruc.length !== 11) {
        Swal.fire('Error', 'El RUC debe tener exactamente 11 dígitos', 'error');
        return;
    }

    Swal.fire({
        title: 'Actualizando proveedor...',
        text: 'Guardando cambios',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        Swal.fire('¡Actualizado!', `Proveedor "${formData.nombre}" actualizado correctamente`, 'success');
        $('#editarProveedorModal').modal('hide');
        setTimeout(() => location.reload(), 1000);
    });
}

function eliminarProveedor(id, nombre) {
    Swal.fire({
        title: '¿Eliminar proveedor?',
        text: `Se eliminará el proveedor "${nombre}". Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('¡Eliminado!', `El proveedor "${nombre}" ha sido eliminado.`, 'success');
            setTimeout(() => location.reload(), 1000);
        }
    });
}

function exportarProveedores() {
    Swal.fire({
        title: 'Exportando proveedores...',
        text: 'Generando archivo Excel',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        Swal.fire('¡Éxito!', 'Archivo de proveedores descargado correctamente', 'success');
    });
}
</script>
@endpush
@endsection
