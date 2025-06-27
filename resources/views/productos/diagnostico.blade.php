@extends('layouts.app')

@section('title', 'Diagnóstico Productos')

@section('content')
<div class="container-fluid">
    <h1>🔧 Diagnóstico de Productos</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <h3>Tests del Sistema</h3>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item">{{ $testPhp ?? 'Test PHP no disponible' }}</li>
                <li class="list-group-item">{{ $testBd ?? 'Test BD no disponible' }}</li>
                <li class="list-group-item">{{ $testProductos ?? 'Test Productos no disponible' }}</li>
                <li class="list-group-item">{{ $testVista ?? 'Test Vista no disponible' }}</li>
            </ul>
        </div>
    </div>
    
    @if(isset($productos) && count($productos) > 0)
    <div class="card">
        <div class="card-header">
            <h3>Productos Encontrados ({{ count($productos) }})</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Stock</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->codigo }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->stock_actual }}</td>
                            <td>S/ {{ number_format($producto->precio_venta, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-warning">
        <h4>⚠️ No hay productos</h4>
        <p>La consulta no devolvió productos. Esto puede deberse a:</p>
        <ul>
            <li>La tabla productos está vacía</li>
            <li>Los seeders no se ejecutaron</li>
            <li>Hay un error en la consulta</li>
        </ul>
    </div>
    @endif
    
    <div class="mt-4">
        <a href="{{ route('productos.index') }}" class="btn btn-primary">← Volver a Productos</a>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">🏠 Dashboard</a>
    </div>
</div>
@endsection 