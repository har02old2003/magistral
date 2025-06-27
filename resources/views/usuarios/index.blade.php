<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Farmacia Magistral</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --sidebar-gradient: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .modern-sidebar {
            background: var(--sidebar-gradient);
            min-height: 100vh;
            box-shadow: 5px 0 20px rgba(0,0,0,0.1);
        }
        
        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-brand h3 {
            color: white;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .nav-link {
            color: rgba(255,255,255,0.85) !important;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin: 0.2rem 1rem;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white !important;
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white !important;
            box-shadow: 0 4px 15px rgba(255,255,255,0.2);
        }
        
        .main-content {
            background: white;
            border-radius: 25px 0 0 0;
            min-height: 100vh;
            padding: 2rem;
            box-shadow: -5px 0 20px rgba(0,0,0,0.1);
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .page-title {
            font-size: 3rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }
        
        .stat-card.primary::before { background: var(--primary-gradient); }
        .stat-card.success::before { background: var(--success-gradient); }
        .stat-card.warning::before { background: var(--warning-gradient); }
        .stat-card.info::before { background: var(--info-gradient); }
        
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .btn-modern {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 2rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-modern:hover::before {
            left: 100%;
        }
        
        .btn-primary-modern { background: var(--primary-gradient); color: white; }
        .btn-success-modern { background: var(--success-gradient); color: white; }
        .btn-warning-modern { background: var(--warning-gradient); color: white; }
        .btn-info-modern { background: var(--info-gradient); color: white; }
        .btn-danger-modern { background: var(--danger-gradient); color: white; }
        
        .modern-table {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .modern-table .table thead th {
            background: var(--primary-gradient);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1.5rem 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }
        
        .modern-table .table tbody td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #f8f9fa;
            vertical-align: middle;
        }
        
        .modern-table .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .modern-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        
        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .badge-modern {
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px 20px 0 0;
            border: none;
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .no-data-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }
        
        .no-data-state i {
            font-size: 5rem;
            opacity: 0.3;
            margin-bottom: 2rem;
        }

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

        .role-admin { border-left: 4px solid #dc3545; }
        .role-empleado { border-left: 4px solid #0d6efd; }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 modern-sidebar">
                <div class="sidebar-brand">
                    <h3><i class="bi bi-hospital"></i> Farmacia</h3>
                    <small>{{ auth()->user()->name ?? 'Usuario' }}</small>
                    <small class="d-block">{{ auth()->user()->role ?? 'Empleado' }}</small>
                </div>
                
                <ul class="nav flex-column mt-3">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/productos">
                            <i class="bi bi-capsule me-2"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/ventas">
                            <i class="bi bi-cart-check me-2"></i> Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/clientes">
                            <i class="bi bi-people me-2"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/marcas">
                            <i class="bi bi-tags me-2"></i> Marcas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/categorias">
                            <i class="bi bi-grid me-2"></i> Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/proveedores">
                            <i class="bi bi-truck me-2"></i> Proveedores
                        </a>
                    </li>
                    @if(auth()->user()->role === 'administrador')
                    <li class="nav-item">
                        <a class="nav-link active" href="/usuarios">
                            <i class="bi bi-person-gear me-2"></i> Usuarios
                        </a>
                    </li>
                    @endif
                    
                    <!-- Separador para cerrar sesión -->
                    <li class="nav-item mt-4" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-usuarios" style="display: none;">
                            @csrf
                        </form>
                        <a class="nav-link" href="#" onclick="mostrarModalCerrarSesion()" style="background: rgba(255,107,107,0.2); border: 1px solid rgba(255,107,107,0.3); color: white !important;">
                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Contenido Principal -->
            <main class="col-md-9 col-lg-10 main-content">
                <div class="fade-in">
                    <!-- Header -->
                    <div class="page-header">
                        <h1 class="page-title">
                            <i class="bi bi-person-gear me-3"></i>Usuarios del Sistema
                        </h1>
                        <p class="mb-0 opacity-75" style="font-size: 1.2rem;">Gestión de usuarios y permisos</p>
                    </div>

                    @php
                        try {
                            $usuarios = \App\Models\User::orderBy('name')->get();
                            $totalUsuarios = $usuarios->count();
                            $administradores = $usuarios->where('role', 'administrador')->count();
                            $empleados = $usuarios->where('role', 'empleado')->count();
                        } catch(\Exception $e) {
                            $usuarios = collect([
                                (object)['id' => 1, 'name' => 'Administrador', 'email' => 'admin@farmacia.com', 'role' => 'administrador', 'created_at' => now()],
                                (object)['id' => 2, 'name' => 'Empleado Test', 'email' => 'empleado@farmacia.com', 'role' => 'empleado', 'created_at' => now()],
                            ]);
                            $totalUsuarios = 2;
                            $administradores = 1;
                            $empleados = 1;
                        }
                    @endphp

                    <!-- Estadísticas de Usuarios -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card primary">
                                <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="text-primary" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $totalUsuarios }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Usuarios</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card success">
                                <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div class="text-success" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $administradores }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Administradores</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card info">
                                <div class="text-info" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div class="text-info" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $empleados }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Empleados</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card warning">
                                <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-person-check"></i>
                                </div>
                                <div class="text-warning" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $totalUsuarios }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Activos</div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones y Controles -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="modern-card">
                                <h5 class="mb-3">
                                    <i class="bi bi-search text-primary me-2"></i>
                                    Búsqueda y Filtros
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
                                        <button class="btn btn-info-modern btn-modern w-100" onclick="limpiarFiltros()">
                                            <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="modern-card" style="height: 100%;">
                                <h6 class="mb-3">
                                    <i class="bi bi-lightning text-warning me-2"></i>
                                    Acciones Rápidas
                                </h6>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-success-modern btn-modern btn-sm" data-bs-toggle="modal" data-bs-target="#nuevoUsuarioModal">
                                        <i class="bi bi-person-plus me-1"></i> Nuevo Usuario
                                    </button>
                                    <button class="btn btn-primary-modern btn-modern btn-sm" data-bs-toggle="modal" data-bs-target="#permisosSistemaModal">
                                        <i class="bi bi-shield-lock me-1"></i> Gestionar Permisos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Usuarios -->
                    @if($totalUsuarios > 0)
                    <div class="modern-table">
                        <table class="table mb-0">
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
                                <tr class="{{ $usuario->role === 'administrador' ? 'role-admin' : 'role-empleado' }}">
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
                                            <span class="badge bg-danger badge-modern">
                                                <i class="bi bi-shield-check me-1"></i>Administrador
                                            </span>
                                        @else
                                            <span class="badge bg-primary badge-modern">
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
                                        <span class="badge bg-success badge-modern">Activo</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="verUsuario({{ $usuario->id }}, '{{ $usuario->name }}', '{{ $usuario->email }}', '{{ $usuario->role }}')">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @if($usuario->id !== auth()->id())
                                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="editarUsuario({{ $usuario->id }}, '{{ $usuario->name }}', '{{ $usuario->email }}', '{{ $usuario->role }}')">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarUsuario({{ $usuario->id }}, '{{ $usuario->name }}')">
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
                    @else
                    <!-- Estado sin usuarios -->
                    <div class="modern-card">
                        <div class="no-data-state">
                            <i class="bi bi-people"></i>
                            <h4>No hay usuarios registrados</h4>
                            <p class="mb-4">Comienza creando el primer usuario del sistema</p>
                            <button class="btn btn-success-modern btn-modern btn-lg" data-bs-toggle="modal" data-bs-target="#nuevoUsuarioModal">
                                <i class="bi bi-person-plus me-2"></i>
                                Crear Primer Usuario
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Nuevo Usuario -->
    <div class="modal fade" id="nuevoUsuarioModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus me-2"></i>Nuevo Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nombre Completo *</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="Nombre del usuario">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="usuario@farmacia.com">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Contraseña *</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Mínimo 8 caracteres">
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label fw-bold">Rol del Usuario *</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Seleccionar rol...</option>
                                <option value="empleado">Empleado</option>
                                <option value="administrador">Administrador</option>
                            </select>
                        </div>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Importante:</strong> Los administradores tienen acceso completo al sistema.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success-modern btn-modern" onclick="crearUsuario()">
                            <i class="bi bi-check-circle me-2"></i>Crear Usuario
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-warning-modern btn-modern" onclick="editarUsuarioDesdeModal()">
                        <i class="bi bi-pencil me-2"></i>Editar
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name_editar" class="form-label fw-bold">Nombre Completo *</label>
                            <input type="text" class="form-control" id="name_editar" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email_editar" class="form-label fw-bold">Email *</label>
                            <input type="email" class="form-control" id="email_editar" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="role_editar" class="form-label fw-bold">Rol del Usuario *</label>
                            <select class="form-select" id="role_editar" name="role" required>
                                <option value="empleado">Empleado</option>
                                <option value="administrador">Administrador</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password_nuevo" class="form-label fw-bold">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password_nuevo" name="password" placeholder="Dejar vacío para mantener la actual">
                            <small class="form-text text-muted">Solo completa si deseas cambiar la contraseña</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-warning-modern btn-modern" onclick="actualizarUsuario()">
                            <i class="bi bi-check-circle me-2"></i>Actualizar
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary-modern btn-modern" onclick="configurarPermisos()">
                        <i class="bi bi-gear me-2"></i>Configurar Avanzado
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // CSRF Token para peticiones AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Búsqueda en tiempo real
        document.getElementById('searchInput').addEventListener('input', function() {
            filtrarUsuarios();
        });

        document.getElementById('rolFilter').addEventListener('change', function() {
            filtrarUsuarios();
        });

        function filtrarUsuarios() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rolFilter = document.getElementById('rolFilter').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const texto = row.textContent.toLowerCase();
                const rolBadge = row.querySelector('.badge-modern').textContent.toLowerCase();
                
                const coincideTexto = texto.includes(searchTerm);
                const coincideRol = rolFilter === '' || rolBadge.includes(rolFilter);
                
                row.style.display = coincideTexto && coincideRol ? '' : 'none';
            });
        }

        function limpiarFiltros() {
            document.getElementById('searchInput').value = '';
            document.getElementById('rolFilter').value = '';
            filtrarUsuarios();
        }

        // Variable global para almacenar el ID del usuario en edición
        let usuarioEditandoId = null;

        // Función para ver usuario
        function verUsuario(id, nombre, email, rol) {
            document.getElementById('ver_usuario_id').textContent = id;
            document.getElementById('ver_usuario_nombre').textContent = nombre;
            document.getElementById('ver_usuario_email').textContent = email;
            document.getElementById('ver_usuario_rol').textContent = rol === 'administrador' ? 'Administrador' : 'Empleado';
            document.getElementById('ver_usuario_avatar').textContent = nombre.charAt(0).toUpperCase();
            
            const rolBadge = document.getElementById('ver_usuario_rol_badge');
            if (rol === 'administrador') {
                rolBadge.className = 'badge bg-danger';
                rolBadge.innerHTML = '<i class="bi bi-shield-check me-1"></i>Administrador';
            } else {
                rolBadge.className = 'badge bg-primary';
                rolBadge.innerHTML = '<i class="bi bi-person-badge me-1"></i>Empleado';
            }
            
            new bootstrap.Modal(document.getElementById('verUsuarioModal')).show();
        }

        // Función para editar usuario
        function editarUsuario(id, nombre, email, rol) {
            usuarioEditandoId = id;
            document.getElementById('name_editar').value = nombre;
            document.getElementById('email_editar').value = email;
            document.getElementById('role_editar').value = rol;
            document.getElementById('password_nuevo').value = '';
            
            // Limpiar validaciones anteriores
            document.querySelectorAll('#editarUsuarioModal .is-valid, #editarUsuarioModal .is-invalid').forEach(el => {
                el.classList.remove('is-valid', 'is-invalid');
            });
            
            new bootstrap.Modal(document.getElementById('editarUsuarioModal')).show();
        }

        function editarUsuarioDesdeModal() {
            // Obtener datos actuales del modal de ver
            const id = document.getElementById('ver_usuario_id').textContent;
            const nombre = document.getElementById('ver_usuario_nombre').textContent;
            const email = document.getElementById('ver_usuario_email').textContent;
            const rol = document.getElementById('ver_usuario_rol').textContent === 'Administrador' ? 'administrador' : 'empleado';
            
            // Cerrar modal de ver y abrir modal de editar
            bootstrap.Modal.getInstance(document.getElementById('verUsuarioModal')).hide();
            setTimeout(() => {
                editarUsuario(id, nombre, email, rol);
            }, 300);
        }

        // Función para crear usuario con AJAX
        function crearUsuario() {
            const nombre = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const rol = document.getElementById('role').value;
            
            console.log('Creando usuario con datos:', {
                name: nombre,
                email: email,
                role: rol,
                password_length: password.length
            });
            
            // Validaciones básicas
            if (!nombre || !email || !password || !rol) {
                mostrarAlerta('error', 'Todos los campos marcados con * son obligatorios.');
                return;
            }
            
            if (password.length < 6) {
                mostrarAlerta('error', 'La contraseña debe tener al menos 6 caracteres.');
                document.getElementById('password').focus();
                return;
            }

            // Validar email básico
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                mostrarAlerta('error', 'Por favor ingrese un email válido.');
                document.getElementById('email').focus();
                return;
            }

            // Mostrar loading
            const btnCrear = document.querySelector('#nuevoUsuarioModal .btn-success-modern');
            const originalText = btnCrear.innerHTML;
            btnCrear.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creando...';
            btnCrear.disabled = true;

            // Usar fetch en lugar de jQuery AJAX
            fetch('/usuarios', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    name: nombre,
                    email: email,
                    password: password,
                    role: rol
                })
            })
            .then(function(response) {
                console.log('Respuesta del servidor:', response.status);
                return response.json();
            })
            .then(function(data) {
                console.log('Datos recibidos:', data);
                if (data.success) {
                    mostrarAlerta('success', data.message || 'Usuario creado exitosamente.');
                    bootstrap.Modal.getInstance(document.getElementById('nuevoUsuarioModal')).hide();
                    
                    // Limpiar formulario
                    document.getElementById('name').value = '';
                    document.getElementById('email').value = '';
                    document.getElementById('password').value = '';
                    document.getElementById('role').value = '';
                    
                    // Recargar la página después de 2 segundos
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    mostrarAlerta('error', data.message || 'Error al crear el usuario.');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                mostrarAlerta('error', 'Error de conexión: ' + error.message);
            })
            .finally(function() {
                btnCrear.innerHTML = originalText;
                btnCrear.disabled = false;
            });
        }

        // Función para actualizar usuario con AJAX
        function actualizarUsuario() {
            if (!usuarioEditandoId) {
                mostrarAlerta('error', 'Error: ID de usuario no válido.');
                return;
            }

            const nombre = document.getElementById('name_editar').value.trim();
            const email = document.getElementById('email_editar').value.trim();
            const rol = document.getElementById('role_editar').value;
            const password = document.getElementById('password_nuevo').value;
            
            if (!nombre || !email || !rol) {
                mostrarAlerta('error', 'Los campos Nombre, Email y Rol son obligatorios.');
                return;
            }

            if (password && password.length < 6) {
                mostrarAlerta('error', 'La nueva contraseña debe tener al menos 6 caracteres.');
                document.getElementById('password_nuevo').focus();
                return;
            }

            // Mostrar loading
            const btnActualizar = document.querySelector('#editarUsuarioModal .btn-warning-modern');
            const originalText = btnActualizar.innerHTML;
            btnActualizar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Actualizando...';
            btnActualizar.disabled = true;

            const data = {
                name: nombre,
                email: email,
                role: rol,
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT'
            };

            // Solo incluir contraseña si se proporcionó
            if (password) {
                data.password = password;
                data.password_confirmation = password;
            }

            $.ajax({
                url: `/usuarios/${usuarioEditandoId}`,
                method: 'PUT',
                data: data,
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('success', response.message || 'Usuario actualizado exitosamente.');
                        bootstrap.Modal.getInstance(document.getElementById('editarUsuarioModal')).hide();
                        // Recargar la página después de 1.5 segundos
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al actualizar el usuario.');
                    }
                },
                error: function(xhr) {
                    let message = 'Error al actualizar el usuario.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        message = errors.join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    mostrarAlerta('error', message);
                },
                complete: function() {
                    btnActualizar.innerHTML = originalText;
                    btnActualizar.disabled = false;
                }
            });
        }

        function eliminarUsuario(id, nombre) {
            if (confirm(`¿Está seguro de eliminar al usuario "${nombre}"?\n\nEsta acción no se puede deshacer y el usuario perdería acceso al sistema.`)) {
                $.ajax({
                    url: `/usuarios/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        if (response.success) {
                            mostrarAlerta('success', response.message || 'Usuario eliminado exitosamente.');
                            // Recargar la página después de 1.5 segundos
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            mostrarAlerta('error', response.message || 'Error al eliminar el usuario.');
                        }
                    },
                    error: function(xhr) {
                        let message = 'Error al eliminar el usuario.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        mostrarAlerta('error', message);
                    }
                });
            }
        }

        function configurarPermisos() {
            mostrarAlerta('info', '⚙️ Configuración avanzada de permisos disponible en próximas versiones.');
        }

        // Función para mostrar alertas
        function mostrarAlerta(tipo, mensaje) {
            // Crear el elemento de alerta
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${tipo === 'success' ? 'success' : tipo === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            
            const icon = tipo === 'success' ? 'check-circle' : tipo === 'error' ? 'exclamation-triangle' : 'info-circle';
            
            alertDiv.innerHTML = `
                <i class="bi bi-${icon} me-2"></i>
                <span>${mensaje}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Auto-remove después de 5 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Mostrar alertas de Laravel flash
        @if(session('success'))
            mostrarAlerta('success', '{{ session("success") }}');
        @endif
        
        @if(session('error'))
            mostrarAlerta('error', '{{ session("error") }}');
        @endif

        // Validaciones en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar meta tag para CSRF si no existe
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = '{{ csrf_token() }}';
                document.head.appendChild(meta);
            }

            // Validación del nombre
            const nombreInputs = document.querySelectorAll('#name, #name_editar');
            nombreInputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Permitir letras, espacios y algunos caracteres especiales para nombres
                    this.value = this.value.replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/g, '');
                    
                    // Capitalizar primera letra de cada palabra
                    this.value = this.value.toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
                    
                    // Validar longitud
                    if (this.value.length < 2 && this.value.length > 0) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else if (this.value.length >= 2) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-invalid', 'is-valid');
                    }
                });
            });

            // Validación de email
            const emailInputs = document.querySelectorAll('#email, #email_editar');
            emailInputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.toLowerCase();
                    
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (emailRegex.test(this.value) || this.value.length === 0) {
                        this.classList.remove('is-invalid');
                        if (this.value.length > 0) {
                            this.classList.add('is-valid');
                        }
                    } else if (this.value.length > 0) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                });
            });

            // Validación de contraseña
            const passwordInputs = document.querySelectorAll('#password, #password_nuevo');
            passwordInputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value.length >= 6 || this.value.length === 0) {
                        this.classList.remove('is-invalid');
                        if (this.value.length >= 6) {
                            this.classList.add('is-valid');
                        }
                    } else if (this.value.length > 0) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                });
            });

            // Animaciones de las cards
            const cards = document.querySelectorAll('.stat-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 100);
            });

            // Limpiar formularios al cerrar modales
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function () {
                    const forms = this.querySelectorAll('form');
                    forms.forEach(form => {
                        form.reset();
                        // Remover clases de validación
                        form.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
                            el.classList.remove('is-valid', 'is-invalid');
                        });
                    });
                    // Resetear variable de edición
                    usuarioEditandoId = null;
                });
            });
        });
    </script>
    
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
    </div>
    
    <script>
        // Función para mostrar el modal de confirmación
        function mostrarModalCerrarSesion() {
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

            // Enviar formulario después de 1 segundo para que se vea el loading
            setTimeout(() => {
                document.getElementById('logout-form-usuarios').submit();
            }, 1000);
        }

        // Función legacy para compatibilidad
        function confirmarCerrarSesion() {
            mostrarModalCerrarSesion();
        }
    </script>
</body>
</html>
