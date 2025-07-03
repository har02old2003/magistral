@extends('layouts.modern')

@section('title', 'Costo Inventario - Reportes')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-calculator me-3"></i>Costo Inventario
        </h1>
        <p class="text-muted mb-0">Análisis de costos con métodos de valoración FIFO, LIFO y Promedio</p>
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
<!-- Configuración del Análisis -->
<div class="modern-card mb-4">
    <h5 class="mb-3">
        <i class="bi bi-gear text-primary me-2"></i>
        Configuración del Análisis
    </h5>
    <form method="GET" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Fecha de Corte</label>
            <input type="date" class="form-control" name="fecha_corte" value="{{ $fecha_corte }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Método de Valoración</label>
            <select class="form-select" name="metodo_valoracion">
                <option value="PROMEDIO" {{ $metodo_valoracion == 'PROMEDIO' ? 'selected' : '' }}>Costo Promedio Ponderado</option>
                <option value="FIFO" {{ $metodo_valoracion == 'FIFO' ? 'selected' : '' }}>FIFO (Primero en Entrar, Primero en Salir)</option>
                <option value="LIFO" {{ $metodo_valoracion == 'LIFO' ? 'selected' : '' }}>LIFO (Último en Entrar, Primero en Salir)</option>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-modern me-2">
                <i class="bi bi-calculator me-1"></i> Recalcular
            </button>
            <a href="{{ route('reportes.costo-inventario') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-clockwise me-1"></i> Restablecer
            </a>
        </div>
    </form>
</div>

<!-- Resumen General -->
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
                <i class="bi bi-stack"></i>
            </div>
            <div class="stat-value">{{ number_format($estadisticas['total_unidades']) }}</div>
            <div class="stat-label">Total Unidades</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-value">S/ {{ number_format($estadisticas['valor_total_inventario'], 2) }}</div>
            <div class="stat-label">Valor Total Inventario</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="bi bi-graph-up"></i>
            </div>
            <div class="stat-value">S/ {{ number_format($estadisticas['costo_promedio_unitario'], 2) }}</div>
            <div class="stat-label">Costo Promedio Unitario</div>
        </div>
    </div>
</div>

<!-- Información del Método -->
<div class="modern-card mb-4">
    <div class="row">
        <div class="col-md-8">
            <h5 class="mb-3">
                <i class="bi bi-info-circle text-info me-2"></i>
                Método de Valoración: {{ $estadisticas['metodo_valoracion'] }}
            </h5>
            <div class="alert alert-info">
                @switch($estadisticas['metodo_valoracion'])
                    @case('PROMEDIO')
                        <strong>Costo Promedio Ponderado:</strong> Se calcula dividiendo el costo total de las existencias entre el número total de unidades disponibles.
                        @break
                    @case('FIFO')
                        <strong>FIFO (Primero en Entrar, Primero en Salir):</strong> Se valora el inventario con los costos más antiguos de las compras.
                        @break
                    @case('LIFO')
                        <strong>LIFO (Último en Entrar, Primero en Salir):</strong> Se valora el inventario con los costos más recientes de las compras.
                        @break
                @endswitch
            </div>
        </div>
        <div class="col-md-4">
            <div class="text-center">
                <div class="mb-2">
                    <strong>Fecha de Corte:</strong><br>
                    <span class="badge bg-primary fs-6">{{ \Carbon\Carbon::parse($estadisticas['fecha_corte'])->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Análisis ABC -->
<div class="modern-card mb-4">
    <h5 class="mb-3">
        <i class="bi bi-graph-up-arrow text-warning me-2"></i>
        Análisis ABC del Inventario
    </h5>
    <div class="row">
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h4 class="text-success">Categoría A</h4>
                    <p class="mb-1"><strong>{{ count($analisisABC['A']['productos']) }}</strong> productos</p>
                    <p class="mb-1">S/ {{ number_format($analisisABC['A']['valor'], 2) }}</p>
                    <p class="text-muted mb-0">{{ number_format($analisisABC['A']['porcentaje'], 1) }}% del valor total</p>
                    <small class="text-muted">Alta rotación - 80% del valor</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h4 class="text-warning">Categoría B</h4>
                    <p class="mb-1"><strong>{{ count($analisisABC['B']['productos']) }}</strong> productos</p>
                    <p class="mb-1">S/ {{ number_format($analisisABC['B']['valor'], 2) }}</p>
                    <p class="text-muted mb-0">{{ number_format($analisisABC['B']['porcentaje'], 1) }}% del valor total</p>
                    <small class="text-muted">Rotación media - 15% del valor</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h4 class="text-info">Categoría C</h4>
                    <p class="mb-1"><strong>{{ count($analisisABC['C']['productos']) }}</strong> productos</p>
                    <p class="mb-1">S/ {{ number_format($analisisABC['C']['valor'], 2) }}</p>
                    <p class="text-muted mb-0">{{ number_format($analisisABC['C']['porcentaje'], 1) }}% del valor total</p>
                    <small class="text-muted">Baja rotación - 5% del valor</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detalle del Inventario -->
<div class="modern-card">
    <h5 class="mb-3">
        <i class="bi bi-table text-primary me-2"></i>
        Detalle del Costo de Inventario
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
                    <th>Costo Unitario</th>
                    <th>Valor Total</th>
                    <th>Clasificación ABC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventario as $item)
                <tr>
                    <td><strong>{{ $item['producto']->codigo }}</strong></td>
                    <td>
                        <div>
                            <strong>{{ $item['producto']->nombre }}</strong><br>
                            <small class="text-muted">{{ $item['producto']->presentacion }}</small>
                        </div>
                    </td>
                    <td><span class="badge bg-secondary">{{ $item['categoria'] }}</span></td>
                    <td><span class="badge bg-info">{{ $item['marca'] }}</span></td>
                    <td class="text-center">
                        <span class="badge bg-primary">{{ number_format($item['stock_actual']) }}</span>
                    </td>
                    <td class="text-end">S/ {{ number_format($item['costo_unitario'], 4) }}</td>
                    <td class="text-end">
                        <strong>S/ {{ number_format($item['valor_total'], 2) }}</strong>
                    </td>
                    <td class="text-center">
                        @php
                            $abc = '';
                            $acumulado = 0;
                            foreach($inventario as $temp) {
                                $acumulado += $temp['valor_total'];
                                if($temp['producto']->id == $item['producto']->id) {
                                    $porcentaje = ($acumulado / $estadisticas['valor_total_inventario']) * 100;
                                    if($porcentaje <= 80) $abc = 'A';
                                    elseif($porcentaje <= 95) $abc = 'B';
                                    else $abc = 'C';
                                    break;
                                }
                            }
                        @endphp
                        <span class="badge {{ $abc == 'A' ? 'bg-success' : ($abc == 'B' ? 'bg-warning' : 'bg-info') }}">
                            {{ $abc }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-boxes me-2"></i>
                        No hay productos con stock disponible
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <th colspan="6" class="text-end">TOTAL INVENTARIO:</th>
                    <th class="text-end">S/ {{ number_format($estadisticas['valor_total_inventario'], 2) }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@push('scripts')
<script>
function exportarPDF() {
    const params = new URLSearchParams(window.location.search);
    window.open(`{{ route('reportes.exportar-pdf', 'costo-inventario') }}?${params.toString()}`, '_blank');
}

function exportarExcel() {
    mostrarToast('Exportación a Excel en desarrollo', 'info');
}
</script>
@endpush
@endsection 