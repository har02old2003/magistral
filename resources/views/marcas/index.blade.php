<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Marcas - Farmacia Magistral</title>
    
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
                        <a class="nav-link active" href="/marcas">
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
                        <a class="nav-link" href="/usuarios">
                            <i class="bi bi-person-gear me-2"></i> Usuarios
                        </a>
                    </li>
                    @endif
                    
                    <!-- Separador para cerrar sesión -->
                    <li class="nav-item mt-4" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-marcas" style="display: none;">
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
                            <i class="bi bi-tags me-3"></i>Marcas
                        </h1>
                        <p class="mb-0 opacity-75" style="font-size: 1.2rem;">Gestión de marcas y laboratorios</p>
                    </div>

                    @php
                        try {
                            $marcas = \App\Models\Marca::withCount('productos')->orderBy('nombre')->get();
                            $totalMarcas = $marcas->count();
                            $marcasActivas = $marcas->where('activo', true)->count();
                            $marcasInactivas = $marcas->where('activo', false)->count();
                            $productosConMarca = $marcas->sum('productos_count');
                        } catch(\Exception $e) {
                            $marcas = collect([
                                (object)['id' => 1, 'nombre' => 'Bayer', 'descripcion' => 'Laboratorio farmacéutico alemán', 'activo' => true, 'productos_count' => 5],
                                (object)['id' => 2, 'nombre' => 'Pfizer', 'descripcion' => 'Compañía farmacéutica americana', 'activo' => true, 'productos_count' => 3],
                                (object)['id' => 3, 'nombre' => 'Roche', 'descripcion' => 'Empresa suiza de biotecnología', 'activo' => true, 'productos_count' => 2]
                            ]);
                            $totalMarcas = 3;
                            $marcasActivas = 3;
                            $marcasInactivas = 0;
                            $productosConMarca = 10;
                        }
                    @endphp

                    <!-- Estadísticas de Marcas -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card primary">
                                <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-tags"></i>
                                </div>
                                <div class="text-primary" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $totalMarcas }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Marcas</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card success">
                                <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="text-success" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $marcasActivas }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Activas</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card warning">
                                <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-pause-circle"></i>
                                </div>
                                <div class="text-warning" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $marcasInactivas }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Inactivas</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card info">
                                <div class="text-info" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-capsule"></i>
                                </div>
                                <div class="text-info" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $productosConMarca }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Productos</div>
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
                                        <input type="text" class="form-control" placeholder="Buscar marca..." id="searchInput" style="border-radius: 12px; padding: 0.75rem;">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <select class="form-select" id="estadoFilter" style="border-radius: 12px; padding: 0.75rem;">
                                            <option value="">Todos los estados</option>
                                            <option value="activo">Activas</option>
                                            <option value="inactivo">Inactivas</option>
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
                                    @if(auth()->user()->role === 'administrador')
                                    <button class="btn btn-success-modern btn-modern btn-sm" data-bs-toggle="modal" data-bs-target="#nuevaMarcaModal">
                                        <i class="bi bi-plus-circle me-1"></i> Nueva Marca
                                    </button>
                                    @endif
                                    <button class="btn btn-primary-modern btn-modern btn-sm" onclick="exportarMarcas()">
                                        <i class="bi bi-download me-1"></i> Exportar Lista
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Marcas -->
                    <div class="modern-table">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Marca</th>
                                    <th>Descripción</th>
                                    <th>Productos</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($marcas as $marca)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary badge-modern">{{ $marca->id }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $marca->nombre }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $marca->descripcion ?? 'Sin descripción' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info badge-modern">{{ $marca->productos_count ?? 0 }} productos</span>
                                    </td>
                                    <td>
                                        @if($marca->activo)
                                            <span class="badge bg-success badge-modern">Activa</span>
                                        @else
                                            <span class="badge bg-danger badge-modern">Inactiva</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" title="Ver detalles" onclick="verMarca({{ $marca->id }}, '{{ $marca->nombre }}', '{{ $marca->descripcion }}', {{ $marca->activo ? 'true' : 'false' }}, {{ $marca->productos_count ?? 0 }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @if(auth()->user()->role === 'administrador')
                                            <button type="button" class="btn btn-outline-warning btn-sm" title="Editar" onclick="editarMarca({{ $marca->id }}, '{{ $marca->nombre }}', '{{ $marca->descripcion }}', {{ $marca->activo ? 'true' : 'false' }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="eliminarMarca({{ $marca->id }}, '{{ $marca->nombre }}')">
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
            </main>
        </div>
    </div>

    <!-- Modal Nueva Marca -->
    <div class="modal fade" id="nuevaMarcaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Marca
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formNuevaMarca">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label fw-bold">Nombre de la Marca *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej: Bayer, Pfizer..." maxlength="255">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
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
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-success-modern btn-modern" onclick="crearMarca()">
                            <i class="bi bi-check-circle me-2"></i>Crear Marca
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_nombre" class="form-label fw-bold">Nombre de la Marca *</label>
                                    <input type="text" class="form-control" id="edit_nombre" name="nombre" required placeholder="Ej: Bayer, Pfizer..." maxlength="255">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
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
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-warning-modern btn-modern" onclick="actualizarMarcaReal()">
                            <i class="bi bi-check-circle me-2"></i>Actualizar Marca
                        </button>
                    </div>
                </form>
            </div>
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
                        <small><strong>Usuario:</strong> {{ auth()->user()->name ?? 'Usuario' }}</small>
                    </p>
                    <div class="progress mb-3" id="logout-progress" style="display: none; height: 8px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" style="width: 100%"></div>
                    </div>
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
            filtrarMarcas();
        });

        document.getElementById('estadoFilter').addEventListener('change', function() {
            filtrarMarcas();
        });

        function filtrarMarcas() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const estadoFilter = document.getElementById('estadoFilter').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const texto = row.textContent.toLowerCase();
                const estadoBadge = row.querySelector('.badge-modern:last-of-type').textContent.toLowerCase();
                
                const coincideTexto = texto.includes(searchTerm);
                const coincideEstado = estadoFilter === '' || 
                    (estadoFilter === 'activa' && estadoBadge.includes('activa')) ||
                    (estadoFilter === 'inactiva' && estadoBadge.includes('inactiva'));
                
                row.style.display = coincideTexto && coincideEstado ? '' : 'none';
            });
        }

        function limpiarFiltros() {
            document.getElementById('searchInput').value = '';
            document.getElementById('estadoFilter').value = '';
            filtrarMarcas();
        }

        // Función para ver marca
        function verMarca(id, nombre, descripcion, activo, productos) {
            document.getElementById('ver_marca_id').textContent = id;
            document.getElementById('ver_marca_nombre').textContent = nombre;
            document.getElementById('ver_marca_descripcion').textContent = descripcion || 'Sin descripción';
            document.getElementById('ver_marca_productos').textContent = productos + ' productos';
            
            const estadoBadge = document.getElementById('ver_marca_estado');
            if (activo) {
                estadoBadge.className = 'badge bg-success';
                estadoBadge.innerHTML = '<i class="bi bi-check-circle me-1"></i>Activa';
            } else {
                estadoBadge.className = 'badge bg-danger';
                estadoBadge.innerHTML = '<i class="bi bi-x-circle me-1"></i>Inactiva';
            }
            
            new bootstrap.Modal(document.getElementById('verMarcaModal')).show();
        }

        // Función para editar marca
        function editarMarca(id, nombre, descripcion, activo) {
            console.log('Editando marca:', {id, nombre, descripcion, activo});
            
            document.getElementById('edit_marca_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_descripcion').value = descripcion || '';
            document.getElementById('edit_activo').checked = activo;
            document.getElementById('edit_marca_nombre_info').textContent = nombre;
            
            // Limpiar validaciones anteriores
            document.querySelectorAll('#editarMarcaModal .is-valid, #editarMarcaModal .is-invalid').forEach(el => {
                el.classList.remove('is-valid', 'is-invalid');
            });
            
            new bootstrap.Modal(document.getElementById('editarMarcaModal')).show();
        }

        function editarMarcaDesdeModal() {
            alert('Editar marca desde modal - Funcionalidad en desarrollo.');
        }

        // Validaciones en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            // Validación del nombre (solo letras, espacios, guiones y puntos)
            const nombreInputs = document.querySelectorAll('#nombre, #edit_nombre');
            nombreInputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ\s\-\.]/g, '');
                    
                    // Capitalizar primera letra de cada palabra
                    this.value = this.value.toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
                    
                    // Validar longitud
                    const feedback = this.parentNode.querySelector('.invalid-feedback') || 
                                   this.parentNode.appendChild(document.createElement('div'));
                    feedback.className = 'invalid-feedback';
                    
                    if (this.value.length < 2 && this.value.length > 0) {
                        this.classList.add('is-invalid');
                        feedback.textContent = 'El nombre debe tener al menos 2 caracteres.';
                        feedback.style.display = 'block';
                    } else if (this.value.length > 255) {
                        this.classList.add('is-invalid');
                        feedback.textContent = 'El nombre no puede exceder 255 caracteres.';
                        feedback.style.display = 'block';
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                        feedback.style.display = 'none';
                    }
                });
            });

            // Validación de descripción
            const descripcionInputs = document.querySelectorAll('#descripcion, #edit_descripcion');
            descripcionInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const feedback = this.parentNode.querySelector('.invalid-feedback') || 
                                   this.parentNode.appendChild(document.createElement('div'));
                    feedback.className = 'invalid-feedback';
                    
                    if (this.value.length > 1000) {
                        this.classList.add('is-invalid');
                        feedback.textContent = 'La descripción no puede exceder 1000 caracteres.';
                        feedback.style.display = 'block';
                    } else {
                        this.classList.remove('is-invalid');
                        if (this.value.length > 0) {
                            this.classList.add('is-valid');
                        }
                        feedback.style.display = 'none';
                    }
                });
            });
        });

        // Función para crear marca con AJAX
        function crearMarca() {
            const form = document.querySelector('#formNuevaMarca');
            const formData = new FormData(form);
            
            // Validar campos requeridos
            const nombre = document.getElementById('nombre').value.trim();
            if (!nombre) {
                mostrarAlerta('error', 'El nombre de la marca es obligatorio.');
                document.getElementById('nombre').focus();
                return;
            }
            
            if (nombre.length < 2) {
                mostrarAlerta('error', 'El nombre debe tener al menos 2 caracteres.');
                document.getElementById('nombre').focus();
                return;
            }

            // Mostrar loading
            const btnCrear = document.querySelector('#nuevaMarcaModal .btn-success-modern');
            const originalText = btnCrear.innerHTML;
            btnCrear.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creando...';
            btnCrear.disabled = true;

            $.ajax({
                url: '/marcas',
                method: 'POST',
                data: {
                    nombre: nombre,
                    descripcion: document.getElementById('descripcion').value.trim(),
                    activo: document.getElementById('activo').checked ? 1 : 0,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('success', response.message);
                        bootstrap.Modal.getInstance(document.getElementById('nuevaMarcaModal')).hide();
                        form.reset();
                        // Recargar la página después de 1.5 segundos
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al crear la marca.');
                    }
                },
                error: function(xhr) {
                    let message = 'Error al crear la marca.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        message = errors.join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    mostrarAlerta('error', message);
                },
                complete: function() {
                    btnCrear.innerHTML = originalText;
                    btnCrear.disabled = false;
                }
            });
        }

        // Función para actualizar marca con AJAX - CORREGIDA
        function actualizarMarcaReal() {
            const form = document.querySelector('#formEditarMarca');
            const marcaId = document.getElementById('edit_marca_id').value;
            
            // Validar campos requeridos
            const nombre = document.getElementById('edit_nombre').value.trim();
            if (!nombre) {
                mostrarAlerta('error', 'El nombre de la marca es obligatorio.');
                document.getElementById('edit_nombre').focus();
                return;
            }
            
            if (nombre.length < 2) {
                mostrarAlerta('error', 'El nombre debe tener al menos 2 caracteres.');
                document.getElementById('edit_nombre').focus();
                return;
            }

            // Mostrar loading
            const btnActualizar = document.querySelector('#editarMarcaModal .btn-warning-modern');
            const originalText = btnActualizar.innerHTML;
            btnActualizar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Actualizando...';
            btnActualizar.disabled = true;

            $.ajax({
                url: `/marcas/${marcaId}`,
                method: 'PUT',
                data: {
                    nombre: nombre,
                    descripcion: document.getElementById('edit_descripcion').value.trim(),
                    activo: document.getElementById('edit_activo').checked ? 1 : 0,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'PUT'
                },
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('success', response.message);
                        bootstrap.Modal.getInstance(document.getElementById('editarMarcaModal')).hide();
                        // Recargar la página después de 1.5 segundos
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al actualizar la marca.');
                    }
                },
                error: function(xhr) {
                    let message = 'Error al actualizar la marca.';
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

        // Función para eliminar marca con confirmación
        function eliminarMarca(id, nombre) {
            // Mostrar confirmación con SweetAlert2 si está disponible, sino usar confirm nativo
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Eliminar marca?',
                    text: `¿Está seguro de eliminar la marca "${nombre}"? Esta acción no se puede deshacer.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        ejecutarEliminacion(id, nombre);
                    }
                });
            } else {
                if (confirm(`¿Está seguro de eliminar la marca "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
                    ejecutarEliminacion(id, nombre);
                }
            }
        }

        function ejecutarEliminacion(id, nombre) {
            $.ajax({
                url: `/marcas/${id}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('success', response.message);
                        // Recargar la página después de 1.5 segundos
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al eliminar la marca.');
                    }
                },
                error: function(xhr) {
                    let message = 'Error al eliminar la marca.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    mostrarAlerta('error', message);
                }
            });
        }

        function exportarMarcas() {
            const btnExportar = document.querySelector('button[onclick="exportarMarcas()"]');
            const originalText = btnExportar.innerHTML;
            btnExportar.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Generando PDF...';
            btnExportar.disabled = true;
            
            // Crear un enlace temporal para descargar el PDF
            const link = document.createElement('a');
            link.href = '/marcas-exportar';
            link.download = `marcas_${new Date().toISOString().split('T')[0]}.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Mostrar mensaje de éxito
            setTimeout(() => {
                mostrarAlerta('success', '¡PDF generado y descargado exitosamente!');
                btnExportar.innerHTML = originalText;
                btnExportar.disabled = false;
            }, 1000);
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

        // Animaciones de las cards
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar meta tag para CSRF si no existe
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = '{{ csrf_token() }}';
                document.head.appendChild(meta);
            }

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
                        // Ocultar mensajes de error
                        form.querySelectorAll('.invalid-feedback').forEach(el => {
                            el.style.display = 'none';
                        });
                    });
                });
            });
        });
        
        // Función para confirmar cerrar sesión - NUEVA VERSION CON MODAL MODERNO
        function mostrarModalCerrarSesion() {
            const modal = new bootstrap.Modal(document.getElementById('modalCerrarSesion'));
            modal.show();
        }

        // Función para ejecutar el cierre de sesión
        function ejecutarCerrarSesion() {
            // Mostrar loading en el botón
            const btnCerrar = document.querySelector('#modalCerrarSesion .btn-danger');
            const btnCancelar = document.querySelector('#modalCerrarSesion .btn-outline-secondary');
            const progress = document.getElementById('logout-progress');
            
            const originalText = btnCerrar.innerHTML;
            btnCerrar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Cerrando sesión...';
            btnCerrar.disabled = true;
            btnCancelar.disabled = true;
            progress.style.display = 'block';

            // Enviar formulario después de 1.5 segundos para que se vea el loading
            setTimeout(() => {
                document.getElementById('logout-form-marcas').submit();
            }, 1500);
        }
        
        // Función legacy para compatibilidad
        function confirmarCerrarSesion() {
            mostrarModalCerrarSesion();
        }
    </script>
</body>
</html>
