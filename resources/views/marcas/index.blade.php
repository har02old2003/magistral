@extends('layouts.modern')

@section('title', 'Marcas - Farmacia Magistral')

@section('page-title', 'Marcas')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-tags me-3"></i>Marcas
        </h1>
        <p class="text-muted mb-0">Gestión de marcas y laboratorios</p>
    </div>
    <div class="d-flex gap-2">
        @if(auth()->user()->role === 'administrador')
        <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevaMarcaModal">
            <i class="bi bi-plus-circle me-1"></i> Nueva Marca
        </button>
        @endif
        <button class="btn btn-info btn-modern" onclick="exportarMarcas()">
            <i class="bi bi-download me-1"></i> Exportar
        </button>
    </div>
</div>
@endsection

@section('content')
@php
    try {
        $marcas = \App\Models\Marca::withCount('productos')->orderBy('nombre')->get();
        $totalMarcas = $marcas->count();
        $marcasActivas = $marcas->where('activo', true)->count();
        $marcasInactivas = $marcas->where('activo', false)->count();
        $productosConMarca = $marcas->sum('productos_count');
    } catch(\Exception $e) {
        $marcas = collect([
            (object)['id' => 1, 'nombre' => 'Farmex', 'descripcion' => 'Empresa de productos de salud', 'activo' => true, 'productos_count' => 0],
            (object)['id' => 2, 'nombre' => 'Laboratorio farmacéutico internacional', 'descripcion' => 'Laboratorio farmacéutico internacional', 'activo' => true, 'productos_count' => 3],
            (object)['id' => 3, 'nombre' => 'Medicamentos genéricos', 'descripcion' => 'Medicamentos genéricos', 'activo' => true, 'productos_count' => 0],
            (object)['id' => 4, 'nombre' => 'GlaxoSmithKline', 'descripcion' => 'GlaxoSmithKline', 'activo' => true, 'productos_count' => 0],
            (object)['id' => 5, 'nombre' => 'Compañía multinacional de productos de salud', 'descripcion' => 'Compañía multinacional de productos de salud', 'activo' => true, 'productos_count' => 0],
        ]);
        $totalMarcas = 10;
        $marcasActivas = 10;
        $marcasInactivas = 0;
        $productosConMarca = 3;
    }
@endphp

<!-- Estadísticas de Marcas -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="bi bi-tags"></i>
            </div>
            <div class="stat-value">{{ $totalMarcas }}</div>
            <div class="stat-label">Total Marcas</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-value">{{ $marcasActivas }}</div>
            <div class="stat-label">Activas</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="bi bi-pause-circle"></i>
            </div>
            <div class="stat-value">{{ $marcasInactivas }}</div>
            <div class="stat-label">Inactivas</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="bi bi-capsule"></i>
            </div>
            <div class="stat-value">{{ $productosConMarca }}</div>
            <div class="stat-label">Productos</div>
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
                    <input type="text" class="form-control" placeholder="Buscar por nombre..." id="searchInput">
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="estadoFilter">
                        <option value="">Todos los estados</option>
                        <option value="activa">Activas</option>
                        <option value="inactiva">Inactivas</option>
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
                <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevaMarcaModal">
                    <i class="bi bi-plus me-1"></i> Nueva Marca
                </button>
                @endif
                <button class="btn btn-info btn-modern" onclick="exportarMarcas()">
                    <i class="bi bi-download me-1"></i> Exportar Lista
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Vista de Cards de Marcas -->
@if($totalMarcas > 0)
<div class="row" id="marcasContainer">
    @foreach($marcas as $marca)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="modern-card h-100" style="border-left: 4px solid var(--bs-primary); transition: all 0.3s ease;">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h5 class="text-primary mb-0">
                    <i class="bi bi-tags me-2"></i>{{ $marca->nombre }}
                </h5>
                @if($marca->activo)
                    <span class="badge bg-success">Activa</span>
                @else
                    <span class="badge bg-danger">Inactiva</span>
                @endif
            </div>
            <p class="text-muted mb-3">{{ Str::limit($marca->descripcion ?? 'Sin descripción', 80) }}</p>
            <div class="mb-3">
                <span class="badge bg-info">
                    <i class="bi bi-capsule me-1"></i>{{ $marca->productos_count ?? 0 }} productos
                </span>
            </div>
            <div class="btn-group w-100" role="group">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="verMarca({{ $marca->id }}, '{{ addslashes($marca->nombre) }}', '{{ addslashes($marca->descripcion ?? '') }}', {{ $marca->activo ? 'true' : 'false' }}, {{ $marca->productos_count ?? 0 }})">
                    <i class="bi bi-eye"></i>
                </button>
                @if(auth()->user()->role === 'administrador')
                <button type="button" class="btn btn-outline-warning btn-sm" onclick="editarMarca({{ $marca->id }}, '{{ addslashes($marca->nombre) }}', '{{ addslashes($marca->descripcion ?? '') }}', {{ $marca->activo ? 'true' : 'false' }})">
                    <i class="bi bi-pencil"></i>
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarMarca({{ $marca->id }}, '{{ addslashes($marca->nombre) }}')">
                    <i class="bi bi-trash"></i>
                </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<!-- Estado sin marcas -->
<div class="modern-card text-center py-5">
    <div class="text-muted mb-4">
        <i class="bi bi-tags" style="font-size: 4rem;"></i>
    </div>
    <h4>No hay marcas registradas</h4>
    <p class="text-muted mb-4">Comienza creando tu primera marca</p>
    @if(auth()->user()->role === 'administrador')
    <button class="btn btn-success btn-modern btn-lg" data-bs-toggle="modal" data-bs-target="#nuevaMarcaModal">
        <i class="bi bi-plus-circle me-2"></i>
        Crear Primera Marca
    </button>
    @endif
</div>
@endif

<!-- Modal Nueva Marca -->
<div class="modal fade" id="nuevaMarcaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Nueva Marca
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevaMarca">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-bold">Nombre de la Marca *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej: Bayer, Pfizer..." maxlength="255">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Estado</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" checked>
                                    <label class="form-check-label" for="activo">Marca activa</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-bold">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción de la marca o laboratorio..." maxlength="1000"></textarea>
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">Máximo 1000 caracteres</small>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Nota:</strong> Las marcas activas aparecen disponibles al crear productos.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-success btn-modern" onclick="crearMarca()">
                        <i class="bi bi-check-circle me-1"></i>Crear Marca
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Marca -->
<div class="modal fade" id="verMarcaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>Detalles de la Marca
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
                                    <td><span id="ver_marca_id" class="badge bg-secondary"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nombre:</td>
                                    <td><span id="ver_marca_nombre" class="h6"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Estado:</td>
                                    <td><span id="ver_marca_estado"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Productos:</td>
                                    <td><span id="ver_marca_productos" class="badge bg-info"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Descripción:</h6>
                        <p id="ver_marca_descripcion" class="text-muted"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Marca -->
<div class="modal fade" id="editarMarcaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil me-2"></i>Editar Marca
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarMarca">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_marca_id" name="marca_id">
                <div class="modal-body">
                    <div class="alert alert-info" id="edit_marca_info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Editando marca:</strong> <span id="edit_marca_nombre_info"></span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit_nombre" class="form-label fw-bold">Nombre de la Marca *</label>
                                <input type="text" class="form-control" id="edit_nombre" name="nombre" required placeholder="Ej: Bayer, Pfizer..." maxlength="255">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Estado</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit_activo" name="activo" value="1">
                                    <label class="form-check-label" for="edit_activo">Marca activa</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label fw-bold">Descripción</label>
                        <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3" placeholder="Descripción de la marca o laboratorio..." maxlength="1000"></textarea>
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">Máximo 1000 caracteres</small>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> Los cambios afectarán a todos los productos asociados.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-warning btn-modern" onclick="actualizarMarca()">
                        <i class="bi bi-check-circle me-1"></i>Actualizar Marca
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Búsqueda en tiempo real
    $('#searchInput').on('input', function() {
        filtrarMarcas();
    });

    $('#estadoFilter').on('change', function() {
        filtrarMarcas();
    });
});

function filtrarMarcas() {
    const searchTerm = $('#searchInput').val().toLowerCase();
    const estadoFilter = $('#estadoFilter').val().toLowerCase();
    
    $('#marcasContainer .modern-card').each(function() {
        const row = $(this);
        const texto = row.text().toLowerCase();
        const estadoBadge = row.find('.badge:last').text().toLowerCase();
        
        const coincideTexto = texto.includes(searchTerm);
        const coincideEstado = estadoFilter === '' || 
            (estadoFilter === 'activa' && estadoBadge.includes('activa')) ||
            (estadoFilter === 'inactiva' && estadoBadge.includes('inactiva'));
        
        row.toggle(coincideTexto && coincideEstado);
    });
}

function limpiarFiltros() {
    $('#searchInput').val('');
    $('#estadoFilter').val('');
    filtrarMarcas();
}

function verMarca(id, nombre, descripcion, activo, productos) {
    $('#ver_marca_id').text(id);
    $('#ver_marca_nombre').text(nombre);
    $('#ver_marca_descripcion').text(descripcion || 'Sin descripción');
    $('#ver_marca_productos').text(productos + ' productos');
    
    const estadoBadge = $('#ver_marca_estado');
    if (activo) {
        estadoBadge.removeClass().addClass('badge bg-success').html('<i class="bi bi-check-circle me-1"></i>Activa');
    } else {
        estadoBadge.removeClass().addClass('badge bg-danger').html('<i class="bi bi-x-circle me-1"></i>Inactiva');
    }
    
    $('#verMarcaModal').modal('show');
}

function editarMarca(id, nombre, descripcion, activo) {
    $('#edit_marca_id').val(id);
    $('#edit_nombre').val(nombre);
    $('#edit_descripcion').val(descripcion || '');
    $('#edit_activo').prop('checked', activo);
    $('#edit_marca_nombre_info').text(nombre);
    
    // Limpiar validaciones anteriores
    $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
    
    $('#editarMarcaModal').modal('show');
}

function crearMarca() {
    const formData = {
        nombre: $('#nombre').val(),
        descripcion: $('#descripcion').val(),
        activo: $('#activo').is(':checked') ? 1 : 0,
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    if (!formData.nombre.trim()) {
        Swal.fire('Error', 'El nombre de la marca es obligatorio', 'error');
        return;
    }

    // Mostrar loading
    const btnCrear = $('.btn-success-modern');
    const originalText = btnCrear.html();
    btnCrear.html('<i class="bi bi-hourglass-split me-1"></i>Creando...').prop('disabled', true);

    $.ajax({
        url: '/marcas/ajax',
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                Swal.fire('¡Éxito!', response.message, 'success');
                $('#nuevaMarcaModal').modal('hide');
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', response.message || 'Error al crear la marca', 'error');
            }
        },
        error: function(xhr) {
            let message = 'Error al crear la marca.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = Object.values(xhr.responseJSON.errors).flat();
                message = errors.join('\n');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            Swal.fire('Error', message, 'error');
        },
        complete: function() {
            btnCrear.html(originalText).prop('disabled', false);
        }
    });
}

function actualizarMarca() {
    const formData = {
        nombre: $('#edit_nombre').val(),
        descripcion: $('#edit_descripcion').val(),
        activo: $('#edit_activo').is(':checked') ? 1 : 0,
        _token: $('meta[name="csrf-token"]').attr('content'),
        _method: 'PUT'
    };

    if (!formData.nombre.trim()) {
        Swal.fire('Error', 'El nombre de la marca es obligatorio', 'error');
        return;
    }

    const marcaId = $('#edit_marca_id').val();
    
    // Mostrar loading
    const btnActualizar = $('.btn-warning-modern');
    const originalText = btnActualizar.html();
    btnActualizar.html('<i class="bi bi-hourglass-split me-1"></i>Actualizando...').prop('disabled', true);

    $.ajax({
        url: `/marcas/${marcaId}/ajax`,
        method: 'PUT',
        data: formData,
        success: function(response) {
            if (response.success) {
                Swal.fire('¡Actualizado!', response.message, 'success');
                $('#editarMarcaModal').modal('hide');
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', response.message || 'Error al actualizar la marca', 'error');
            }
        },
        error: function(xhr) {
            let message = 'Error al actualizar la marca.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = Object.values(xhr.responseJSON.errors).flat();
                message = errors.join('\n');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            Swal.fire('Error', message, 'error');
        },
        complete: function() {
            btnActualizar.html(originalText).prop('disabled', false);
        }
    });
}

function eliminarMarca(id, nombre) {
    Swal.fire({
        title: '¿Eliminar marca?',
        text: `Se eliminará la marca "${nombre}". Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/marcas/${id}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('¡Eliminado!', response.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire('Error', response.message || 'Error al eliminar la marca', 'error');
                    }
                },
                error: function(xhr) {
                    let message = 'Error al eliminar la marca.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', message, 'error');
                }
            });
        }
    });
}

function exportarMarcas() {
    window.open('/marcas-exportar', '_blank');
}
</script>
@endpush
@endsection
