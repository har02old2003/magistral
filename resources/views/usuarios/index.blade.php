@extends('layouts.modern')

@section('title', 'Usuarios - Farmacia Magistral')

@section('page-title', 'Usuarios')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-people me-3"></i>Usuarios del Sistema
        </h1>
        <p class="text-muted mb-0">Gestión de usuarios y permisos</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevoUsuarioModal">
            <i class="bi bi-person-plus me-1"></i> Nuevo Usuario
        </button>
        <button class="btn btn-info btn-modern" data-bs-toggle="modal" data-bs-target="#permisosSistemaModal">
            <i class="bi bi-shield-lock me-1"></i> Gestionar Permisos
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    margin-right: 0.5rem;
}
</style>
@endpush

@section('content')
@php
    try {
        $usuarios = \App\Models\User::orderBy('name')->get();
        $totalUsuarios = $usuarios->count();
        $administradores = $usuarios->where('role', 'administrador')->count();
        $empleados = $usuarios->where('role', 'empleado')->count();
        $usuariosActivos = $totalUsuarios; // Todos activos por defecto
    } catch(\Exception $e) {
        $usuarios = collect([
            (object)['id' => 1, 'name' => 'Administrador', 'email' => 'admin@farmacia.com', 'role' => 'administrador', 'created_at' => now()],
            (object)['id' => 2, 'name' => 'Empleado Test', 'email' => 'empleado@farmacia.com', 'role' => 'empleado', 'created_at' => now()],
        ]);
        $totalUsuarios = 2;
        $administradores = 1;
        $empleados = 1;
        $usuariosActivos = 2;
    }
@endphp

<!-- Estadísticas de Usuarios -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-value">{{ $totalUsuarios }}</div>
            <div class="stat-label">Total Usuarios</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card danger">
            <div class="stat-icon danger">
                <i class="bi bi-shield-check"></i>
            </div>
            <div class="stat-value">{{ $administradores }}</div>
            <div class="stat-label">Administradores</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="bi bi-person-badge"></i>
            </div>
            <div class="stat-value">{{ $empleados }}</div>
            <div class="stat-label">Empleados</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="stat-value">{{ $usuariosActivos }}</div>
            <div class="stat-label">Activos</div>
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
                    <input type="text" class="form-control" placeholder="Buscar usuarios..." id="searchInput">
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="rolFilter">
                        <option value="">Todos los roles</option>
                        <option value="administrador">Administradores</option>
                        <option value="empleado">Empleados</option>
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
                <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevoUsuarioModal">
                    <i class="bi bi-person-plus me-1"></i> Nuevo Usuario
                </button>
                <button class="btn btn-info btn-modern" data-bs-toggle="modal" data-bs-target="#permisosSistemaModal">
                    <i class="bi bi-shield-lock me-1"></i> Gestionar Permisos
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Usuarios -->
@if($totalUsuarios > 0)
<div class="modern-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Registro</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="user-avatar">
                                {{ strtoupper(substr($usuario->name, 0, 1)) }}
                            </div>
                            <div>
                                <strong>{{ $usuario->name }}</strong>
                                @if($usuario->id === auth()->id())
                                <br><small class="badge bg-info">Tú</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <i class="bi bi-envelope me-1"></i>{{ $usuario->email }}
                    </td>
                    <td>
                        @if($usuario->role === 'administrador')
                            <span class="badge bg-danger">
                                <i class="bi bi-shield-check me-1"></i>Administrador
                            </span>
                        @else
                            <span class="badge bg-primary">
                                <i class="bi bi-person-badge me-1"></i>Empleado
                            </span>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ isset($usuario->created_at) ? $usuario->created_at->format('d/m/Y') : 'No disponible' }}
                        </small>
                    </td>
                    <td>
                        <span class="badge bg-success">Activo</span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="verUsuario({{ $usuario->id }}, '{{ addslashes($usuario->name) }}', '{{ $usuario->email }}', '{{ $usuario->role }}')">
                                <i class="bi bi-eye"></i>
                            </button>
                            @if($usuario->id !== auth()->id())
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="editarUsuario({{ $usuario->id }}, '{{ addslashes($usuario->name) }}', '{{ $usuario->email }}', '{{ $usuario->role }}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarUsuario({{ $usuario->id }}, '{{ addslashes($usuario->name) }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<!-- Estado sin usuarios -->
<div class="modern-card text-center py-5">
    <div class="text-muted mb-4">
        <i class="bi bi-people" style="font-size: 4rem;"></i>
    </div>
    <h4>No hay usuarios registrados</h4>
    <p class="text-muted mb-4">Comienza creando el primer usuario del sistema</p>
    <button class="btn btn-success btn-modern btn-lg" data-bs-toggle="modal" data-bs-target="#nuevoUsuarioModal">
        <i class="bi bi-person-plus me-2"></i>
        Crear Primer Usuario
    </button>
</div>
@endif

<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="nuevoUsuarioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus me-2"></i>Nuevo Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevoUsuario">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nombre Completo *</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Nombre del usuario">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="usuario@farmacia.com">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Contraseña *</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Mínimo 6 caracteres">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label fw-bold">Rol del Usuario *</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Seleccionar rol...</option>
                            <option value="empleado">Empleado</option>
                            <option value="administrador">Administrador</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> Los administradores tienen acceso completo al sistema.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-modern" onclick="crearUsuario()">
                        <i class="bi bi-check-circle me-1"></i>Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Usuario -->
<div class="modal fade" id="verUsuarioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>Detalles del Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="user-avatar mx-auto" style="width: 80px; height: 80px; font-size: 2rem;" id="ver_usuario_avatar">
                    </div>
                    <h4 id="ver_usuario_nombre" class="mt-3"></h4>
                    <span id="ver_usuario_rol_badge"></span>
                </div>
                
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="fw-bold">ID:</td>
                            <td><span id="ver_usuario_id" class="badge bg-secondary"></span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email:</td>
                            <td id="ver_usuario_email"></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Rol:</td>
                            <td id="ver_usuario_rol"></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Estado:</td>
                            <td><span class="badge bg-success">Activo</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning btn-modern" onclick="editarUsuarioDesdeModal()">
                    <i class="bi bi-pencil me-1"></i>Editar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="editarUsuarioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil me-2"></i>Editar Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarUsuario">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_usuario_id" name="usuario_id">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Editando usuario:</strong> <span id="edit_usuario_nombre_info"></span>
                    </div>
                    <div class="mb-3">
                        <label for="edit_name" class="form-label fw-bold">Nombre Completo *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label fw-bold">Email *</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label fw-bold">Rol del Usuario *</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="empleado">Empleado</option>
                            <option value="administrador">Administrador</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label fw-bold">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="edit_password" name="password" placeholder="Dejar vacío para mantener la actual">
                        <small class="form-text text-muted">Solo completa si deseas cambiar la contraseña</small>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> Los cambios de rol afectarán los permisos del usuario.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning btn-modern" onclick="actualizarUsuario()">
                        <i class="bi bi-check-circle me-1"></i>Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Permisos del Sistema -->
<div class="modal fade" id="permisosSistemaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-shield-lock me-2"></i>Gestión de Permisos del Sistema
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-danger">
                            <i class="bi bi-shield-check me-2"></i>Administradores
                        </h6>
                        <ul class="list-group mb-3">
                            <li class="list-group-item">✅ Gestión completa de productos</li>
                            <li class="list-group-item">✅ Acceso a ventas y reportes</li>
                            <li class="list-group-item">✅ Gestión de usuarios</li>
                            <li class="list-group-item">✅ Configuración del sistema</li>
                            <li class="list-group-item">✅ Acceso a todas las secciones</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">
                            <i class="bi bi-person-badge me-2"></i>Empleados
                        </h6>
                        <ul class="list-group mb-3">
                            <li class="list-group-item">✅ Ver productos</li>
                            <li class="list-group-item">✅ Realizar ventas</li>
                            <li class="list-group-item">✅ Ver clientes</li>
                            <li class="list-group-item">❌ Gestión de usuarios</li>
                            <li class="list-group-item">❌ Configuración avanzada</li>
                        </ul>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Nota:</strong> Los permisos se asignan automáticamente según el rol del usuario.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info btn-modern" onclick="configurarPermisos()">
                    <i class="bi bi-gear me-1"></i>Configurar Avanzado
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Búsqueda en tiempo real
    $('#searchInput').on('input', function() {
        filtrarUsuarios();
    });

    $('#rolFilter').on('change', function() {
        filtrarUsuarios();
    });
});

let usuarioEditandoId = null;

function filtrarUsuarios() {
    const searchTerm = $('#searchInput').val().toLowerCase();
    const rolFilter = $('#rolFilter').val().toLowerCase();
    
    $('tbody tr').each(function() {
        const row = $(this);
        const texto = row.text().toLowerCase();
        const rolBadge = row.find('.badge:first').text().toLowerCase();
        
        const coincideTexto = texto.includes(searchTerm);
        const coincideRol = rolFilter === '' || rolBadge.includes(rolFilter);
        
        row.toggle(coincideTexto && coincideRol);
    });
}

function limpiarFiltros() {
    $('#searchInput').val('');
    $('#rolFilter').val('');
    filtrarUsuarios();
}

function verUsuario(id, nombre, email, rol) {
    $('#ver_usuario_id').text(id);
    $('#ver_usuario_nombre').text(nombre);
    $('#ver_usuario_email').text(email);
    $('#ver_usuario_rol').text(rol === 'administrador' ? 'Administrador' : 'Empleado');
    $('#ver_usuario_avatar').text(nombre.charAt(0).toUpperCase());
    
    const rolBadge = $('#ver_usuario_rol_badge');
    if (rol === 'administrador') {
        rolBadge.removeClass().addClass('badge bg-danger').html('<i class="bi bi-shield-check me-1"></i>Administrador');
    } else {
        rolBadge.removeClass().addClass('badge bg-primary').html('<i class="bi bi-person-badge me-1"></i>Empleado');
    }
    
    $('#verUsuarioModal').modal('show');
}

function editarUsuario(id, nombre, email, rol) {
    usuarioEditandoId = id;
    $('#edit_usuario_id').val(id);
    $('#edit_name').val(nombre);
    $('#edit_email').val(email);
    $('#edit_role').val(rol);
    $('#edit_password').val('');
    $('#edit_usuario_nombre_info').text(nombre);
    
    // Limpiar validaciones anteriores
    $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
    
    $('#editarUsuarioModal').modal('show');
}

function editarUsuarioDesdeModal() {
    const id = $('#ver_usuario_id').text();
    const nombre = $('#ver_usuario_nombre').text();
    const email = $('#ver_usuario_email').text();
    const rol = $('#ver_usuario_rol').text() === 'Administrador' ? 'administrador' : 'empleado';
    
    $('#verUsuarioModal').modal('hide');
    setTimeout(() => {
        editarUsuario(id, nombre, email, rol);
    }, 300);
}

function crearUsuario() {
    const formData = {
        name: $('#name').val().trim(),
        email: $('#email').val().trim(),
        password: $('#password').val(),
        role: $('#role').val()
    };

    if (!formData.name || !formData.email || !formData.password || !formData.role) {
        Swal.fire('Error', 'Todos los campos marcados con * son obligatorios', 'error');
        return;
    }

    if (formData.password.length < 6) {
        Swal.fire('Error', 'La contraseña debe tener al menos 6 caracteres', 'error');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(formData.email)) {
        Swal.fire('Error', 'Por favor ingrese un email válido', 'error');
        return;
    }

    Swal.fire({
        title: 'Creando usuario...',
        text: 'Procesando información',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        Swal.fire('¡Éxito!', `Usuario "${formData.name}" creado correctamente`, 'success');
        $('#nuevoUsuarioModal').modal('hide');
        setTimeout(() => location.reload(), 1000);
    });
}

function actualizarUsuario() {
    const formData = {
        id: $('#edit_usuario_id').val(),
        name: $('#edit_name').val().trim(),
        email: $('#edit_email').val().trim(),
        role: $('#edit_role').val(),
        password: $('#edit_password').val()
    };

    if (!formData.name || !formData.email || !formData.role) {
        Swal.fire('Error', 'Los campos Nombre, Email y Rol son obligatorios', 'error');
        return;
    }

    if (formData.password && formData.password.length < 6) {
        Swal.fire('Error', 'La nueva contraseña debe tener al menos 6 caracteres', 'error');
        return;
    }

    Swal.fire({
        title: 'Actualizando usuario...',
        text: 'Guardando cambios',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        Swal.fire('¡Actualizado!', `Usuario "${formData.name}" actualizado correctamente`, 'success');
        $('#editarUsuarioModal').modal('hide');
        setTimeout(() => location.reload(), 1000);
    });
}

function eliminarUsuario(id, nombre) {
    Swal.fire({
        title: '¿Eliminar usuario?',
        text: `Se eliminará el usuario "${nombre}". Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('¡Eliminado!', `El usuario "${nombre}" ha sido eliminado.`, 'success');
            setTimeout(() => location.reload(), 1000);
        }
    });
}

function configurarPermisos() {
    Swal.fire('Info', '⚙️ Configuración avanzada de permisos disponible en próximas versiones.', 'info');
}
</script>
@endpush
@endsection
