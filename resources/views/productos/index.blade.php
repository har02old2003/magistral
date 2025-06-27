<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Farmacia Magistral</title>
    
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
        .stat-card.danger::before { background: var(--danger-gradient); }
        
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
        
        .pulse-warning {
            animation: pulseWarning 2s infinite;
        }
        
        @keyframes pulseWarning {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
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
                        <a class="nav-link active" href="/productos">
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
                        <a class="nav-link" href="/usuarios">
                            <i class="bi bi-person-gear me-2"></i> Usuarios
                        </a>
                    </li>
                    @endif
                    
                    <!-- Separador para cerrar sesión -->
                    <li class="nav-item mt-4" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-productos" style="display: none;">
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
                            <i class="bi bi-capsule me-3"></i>Productos
                        </h1>
                        <p class="mb-0 opacity-75" style="font-size: 1.2rem;">Gestión de inventario farmacéutico</p>
                    </div>

                    @php
                        try {
                            $productos = \App\Models\Producto::with(['categoria', 'marca'])->get();
                            $totalProductos = $productos->count();
                            $productosDisponibles = $productos->where('stock_actual', '>', 10)->count();
                            $productosStockBajo = $productos->where('stock_actual', '<=', 10)->where('stock_actual', '>', 0)->count();
                            $productosAgotados = $productos->where('stock_actual', 0)->count();
                        } catch(\Exception $e) {
                            $productos = collect([
                                (object)['id' => 1, 'codigo' => 'MED001', 'nombre' => 'Paracetamol 500mg', 'categoria' => (object)['nombre' => 'Analgésicos'], 'marca' => (object)['nombre' => 'Bayer'], 'stock_actual' => 100, 'precio_venta' => 25.00, 'precio_compra' => 15.00, 'activo' => true],
                                (object)['id' => 2, 'codigo' => 'MED002', 'nombre' => 'Ibuprofeno 400mg', 'categoria' => (object)['nombre' => 'Analgésicos'], 'marca' => (object)['nombre' => 'Bayer'], 'stock_actual' => 5, 'precio_venta' => 35.00, 'precio_compra' => 20.00, 'activo' => true],
                                (object)['id' => 3, 'codigo' => 'MED003', 'nombre' => 'Aspirina 100mg', 'categoria' => (object)['nombre' => 'Analgésicos'], 'marca' => (object)['nombre' => 'Bayer'], 'stock_actual' => 0, 'precio_venta' => 20.00, 'precio_compra' => 12.00, 'activo' => true]
                            ]);
                            $totalProductos = 3;
                            $productosDisponibles = 1;
                            $productosStockBajo = 1;
                            $productosAgotados = 1;
                        }
                    @endphp

                    <!-- Estadísticas de Productos -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card primary">
                                <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-capsule"></i>
                                </div>
                                <div class="text-primary" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $totalProductos }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Productos</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card success">
                                <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="text-success" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $productosDisponibles }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Disponibles</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card warning @if($productosStockBajo > 0) pulse-warning @endif">
                                <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="text-warning" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $productosStockBajo }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Stock Bajo</div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card danger">
                                <div class="text-danger" style="font-size: 3rem; margin-bottom: 1rem;">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="text-danger" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $productosAgotados }}</div>
                                <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Agotados</div>
                            </div>
                        </div>
                    </div>

                    <!-- Alerta de Stock Bajo -->
                    @if($productosStockBajo > 0 || $productosAgotados > 0)
                    <div class="alert alert-warning" style="border-radius: 15px; border: none; padding: 1.5rem; box-shadow: 0 5px 15px rgba(0,0,0,0.08); margin-bottom: 2rem;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 2rem;"></i>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading mb-2">¡Atención! Productos que necesitan reabastecimiento</h5>
                                <p class="mb-2">
                                    @if($productosStockBajo > 0)
                                        <strong>{{ $productosStockBajo }}</strong> productos con stock bajo
                                    @endif
                                    @if($productosStockBajo > 0 && $productosAgotados > 0)
                                        y 
                                    @endif
                                    @if($productosAgotados > 0)
                                        <strong>{{ $productosAgotados }}</strong> productos agotados
                                    @endif
                                </p>
                                <button class="btn btn-warning-modern btn-modern btn-sm" data-bs-toggle="modal" data-bs-target="#stockCriticoModal">
                                    <i class="bi bi-eye me-1"></i> Ver productos críticos
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

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
                                        <input type="text" class="form-control" placeholder="Buscar por nombre o código..." id="searchInput" style="border-radius: 12px; padding: 0.75rem;">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <select class="form-select" style="border-radius: 12px; padding: 0.75rem;">
                                            <option>Todas las categorías</option>
                                            <option>Analgésicos</option>
                                            <option>Antibióticos</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <select class="form-select" style="border-radius: 12px; padding: 0.75rem;">
                                            <option>Todos los estados</option>
                                            <option>Disponibles</option>
                                            <option>Stock bajo</option>
                                            <option>Agotados</option>
                                        </select>
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
                                    <button class="btn btn-success-modern btn-modern btn-sm" data-bs-toggle="modal" data-bs-target="#nuevoProductoModal">
                                        <i class="bi bi-plus-circle me-1"></i> Nuevo Producto
                                    </button>
                                    <button class="btn btn-info-modern btn-modern btn-sm" onclick="exportarLista()">
                                        <i class="bi bi-download me-1"></i> Exportar Lista
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Productos -->
                    <div class="modern-table">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Stock</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos as $producto)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary badge-modern">{{ $producto->codigo }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $producto->nombre }}</strong><br>
                                            <small class="text-muted">{{ $producto->marca->nombre ?? 'Sin marca' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info badge-modern">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</span>
                                    </td>
                                    <td>
                                        @if($producto->stock_actual > 10)
                                            <span class="fw-bold text-success">{{ $producto->stock_actual }} unidades</span>
                                        @elseif($producto->stock_actual > 0)
                                            <span class="fw-bold text-warning">{{ $producto->stock_actual }} unidades</span>
                                        @else
                                            <span class="fw-bold text-danger">{{ $producto->stock_actual }} unidades</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-success">S/ {{ number_format($producto->precio_venta, 2) }}</strong><br>
                                            <small class="text-muted">Compra: S/ {{ number_format($producto->precio_compra, 2) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($producto->stock_actual > 10)
                                            <span class="badge bg-success badge-modern">Disponible</span>
                                        @elseif($producto->stock_actual > 0)
                                            <span class="badge bg-warning badge-modern">Stock Bajo</span>
                                        @else
                                            <span class="badge bg-danger badge-modern">Agotado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" title="Ver detalles" onclick="verProducto({{ $producto->id }}, '{{ $producto->codigo }}', '{{ $producto->nombre }}', '{{ $producto->categoria->nombre ?? 'Sin categoría' }}', '{{ $producto->marca->nombre ?? 'Sin marca' }}', {{ $producto->stock_actual }}, {{ $producto->precio_venta }}, {{ $producto->precio_compra }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm" title="Editar" onclick="editarProducto({{ $producto->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            @if(auth()->user()->role === 'administrador')
                                            <button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="eliminarProducto({{ $producto->id }}, '{{ $producto->nombre }}')">
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

    <!-- Modal Nuevo Producto -->
    <div class="modal fade" id="nuevoProductoModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Producto Farmacéutico
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formNuevoProducto">
                    @csrf
                    <div class="modal-body">
                        <!-- Información Básica -->
                        <div class="card mb-4" style="border-radius: 15px; border: 1px solid #e9ecef;">
                            <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información Básica</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="codigo" class="form-label fw-bold">Código del Producto *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="codigo" name="codigo" required 
                                                       placeholder="Generando..." maxlength="50" readonly>
                                                <button class="btn btn-outline-primary" type="button" onclick="generarCodigoAutomatico()">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback"></div>
                                            <small class="text-muted">Se genera automáticamente en formato MED0001</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label fw-bold">Nombre del Producto *</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" required 
                                                   placeholder="Ej: Paracetamol 500mg" maxlength="255">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label fw-bold">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" 
                                              placeholder="Descripción detallada del producto..." maxlength="1000"></textarea>
                                    <div class="invalid-feedback"></div>
                                    <small class="text-muted">Máximo 1000 caracteres</small>
                                </div>
                            </div>
                        </div>

                        <!-- Clasificación -->
                        <div class="card mb-4" style="border-radius: 15px; border: 1px solid #e9ecef;">
                            <div class="card-header bg-info text-white" style="border-radius: 15px 15px 0 0;">
                                <h6 class="mb-0"><i class="bi bi-grid me-2"></i>Clasificación</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="categoria_id" class="form-label fw-bold">Categoría *</label>
                                            <select class="form-select" id="categoria_id" name="categoria_id" required>
                                                <option value="">Seleccionar categoría</option>
                                                @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="marca_id" class="form-label fw-bold">Marca *</label>
                                            <select class="form-select" id="marca_id" name="marca_id" required>
                                                <option value="">Seleccionar marca</option>
                                                @foreach($marcas as $marca)
                                                <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="proveedor_id" class="form-label fw-bold">Proveedor *</label>
                                            <select class="form-select" id="proveedor_id" name="proveedor_id" required>
                                                <option value="">Seleccionar proveedor</option>
                                                @foreach($proveedores as $proveedor)
                                                <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Precios e Inventario -->
                        <div class="card mb-4" style="border-radius: 15px; border: 1px solid #e9ecef;">
                            <div class="card-header bg-success text-white" style="border-radius: 15px 15px 0 0;">
                                <h6 class="mb-0"><i class="bi bi-cash me-2"></i>Precios e Inventario</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="precio_compra" class="form-label fw-bold">Precio Compra *</label>
                                            <div class="input-group">
                                                <span class="input-group-text">S/</span>
                                                <input type="number" class="form-control" id="precio_compra" name="precio_compra" 
                                                       required min="0.01" max="99999.99" step="0.01" placeholder="0.00">
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="precio_venta" class="form-label fw-bold">Precio Venta *</label>
                                            <div class="input-group">
                                                <span class="input-group-text">S/</span>
                                                <input type="number" class="form-control" id="precio_venta" name="precio_venta" 
                                                       required min="0.01" max="99999.99" step="0.01" placeholder="0.00">
                                            </div>
                                            <div class="invalid-feedback"></div>
                                            <small class="text-muted">Debe ser mayor al precio de compra</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="stock_actual" class="form-label fw-bold">Stock Actual *</label>
                                            <input type="number" class="form-control" id="stock_actual" name="stock_actual" 
                                                   required min="0" max="99999" placeholder="0">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="stock_minimo" class="form-label fw-bold">Stock Mínimo *</label>
                                            <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" 
                                                   required min="1" max="9999" value="5" placeholder="5">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info">
                                    <i class="bi bi-calculator me-2"></i>
                                    <strong>Ganancia calculada:</strong> <span id="ganancia_calculada">0%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Lote y Vencimiento -->
                        <div class="card mb-4" style="border-radius: 15px; border: 1px solid #e9ecef;">
                            <div class="card-header bg-warning text-dark" style="border-radius: 15px 15px 0 0;">
                                <h6 class="mb-0"><i class="bi bi-calendar me-2"></i>Lote y Vencimiento</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="lote" class="form-label fw-bold">Lote *</label>
                                            <input type="text" class="form-control" id="lote" name="lote" required 
                                                   placeholder="Ej: LOT2024001" maxlength="50" style="text-transform: uppercase;">
                                            <div class="invalid-feedback"></div>
                                            <small class="text-muted">Solo letras mayúsculas, números y guiones</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="fecha_vencimiento" class="form-label fw-bold">Fecha Vencimiento *</label>
                                            <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="meses_vencimiento" class="form-label fw-bold">Meses Vencimiento *</label>
                                            <select class="form-select" id="meses_vencimiento" name="meses_vencimiento" required>
                                                <option value="">Seleccionar</option>
                                                <option value="12">12 meses</option>
                                                <option value="18">18 meses</option>
                                                <option value="24">24 meses</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Farmacéutica -->
                        <div class="card mb-4" style="border-radius: 15px; border: 1px solid #e9ecef;">
                            <div class="card-header bg-secondary text-white" style="border-radius: 15px 15px 0 0;">
                                <h6 class="mb-0"><i class="bi bi-heart-pulse me-2"></i>Información Farmacéutica</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="presentacion" class="form-label fw-bold">Presentación</label>
                                            <input type="text" class="form-control" id="presentacion" name="presentacion" 
                                                   placeholder="Ej: Tabletas, Jarabe, Cápsulas" maxlength="100">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="concentracion" class="form-label fw-bold">Concentración</label>
                                            <input type="text" class="form-control" id="concentracion" name="concentracion" 
                                                   placeholder="Ej: 500mg, 250mg/5ml" maxlength="100">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="principio_activo" class="form-label fw-bold">Principio Activo</label>
                                            <input type="text" class="form-control" id="principio_activo" name="principio_activo" 
                                                   placeholder="Ej: Paracetamol, Ibuprofeno" maxlength="255">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="laboratorio" class="form-label fw-bold">Laboratorio</label>
                                            <input type="text" class="form-control" id="laboratorio" name="laboratorio" 
                                                   placeholder="Ej: Laboratorios Bagó" maxlength="255">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="registro_sanitario" class="form-label fw-bold">Registro Sanitario</label>
                                            <input type="text" class="form-control" id="registro_sanitario" name="registro_sanitario" 
                                                   placeholder="Ej: RS-001-2024" maxlength="50" style="text-transform: uppercase;">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Opciones</label>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="requiere_receta" name="requiere_receta" value="1">
                                                <label class="form-check-label" for="requiere_receta">
                                                    <i class="bi bi-file-medical me-1"></i>Requiere receta médica
                                                </label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" checked>
                                                <label class="form-check-label" for="activo">
                                                    <i class="bi bi-check-circle me-1"></i>Producto activo
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-success-modern btn-modern" onclick="crearProducto()">
                            <i class="bi bi-check-circle me-2"></i>Crear Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ver Producto -->
    <div class="modal fade" id="verProductoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-eye me-2"></i>Detalles del Producto
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
                                        <td><span id="ver_producto_id" class="badge bg-secondary"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Código:</td>
                                        <td><span id="ver_producto_codigo" class="badge bg-primary"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nombre:</td>
                                        <td><span id="ver_producto_nombre" class="h6"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Categoría:</td>
                                        <td><span id="ver_producto_categoria" class="badge bg-info"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Marca:</td>
                                        <td><span id="ver_producto_marca"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">Stock:</td>
                                        <td><span id="ver_producto_stock"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Precio Venta:</td>
                                        <td><span id="ver_producto_precio_venta" class="text-success fw-bold"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Precio Compra:</td>
                                        <td><span id="ver_producto_precio_compra" class="text-muted"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Ganancia:</td>
                                        <td><span id="ver_producto_ganancia" class="text-primary fw-bold"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Estado:</td>
                                        <td><span id="ver_producto_estado"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-warning-modern btn-modern" onclick="editarProductoDesdeModal()">
                        <i class="bi bi-pencil me-2"></i>Editar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Stock Crítico -->
    <div class="modal fade" id="stockCriticoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle me-2"></i>Productos con Stock Crítico
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>¡Atención!</strong> Los siguientes productos necesitan reabastecimiento urgente.
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Stock Actual</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos as $producto)
                                    @if($producto->stock_actual <= 10)
                                    <tr>
                                        <td>
                                            <strong>{{ $producto->nombre }}</strong><br>
                                            <small class="text-muted">{{ $producto->codigo }}</small>
                                        </td>
                                        <td>
                                            @if($producto->stock_actual > 0)
                                                <span class="text-warning fw-bold">{{ $producto->stock_actual }} unidades</span>
                                            @else
                                                <span class="text-danger fw-bold">AGOTADO</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($producto->stock_actual > 0)
                                                <span class="badge bg-warning">Stock Bajo</span>
                                            @else
                                                <span class="badge bg-danger">Agotado</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-warning-modern btn-modern" onclick="generarOrdenCompra()">
                        <i class="bi bi-cart-plus me-2"></i>Generar Orden de Compra
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Producto (Estático) -->
    <div class="modal fade" id="editarProductoModal" tabindex="-1" aria-labelledby="editarProductoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold" id="editarProductoModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Editar Producto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarProducto">
                    <input type="hidden" id="edit_producto_id" name="producto_id">
                    <div class="modal-body">
                        <div class="alert alert-info" id="edit_alert_info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Editando producto:</strong> <span id="edit_producto_info"></span>
                        </div>
                        
                        <!-- Información Básica -->
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-info-circle me-2"></i>Información Básica
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_codigo" class="form-label fw-bold">Código del Producto *</label>
                                    <input type="text" class="form-control" id="edit_codigo" name="codigo" required maxlength="50">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_nombre" class="form-label fw-bold">Nombre del Producto *</label>
                                    <input type="text" class="form-control" id="edit_nombre" name="nombre" required maxlength="255">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="edit_descripcion" class="form-label fw-bold">Descripción</label>
                                    <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="2" maxlength="1000"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Precios -->
                        <h6 class="fw-bold text-success mb-3">
                            <i class="bi bi-currency-dollar me-2"></i>Precios
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_precio_compra" class="form-label fw-bold">Precio de Compra *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">S/</span>
                                        <input type="number" class="form-control" id="edit_precio_compra" name="precio_compra" step="0.01" min="0.01" max="99999.99" required>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_precio_venta" class="form-label fw-bold">Precio de Venta *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">S/</span>
                                        <input type="number" class="form-control" id="edit_precio_venta" name="precio_venta" step="0.01" min="0.01" max="99999.99" required>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                    <small class="text-muted">Ganancia: <span id="edit_ganancia_calculada" class="fw-bold">0%</span></small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Stock -->
                        <h6 class="fw-bold text-info mb-3">
                            <i class="bi bi-boxes me-2"></i>Inventario
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_stock_actual" class="form-label fw-bold">Stock Actual *</label>
                                    <input type="number" class="form-control" id="edit_stock_actual" name="stock_actual" min="0" max="99999" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_stock_minimo" class="form-label fw-bold">Stock Mínimo *</label>
                                    <input type="number" class="form-control" id="edit_stock_minimo" name="stock_minimo" min="1" max="9999" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Lote y Vencimiento -->
                        <h6 class="fw-bold text-warning mb-3">
                            <i class="bi bi-calendar-event me-2"></i>Lote y Vencimiento
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_lote" class="form-label fw-bold">Lote *</label>
                                    <input type="text" class="form-control" id="edit_lote" name="lote" required maxlength="50" style="text-transform: uppercase;">
                                    <div class="invalid-feedback"></div>
                                    <small class="text-muted">Solo letras mayúsculas, números y guiones</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_fecha_vencimiento" class="form-label fw-bold">Fecha de Vencimiento *</label>
                                    <input type="date" class="form-control" id="edit_fecha_vencimiento" name="fecha_vencimiento" required>
                                    <div class="invalid-feedback"></div>
                                    <small class="text-muted">Debe ser posterior a hoy</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_meses_vencimiento" class="form-label fw-bold">Meses de Vencimiento *</label>
                                    <select class="form-select" id="edit_meses_vencimiento" name="meses_vencimiento" required>
                                        <option value="">Seleccionar meses...</option>
                                        <option value="12">12 meses</option>
                                        <option value="18">18 meses</option>
                                        <option value="24">24 meses</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_presentacion" class="form-label fw-bold">Presentación</label>
                                    <input type="text" class="form-control" id="edit_presentacion" name="presentacion" maxlength="100" placeholder="Ej: Caja x 20 tabletas">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información Farmacéutica -->
                        <h6 class="fw-bold text-secondary mb-3">
                            <i class="bi bi-heart-pulse me-2"></i>Información Farmacéutica
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_principio_activo" class="form-label fw-bold">Principio Activo</label>
                                    <input type="text" class="form-control" id="edit_principio_activo" name="principio_activo" maxlength="255" placeholder="Ej: Paracetamol">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_concentracion" class="form-label fw-bold">Concentración</label>
                                    <input type="text" class="form-control" id="edit_concentracion" name="concentracion" maxlength="100" placeholder="Ej: 500mg">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_laboratorio" class="form-label fw-bold">Laboratorio</label>
                                    <input type="text" class="form-control" id="edit_laboratorio" name="laboratorio" maxlength="255" placeholder="Ej: Bayer">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_registro_sanitario" class="form-label fw-bold">Registro Sanitario</label>
                                    <input type="text" class="form-control" id="edit_registro_sanitario" name="registro_sanitario" maxlength="50" style="text-transform: uppercase;" placeholder="Ej: DIGEMID-RS-123">
                                    <small class="text-muted">Solo letras mayúsculas, números y guiones</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Categorías y Relaciones -->
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="bi bi-tags me-2"></i>Categorización
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_categoria_id" class="form-label fw-bold">Categoría *</label>
                                    <select class="form-select" id="edit_categoria_id" name="categoria_id" required>
                                        <option value="">Seleccionar categoría...</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_marca_id" class="form-label fw-bold">Marca *</label>
                                    <select class="form-select" id="edit_marca_id" name="marca_id" required>
                                        <option value="">Seleccionar marca...</option>
                                        @foreach($marcas as $marca)
                                            <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_proveedor_id" class="form-label fw-bold">Proveedor *</label>
                                    <select class="form-select" id="edit_proveedor_id" name="proveedor_id" required>
                                        <option value="">Seleccionar proveedor...</option>
                                        @foreach($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Opciones -->
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-gear me-2"></i>Opciones
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit_requiere_receta" name="requiere_receta">
                                    <label class="form-check-label fw-bold" for="edit_requiere_receta">
                                        <i class="bi bi-prescription2 me-2"></i>Requiere receta médica
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit_activo" name="activo" checked>
                                    <label class="form-check-label fw-bold" for="edit_activo">
                                        <i class="bi bi-check-circle me-2"></i>Producto activo
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-warning-modern btn-modern" onclick="actualizarProductoDesdeModalEdicion()">
                            <i class="bi bi-check-circle me-2"></i>Actualizar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // CSRF Token para peticiones AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Datos para modales dinámicos
        const categorias = @json($categorias);
        const marcas = @json($marcas);
        const proveedores = @json($proveedores);

        // Funciones para generar opciones de selects
        function generarOpcionesCategorias(categoriaSeleccionada = null) {
            let opciones = '<option value="">Seleccionar categoría...</option>';
            categorias.forEach(categoria => {
                const selected = categoria.id == categoriaSeleccionada ? 'selected' : '';
                opciones += `<option value="${categoria.id}" ${selected}>${categoria.nombre}</option>`;
            });
            return opciones;
        }

        function generarOpcionesMarcas(marcaSeleccionada = null) {
            let opciones = '<option value="">Seleccionar marca...</option>';
            marcas.forEach(marca => {
                const selected = marca.id == marcaSeleccionada ? 'selected' : '';
                opciones += `<option value="${marca.id}" ${selected}>${marca.nombre}</option>`;
            });
            return opciones;
        }

        function generarOpcionesProveedores(proveedorSeleccionado = null) {
            let opciones = '<option value="">Seleccionar proveedor...</option>';
            proveedores.forEach(proveedor => {
                const selected = proveedor.id == proveedorSeleccionado ? 'selected' : '';
                opciones += `<option value="${proveedor.id}" ${selected}>${proveedor.nombre}</option>`;
            });
            return opciones;
        }

        // Función para ver producto
        function verProducto(id, codigo, nombre, categoria, marca, stock, precioVenta, precioCompra) {
            document.getElementById('ver_producto_id').textContent = id;
            document.getElementById('ver_producto_codigo').textContent = codigo;
            document.getElementById('ver_producto_nombre').textContent = nombre;
            document.getElementById('ver_producto_categoria').textContent = categoria;
            document.getElementById('ver_producto_marca').textContent = marca;
            document.getElementById('ver_producto_stock').textContent = stock + ' unidades';
            document.getElementById('ver_producto_precio_venta').textContent = 'S/ ' + precioVenta.toFixed(2);
            document.getElementById('ver_producto_precio_compra').textContent = 'S/ ' + precioCompra.toFixed(2);
            
            const ganancia = ((precioVenta - precioCompra) / precioCompra * 100).toFixed(1);
            document.getElementById('ver_producto_ganancia').textContent = ganancia + '%';
            
            const estadoElement = document.getElementById('ver_producto_estado');
            if (stock > 10) {
                estadoElement.className = 'badge bg-success';
                estadoElement.innerHTML = '<i class="bi bi-check-circle me-1"></i>Disponible';
            } else if (stock > 0) {
                estadoElement.className = 'badge bg-warning';
                estadoElement.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Stock Bajo';
            } else {
                estadoElement.className = 'badge bg-danger';
                estadoElement.innerHTML = '<i class="bi bi-x-circle me-1"></i>Agotado';
            }
            
            new bootstrap.Modal(document.getElementById('verProductoModal')).show();
        }

        // Función para generar código automático
        function generarCodigoAutomatico() {
            $.ajax({
                url: '/productos-generar-codigo',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        document.getElementById('codigo').value = response.codigo;
                        document.getElementById('codigo').classList.add('is-valid');
                    } else {
                        mostrarAlerta('error', response.message || 'Error al generar código.');
                    }
                },
                error: function() {
                    // Fallback: generar código localmente
                    const productos = @json($productos->pluck('codigo'));
                    let ultimoNumero = 0;
                    
                    productos.forEach(codigo => {
                        if (codigo.startsWith('MED')) {
                            const numero = parseInt(codigo.substring(3));
                            if (numero > ultimoNumero) {
                                ultimoNumero = numero;
                            }
                        }
                    });
                    
                    const nuevoCodigo = 'MED' + String(ultimoNumero + 1).padStart(4, '0');
                    document.getElementById('codigo').value = nuevoCodigo;
                    document.getElementById('codigo').classList.add('is-valid');
                }
            });
        }

        function editarProducto(id) {
            // Cargar datos del producto via AJAX
            $.ajax({
                url: `/productos/${id}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const producto = response.producto;
                        
                        // Llenar formulario de edición (crear modal de edición)
                        mostrarModalEdicion(producto);
                    } else {
                        mostrarAlerta('error', 'Error al cargar el producto.');
                    }
                },
                error: function() {
                    mostrarAlerta('error', 'Error al cargar los datos del producto.');
                }
            });
        }

        function mostrarModalEdicion(producto) {
            // DEBUG: Verificar datos del producto
            console.log('📝 DATOS DEL PRODUCTO PARA EDICIÓN:', producto);
            console.log('🔍 CAMPOS ESPECÍFICOS:');
            console.log('  - Lote:', producto.lote);
            console.log('  - Fecha vencimiento:', producto.fecha_vencimiento);
            console.log('  - Meses vencimiento:', producto.meses_vencimiento);
            console.log('  - Categoría ID:', producto.categoria_id);
            console.log('  - Marca ID:', producto.marca_id);
            console.log('  - Proveedor ID:', producto.proveedor_id);
            console.log('  - Presentación:', producto.presentacion);
            
            try {
                // Llenar TODOS los campos del modal con los datos del producto existente
                document.getElementById('edit_producto_id').value = producto.id || '';
                document.getElementById('edit_codigo').value = producto.codigo || '';
                document.getElementById('edit_nombre').value = producto.nombre || '';
                document.getElementById('edit_descripcion').value = producto.descripcion || '';
                
                // Precios (asegurar formato numérico)
                document.getElementById('edit_precio_compra').value = parseFloat(producto.precio_compra) || '';
                document.getElementById('edit_precio_venta').value = parseFloat(producto.precio_venta) || '';
                
                // Stock (asegurar formato entero)
                document.getElementById('edit_stock_actual').value = parseInt(producto.stock_actual) || '';
                document.getElementById('edit_stock_minimo').value = parseInt(producto.stock_minimo) || '';
                
                // Lote (asegurar valor string)
                document.getElementById('edit_lote').value = producto.lote || '';
                console.log('✅ Lote asignado:', producto.lote);
                
                // Fecha de vencimiento (formatear correctamente)
                if (producto.fecha_vencimiento) {
                    // Si viene como string ISO, extraer solo la fecha
                    let fecha = producto.fecha_vencimiento;
                    if (fecha.includes('T')) {
                        fecha = fecha.split('T')[0];
                    }
                    document.getElementById('edit_fecha_vencimiento').value = fecha;
                    console.log('✅ Fecha vencimiento asignada:', fecha);
                } else {
                    document.getElementById('edit_fecha_vencimiento').value = '';
                }
                
                // Meses de vencimiento
                document.getElementById('edit_meses_vencimiento').value = producto.meses_vencimiento || '';
                console.log('✅ Meses vencimiento asignados:', producto.meses_vencimiento);
                
                // Información farmacéutica
                document.getElementById('edit_presentacion').value = producto.presentacion || '';
                console.log('✅ Presentación asignada:', producto.presentacion);
                
                document.getElementById('edit_principio_activo').value = producto.principio_activo || '';
                document.getElementById('edit_concentracion').value = producto.concentracion || '';
                document.getElementById('edit_laboratorio').value = producto.laboratorio || '';
                document.getElementById('edit_registro_sanitario').value = producto.registro_sanitario || '';
                
                // IMPORTANTE: Seleccionar categoría, marca y proveedor
                const categoriaSelect = document.getElementById('edit_categoria_id');
                const marcaSelect = document.getElementById('edit_marca_id');
                const proveedorSelect = document.getElementById('edit_proveedor_id');
                
                if (categoriaSelect && producto.categoria_id) {
                    categoriaSelect.value = producto.categoria_id;
                    console.log('✅ Categoría seleccionada:', producto.categoria_id);
                }
                
                if (marcaSelect && producto.marca_id) {
                    marcaSelect.value = producto.marca_id;
                    console.log('✅ Marca seleccionada:', producto.marca_id);
                }
                
                if (proveedorSelect && producto.proveedor_id) {
                    proveedorSelect.value = producto.proveedor_id;
                    console.log('✅ Proveedor seleccionado:', producto.proveedor_id);
                }
                
                // Checkboxes (asegurar valores boolean)
                const requiereRecetaCheckbox = document.getElementById('edit_requiere_receta');
                const activoCheckbox = document.getElementById('edit_activo');
                
                if (requiereRecetaCheckbox) {
                    requiereRecetaCheckbox.checked = Boolean(producto.requiere_receta);
                    console.log('✅ Requiere receta:', Boolean(producto.requiere_receta));
                }
                
                if (activoCheckbox) {
                    activoCheckbox.checked = Boolean(producto.activo);
                    console.log('✅ Producto activo:', Boolean(producto.activo));
                }
                
                // Actualizar información del producto en el alert
                const infoElement = document.getElementById('edit_producto_info');
                if (infoElement) {
                    infoElement.textContent = `${producto.codigo} - ${producto.nombre}`;
                }
                
                // Calcular ganancia automáticamente
                calcularGananciaEdicion();
                
                console.log('✅ TODOS LOS CAMPOS PROCESADOS CORRECTAMENTE');
                console.log('📋 VERIFICACIÓN FINAL DE CAMPOS:');
                console.log('  - Lote input:', document.getElementById('edit_lote').value);
                console.log('  - Fecha input:', document.getElementById('edit_fecha_vencimiento').value);
                console.log('  - Meses select:', document.getElementById('edit_meses_vencimiento').value);
                console.log('  - Presentación input:', document.getElementById('edit_presentacion').value);
                
            } catch (error) {
                console.error('❌ ERROR AL LLENAR CAMPOS DEL MODAL:', error);
                mostrarAlerta('error', 'Error al cargar los datos en el formulario: ' + error.message);
                return;
            }
            
            // Mostrar modal
            console.log('🚀 Abriendo modal de edición...');
            const modal = new bootstrap.Modal(document.getElementById('editarProductoModal'));
            modal.show();
        }

        // Función para calcular ganancia en modal de edición
        function calcularGananciaEdicion() {
            const precioCompra = parseFloat(document.getElementById('edit_precio_compra').value) || 0;
            const precioVenta = parseFloat(document.getElementById('edit_precio_venta').value) || 0;
            const gananciaSpan = document.getElementById('edit_ganancia_calculada');

            if (precioCompra > 0 && precioVenta > 0) {
                const ganancia = ((precioVenta - precioCompra) / precioCompra * 100).toFixed(1);
                gananciaSpan.textContent = ganancia + '%';
                
                if (ganancia > 50) {
                    gananciaSpan.className = 'text-success fw-bold';
                } else if (ganancia > 25) {
                    gananciaSpan.className = 'text-warning fw-bold';
                } else if (ganancia > 0) {
                    gananciaSpan.className = 'text-danger fw-bold';
                } else {
                    gananciaSpan.className = 'text-muted';
                }
            } else {
                gananciaSpan.textContent = '0%';
                gananciaSpan.className = 'text-muted';
            }
        }

        function actualizarProducto(id) {
            // Validar campos obligatorios
            const camposObligatorios = ['edit_codigo', 'edit_nombre', 'edit_precio_compra', 'edit_precio_venta', 'edit_stock_actual', 'edit_stock_minimo', 'edit_lote', 'edit_fecha_vencimiento', 'edit_meses_vencimiento', 'edit_categoria_id', 'edit_marca_id', 'edit_proveedor_id'];
            let camposFaltantes = [];
            
            camposObligatorios.forEach(campo => {
                const elemento = document.getElementById(campo);
                if (!elemento || !elemento.value.trim()) {
                    camposFaltantes.push(campo.replace('edit_', '').replace('_', ' '));
                }
            });

            if (camposFaltantes.length > 0) {
                mostrarAlerta('error', `Faltan campos obligatorios: ${camposFaltantes.join(', ')}`);
                return;
            }

            // Validar precios
            const precioCompra = parseFloat(document.getElementById('edit_precio_compra').value);
            const precioVenta = parseFloat(document.getElementById('edit_precio_venta').value);
            
            if (precioVenta <= precioCompra) {
                mostrarAlerta('error', 'El precio de venta debe ser mayor al precio de compra.');
                return;
            }

            // Mostrar loading
            const btnActualizar = document.querySelector('#editarProductoModal .btn-warning-modern');
            const originalText = btnActualizar.innerHTML;
            btnActualizar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Actualizando...';
            btnActualizar.disabled = true;

            const datosActualizados = {
                codigo: document.getElementById('edit_codigo').value.trim().toUpperCase(),
                nombre: document.getElementById('edit_nombre').value.trim(),
                descripcion: document.getElementById('edit_descripcion').value.trim(),
                precio_compra: precioCompra,
                precio_venta: precioVenta,
                stock_actual: parseInt(document.getElementById('edit_stock_actual').value),
                stock_minimo: parseInt(document.getElementById('edit_stock_minimo').value),
                lote: document.getElementById('edit_lote').value.trim().toUpperCase(),
                fecha_vencimiento: document.getElementById('edit_fecha_vencimiento').value,
                meses_vencimiento: document.getElementById('edit_meses_vencimiento').value,
                presentacion: document.getElementById('edit_presentacion').value.trim(),
                principio_activo: document.getElementById('edit_principio_activo').value.trim(),
                concentracion: document.getElementById('edit_concentracion').value.trim(),
                laboratorio: document.getElementById('edit_laboratorio').value.trim(),
                registro_sanitario: document.getElementById('edit_registro_sanitario').value.trim().toUpperCase(),
                requiere_receta: document.getElementById('edit_requiere_receta').checked ? 1 : 0,
                activo: document.getElementById('edit_activo').checked ? 1 : 0,
                categoria_id: document.getElementById('edit_categoria_id').value,
                marca_id: document.getElementById('edit_marca_id').value,
                proveedor_id: document.getElementById('edit_proveedor_id').value,
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT'
            };

            $.ajax({
                url: `/productos/${id}`,
                method: 'PUT',
                data: datosActualizados,
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('success', response.message);
                        bootstrap.Modal.getInstance(document.getElementById('editarProductoModal')).hide();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al actualizar el producto.');
                    }
                },
                error: function(xhr) {
                    let message = 'Error al actualizar el producto.';
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

        function editarProductoDesdeModal() {
            const id = document.getElementById('ver_producto_id').textContent;
            bootstrap.Modal.getInstance(document.getElementById('verProductoModal')).hide();
            setTimeout(() => editarProducto(id), 500);
        }

        function actualizarProductoDesdeModalEdicion() {
            // Obtener ID del producto desde el campo hidden del modal de edición
            const id = document.getElementById('edit_producto_id').value;
            console.log('🔄 Actualizando producto ID:', id);
            
            if (!id) {
                mostrarAlerta('error', 'Error: No se pudo obtener el ID del producto.');
                return;
            }
            
            // Llamar a la función de actualización
            actualizarProducto(id);
        }

        function eliminarProducto(id, nombre) {
            if (confirm(`¿Está seguro de eliminar el producto "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
                // AJAX para eliminar producto
                $.ajax({
                    url: `/productos/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        if (response.success) {
                            mostrarAlerta('success', response.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            mostrarAlerta('error', response.message || 'Error al eliminar el producto.');
                        }
                    },
                    error: function(xhr) {
                        let message = 'Error al eliminar el producto.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        mostrarAlerta('error', message);
                    }
                });
            }
        }

        function exportarLista() {
            $.ajax({
                url: '{{ route("productos.exportar") }}',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('info', response.message);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al exportar productos.');
                    }
                },
                error: function() {
                    mostrarAlerta('error', 'Error al exportar productos.');
                }
            });
        }

        function generarOrdenCompra() {
            alert('📋 GENERANDO ORDEN DE COMPRA\n\n✅ Productos con stock crítico\n📊 Cantidades sugeridas automáticamente\n📧 Enviado a proveedores\n\nFuncionalidad en desarrollo.');
        }

        // Función para crear producto con AJAX
        function crearProducto() {
            // Validar campos obligatorios
            const campos = ['codigo', 'nombre', 'precio_compra', 'precio_venta', 'stock_actual', 'stock_minimo', 'lote', 'fecha_vencimiento', 'meses_vencimiento', 'categoria_id', 'marca_id', 'proveedor_id'];
            let camposFaltantes = [];
            
            campos.forEach(campo => {
                const valor = document.getElementById(campo).value.trim();
                if (!valor) {
                    camposFaltantes.push(campo.replace('_', ' ').replace('id', ''));
                }
            });

            if (camposFaltantes.length > 0) {
                mostrarAlerta('error', `Faltan campos obligatorios: ${camposFaltantes.join(', ')}`);
                return;
            }

            // Validar precios
            const precioCompra = parseFloat(document.getElementById('precio_compra').value);
            const precioVenta = parseFloat(document.getElementById('precio_venta').value);
            
            if (precioVenta <= precioCompra) {
                mostrarAlerta('error', 'El precio de venta debe ser mayor al precio de compra.');
                return;
            }

            // Validar fecha de vencimiento
            const fechaVencimiento = new Date(document.getElementById('fecha_vencimiento').value);
            const hoy = new Date();
            
            if (fechaVencimiento <= hoy) {
                mostrarAlerta('error', 'La fecha de vencimiento debe ser posterior a hoy.');
                return;
            }

            // Mostrar loading
            const btnCrear = document.querySelector('#nuevoProductoModal .btn-success-modern');
            const originalText = btnCrear.innerHTML;
            btnCrear.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creando producto...';
            btnCrear.disabled = true;

            // Preparar datos
            const formData = {
                codigo: document.getElementById('codigo').value.trim().toUpperCase(),
                nombre: document.getElementById('nombre').value.trim(),
                descripcion: document.getElementById('descripcion').value.trim(),
                precio_compra: precioCompra,
                precio_venta: precioVenta,
                stock_actual: parseInt(document.getElementById('stock_actual').value),
                stock_minimo: parseInt(document.getElementById('stock_minimo').value),
                lote: document.getElementById('lote').value.trim().toUpperCase(),
                fecha_vencimiento: document.getElementById('fecha_vencimiento').value,
                meses_vencimiento: document.getElementById('meses_vencimiento').value,
                presentacion: document.getElementById('presentacion').value.trim(),
                principio_activo: document.getElementById('principio_activo').value.trim(),
                concentracion: document.getElementById('concentracion').value.trim(),
                laboratorio: document.getElementById('laboratorio').value.trim(),
                registro_sanitario: document.getElementById('registro_sanitario').value.trim().toUpperCase(),
                requiere_receta: document.getElementById('requiere_receta').checked ? 1 : 0,
                activo: document.getElementById('activo').checked ? 1 : 0,
                categoria_id: document.getElementById('categoria_id').value,
                marca_id: document.getElementById('marca_id').value,
                proveedor_id: document.getElementById('proveedor_id').value,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                url: '{{ route("productos.store") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('success', response.message);
                        bootstrap.Modal.getInstance(document.getElementById('nuevoProductoModal')).hide();
                        
                        // Recargar la página después de 2 segundos
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al crear el producto.');
                    }
                },
                error: function(xhr) {
                    let message = 'Error al crear el producto.';
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

        // Función para mostrar alertas
        function mostrarAlerta(tipo, mensaje) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${tipo === 'success' ? 'success' : tipo === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 350px; max-width: 500px;';
            
            const icon = tipo === 'success' ? 'check-circle' : tipo === 'error' ? 'exclamation-triangle' : 'info-circle';
            
            alertDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-${icon} me-3" style="font-size: 1.5rem;"></i>
                    <div class="flex-grow-1">
                        <strong>${tipo === 'success' ? '¡Éxito!' : tipo === 'error' ? '¡Error!' : 'Información'}</strong><br>
                        <span>${mensaje}</span>
                    </div>
                </div>
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

        // Búsqueda en tiempo real
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Validaciones en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar CSRF si no existe
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = '{{ csrf_token() }}';
                document.head.appendChild(meta);
            }

            // Validación de código (solo mayúsculas, números y guiones)
            const codigoInput = document.getElementById('codigo');
            if (codigoInput) {
                codigoInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
                });
            }

            // Validación de lote
            const loteInput = document.getElementById('lote');
            if (loteInput) {
                loteInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
                });
            }

            // Validación de registro sanitario
            const registroInput = document.getElementById('registro_sanitario');
            if (registroInput) {
                registroInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
                });
            }

            // Cálculo automático de ganancia
            const precioCompra = document.getElementById('precio_compra');
            const precioVenta = document.getElementById('precio_venta');
            const gananciaSpan = document.getElementById('ganancia_calculada');

            function calcularGanancia() {
                const compra = parseFloat(precioCompra.value) || 0;
                const venta = parseFloat(precioVenta.value) || 0;

                if (compra > 0 && venta > 0) {
                    const ganancia = ((venta - compra) / compra * 100).toFixed(1);
                    gananciaSpan.textContent = ganancia + '%';
                    
                    if (ganancia > 50) {
                        gananciaSpan.className = 'text-success fw-bold';
                    } else if (ganancia > 25) {
                        gananciaSpan.className = 'text-warning fw-bold';
                    } else if (ganancia > 0) {
                        gananciaSpan.className = 'text-danger fw-bold';
                    } else {
                        gananciaSpan.className = 'text-muted';
                    }
                } else {
                    gananciaSpan.textContent = '0%';
                    gananciaSpan.className = 'text-muted';
                }
            }

            if (precioCompra && precioVenta) {
                precioCompra.addEventListener('input', calcularGanancia);
                precioVenta.addEventListener('input', calcularGanancia);
            }

            // Eventos para cálculo automático de ganancia en modal de edición
            const editPrecioCompra = document.getElementById('edit_precio_compra');
            const editPrecioVenta = document.getElementById('edit_precio_venta');

            if (editPrecioCompra && editPrecioVenta) {
                editPrecioCompra.addEventListener('input', calcularGananciaEdicion);
                editPrecioVenta.addEventListener('input', calcularGananciaEdicion);
            }

            // Limpiar formularios al cerrar modales
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function () {
                    const forms = this.querySelectorAll('form');
                    forms.forEach(form => {
                        form.reset();
                        form.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
                            el.classList.remove('is-valid', 'is-invalid');
                        });
                    });
                    if (gananciaSpan) gananciaSpan.textContent = '0%';
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

            // Generar código automático al abrir modal de nuevo producto
            const nuevoProductoModal = document.getElementById('nuevoProductoModal');
            if (nuevoProductoModal) {
                nuevoProductoModal.addEventListener('show.bs.modal', function () {
                    setTimeout(() => {
                        generarCodigoAutomatico();
                    }, 300);
                });
            }
        });

        // Mostrar alertas de Laravel flash
        @if(session('success'))
            mostrarAlerta('success', '{{ session("success") }}');
        @endif
        
        @if(session('error'))
            mostrarAlerta('error', '{{ session("error") }}');
        @endif

        function actualizarProductoReal() {
            const id = document.getElementById('edit_producto_id').value;
            
            // Validar campos obligatorios
            const camposObligatorios = ['edit_codigo', 'edit_nombre', 'edit_precio_compra', 'edit_precio_venta', 'edit_stock_actual', 'edit_stock_minimo', 'edit_lote', 'edit_fecha_vencimiento', 'edit_meses_vencimiento', 'edit_categoria_id', 'edit_marca_id', 'edit_proveedor_id'];
            let camposFaltantes = [];
            
            camposObligatorios.forEach(campo => {
                const elemento = document.getElementById(campo);
                if (!elemento || !elemento.value.trim()) {
                    camposFaltantes.push(campo.replace('edit_', '').replace('_', ' '));
                }
            });

            if (camposFaltantes.length > 0) {
                mostrarAlerta('error', `Faltan campos obligatorios: ${camposFaltantes.join(', ')}`);
                return;
            }

            // Validar precios
            const precioCompra = parseFloat(document.getElementById('edit_precio_compra').value);
            const precioVenta = parseFloat(document.getElementById('edit_precio_venta').value);
            
            if (precioVenta <= precioCompra) {
                mostrarAlerta('error', 'El precio de venta debe ser mayor al precio de compra.');
                return;
            }

            // Mostrar loading
            const btnActualizar = document.querySelector('#editarProductoModal .btn-warning-modern');
            const originalText = btnActualizar.innerHTML;
            btnActualizar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Actualizando...';
            btnActualizar.disabled = true;

            const datosActualizados = {
                codigo: document.getElementById('edit_codigo').value.trim().toUpperCase(),
                nombre: document.getElementById('edit_nombre').value.trim(),
                descripcion: document.getElementById('edit_descripcion').value.trim(),
                precio_compra: precioCompra,
                precio_venta: precioVenta,
                stock_actual: parseInt(document.getElementById('edit_stock_actual').value),
                stock_minimo: parseInt(document.getElementById('edit_stock_minimo').value),
                lote: document.getElementById('edit_lote').value.trim().toUpperCase(),
                fecha_vencimiento: document.getElementById('edit_fecha_vencimiento').value,
                meses_vencimiento: document.getElementById('edit_meses_vencimiento').value,
                presentacion: document.getElementById('edit_presentacion').value.trim(),
                principio_activo: document.getElementById('edit_principio_activo').value.trim(),
                concentracion: document.getElementById('edit_concentracion').value.trim(),
                laboratorio: document.getElementById('edit_laboratorio').value.trim(),
                registro_sanitario: document.getElementById('edit_registro_sanitario').value.trim().toUpperCase(),
                requiere_receta: document.getElementById('edit_requiere_receta').checked ? 1 : 0,
                activo: document.getElementById('edit_activo').checked ? 1 : 0,
                categoria_id: document.getElementById('edit_categoria_id').value,
                marca_id: document.getElementById('edit_marca_id').value,
                proveedor_id: document.getElementById('edit_proveedor_id').value,
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT'
            };

            $.ajax({
                url: `/productos/${id}`,
                method: 'PUT',
                data: datosActualizados,
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('success', response.message);
                        bootstrap.Modal.getInstance(document.getElementById('editarProductoModal')).hide();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        mostrarAlerta('error', response.message || 'Error al actualizar el producto.');
                    }
                },
                error: function(xhr) {
                    let message = 'Error al actualizar el producto.';
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
        
        // Función para confirmar cerrar sesión
        function confirmarCerrarSesion() {
            if (confirm('¿Está seguro de que desea cerrar sesión?')) {
                document.getElementById('logout-form-productos').submit();
            }
        }
        
        // Función para cerrar sesión CON MODAL MODERNO
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
                document.getElementById('logout-form-productos').submit();
            }, 1500);
        }
        
        // Función legacy para compatibilidad
        function confirmarCerrarSesion() {
            mostrarModalCerrarSesion();
        }
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
</body>
</html>
