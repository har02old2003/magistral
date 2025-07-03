@extends('layouts.modern')

@section('title', 'Reporte de Stock - Reportes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-boxes me-3"></i>Reporte de Stock
        </h1>
        <p class="text-muted mb-0">Control y análisis del inventario actual</p>
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

<!-- Filtros -->
<div class="modern-card mb-4">
    <h5 class="mb-3">
        <i class="bi bi-funnel text-primary me-2"></i>
        Filtros de Búsqueda
    </h5>
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Categoría</label>
            <select class="form-select" name="categoria_id">
                <option value="">Todas las categorías</option>
                @if(isset($categorias))
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Marca</label>
            <select class="form-select" name="marca_id">
                <option value="">Todas las marcas</option>
                @if(isset($marcas))
                    @foreach($marcas as $marca)
                        <option value="{{ $marca->id }}" {{ request('marca_id') == $marca->id ? 'selected' : '' }}>
                            {{ $marca->nombre }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Estado del Stock</label>
            <select class="form-select" name="estado_stock">
                <option value="">Todos los estados</option>
                <option value="normal" {{ request('estado_stock') == 'normal' ? 'selected' : '' }}>Stock Normal</option>
                <option value="bajo" {{ request('estado_stock') == 'bajo' ? 'selected' : '' }}>Stock Bajo</option>
                <option value="agotado" {{ request('estado_stock') == 'agotado' ? 'selected' : '' }}>Agotado</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-modern me-2">
                <i class="bi bi-search me-1"></i> Filtrar
            </button>
            <a href="{{ route('reportes.stock') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="bi bi-boxes"></i>
            </div>
            <div class="stat-value">{{ isset($estadisticas['total_productos']) ? $estadisticas['total_productos'] : 0 }}</div>
            <div class="stat-label">Total Productos</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-value">{{ isset($estadisticas['stock_normal']) ? $estadisticas['stock_normal'] : 0 }}</div>
            <div class="stat-label">Stock Normal</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-value">{{ isset($estadisticas['stock_bajo']) ? $estadisticas['stock_bajo'] : 0 }}</div>
            <div class="stat-label">Stock Bajo</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card danger">
            <div class="stat-icon danger">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-value">{{ isset($estadisticas['agotados']) ? $estadisticas['agotados'] : 0 }}</div>
            <div class="stat-label">Agotados</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-8 col-sm-12 mb-3">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-value">S/ {{ isset($estadisticas['valor_total_inventario']) ? number_format($estadisticas['valor_total_inventario'], 2) : '0.00' }}</div>
            <div class="stat-label">Valor Total Inventario</div>
        </div>
    </div>
</div>

<!-- Tabla de Productos -->
<div class="modern-card">
    <h5 class="mb-3">
        <i class="bi bi-table text-primary me-2"></i>
        Detalle del Stock
    </h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th>Stock Actual</th>
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                    <th>Valor Stock</th>
                    <th>Estado</th>
                    <th>Fecha Venc.</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($productos) && $productos->count() > 0)
                    @foreach($productos as $producto)
                    <tr>
                        <td><strong>{{ $producto->codigo ?? 'N/A' }}</strong></td>
                        <td>
                            <div>
                                <strong>{{ $producto->nombre ?? 'Sin nombre' }}</strong><br>
                                <small class="text-muted">{{ $producto->presentacion ?? 'Sin presentación' }}</small>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</span></td>
                        <td><span class="badge bg-info">{{ $producto->marca->nombre ?? 'Sin marca' }}</span></td>
                        <td class="text-center">
                            <span class="badge {{ ($producto->stock_actual ?? 0) <= 0 ? 'bg-danger' : (($producto->stock_actual ?? 0) <= 10 ? 'bg-warning' : 'bg-success') }}">
                                {{ $producto->stock_actual ?? 0 }}
                            </span>
                        </td>
                        <td class="text-end">S/ {{ number_format($producto->precio_compra ?? 0, 2) }}</td>
                        <td class="text-end">S/ {{ number_format($producto->precio_venta ?? 0, 2) }}</td>
                        <td class="text-end">
                            <strong>S/ {{ number_format(($producto->stock_actual ?? 0) * ($producto->precio_compra ?? 0), 2) }}</strong>
                        </td>
                        <td>
                            @if(($producto->stock_actual ?? 0) <= 0)
                                <span class="badge bg-danger">Agotado</span>
                            @elseif(($producto->stock_actual ?? 0) <= 10)
                                <span class="badge bg-warning">Stock Bajo</span>
                            @else
                                <span class="badge bg-success">Normal</span>
                            @endif
                        </td>
                        <td>
                            @if($producto->fecha_vencimiento)
                                @php
                                    $fechaVenc = \Carbon\Carbon::parse($producto->fecha_vencimiento);
                                    $diasParaVencer = $fechaVenc->diffInDays(now());
                                    $yaVencio = $fechaVenc->isPast();
                                @endphp
                                <span class="badge {{ $yaVencio ? 'bg-danger' : ($diasParaVencer <= 30 ? 'bg-warning' : 'bg-success') }}">
                                    {{ $fechaVenc->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-muted">Sin fecha</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        <i class="bi bi-boxes me-2"></i>
                        No hay productos que coincidan con los filtros seleccionados
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function exportarPDF() {
    const params = new URLSearchParams(window.location.search);
    window.open(`{{ route('reportes.exportar-pdf', 'stock') }}?${params.toString()}`, '_blank');
}

function exportarExcel() {
    mostrarToast('Exportación a Excel en desarrollo', 'info');
}
</script>
@endpush
@endsection 