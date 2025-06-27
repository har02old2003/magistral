<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Farmacia Magistral</title>
    
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
                        <a class="nav-link active" href="/clientes">
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
                        <a class="nav-link" href="/usuarios">
                            <i class="bi bi-person-gear me-2"></i> Usuarios
                        </a>
                    </li>
                    @endif
                    
                    <!-- Separador para cerrar sesión -->
                    <li class="nav-item mt-4" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-clientes" style="display: none;">
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
                            <i class="bi bi-people me-3"></i>Clientes
                        </h1>
                        <p class="mb-0 opacity-75" style="font-size: 1.2rem;">Gestión de clientes y directorio</p>
                    </div>

                    @php
                        try {
                            $clientes = \App\Models\Cliente::orderBy('nombres')->get();
                            $totalClientes = $clientes->count();
                            $clientesActivos = $clientes->where('activo', true)->count();
                            $clientesConCompras = \App\Models\Cliente::has('ventas')->count();
                            $clientesVip = $clientes->take(5);
                        } catch(\Exception $e) {
                            $clientes = collect([
                                (object)['id' => 1, 'nombres' => 'Juan Carlos', 'apellidos' => 'Pérez García', 'dni' => '12345678', 'telefono' => '987654321', 'email' => 'juan@email.com', 'activo' => true],
                                (object)['id' => 2, 'nombres' => 'María', 'apellidos' => 'López Torres', 'dni' => '87654321', 'telefono' => '123456789', 'email' => null, 'activo' => true],
                                (object)['id' => 3, 'nombres' => 'Cliente', 'apellidos' => 'General', 'dni' => null, 'telefono' => null, 'email' => null, 'activo' => true]
                            ]);
                            $totalClientes = 3;
                            $clientesActivos = 3;
                            $clientesConCompras = 1;
                            $clientesVip = $clientes->take(3);
                        }
                    @endphp

                    <!-- Estadísticas de Clientes -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card primary">
                                <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="text-primary" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $totalClientes }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Clientes</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card success">
                                <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="text-success" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $clientesActivos }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Activos</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card info">
                                <div class="text-info" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-cart-check"></i>
                                </div>
                                <div class="text-info" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $clientesConCompras }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Con Compras</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card warning">
                                <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-star"></i>
                                </div>
                                <div class="text-warning" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $clientesVip->count() }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Clientes VIP</div>
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
                                        <input type="text" class="form-control" placeholder="Buscar por nombre, DNI o teléfono..." id="searchInput" style="border-radius: 12px; padding: 0.75rem;">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <select class="form-select" id="estadoFilter" style="border-radius: 12px; padding: 0.75rem;">
                                            <option value="">Todos los estados</option>
                                            <option value="activo">Activos</option>
                                            <option value="inactivo">Inactivos</option>
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
                                    <button class="btn btn-success-modern btn-modern btn-sm" data-bs-toggle="modal" data-bs-target="#nuevoClienteModal">
                                        <i class="bi bi-person-plus me-1"></i> Nuevo Cliente
                                    </button>
                                    @endif
                                    <button class="btn btn-primary-modern btn-modern btn-sm" onclick="exportarClientes()">
                                        <i class="bi bi-download me-1"></i> Exportar Lista
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Clientes -->
                    @if($totalClientes > 0)
                    <div class="modern-table">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>DNI</th>
                                    <th>Contacto</th>
                                    <th>Estado</th>
                                    <th>Compras</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clientes as $cliente)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $cliente->nombres ?? 'Sin nombre' }}</strong>
                                            @if($cliente->apellidos)
                                            <br><small class="text-muted">{{ $cliente->apellidos }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($cliente->documento)
                                            <span class="badge bg-secondary badge-modern">{{ $cliente->tipo_documento ?? 'DOC' }}: {{ $cliente->documento }}</span>
                                        @else
                                            <span class="text-muted">Sin documento</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            @if($cliente->telefono)
                                                <i class="bi bi-telephone me-1"></i>{{ $cliente->telefono }}<br>
                                            @endif
                                            @if($cliente->email)
                                                <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $cliente->email }}</small>
                                            @endif
                                            @if(!$cliente->telefono && !$cliente->email)
                                                <span class="text-muted">Sin contacto</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($cliente->activo)
                                            <span class="badge bg-success badge-modern">Activo</span>
                                        @else
                                            <span class="badge bg-danger badge-modern">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info badge-modern">0 compras</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" title="Ver detalles" onclick="verCliente({{ $cliente->id }}, '{{ $cliente->nombres }}', '{{ $cliente->apellidos }}', '{{ $cliente->documento }}', '{{ $cliente->tipo_documento }}', '{{ $cliente->telefono }}', '{{ $cliente->email }}', '{{ $cliente->direccion }}', {{ $cliente->activo ? 'true' : 'false' }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @if(auth()->user()->role === 'administrador')
                                            <button type="button" class="btn btn-outline-warning btn-sm" title="Editar" onclick="editarCliente({{ $cliente->id }}, '{{ $cliente->nombres }}', '{{ $cliente->apellidos }}', '{{ $cliente->documento }}', '{{ $cliente->tipo_documento }}', '{{ $cliente->telefono }}', '{{ $cliente->email }}', '{{ $cliente->direccion }}', {{ $cliente->activo ? 'true' : 'false' }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="eliminarCliente({{ $cliente->id }}, '{{ $cliente->nombres }} {{ $cliente->apellidos }}')">
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
                    <!-- Estado sin clientes -->
                    <div class="modern-card">
                        <div class="no-data-state">
                            <i class="bi bi-people"></i>
                            <h4>No hay clientes registrados</h4>
                            <p class="mb-4">Comienza agregando tu primer cliente</p>
                            @if(auth()->user()->role === 'administrador')
                            <button class="btn btn-success-modern btn-modern btn-lg" data-bs-toggle="modal" data-bs-target="#nuevoClienteModal">
                                <i class="bi bi-person-plus me-2"></i>
                                Agregar Primer Cliente
                            </button>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Nuevo Cliente -->
    <div class="modal fade" id="nuevoClienteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus me-2"></i>Nuevo Cliente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formNuevoCliente">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombres" class="form-label fw-bold">Nombres *</label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" required placeholder="Nombres del cliente" maxlength="100">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="apellidos" class="form-label fw-bold">Apellidos *</label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" required placeholder="Apellidos del cliente" maxlength="100">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tipo_documento" class="form-label fw-bold">Tipo Documento *</label>
                                    <select class="form-select" id="tipo_documento" name="tipo_documento" required>
                                        <option value="">Seleccionar</option>
                                        <option value="DNI">DNI</option>
                                        <option value="CE">CE</option>
                                        <option value="RUC">RUC</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="documento" class="form-label fw-bold">Número Documento *</label>
                                    <input type="text" class="form-control" id="documento" name="documento" required placeholder="12345678" maxlength="11">
                                    <div class="invalid-feedback"></div>
                                    <small class="text-muted" id="doc_help">Ingrese 8 dígitos para DNI</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label fw-bold">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="987654321" maxlength="15">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="cliente@email.com">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_nacimiento" class="form-label fw-bold">Fecha Nacimiento</label>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label fw-bold">Dirección</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2" placeholder="Dirección completa del cliente..." maxlength="255"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" checked>
                            <label class="form-check-label fw-bold" for="activo">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                Cliente activo
                            </label>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Nota:</strong> Los campos marcados con * son obligatorios.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success-modern btn-modern" onclick="crearCliente()">
                            <i class="bi bi-check-circle me-2"></i>Crear Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ver Cliente -->
    <div class="modal fade" id="verClienteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-eye me-2"></i>Detalles del Cliente
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
                                        <td><span id="ver_cliente_id" class="badge bg-secondary"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nombres:</td>
                                        <td><span id="ver_cliente_nombres" class="h6"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Apellidos:</td>
                                        <td><span id="ver_cliente_apellidos"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Documento:</td>
                                        <td><span id="ver_cliente_documento"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">Teléfono:</td>
                                        <td><span id="ver_cliente_telefono"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Email:</td>
                                        <td><span id="ver_cliente_email"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Estado:</td>
                                        <td><span id="ver_cliente_estado"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Compras:</td>
                                        <td><span class="badge bg-info">0 compras realizadas</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="fw-bold">Dirección:</h6>
                            <p id="ver_cliente_direccion" class="text-muted p-3 bg-light rounded"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->role === 'administrador')
                    <button type="button" class="btn btn-warning-modern btn-modern" onclick="editarClienteDesdeModal()">
                        <i class="bi bi-pencil me-2"></i>Editar
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Cliente -->
    <div class="modal fade" id="editarClienteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil me-2"></i>Editar Cliente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditarCliente">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_cliente_id" name="cliente_id">
                    <div class="modal-body">
                        <div class="alert alert-info" id="edit_cliente_info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Editando cliente:</strong> <span id="edit_cliente_nombre_info"></span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_nombres" class="form-label fw-bold">Nombres *</label>
                                    <input type="text" class="form-control" id="edit_nombres" name="nombres" required maxlength="100">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_apellidos" class="form-label fw-bold">Apellidos *</label>
                                    <input type="text" class="form-control" id="edit_apellidos" name="apellidos" required maxlength="100">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_tipo_documento" class="form-label fw-bold">Tipo Documento *</label>
                                    <select class="form-select" id="edit_tipo_documento" name="tipo_documento" required>
                                        <option value="">Seleccionar</option>
                                        <option value="DNI">DNI</option>
                                        <option value="CE">CE</option>
                                        <option value="RUC">RUC</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_documento" class="form-label fw-bold">Número Documento *</label>
                                    <input type="text" class="form-control" id="edit_documento" name="documento" required maxlength="11">
                                    <div class="invalid-feedback"></div>
                                    <small class="text-muted" id="edit_doc_help">Validación automática</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_telefono" class="form-label fw-bold">Teléfono</label>
                                    <input type="text" class="form-control" id="edit_telefono" name="telefono" maxlength="15">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_email" class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" id="edit_email" name="email">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_fecha_nacimiento" class="form-label fw-bold">Fecha Nacimiento</label>
                                    <input type="date" class="form-control" id="edit_fecha_nacimiento" name="fecha_nacimiento">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_direccion" class="form-label fw-bold">Dirección</label>
                            <textarea class="form-control" id="edit_direccion" name="direccion" rows="2" maxlength="255"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="edit_activo" name="activo" value="1">
                            <label class="form-check-label fw-bold" for="edit_activo">
                                Cliente activo
                            </label>
                        </div>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Importante:</strong> Los cambios afectarán el historial de ventas del cliente.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-warning-modern btn-modern" onclick="actualizarClienteReal()">
                            <i class="bi bi-check-circle me-2"></i>Actualizar Cliente
                        </button>
                    </div>
                </form>
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
            filtrarTabla();
        });

        document.getElementById('estadoFilter').addEventListener('change', function() {
            filtrarTabla();
        });

        function filtrarTabla() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const estadoFilter = document.getElementById('estadoFilter').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const texto = row.textContent.toLowerCase();
                const estadoBadge = row.querySelector('.badge-modern:last-of-type').textContent.toLowerCase();
                
                const coincideTexto = texto.includes(searchTerm);
                const coincideEstado = estadoFilter === '' || 
                    (estadoFilter === 'activo' && estadoBadge.includes('activo')) ||
                    (estadoFilter === 'inactivo' && estadoBadge.includes('inactivo'));
                
                row.style.display = coincideTexto && coincideEstado ? '' : 'none';
            });
        }

        function limpiarFiltros() {
            document.getElementById('searchInput').value = '';
            document.getElementById('estadoFilter').value = '';
            filtrarTabla();
        }

        // Variable global para almacenar el ID del cliente en edición
        let clienteEditandoId = null;

        // Función para ver cliente
        function verCliente(id, nombres, apellidos, documento, tipo_documento, telefono, email, direccion, activo) {
            document.getElementById('ver_cliente_id').textContent = id;
            document.getElementById('ver_cliente_nombres').textContent = nombres || 'Sin nombres';
            document.getElementById('ver_cliente_apellidos').textContent = apellidos || 'Sin apellidos';
            document.getElementById('ver_cliente_documento').textContent = documento ? `${tipo_documento || 'DOC'}: ${documento}` : 'Sin documento';
            document.getElementById('ver_cliente_telefono').textContent = telefono || 'Sin teléfono';
            document.getElementById('ver_cliente_email').textContent = email || 'Sin email';
            document.getElementById('ver_cliente_direccion').textContent = direccion || 'Sin dirección';
            
            const estadoBadge = document.getElementById('ver_cliente_estado');
            if (activo) {
                estadoBadge.className = 'badge bg-success';
                estadoBadge.innerHTML = '<i class="bi bi-check-circle me-1"></i>Activo';
            } else {
                estadoBadge.className = 'badge bg-danger';
                estadoBadge.innerHTML = '<i class="bi bi-x-circle me-1"></i>Inactivo';
            }
            
            new bootstrap.Modal(document.getElementById('verClienteModal')).show();
        }

        // Función para editar cliente
        function editarCliente(id, nombres, apellidos, documento, tipo_documento, telefono, email, direccion, activo) {
            console.log('Editando cliente:', {id, nombres, apellidos, documento, tipo_documento, telefono, email, direccion, activo});
            
            clienteEditandoId = id;
            document.getElementById('edit_cliente_id').value = id;
            document.getElementById('edit_nombres').value = nombres || '';
            document.getElementById('edit_apellidos').value = apellidos || '';
            document.getElementById('edit_documento').value = documento || '';
            document.getElementById('edit_tipo_documento').value = tipo_documento || '';
            document.getElementById('edit_telefono').value = telefono || '';
            document.getElementById('edit_email').value = email || '';
            document.getElementById('edit_direccion').value = direccion || '';
            document.getElementById('edit_activo').checked = activo;
            document.getElementById('edit_cliente_nombre_info').textContent = `${nombres || ''} ${apellidos || ''}`.trim();
            
            console.log('Campo activo configurado a:', activo, 'Checkbox checked:', document.getElementById('edit_activo').checked);
            
            // Limpiar validaciones anteriores
            document.querySelectorAll('#editarClienteModal .is-valid, #editarClienteModal .is-invalid').forEach(el => {
                el.classList.remove('is-valid', 'is-invalid');
            });
            
            new bootstrap.Modal(document.getElementById('editarClienteModal')).show();
        }

        function editarClienteDesdeModal() {
            // Obtener datos actuales del modal de ver
            const id = document.getElementById('ver_cliente_id').textContent;
            const nombres = document.getElementById('ver_cliente_nombres').textContent;
            const apellidos = document.getElementById('ver_cliente_apellidos').textContent;
            const documento_info = document.getElementById('ver_cliente_documento').textContent;
            const [tipo_documento, documento] = documento_info.includes(':') ? documento_info.split(': ') : ['', documento_info];
            const telefono = document.getElementById('ver_cliente_telefono').textContent;
            const email = document.getElementById('ver_cliente_email').textContent;
            const direccion = document.getElementById('ver_cliente_direccion').textContent;
            const activo = document.getElementById('ver_cliente_estado').textContent.includes('Activo');
            
            // Cerrar modal de ver y abrir modal de editar
            bootstrap.Modal.getInstance(document.getElementById('verClienteModal')).hide();
            setTimeout(() => {
                editarCliente(id, nombres, apellidos, documento, tipo_documento, telefono, email, direccion, activo);
            }, 300);
        }

        // Función para crear cliente con AJAX
        function crearCliente() {
            const nombres = document.getElementById('nombres').value.trim();
            const apellidos = document.getElementById('apellidos').value.trim();
            const tipo_documento = document.getElementById('tipo_documento').value;
            const documento = document.getElementById('documento').value.trim();
            const activo = document.getElementById('activo').checked;
            
            console.log('Creando cliente con activo:', activo);
            
            if (!nombres || !apellidos || !tipo_documento || !documento) {
                mostrarAlerta('error', 'Los campos Nombres, Apellidos, Tipo de Documento y Número de Documento son obligatorios.');
                return;
            }

            // Mostrar loading
            const btnCrear = document.querySelector('#nuevoClienteModal .btn-success-modern');
            const originalText = btnCrear.innerHTML;
            btnCrear.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creando...';
            btnCrear.disabled = true;

            $.ajax({
                url: '/clientes',
                method: 'POST',
                data: {
                    nombres: nombres,
                    apellidos: apellidos,
                    tipo_documento: tipo_documento,
                    documento: documento,
                    telefono: document.getElementById('telefono').value.trim(),
                    email: document.getElementById('email').value.trim(),
                    direccion: document.getElementById('direccion').value.trim(),
                    fecha_nacimiento: document.getElementById('fecha_nacimiento').value,
                    activo: activo ? 1 : 0,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('success', response.message);
                        bootstrap.Modal.getInstance(document.getElementById('nuevoClienteModal')).hide();
                        // Recargar la página después de 1.5 segundos
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al crear el cliente.');
                    }
                },
                error: function(xhr) {
                    let message = 'Error al crear el cliente.';
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

        // Función para actualizar cliente con AJAX
        function actualizarClienteReal() {
            if (!clienteEditandoId) {
                mostrarAlerta('error', 'Error: ID de cliente no válido.');
                return;
            }

            const nombres = document.getElementById('edit_nombres').value.trim();
            const apellidos = document.getElementById('edit_apellidos').value.trim();
            const tipo_documento = document.getElementById('edit_tipo_documento').value;
            const documento = document.getElementById('edit_documento').value.trim();
            const activo = document.getElementById('edit_activo').checked;
            
            console.log('Actualizando cliente:', {clienteEditandoId, nombres, apellidos, activo});
            
            if (!nombres || !apellidos || !tipo_documento || !documento) {
                mostrarAlerta('error', 'Los campos Nombres, Apellidos, Tipo de Documento y Número de Documento son obligatorios.');
                return;
            }

            // Mostrar loading
            const btnActualizar = document.querySelector('#editarClienteModal .btn-warning-modern');
            const originalText = btnActualizar.innerHTML;
            btnActualizar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Actualizando...';
            btnActualizar.disabled = true;

            $.ajax({
                url: `/clientes/${clienteEditandoId}`,
                method: 'PUT',
                data: {
                    nombres: nombres,
                    apellidos: apellidos,
                    tipo_documento: tipo_documento,
                    documento: documento,
                    telefono: document.getElementById('edit_telefono').value.trim(),
                    email: document.getElementById('edit_email').value.trim(),
                    direccion: document.getElementById('edit_direccion').value.trim(),
                    fecha_nacimiento: document.getElementById('edit_fecha_nacimiento').value,
                    activo: activo ? 1 : 0,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'PUT'
                },
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('success', response.message);
                        bootstrap.Modal.getInstance(document.getElementById('editarClienteModal')).hide();
                        // Recargar la página después de 1.5 segundos
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al actualizar el cliente.');
                    }
                },
                error: function(xhr) {
                    let message = 'Error al actualizar el cliente.';
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

        // Función para eliminar cliente con confirmación
        function eliminarCliente(id, nombre) {
            if (confirm(`¿Está seguro de eliminar al cliente "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
                $.ajax({
                    url: `/clientes/${id}`,
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
                            mostrarAlerta('error', response.message || 'Error al eliminar el cliente.');
                        }
                    },
                    error: function(xhr) {
                        let message = 'Error al eliminar el cliente.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        mostrarAlerta('error', message);
                    }
                });
            }
        }

        // Función para exportar clientes a PDF
        function exportarClientes() {
            const btnExportar = document.querySelector('button[onclick="exportarClientes()"]');
            const originalText = btnExportar.innerHTML;
            btnExportar.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Generando PDF...';
            btnExportar.disabled = true;
            
            // Crear un enlace temporal para descargar el PDF
            const link = document.createElement('a');
            link.href = '/clientes-exportar';
            link.download = `clientes_${new Date().toISOString().split('T')[0]}.pdf`;
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

        // Validaciones en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar meta tag para CSRF si no existe
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = '{{ csrf_token() }}';
                document.head.appendChild(meta);
            }

            // Validación de campos de texto (solo letras y espacios)
            const textInputs = document.querySelectorAll('#nombres, #apellidos, #edit_nombres, #edit_apellidos');
            textInputs.forEach(input => {
                input.addEventListener('input', function() {
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

            // Validación de documento según tipo
            const tipoDocInputs = document.querySelectorAll('#tipo_documento, #edit_tipo_documento');
            const docInputs = document.querySelectorAll('#documento, #edit_documento');
            
            tipoDocInputs.forEach((select, index) => {
                const docInput = docInputs[index];
                const helpText = document.querySelector(index === 0 ? '#doc_help' : '#edit_doc_help');
                
                select.addEventListener('change', function() {
                    const tipo = this.value;
                    if (tipo === 'DNI') {
                        docInput.maxLength = 8;
                        helpText.textContent = 'Ingrese 8 dígitos para DNI';
                    } else if (tipo === 'CE') {
                        docInput.maxLength = 9;
                        helpText.textContent = 'Ingrese 9 dígitos para CE';
                    } else if (tipo === 'RUC') {
                        docInput.maxLength = 11;
                        helpText.textContent = 'Ingrese 11 dígitos para RUC';
                    }
                    docInput.value = '';
                });
            });

            // Validación de documentos (solo números)
            docInputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '');
                    
                    const tipo = this.closest('.modal').querySelector('select[name="tipo_documento"]').value;
                    const maxLength = tipo === 'DNI' ? 8 : tipo === 'CE' ? 9 : tipo === 'RUC' ? 11 : 11;
                    
                    if (this.value.length === maxLength) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else if (this.value.length > 0) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else {
                        this.classList.remove('is-invalid', 'is-valid');
                    }
                });
            });

            // Validación de teléfono (solo números)
            const telefonoInputs = document.querySelectorAll('#telefono, #edit_telefono');
            telefonoInputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '');
                    
                    if (this.value.length >= 7 && this.value.length <= 15) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else if (this.value.length > 0) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else {
                        this.classList.remove('is-invalid', 'is-valid');
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
                    clienteEditandoId = null;
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
            const btnCancelar = document.querySelector('#modalCerrarSesion .btn-outline-secondary');
            const progress = document.getElementById('logout-progress');
            
            const originalText = btnCerrar.innerHTML;
            btnCerrar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Cerrando sesión...';
            btnCerrar.disabled = true;
            btnCancelar.disabled = true;
            progress.style.display = 'block';

            // Enviar formulario después de 1.5 segundos para que se vea el loading
            setTimeout(() => {
                document.getElementById('logout-form-clientes').submit();
            }, 1500);
        }

        // Función legacy para compatibilidad
        function confirmarCerrarSesion() {
            mostrarModalCerrarSesion();
        }
    </script>
</body>
</html> 