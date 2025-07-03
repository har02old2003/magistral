@extends('layouts.modern')

@section('title', 'Stock Valorizado - Reportes')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-graph-up me-3"></i>Stock Valorizado
        </h1>
        <p class="text-muted mb-0">Análisis del valor económico del inventario actual</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success btn-modern" onclick="exportarPDF()">
            <i class="bi bi-file-pdf me-1"></i> Exportar PDF
        </button>
        <button class="btn btn-info btn-modern" onclick="exportarExcel()">
            <i class="bi bi-file-excel me-1"></i> Exportar Excel
        </button>
    </div>
</div>
@endsection

@section('content')
<!-- Filtros -->
<div class="modern-card mb-4">
    <h5 class="mb-3">
        <i class="bi bi-funnel text-primary me-2"></i>
        Filtros de Análisis
    </h5>
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Categoría</label>
            <select class="form-select" name="categoria_id">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ $categoria_id == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Marca</label>
            <select class="form-select" name="marca_id">
                <option value="">Todas las marcas</option>
                @foreach($marcas as $marca)
                    <option value="{{ $marca->id }}" {{ $marca_id == $marca->id ? 'selected' : '' }}>
                        {{ $marca->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Proveedor</label>
            <select class="form-select" name="proveedor_id">
                <option value="">Todos los proveedores</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}" {{ $proveedor_id == $proveedor->id ? 'selected' : '' }}>
                        {{ $proveedor->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-modern me-2">
                <i class="bi bi-search me-1"></i> Filtrar
            </button>
            <a href="{{ route('reportes.stock-valorizado') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas Generales -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="bi bi-boxes"></i>
            </div>
            <div class="stat-value">{{ $estadisticas['total_productos'] }}</div>
            <div class="stat-label">Total Productos</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-value">S/ {{ number_format($estadisticas['valor_total_compra'], 2) }}</div>
            <div class="stat-label">Valor Total Compra</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="bi bi-graph-up"></i>
            </div>
            <div class="stat-value">S/ {{ number_format($estadisticas['valor_total_venta'], 2) }}</div>
            <div class="stat-label">Valor Total Venta</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="bi bi-percent"></i>
            </div>
            <div class="stat-value">{{ number_format($estadisticas['porcentaje_utilidad'], 1) }}%</div>
            <div class="stat-label">Margen Utilidad</div>
        </div>
    </div>
</div>

<!-- Resumen de Utilidad -->
<div class="modern-card mb-4">
    <div class="row">
        <div class="col-md-8">
            <h5 class="mb-3">
                <i class="bi bi-pie-chart text-warning me-2"></i>
                Análisis de Utilidad Potencial
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Valor Total en Compra:</span>
                        <strong class="text-primary">S/ {{ number_format($estadisticas['valor_total_compra'], 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Valor Total en Venta:</span>
                        <strong class="text-success">S/ {{ number_format($estadisticas['valor_total_venta'], 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>Utilidad Potencial Total:</span>
                        <strong class="text-warning">S/ {{ number_format($estadisticas['utilidad_potencial_total'], 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-center justify-content-center">
            <div class="text-center">
                <div style="font-size: 3rem; color: var(--warning);">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <h3 class="text-warning mb-0">{{ number_format($estadisticas['porcentaje_utilidad'], 1) }}%</h3>
                <p class="text-muted">Margen Promedio</p>
            </div>
        </div>
    </div>
</div>

<!-- Detalle de Productos -->
<div class="modern-card">
    <h5 class="mb-3">
        <i class="bi bi-table text-primary me-2"></i>
        Detalle del Stock Valorizado
    </h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th>Stock</th>
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                    <th>Valor Compra</th>
                    <th>Valor Venta</th>
                    <th>Utilidad Pot.</th>
                    <th>% Margen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                <tr>
                    <td><strong>{{ $producto->codigo }}</strong></td>
                    <td>
                        <div>
                            <strong>{{ $producto->nombre }}</strong><br>
                            <small class="text-muted">{{ $producto->presentacion }}</small>
                        </div>
                    </td>
                    <td><span class="badge bg-secondary">{{ $producto->categoria_nombre }}</span></td>
                    <td><span class="badge bg-info">{{ $producto->marca_nombre }}</span></td>
                    <td class="text-center">
                        <span class="badge bg-primary">{{ $producto->stock_actual }}</span>
                    </td>
                    <td class="text-end">S/ {{ number_format($producto->precio_compra, 2) }}</td>
                    <td class="text-end">S/ {{ number_format($producto->precio_venta, 2) }}</td>
                    <td class="text-end">
                        <strong class="text-primary">S/ {{ number_format($producto->valor_compra, 2) }}</strong>
                    </td>
                    <td class="text-end">
                        <strong class="text-success">S/ {{ number_format($producto->valor_venta, 2) }}</strong>
                    </td>
                    <td class="text-end">
                        <strong class="text-warning">S/ {{ number_format($producto->utilidad_potencial, 2) }}</strong>
                    </td>
                    <td class="text-center">
                        @php
                            $margen = $producto->precio_compra > 0 ? (($producto->precio_venta - $producto->precio_compra) / $producto->precio_compra) * 100 : 0;
                        @endphp
                        <span class="badge {{ $margen > 30 ? 'bg-success' : ($margen > 15 ? 'bg-warning' : 'bg-danger') }}">
                            {{ number_format($margen, 1) }}%
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center text-muted py-4">
                        <i class="bi bi-boxes me-2"></i>
                        No hay productos con stock disponible
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <th colspan="7" class="text-end">TOTALES:</th>
                    <th class="text-end">S/ {{ number_format($estadisticas['valor_total_compra'], 2) }}</th>
                    <th class="text-end">S/ {{ number_format($estadisticas['valor_total_venta'], 2) }}</th>
                    <th class="text-end">S/ {{ number_format($estadisticas['utilidad_potencial_total'], 2) }}</th>
                    <th class="text-center">{{ number_format($estadisticas['porcentaje_utilidad'], 1) }}%</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@push('scripts')
<script>
function exportarPDF() {
    const params = new URLSearchParams(window.location.search);
    window.open(`{{ route('reportes.exportar-pdf', 'stock-valorizado') }}?${params.toString()}`, '_blank');
}

function exportarExcel() {
    mostrarToast('Exportación a Excel en desarrollo', 'info');
}
</script>
@endpush
@endsection 