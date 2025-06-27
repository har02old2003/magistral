<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico Clientes - Farmacia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1><i class="bi bi-tools"></i> 🔧 Diagnóstico Módulo Clientes</h1>
        
        @php
            try {
                echo "<div class='alert alert-info'><strong>Iniciando diagnóstico...</strong></div>";
                
                // Test 1: Conexión básica
                $totalClientes = \App\Models\Cliente::count();
                echo "<div class='alert alert-success'>✅ Conexión DB: {$totalClientes} clientes encontrados</div>";
                
                // Test 2: Clientes activos
                $clientesActivos = \App\Models\Cliente::where('activo', true)->count();
                echo "<div class='alert alert-success'>✅ Clientes activos: {$clientesActivos}</div>";
                
                // Test 3: Clientes nuevos (este mes)
                $clientesNuevos = \App\Models\Cliente::whereMonth('created_at', now()->month)
                                                   ->whereYear('created_at', now()->year)
                                                   ->count();
                echo "<div class='alert alert-success'>✅ Clientes nuevos este mes: {$clientesNuevos}</div>";
                
                // Test 4: Consulta VIP corregida
                $clientesVip = \App\Models\Cliente::whereHas('ventas', function($query) {
                    $query->selectRaw('cliente_id')
                          ->groupBy('cliente_id')
                          ->havingRaw('SUM(total) >= 1000');
                })->count();
                echo "<div class='alert alert-success'>✅ Clientes VIP (>= S/1000): {$clientesVip}</div>";
                
                // Test 5: Obtener algunos clientes de ejemplo
                $clientesEjemplo = \App\Models\Cliente::limit(3)->get();
                echo "<div class='alert alert-success'>✅ Clientes obtenidos: " . $clientesEjemplo->count() . "</div>";
                
                // Test 6: Clientes con ventas
                $clientesConVentas = \App\Models\Cliente::has('ventas')->count();
                echo "<div class='alert alert-success'>✅ Clientes con ventas: {$clientesConVentas}</div>";
                
                echo "<div class='alert alert-primary'><strong>🎉 Todos los tests pasaron exitosamente!</strong></div>";
                
                $allTestsPassed = true;
                
            } catch(\Exception $e) {
                echo "<div class='alert alert-danger'><strong>❌ Error:</strong> " . $e->getMessage() . "</div>";
                echo "<div class='alert alert-warning'><strong>Archivo:</strong> " . $e->getFile() . " línea " . $e->getLine() . "</div>";
                $allTestsPassed = false;
            }
        @endphp
        
        @if(isset($allTestsPassed) && $allTestsPassed)
            <div class="row">
                <div class="col-12">
                    <h3>📋 Datos de Clientes</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Documento</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Cliente::limit(5)->get() as $cliente)
                                <tr>
                                    <td>{{ $cliente->id }}</td>
                                    <td>{{ $cliente->nombres }}</td>
                                    <td>{{ $cliente->apellidos }}</td>
                                    <td>{{ $cliente->documento }}</td>
                                    <td>
                                        <span class="badge {{ $cliente->activo ? 'bg-success' : 'bg-danger' }}">
                                            {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="mt-4">
            <a href="/dashboard" class="btn btn-primary">← Volver al Dashboard</a>
            <a href="/clientes" class="btn btn-success">Ir a Clientes</a>
        </div>
    </div>
</body>
</html> 