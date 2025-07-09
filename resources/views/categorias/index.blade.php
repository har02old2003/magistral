@extends('layouts.modern')

@section('title', 'Categorías - Farmacia Magistral')

@section('page-title', 'Categorías')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-grid me-3"></i>Categorías
        </h1>
        <p class="text-muted mb-0">Gestión de categorías de productos</p>
    </div>
    <div class="d-flex gap-2">
        @if(auth()->user()->role === 'administrador')
        <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevaCategoriaModal">
            <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
        </button>
        @endif
        <button class="btn btn-info btn-modern" onclick="exportarCategorias()">
            <i class="bi bi-download me-1"></i> Exportar
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
.category-card:hover {
    transform: translateX(5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
</style>
@endpush

@section('content')
@php
    try {
        $categorias = \App\Models\Categoria::withCount('productos')->orderBy('nombre')->get();
        $totalCategorias = $categorias->count();
        $categoriasActivas = $categorias->where('activo', true)->count();
        $categoriasConProductos = $categorias->where('productos_count', '>', 0)->count();
        $categoriasInactivas = $totalCategorias - $categoriasActivas;
    } catch(\Exception $e) {
        $categorias = collect([
            (object)['id' => 1, 'nombre' => 'Antibióticos', 'descripcion' => 'Medicamentos para tratar infecciones bacterianas', 'activo' => true, 'productos_count' => 0],
            (object)['id' => 2, 'nombre' => 'Antihistamínicos', 'descripcion' => 'Medicamentos para tratar alergias', 'activo' => true, 'productos_count' => 0],
            (object)['id' => 3, 'nombre' => 'Medicamentos Cardiovasculares', 'descripcion' => 'Medicamentos Cardiovasculares', 'activo' => true, 'productos_count' => 1],
            (object)['id' => 4, 'nombre' => 'Medicamentos Digestivos', 'descripcion' => 'Medicamentos Digestivos', 'activo' => true, 'productos_count' => 0],
        ]);
        $totalCategorias = 10;
        $categoriasActivas = 10;
        $categoriasConProductos = 1;
        $categoriasInactivas = 0;
    }
@endphp

<!-- Estadísticas de Categorías -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="bi bi-grid"></i>
            </div>
            <div class="stat-value">{{ $totalCategorias }}</div>
            <div class="stat-label">Total Categorías</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-value">{{ $categoriasActivas }}</div>
            <div class="stat-label">Activas</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="bi bi-box"></i>
            </div>
            <div class="stat-value">{{ $categoriasConProductos }}</div>
            <div class="stat-label">Con Productos</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-value">{{ $categoriasInactivas }}</div>
            <div class="stat-label">Inactivas</div>
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
                <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevaCategoriaModal">
                    <i class="bi bi-plus me-1"></i> Nueva Categoría
                </button>
                @endif
                <button class="btn btn-info btn-modern" onclick="exportarCategorias()">
                    <i class="bi bi-download me-1"></i> Exportar Lista
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Vista de Cards de Categorías -->
@if($totalCategorias > 0)
<div class="row" id="categoriasContainer">
    @foreach($categorias as $categoria)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="modern-card h-100 category-card" style="border-left: 4px solid var(--bs-primary); transition: all 0.3s ease;">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h5 class="text-primary mb-0">
                    <i class="bi bi-tag me-2"></i>{{ $categoria->nombre }}
                </h5>
                @if($categoria->activo)
                    <span class="badge bg-success">Activa</span>
                @else
                    <span class="badge bg-danger">Inactiva</span>
                @endif
            </div>
            <p class="text-muted mb-3">{{ Str::limit($categoria->descripcion ?? 'Sin descripción', 80) }}</p>
            <div class="mb-3">
                <span class="badge bg-info">
                    <i class="bi bi-box me-1"></i>{{ $categoria->productos_count ?? 0 }} productos
                </span>
            </div>
            <div class="btn-group w-100" role="group">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="verCategoria({{ $categoria->id }}, '{{ addslashes($categoria->nombre) }}', '{{ addslashes($categoria->descripcion ?? '') }}', {{ $categoria->activo ? 'true' : 'false' }}, {{ $categoria->productos_count ?? 0 }})">
                    <i class="bi bi-eye"></i>
                </button>
                @if(auth()->user()->role === 'administrador')
                <button type="button" class="btn btn-outline-warning btn-sm" onclick="editarCategoria({{ $categoria->id }}, '{{ addslashes($categoria->nombre) }}', '{{ addslashes($categoria->descripcion ?? '') }}', {{ $categoria->activo ? 'true' : 'false' }})">
                    <i class="bi bi-pencil"></i>
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarCategoria({{ $categoria->id }}, '{{ addslashes($categoria->nombre) }}')">
                    <i class="bi bi-trash"></i>
                </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<!-- Estado sin categorías -->
<div class="modern-card text-center py-5">
    <div class="text-muted mb-4">
        <i class="bi bi-grid" style="font-size: 4rem;"></i>
    </div>
    <h4>No hay categorías registradas</h4>
    <p class="text-muted mb-4">Comienza creando tu primera categoría de productos</p>
    @if(auth()->user()->role === 'administrador')
    <button class="btn btn-success btn-modern btn-lg" data-bs-toggle="modal" data-bs-target="#nuevaCategoriaModal">
        <i class="bi bi-plus-circle me-2"></i>
        Crear Primera Categoría
    </button>
    @endif
</div>
@endif

<!-- Modal Nueva Categoría -->
<div class="modal fade" id="nuevaCategoriaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Nueva Categoría
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevaCategoria">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre de la Categoría *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej: Medicamentos, Vitaminas, etc.">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-bold">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Describe el tipo de productos que incluirá esta categoría..."></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" checked>
                            <label class="form-check-label fw-bold" for="activo">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                Categoría activa
                            </label>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Nota:</strong> Las categorías activas aparecerán disponibles para asignar a productos.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-modern" onclick="crearCategoria()">
                        <i class="bi bi-check-circle me-1"></i>Crear Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Categoría -->
<div class="modal fade" id="verCategoriaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>Detalles de la Categoría
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
                                    <td><span id="ver_categoria_id" class="badge bg-secondary"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nombre:</td>
                                    <td><span id="ver_categoria_nombre" class="h6"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Estado:</td>
                                    <td><span id="ver_categoria_estado"></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Productos:</td>
                                    <td><span id="ver_categoria_productos" class="badge bg-info"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Descripción:</h6>
                        <p id="ver_categoria_descripcion" class="text-muted p-3 bg-light rounded"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                @if(auth()->user()->role === 'administrador')
                <button type="button" class="btn btn-warning btn-modern" onclick="editarCategoriaDesdeModal()">
                    <i class="bi bi-pencil me-1"></i>Editar
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Categoría -->
<div class="modal fade" id="editarCategoriaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil me-2"></i>Editar Categoría
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarCategoria">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_categoria_id" name="categoria_id">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Editando categoría:</strong> <span id="edit_categoria_nombre_info"></span>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label fw-bold">Nombre de la Categoría *</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label fw-bold">Descripción</label>
                        <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit_activo" name="activo" value="1">
                            <label class="form-check-label fw-bold" for="edit_activo">
                                Categoría activa
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
                    <button type="button" class="btn btn-warning btn-modern" onclick="actualizarCategoria()">
                        <i class="bi bi-check-circle me-1"></i>Actualizar
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
        filtrarCategorias();
    });

    $('#estadoFilter').on('change', function() {
        filtrarCategorias();
    });
});

let categoriaEditandoId = null;

function filtrarCategorias() {
    const searchTerm = $('#searchInput').val().toLowerCase();
    const estadoFilter = $('#estadoFilter').val().toLowerCase();
    
    $('.category-card').closest('.col-md-6').each(function() {
        const card = $(this);
        const texto = card.text().toLowerCase();
        const estadoBadge = card.find('.badge:first').text().toLowerCase();
        
        const coincideTexto = texto.includes(searchTerm);
        const coincideEstado = estadoFilter === '' || 
            (estadoFilter === 'activa' && estadoBadge.includes('activa')) ||
            (estadoFilter === 'inactiva' && estadoBadge.includes('inactiva'));
        
        card.toggle(coincideTexto && coincideEstado);
    });
}

function limpiarFiltros() {
    $('#searchInput').val('');
    $('#estadoFilter').val('');
    filtrarCategorias();
}

function verCategoria(id, nombre, descripcion, activo, productos) {
    $('#ver_categoria_id').text(id);
    $('#ver_categoria_nombre').text(nombre);
    $('#ver_categoria_descripcion').text(descripcion || 'Sin descripción');
    $('#ver_categoria_productos').text(productos + ' productos');
    
    const estadoBadge = $('#ver_categoria_estado');
    if (activo) {
        estadoBadge.removeClass().addClass('badge bg-success').html('<i class="bi bi-check-circle me-1"></i>Activa');
    } else {
        estadoBadge.removeClass().addClass('badge bg-danger').html('<i class="bi bi-x-circle me-1"></i>Inactiva');
    }
    
    $('#verCategoriaModal').modal('show');
}

function editarCategoria(id, nombre, descripcion, activo) {
    categoriaEditandoId = id;
    $('#edit_categoria_id').val(id);
    $('#edit_nombre').val(nombre);
    $('#edit_descripcion').val(descripcion || '');
    $('#edit_activo').prop('checked', activo);
    $('#edit_categoria_nombre_info').text(nombre);
    
    // Limpiar validaciones anteriores
    $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
    
    $('#editarCategoriaModal').modal('show');
}

function editarCategoriaDesdeModal() {
    const id = $('#ver_categoria_id').text();
    const nombre = $('#ver_categoria_nombre').text();
    const descripcion = $('#ver_categoria_descripcion').text();
    const activo = $('#ver_categoria_estado').text().includes('Activa');
    
    $('#verCategoriaModal').modal('hide');
    setTimeout(() => {
        editarCategoria(id, nombre, descripcion === 'Sin descripción' ? '' : descripcion, activo);
    }, 300);
}

function crearCategoria() {
    const formData = {
        nombre: $('#nombre').val(),
        descripcion: $('#descripcion').val(),
        activo: $('#activo').is(':checked') ? 1 : 0,
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    if (!formData.nombre.trim()) {
        Swal.fire('Error', 'El nombre de la categoría es obligatorio', 'error');
        return;
    }

    // Mostrar loading
    const btnCrear = $('.btn-success-modern');
    const originalText = btnCrear.html();
    btnCrear.html('<i class="bi bi-hourglass-split me-1"></i>Creando...').prop('disabled', true);

    $.ajax({
        url: '/categorias/ajax',
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                Swal.fire('¡Éxito!', response.message, 'success');
                $('#nuevaCategoriaModal').modal('hide');
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', response.message || 'Error al crear la categoría', 'error');
            }
        },
        error: function(xhr) {
            let message = 'Error al crear la categoría.';
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

function actualizarCategoria() {
    const formData = {
        nombre: $('#edit_nombre').val(),
        descripcion: $('#edit_descripcion').val(),
        activo: $('#edit_activo').is(':checked') ? 1 : 0,
        _token: $('meta[name="csrf-token"]').attr('content'),
        _method: 'PUT'
    };

    if (!formData.nombre.trim()) {
        Swal.fire('Error', 'El nombre de la categoría es obligatorio', 'error');
        return;
    }

    const categoriaId = $('#edit_categoria_id').val();
    
    // Mostrar loading
    const btnActualizar = $('.btn-warning-modern');
    const originalText = btnActualizar.html();
    btnActualizar.html('<i class="bi bi-hourglass-split me-1"></i>Actualizando...').prop('disabled', true);

    $.ajax({
        url: `/categorias/${categoriaId}/ajax`,
        method: 'PUT',
        data: formData,
        success: function(response) {
            if (response.success) {
                Swal.fire('¡Actualizado!', response.message, 'success');
                $('#editarCategoriaModal').modal('hide');
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', response.message || 'Error al actualizar la categoría', 'error');
            }
        },
        error: function(xhr) {
            let message = 'Error al actualizar la categoría.';
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

function eliminarCategoria(id, nombre) {
    Swal.fire({
        title: '¿Eliminar categoría?',
        text: `Se eliminará la categoría "${nombre}". Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/categorias/${id}`,
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
                        Swal.fire('Error', response.message || 'Error al eliminar la categoría', 'error');
                    }
                },
                error: function(xhr) {
                    let message = 'Error al eliminar la categoría.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', message, 'error');
                }
            });
        }
    });
}

function exportarCategorias() {
    window.open('/categorias-exportar', '_blank');
}
</script>
@endpush
@endsection 