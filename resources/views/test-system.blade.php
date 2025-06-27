<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico Completo - Sistema Farmacia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .test-card { margin-bottom: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1><i class="bi bi-tools"></i> 🔧 Diagnóstico Completo del Sistema</h1>
        
        <div class="alert alert-info">
            <strong>Revisión completa del sistema de farmacia</strong><br>
            Fecha: {{ date('d/m/Y H:i:s') }}
        </div>

        <!-- Test 1: Autenticación -->
        <div class="card test-card">
            <div class="card-header"><h5>1. 🔐 Sistema de Autenticación</h5></div>
            <div class="card-body">
                @if(auth()->check())
                    <p class="success">✅ Usuario autenticado: <strong>{{ auth()->user()->name }}</strong></p>
                    <p class="success">✅ Rol: <strong>{{ auth()->user()->role }}</strong></p>
                    <p class="success">✅ Email: {{ auth()->user()->email }}</p>
                @else
                    <p class="error">❌ Usuario NO autenticado</p>
                @endif
            </div>
        </div>

        <!-- Test 2: Base de Datos -->
        <div class="card test-card">
            <div class="card-header"><h5>2. 🗄️ Conexión a Base de Datos</h5></div>
            <div class="card-body">
                @php
                    try {
                        $conexion = \DB::connection()->getPdo();
                        echo "<p class='success'>✅ Conexión BD: Exitosa</p>";
                        
                        // Test tabla usuarios
                        $usuarios = \DB::table('users')->count();
                        echo "<p class='success'>✅ Usuarios: $usuarios registrados</p>";
                        
                        // Test tabla productos
                        $productos = \DB::table('productos')->count();
                        echo "<p class='success'>✅ Productos: $productos registrados</p>";
                        
                        // Test tabla marcas
                        $marcas = \DB::table('marcas')->count();
                        echo "<p class='success'>✅ Marcas: $marcas registradas</p>";
                        
                        // Test tabla ventas
                        $ventas = \DB::table('ventas')->count();
                        echo "<p class='success'>✅ Ventas: $ventas registradas</p>";
                        
                    } catch(\Exception $e) {
                        echo "<p class='error'>❌ Error BD: " . $e->getMessage() . "</p>";
                    }
                @endphp
            </div>
        </div>

        <!-- Test 3: Modelos -->
        <div class="card test-card">
            <div class="card-header"><h5>3. 📊 Modelos de Eloquent</h5></div>
            <div class="card-body">
                @php
                    try {
                        $userModel = \App\Models\User::count();
                        echo "<p class='success'>✅ Modelo User: $userModel usuarios</p>";
                    } catch(\Exception $e) {
                        echo "<p class='error'>❌ Modelo User: " . $e->getMessage() . "</p>";
                    }
                    
                    try {
                        $productoModel = \App\Models\Producto::count();
                        echo "<p class='success'>✅ Modelo Producto: $productoModel productos</p>";
                    } catch(\Exception $e) {
                        echo "<p class='error'>❌ Modelo Producto: " . $e->getMessage() . "</p>";
                    }
                    
                    try {
                        $marcaModel = \App\Models\Marca::count();
                        echo "<p class='success'>✅ Modelo Marca: $marcaModel marcas</p>";
                    } catch(\Exception $e) {
                        echo "<p class='error'>❌ Modelo Marca: " . $e->getMessage() . "</p>";
                    }
                    
                    try {
                        $ventaModel = \App\Models\Venta::count();
                        echo "<p class='success'>✅ Modelo Venta: $ventaModel ventas</p>";
                    } catch(\Exception $e) {
                        echo "<p class='error'>❌ Modelo Venta: " . $e->getMessage() . "</p>";
                    }
                @endphp
            </div>
        </div>

        <!-- Test 4: Rutas principales -->
        <div class="card test-card">
            <div class="card-header"><h5>4. 🛤️ Rutas del Sistema</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="/dashboard" class="btn btn-primary w-100 mb-2" target="_blank">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/productos" class="btn btn-info w-100 mb-2" target="_blank">
                            <i class="bi bi-capsule"></i> Productos
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/marcas" class="btn btn-warning w-100 mb-2" target="_blank">
                            <i class="bi bi-bookmark"></i> Marcas
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/ventas" class="btn btn-success w-100 mb-2" target="_blank">
                            <i class="bi bi-cart3"></i> Ventas
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <a href="/clientes" class="btn btn-secondary w-100 mb-2" target="_blank">
                            <i class="bi bi-people"></i> Clientes
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/categorias" class="btn btn-dark w-100 mb-2" target="_blank">
                            <i class="bi bi-tags"></i> Categorías
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/proveedores" class="btn btn-outline-primary w-100 mb-2" target="_blank">
                            <i class="bi bi-truck"></i> Proveedores
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/usuarios" class="btn btn-outline-danger w-100 mb-2" target="_blank">
                            <i class="bi bi-person-gear"></i> Usuarios
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test 5: Datos de prueba -->
        <div class="card test-card">
            <div class="card-header"><h5>5. 📋 Datos de Prueba</h5></div>
            <div class="card-body">
                @php
                    try {
                        $productos = \App\Models\Producto::with(['categoria', 'marca'])->take(3)->get();
                        echo "<p class='success'>✅ Productos con relaciones cargados</p>";
                        foreach($productos as $producto) {
                            echo "<small>- {$producto->nombre} ({$producto->categoria->nombre ?? 'Sin categoría'}) - Stock: {$producto->stock_actual}</small><br>";
                        }
                    } catch(\Exception $e) {
                        echo "<p class='error'>❌ Error productos: " . $e->getMessage() . "</p>";
                    }
                @endphp
            </div>
        </div>

        <!-- Test 6: Archivos importantes -->
        <div class="card test-card">
            <div class="card-header"><h5>6. 📁 Archivos del Sistema</h5></div>
            <div class="card-body">
                @php
                    $archivos = [
                        'resources/views/layouts/app.blade.php' => 'Layout principal',
                        'resources/views/dashboard/index.blade.php' => 'Vista Dashboard',
                        'resources/views/productos/index.blade.php' => 'Vista Productos',
                        'resources/views/marcas/index.blade.php' => 'Vista Marcas',
                        'resources/views/ventas/index.blade.php' => 'Vista Ventas',
                    ];
                    
                    foreach($archivos as $archivo => $descripcion) {
                        if(file_exists(base_path($archivo))) {
                            echo "<p class='success'>✅ $descripcion: Existe</p>";
                        } else {
                            echo "<p class='error'>❌ $descripcion: No encontrado</p>";
                        }
                    }
                @endphp
            </div>
        </div>

        <div class="mt-4">
            <h4>🔗 Enlaces de prueba rápidos:</h4>
            <a href="/dashboard" class="btn btn-lg btn-primary me-2">Dashboard</a>
            <a href="/productos" class="btn btn-lg btn-info me-2">Productos</a>
            <a href="/marcas" class="btn btn-lg btn-warning me-2">Marcas</a>
            <a href="/ventas" class="btn btn-lg btn-success">Ventas</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 