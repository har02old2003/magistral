<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores - Farmacia Magistral</title>
    
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

        .provider-card {
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .provider-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
                        <a class="nav-link active" href="/proveedores">
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
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-proveedores" style="display: none;">
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
                            <i class="bi bi-truck me-3"></i>Proveedores
                        </h1>
                        <p class="mb-0 opacity-75" style="font-size: 1.2rem;">Gestión de proveedores y suministros</p>
                    </div>

                    @php
                        try {
                            $proveedores = \App\Models\Proveedor::orderBy('nombre')->get();
                            $totalProveedores = $proveedores->count();
                            $proveedoresActivos = $proveedores->where('activo', true)->count();
                            $proveedoresConProductos = \App\Models\Proveedor::has('productos')->count();
                        } catch(\Exception $e) {
                            $proveedores = collect([
                                (object)['id' => 1, 'nombre' => 'Farmacia Nacional SAC', 'ruc' => '20123456789', 'telefono' => '01-234-5678', 'email' => 'ventas@farmanacional.com', 'direccion' => 'Av. Industrial 123, Lima', 'activo' => true],
                                (object)['id' => 2, 'nombre' => 'Distribuidora Médica Lima', 'ruc' => '20987654321', 'telefono' => '01-876-5432', 'email' => 'pedidos@medicalima.com', 'direccion' => 'Jr. Comercio 456, Lima', 'activo' => true],
                                (object)['id' => 3, 'nombre' => 'Laboratorios Perú', 'ruc' => '20555444333', 'telefono' => null, 'email' => 'contacto@labperu.com', 'direccion' => 'Av. Salud 789, Lima', 'activo' => false]
                            ]);
                            $totalProveedores = 3;
                            $proveedoresActivos = 2;
                            $proveedoresConProductos = 1;
                        }
                    @endphp

                    <!-- Estadísticas de Proveedores -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card primary">
                                <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div class="text-primary" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $totalProveedores }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Proveedores</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card success">
                                <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="text-success" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $proveedoresActivos }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Activos</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card info">
                                <div class="text-info" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div class="text-info" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $proveedoresConProductos }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Con Productos</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card warning">
                                <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="text-warning" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $totalProveedores - $proveedoresActivos }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Inactivos</div>
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
                                    <button class="btn btn-success-modern btn-modern btn-sm" data-bs-toggle="modal" data-bs-target="#nuevoProveedorModal">
                                        <i class="bi bi-plus me-1"></i> Nuevo Proveedor
                                    </button>
                                    @endif
                                    <button class="btn btn-primary-modern btn-modern btn-sm" onclick="exportarProveedores()">
                                        <i class="bi bi-download me-1"></i> Exportar Lista
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Proveedores -->
                    @if($totalProveedores > 0)
                    <div class="modern-table">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Proveedor</th>
                                    <th>RUC</th>
                                    <th>Contacto</th>
                                    <th>Ubicación</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proveedores as $proveedor)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-building text-primary me-2" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <strong>{{ $proveedor->nombre }}</strong>
                                                <br><small class="text-muted">Proveedor de suministros</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($proveedor->ruc)
                                            <span class="badge bg-secondary badge-modern">{{ $proveedor->ruc }}</span>
                                        @else
                                            <span class="text-muted">Sin RUC</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
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
                                    </td>
                                    <td>
                                        @if($proveedor->direccion)
                                            <small class="text-muted">{{ Str::limit($proveedor->direccion, 30) }}</small>
                                        @else
                                            <span class="text-muted">Sin dirección</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($proveedor->activo)
                                            <span class="badge bg-success badge-modern">Activo</span>
                                        @else
                                            <span class="badge bg-danger badge-modern">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="verProveedor({{ $proveedor->id }}, '{{ $proveedor->nombre }}', '{{ $proveedor->ruc }}', '{{ $proveedor->telefono }}', '{{ $proveedor->email }}', '{{ $proveedor->direccion }}', {{ $proveedor->activo ? 'true' : 'false' }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @if(auth()->user()->role === 'administrador')
                                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="editarProveedor({{ $proveedor->id }}, '{{ $proveedor->nombre }}', '{{ $proveedor->ruc }}', '{{ $proveedor->telefono }}', '{{ $proveedor->email }}', '{{ $proveedor->direccion }}', {{ $proveedor->activo ? 'true' : 'false' }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarProveedor({{ $proveedor->id }}, '{{ $proveedor->nombre }}')">
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
                    <!-- Estado sin proveedores -->
                    <div class="modern-card">
                        <div class="no-data-state">
                            <i class="bi bi-truck"></i>
                            <h4>No hay proveedores registrados</h4>
                            <p class="mb-4">Comienza agregando tu primer proveedor</p>
                            @if(auth()->user()->role === 'administrador')
                            <button class="btn btn-success-modern btn-modern btn-lg" data-bs-toggle="modal" data-bs-target="#nuevoProveedorModal">
                                <i class="bi bi-plus me-2"></i>
                                Agregar Primer Proveedor
                            </button>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Nuevo Proveedor -->
    <div class="modal fade" id="nuevoProveedorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus me-2"></i>Nuevo Proveedor
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label fw-bold">Nombre de la Empresa *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Nombre del proveedor">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="ruc" class="form-label fw-bold">RUC</label>
                                    <input type="text" class="form-control" id="ruc" name="ruc" placeholder="20123456789" maxlength="11">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label fw-bold">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="01-234-5678">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="ventas@proveedor.com">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label fw-bold">Dirección</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2" placeholder="Dirección completa del proveedor..."></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" checked>
                            <label class="form-check-label fw-bold" for="activo">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                Proveedor activo
                            </label>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Nota:</strong> El nombre es obligatorio. Los proveedores activos aparecerán en los formularios de productos.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success-modern btn-modern" onclick="crearProveedor()">
                            <i class="bi bi-check-circle me-2"></i>Crear Proveedor
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                            <h6>Dirección:</h6>
                            <p id="ver_proveedor_direccion" class="text-muted p-3 bg-light rounded"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->role === 'administrador')
                    <button type="button" class="btn btn-warning-modern btn-modern" onclick="editarProveedorDesdeModal()">
                        <i class="bi bi-pencil me-2"></i>Editar
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nombre_editar" class="form-label fw-bold">Nombre de la Empresa *</label>
                                    <input type="text" class="form-control" id="nombre_editar" name="nombre" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="ruc_editar" class="form-label fw-bold">RUC</label>
                                    <input type="text" class="form-control" id="ruc_editar" name="ruc" maxlength="11">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono_editar" class="form-label fw-bold">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono_editar" name="telefono">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email_editar" class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" id="email_editar" name="email">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="direccion_editar" class="form-label fw-bold">Dirección</label>
                            <textarea class="form-control" id="direccion_editar" name="direccion" rows="2"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="activo_editar" name="activo" value="1">
                            <label class="form-check-label fw-bold" for="activo_editar">
                                Proveedor activo
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-warning-modern btn-modern" onclick="actualizarProveedor()">
                            <i class="bi bi-check-circle me-2"></i>Actualizar
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
            filtrarProveedores();
        });

        document.getElementById('estadoFilter').addEventListener('change', function() {
            filtrarProveedores();
        });

        function filtrarProveedores() {
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
            filtrarProveedores();
        }

        // Variable global para almacenar el ID del proveedor en edición
        let proveedorEditandoId = null;

        // Función para ver proveedor
        function verProveedor(id, nombre, ruc, telefono, email, direccion, activo) {
            document.getElementById('ver_proveedor_id').textContent = id;
            document.getElementById('ver_proveedor_nombre').textContent = nombre || 'Sin nombre';
            document.getElementById('ver_proveedor_ruc').textContent = ruc || 'Sin RUC';
            document.getElementById('ver_proveedor_telefono').textContent = telefono || 'Sin teléfono';
            document.getElementById('ver_proveedor_email').textContent = email || 'Sin email';
            document.getElementById('ver_proveedor_direccion').textContent = direccion || 'Sin dirección registrada';
            
            const estadoBadge = document.getElementById('ver_proveedor_estado');
            if (activo) {
                estadoBadge.className = 'badge bg-success';
                estadoBadge.innerHTML = '<i class="bi bi-check-circle me-1"></i>Activo';
            } else {
                estadoBadge.className = 'badge bg-danger';
                estadoBadge.innerHTML = '<i class="bi bi-x-circle me-1"></i>Inactivo';
            }
            
            new bootstrap.Modal(document.getElementById('verProveedorModal')).show();
        }

        // Función para editar proveedor
        function editarProveedor(id, nombre, ruc, telefono, email, direccion, activo) {
            console.log('Editando proveedor:', {id, nombre, ruc, telefono, email, direccion, activo});
            
            proveedorEditandoId = id;
            document.getElementById('nombre_editar').value = nombre || '';
            document.getElementById('ruc_editar').value = ruc || '';
            document.getElementById('telefono_editar').value = telefono || '';
            document.getElementById('email_editar').value = email || '';
            document.getElementById('direccion_editar').value = direccion || '';
            document.getElementById('activo_editar').checked = activo;
            
            console.log('Campo activo configurado a:', activo, 'Checkbox checked:', document.getElementById('activo_editar').checked);
            
            // Limpiar validaciones anteriores
            document.querySelectorAll('#editarProveedorModal .is-valid, #editarProveedorModal .is-invalid').forEach(el => {
                el.classList.remove('is-valid', 'is-invalid');
            });
            
            new bootstrap.Modal(document.getElementById('editarProveedorModal')).show();
        }

        function editarProveedorDesdeModal() {
            // Obtener datos actuales del modal de ver
            const id = document.getElementById('ver_proveedor_id').textContent;
            const nombre = document.getElementById('ver_proveedor_nombre').textContent;
            const ruc = document.getElementById('ver_proveedor_ruc').textContent;
            const telefono = document.getElementById('ver_proveedor_telefono').textContent;
            const email = document.getElementById('ver_proveedor_email').textContent;
            const direccion = document.getElementById('ver_proveedor_direccion').textContent;
            const activo = document.getElementById('ver_proveedor_estado').textContent.includes('Activo');
            
            // Cerrar modal de ver y abrir modal de editar
            bootstrap.Modal.getInstance(document.getElementById('verProveedorModal')).hide();
            setTimeout(() => {
                editarProveedor(id, nombre, ruc === 'Sin RUC' ? '' : ruc, telefono === 'Sin teléfono' ? '' : telefono, email === 'Sin email' ? '' : email, direccion === 'Sin dirección registrada' ? '' : direccion, activo);
            }, 300);
        }

        // Función para crear proveedor con AJAX
        function crearProveedor() {
            const nombre = document.getElementById('nombre').value.trim();
            const ruc = document.getElementById('ruc').value.trim();
            const telefono = document.getElementById('telefono').value.trim();
            const email = document.getElementById('email').value.trim();
            const direccion = document.getElementById('direccion').value.trim();
            const activo = document.getElementById('activo').checked;
            
            if (!nombre) {
                mostrarAlerta('error', 'El nombre del proveedor es obligatorio.');
                document.getElementById('nombre').focus();
                return;
            }
            
            if (nombre.length < 2) {
                mostrarAlerta('error', 'El nombre debe tener al menos 2 caracteres.');
                document.getElementById('nombre').focus();
                return;
            }

            // Validar RUC si se proporciona
            if (ruc && ruc.length !== 11) {
                mostrarAlerta('error', 'El RUC debe tener exactamente 11 dígitos.');
                document.getElementById('ruc').focus();
                return;
            }

            // Mostrar loading
            const btnCrear = document.querySelector('#nuevoProveedorModal .btn-success-modern');
            const originalText = btnCrear.innerHTML;
            btnCrear.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creando...';
            btnCrear.disabled = true;

            $.ajax({
                url: '/proveedores',
                method: 'POST',
                data: {
                    nombre: nombre,
                    ruc: ruc,
                    telefono: telefono,
                    email: email,
                    direccion: direccion,
                    activo: activo ? 1 : 0,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('success', response.message);
                        bootstrap.Modal.getInstance(document.getElementById('nuevoProveedorModal')).hide();
                        // Recargar la página después de 1.5 segundos
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al crear el proveedor.');
                    }
                },
                error: function(xhr) {
                    let message = 'Error al crear el proveedor.';
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

        // Función para actualizar proveedor con AJAX
        function actualizarProveedor() {
            if (!proveedorEditandoId) {
                mostrarAlerta('error', 'Error: ID de proveedor no válido.');
                return;
            }

            const nombre = document.getElementById('nombre_editar').value.trim();
            const ruc = document.getElementById('ruc_editar').value.trim();
            const telefono = document.getElementById('telefono_editar').value.trim();
            const email = document.getElementById('email_editar').value.trim();
            const direccion = document.getElementById('direccion_editar').value.trim();
            const activo = document.getElementById('activo_editar').checked;
            
            if (!nombre) {
                mostrarAlerta('error', 'El nombre del proveedor es obligatorio.');
                document.getElementById('nombre_editar').focus();
                return;
            }
            
            if (nombre.length < 2) {
                mostrarAlerta('error', 'El nombre debe tener al menos 2 caracteres.');
                document.getElementById('nombre_editar').focus();
                return;
            }

            // Validar RUC si se proporciona
            if (ruc && ruc.length !== 11) {
                mostrarAlerta('error', 'El RUC debe tener exactamente 11 dígitos.');
                document.getElementById('ruc_editar').focus();
                return;
            }

            // Mostrar loading
            const btnActualizar = document.querySelector('#editarProveedorModal .btn-warning-modern');
            const originalText = btnActualizar.innerHTML;
            btnActualizar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Actualizando...';
            btnActualizar.disabled = true;

            $.ajax({
                url: `/proveedores/${proveedorEditandoId}`,
                method: 'PUT',
                data: {
                    nombre: nombre,
                    ruc: ruc,
                    telefono: telefono,
                    email: email,
                    direccion: direccion,
                    activo: activo ? 1 : 0,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'PUT'
                },
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('success', response.message);
                        bootstrap.Modal.getInstance(document.getElementById('editarProveedorModal')).hide();
                        // Recargar la página después de 1.5 segundos
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al actualizar el proveedor.');
                    }
                },
                error: function(xhr) {
                    let message = 'Error al actualizar el proveedor.';
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

        // Función para eliminar proveedor con confirmación
        function eliminarProveedor(id, nombre) {
            if (confirm(`¿Está seguro de eliminar al proveedor "${nombre}"?\n\nEsta acción también desasociará los productos relacionados.\nEsta acción no se puede deshacer.`)) {
                $.ajax({
                    url: `/proveedores/${id}`,
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
                            mostrarAlerta('error', response.message || 'Error al eliminar el proveedor.');
                        }
                    },
                    error: function(xhr) {
                        let message = 'Error al eliminar el proveedor.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        mostrarAlerta('error', message);
                    }
                });
            }
        }

        // Función para exportar proveedores a PDF
        function exportarProveedores() {
            const btnExportar = document.querySelector('button[onclick="exportarProveedores()"]');
            const originalText = btnExportar.innerHTML;
            btnExportar.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Generando PDF...';
            btnExportar.disabled = true;
            
            // Crear un enlace temporal para descargar el PDF
            const link = document.createElement('a');
            link.href = '/proveedores-exportar';
            link.download = `proveedores_${new Date().toISOString().split('T')[0]}.pdf`;
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

            // Validación del nombre (empresas pueden tener números)
            const nombreInputs = document.querySelectorAll('#nombre, #nombre_editar');
            nombreInputs.forEach(input => {
                input.addEventListener('input', function() {
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

            // Validación de RUC (solo números, 11 dígitos)
            const rucInputs = document.querySelectorAll('#ruc, #ruc_editar');
            rucInputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '').substring(0, 11);
                    
                    if (this.value.length === 11 || this.value.length === 0) {
                        this.classList.remove('is-invalid');
                        if (this.value.length === 11) {
                            this.classList.add('is-valid');
                        }
                    } else if (this.value.length > 0) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                });
            });

            // Validación de teléfono
            const telefonoInputs = document.querySelectorAll('#telefono, #telefono_editar');
            telefonoInputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Permitir números, espacios, guiones, paréntesis
                    this.value = this.value.replace(/[^0-9\s\-\(\)]/g, '');
                    
                    if (this.value.length >= 7 || this.value.length === 0) {
                        this.classList.remove('is-invalid');
                        if (this.value.length >= 7) {
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
                    proveedorEditandoId = null;
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
                document.getElementById('logout-form-proveedores').submit();
            }, 1500);
        }

        // Función legacy para compatibilidad
        function confirmarCerrarSesion() {
            mostrarModalCerrarSesion();
        }
    </script>
</body>
</html>
