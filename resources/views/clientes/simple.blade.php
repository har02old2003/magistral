<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Farmacia Magistral</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Simple -->
            <nav class="col-md-2 d-md-block bg-primary text-white" style="min-height: 100vh;">
                <div class="p-3">
                    <h4><i class="bi bi-hospital"></i> Farmacia</h4>
                    <small>{{ auth()->user()->name ?? 'Usuario' }}</small>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/productos">
                                <i class="bi bi-capsule"></i> Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white active bg-secondary" href="/clientes">
                                <i class="bi bi-people"></i> Clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/ventas">
                                <i class="bi bi-cart"></i> Ventas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/marcas">
                                <i class="bi bi-tag"></i> Marcas
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Contenido Principal -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="bi bi-people"></i> Gestión de Clientes</h1>
                </div>

                @php
                    try {
                        // Consultas directas y simples
                        $clientes = \App\Models\Cliente::orderBy('nombres')->take(20)->get();
                        $totalClientes = \App\Models\Cliente::count();
                        $clientesActivos = \App\Models\Cliente::where('activo', true)->count();
                        $clientesInactivos = \App\Models\Cliente::where('activo', false)->count();
                    } catch(\Exception $e) {
                        $clientes = collect();
                        $totalClientes = 0;
                        $clientesActivos = 0;
                        $clientesInactivos = 0;
                        $error = $e->getMessage();
                    }
                @endphp

                @if(isset($error))
                <div class="alert alert-danger">
                    <strong>Error:</strong> {{ $error }}
                </div>
                @endif

                <!-- Estadísticas -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4>{{ $totalClientes }}</h4>
                                <p>Total Clientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>{{ $clientesActivos }}</h4>
                                <p>Clientes Activos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4>{{ $clientesInactivos }}</h4>
                                <p>Clientes Inactivos</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de clientes -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="bi bi-table"></i> Directorio de Clientes ({{ $totalClientes }})</h5>
                        <button class="btn btn-success btn-sm">
                            <i class="bi bi-plus"></i> Nuevo Cliente
                        </button>
                    </div>
                    <div class="card-body">
                        @if(count($clientes) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre Completo</th>
                                        <th>Documento</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clientes as $cliente)
                                    <tr>
                                        <td>{{ $cliente->id }}</td>
                                        <td>{{ $cliente->nombres }} {{ $cliente->apellidos }}</td>
                                        <td>{{ $cliente->documento ?? 'N/A' }}</td>
                                        <td>{{ $cliente->telefono ?? 'N/A' }}</td>
                                        <td>{{ $cliente->email ?? 'N/A' }}</td>
                                        <td>
                                            @if($cliente->activo)
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No hay clientes registrados</p>
                            <button class="btn btn-primary">
                                <i class="bi bi-plus"></i> Registrar Primer Cliente
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="bi bi-lightning"></i> Acciones Rápidas</h6>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-outline-success me-2">
                                    <i class="bi bi-plus"></i> Nuevo Cliente
                                </button>
                                <button class="btn btn-outline-info me-2">
                                    <i class="bi bi-download"></i> Exportar Lista
                                </button>
                                <button class="btn btn-outline-warning me-2">
                                    <i class="bi bi-search"></i> Buscar Cliente
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-graph-up"></i> Reportes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 