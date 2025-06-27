<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marca: {{ $marca->nombre }} - Farmacia Magistral</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
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
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .modern-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }
        
        .modern-card:hover {
            transform: translateY(-5px);
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
        }
        
        .btn-success-modern { background: var(--success-gradient); color: white; }
        .btn-primary-modern { background: var(--primary-gradient); color: white; }
        
        .stat-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .table thead th {
            background: var(--primary-gradient);
            color: white;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
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
                </ul>
            </nav>

            <!-- Contenido Principal -->
            <main class="col-md-9 col-lg-10 main-content">
                <!-- Header -->
                <div class="page-header">
                    <h1 style="font-size: 3rem; font-weight: 700; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                        <i class="bi bi-tag me-3"></i>{{ $marca->nombre }}
                    </h1>
                    <p class="mb-0 opacity-75" style="font-size: 1.2rem;">Detalles y productos de la marca</p>
                </div>

                <!-- Navegación -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/marcas" class="text-decoration-none">
                                <i class="bi bi-tags me-1"></i>Marcas
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $marca->nombre }}</li>
                    </ol>
                </nav>

                <!-- Información de la Marca -->
                <div class="modern-card">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="mb-3">
                                <i class="bi bi-info-circle text-primary me-2"></i>
                                Información General
                            </h5>
                            
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 150px;">ID:</td>
                                        <td><span class="stat-badge bg-secondary text-white">{{ $marca->id }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nombre:</td>
                                        <td><h6 class="mb-0">{{ $marca->nombre }}</h6></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Descripción:</td>
                                        <td>{{ $marca->descripcion ?? 'Sin descripción disponible' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Estado:</td>
                                        <td>
                                            @if($marca->activo)
                                                <span class="stat-badge bg-success text-white">
                                                    <i class="bi bi-check-circle me-1"></i>Activa
                                                </span>
                                            @else
                                                <span class="stat-badge bg-danger text-white">
                                                    <i class="bi bi-x-circle me-1"></i>Inactiva
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Productos:</td>
                                        <td>
                                            <span class="stat-badge bg-info text-white">
                                                <i class="bi bi-capsule me-1"></i>{{ $marca->productos->count() }} productos
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Registrado:</td>
                                        <td>{{ $marca->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Actualizado:</td>
                                        <td>{{ $marca->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-4">
                            <h6 class="mb-3">
                                <i class="bi bi-gear text-warning me-2"></i>
                                Acciones
                            </h6>
                            <div class="d-grid gap-2">
                                @if(auth()->user()->role === 'administrador')
                                <a href="{{ route('marcas.edit', $marca->id) }}" class="btn btn-primary-modern btn-modern">
                                    <i class="bi bi-pencil me-2"></i>Editar Marca
                                </a>
                                @endif
                                <a href="{{ route('marcas.index') }}" class="btn btn-outline-secondary btn-modern">
                                    <i class="bi bi-arrow-left me-2"></i>Volver a Marcas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Productos de la Marca -->
                @if($marca->productos->count() > 0)
                <div class="modern-card">
                    <h5 class="mb-3">
                        <i class="bi bi-capsule text-info me-2"></i>
                        Productos de {{ $marca->nombre }} ({{ $marca->productos->count() }})
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Stock</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($marca->productos as $producto)
                                <tr>
                                    <td><span class="stat-badge bg-secondary text-white">{{ $producto->codigo }}</span></td>
                                    <td>
                                        <strong>{{ $producto->nombre }}</strong>
                                    </td>
                                    <td>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
                                    <td>
                                        @if($producto->stock_actual > 10)
                                            <span class="text-success fw-bold">{{ $producto->stock_actual }}</span>
                                        @elseif($producto->stock_actual > 0)
                                            <span class="text-warning fw-bold">{{ $producto->stock_actual }}</span>
                                        @else
                                            <span class="text-danger fw-bold">{{ $producto->stock_actual }}</span>
                                        @endif
                                    </td>
                                    <td><strong>S/ {{ number_format($producto->precio_venta, 2) }}</strong></td>
                                    <td>
                                        @if($producto->activo)
                                            <span class="stat-badge bg-success text-white">Activo</span>
                                        @else
                                            <span class="stat-badge bg-danger text-white">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="modern-card text-center">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #dee2e6; margin-bottom: 1rem;"></i>
                    <h5 class="text-muted">No hay productos registrados</h5>
                    <p class="text-muted">Esta marca aún no tiene productos asociados.</p>
                </div>
                @endif
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 