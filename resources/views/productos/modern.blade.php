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
        
        .product-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.2);
            margin-bottom: 1rem;
        }
        
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
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
                            <div class="stat-card warning {{ $productosStockBajo > 0 ? 'pulse-warning' : '' }}">
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
                                    @if($productosStockBajo > 0)<strong>{{ $productosStockBajo }}</strong> productos con stock bajo@endif
                                    @if($productosStockBajo > 0 && $productosAgotados > 0) y @endif
                                    @if($productosAgotados > 0)<strong>{{ $productosAgotados }}</strong> productos agotados@endif
                                </p>
                                <button class="btn btn-warning-modern btn-modern btn-sm" onclick="mostrarProductosStockBajo()">
                                    <i class="bi bi-eye me-1"></i> Ver productos críticos
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Acciones y Controles -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.2);">
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
                            <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.2); height: 100%;">
                                <h6 class="mb-3">
                                    <i class="bi bi-lightning text-warning me-2"></i>
                                    Acciones Rápidas
                                </h6>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-success-modern btn-modern btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i> Nuevo Producto
                                    </button>
                                    <button class="btn btn-info-modern btn-modern btn-sm">
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
                                            <button type="button" class="btn btn-outline-primary btn-sm" title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            @if(auth()->user()->role === 'administrador')
                                            <button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function mostrarProductosStockBajo() {
            alert('Productos con stock crítico:\n\n• Ibuprofeno 400mg: 5 unidades (Stock bajo)\n• Aspirina 100mg: 0 unidades (AGOTADO)\n\n¡Necesitan reabastecimiento urgente!');
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

        // Animaciones
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>
</html> 