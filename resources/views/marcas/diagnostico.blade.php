@extends('layouts.app')

@section('title', 'Diagnóstico Marcas')

@section('content')
<div class="container-fluid">
    <h1>🔧 Diagnóstico de Marcas</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <h3>Tests del Sistema</h3>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item">{{ $testPhp ?? 'Test PHP no disponible' }}</li>
                <li class="list-group-item">{{ $testBd ?? 'Test BD no disponible' }}</li>
                <li class="list-group-item">{{ $testMarcas ?? 'Test Marcas no disponible' }}</li>
                <li class="list-group-item">{{ $testVista ?? 'Test Vista no disponible' }}</li>
            </ul>
        </div>
    </div>
    
    @if(isset($marcas) && count($marcas) > 0)
    <div class="card">
        <div class="card-header">
            <h3>Marcas Encontradas ({{ count($marcas) }})</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Productos</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($marcas as $marca)
                    <tr>
                        <td>{{ $marca->id }}</td>
                        <td>{{ $marca->nombre }}</td>
                        <td>{{ $marca->descripcion ?? 'Sin descripción' }}</td>
                        <td>{{ $marca->productos_count ?? 0 }}</td>
                        <td>
                            @if($marca->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="alert alert-warning">
        <h4>⚠️ No hay marcas</h4>
        <p>La consulta no devolvió marcas.</p>
    </div>
    @endif
    
    <div class="mt-4">
        <a href="{{ route('marcas.index') }}" class="btn btn-primary">← Volver a Marcas</a>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">🏠 Dashboard</a>
    </div>
</div>
@endsection
