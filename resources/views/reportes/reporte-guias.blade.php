@extends('layouts.modern')

@section('title', 'Reporte de Guías - Reportes')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-file-earmark-ruled me-3"></i>Reporte de Guías
        </h1>
        <p class="text-muted mb-0">Análisis detallado de guías de remisión por estado y período</p>
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
        Filtros de Búsqueda
    </h5>
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Fecha Inicio</label>
            <input type="date" class="form-control" name="fecha_inicio" value="{{ $fechaInicio }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Fecha Fin</label>
            <input type="date" class="form-control" name="fecha_fin" value="{{ $fechaFin }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado">
                @foreach($estados as $est)
                    <option value="{{ $est }}" {{ $estado == $est ? 'selected' : '' }}>
                        {{ $est == 'TODOS' ? 'Todos los estados' : ucfirst(strtolower($est)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-modern me-2">
                <i class="bi bi-search me-1"></i> Filtrar
            </button>
            <a href="{{ route('reportes.reporte-guias') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas por Estado -->
<div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="bi bi-file-earmark-ruled"></i>
            </div>
            <div class="stat-value">{{ $estadisticas['total_guias'] }}</div>
            <div class="stat-label">Total Guías</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="bi bi-clock"></i>
            </div>
            <div class="stat-value">{{ $estadisticas['pendientes'] }}</div>
            <div class="stat-label">Pendientes</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="bi bi-truck"></i>
            </div>
            <div class="stat-value">{{ $estadisticas['en_transito'] }}</div>
            <div class="stat-label">En Tránsito</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-value">{{ $estadisticas['entregadas'] }}</div>
            <div class="stat-label">Entregadas</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-8 col-sm-12 mb-3">
        <div class="stat-card danger">
            <div class="stat-icon danger">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-value">S/ {{ number_format($estadisticas['valor_total'], 2) }}</div>
            <div class="stat-label">Valor Total</div>
        </div>
    </div>
</div>

<!-- Gráfico de Estado de Guías -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="modern-card">
            <h5 class="mb-3">
                <i class="bi bi-pie-chart text-primary me-2"></i>
                Distribución por Estado
            </h5>
            <canvas id="estadoGuiasChart" width="400" height="200"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-card">
            <h5 class="mb-3">
                <i class="bi bi-calculator text-success me-2"></i>
                Estadísticas Adicionales
            </h5>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span>Promedio productos por guía:</span>
                <strong>{{ number_format($estadisticas['promedio_productos_por_guia'], 1) }}</strong>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span>Valor promedio por guía:</span>
                <strong>S/ {{ $estadisticas['total_guias'] > 0 ? number_format($estadisticas['valor_total'] / $estadisticas['total_guias'], 2) : '0.00' }}</strong>
            </div>
            <div class="d-flex justify-content-between py-2">
                <span>Eficiencia de entrega:</span>
                <strong>{{ $estadisticas['total_guias'] > 0 ? number_format(($estadisticas['entregadas'] / $estadisticas['total_guias']) * 100, 1) : '0' }}%</strong>
            </div>
        </div>
    </div>
</div>

<!-- Detalle de Guías -->
<div class="modern-card">
    <h5 class="mb-3">
        <i class="bi bi-table text-primary me-2"></i>
        Detalle de Guías de Remisión
    </h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-primary">
                <tr>
                    <th>N° Guía</th>
                    <th>Fecha Emisión</th>
                    <th>Cliente</th>
                    <th>Dirección Destino</th>
                    <th>N° Productos</th>
                    <th>Valor Total</th>
                    <th>Estado</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guias as $guia)
                <tr>
                    <td><strong>{{ $guia->numero_guia }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($guia->fecha_emision)->format('d/m/Y') }}</td>
                    <td>
                        <div>
                            <strong>{{ $guia->cliente_nombre }}</strong><br>
                            <small class="text-muted">{{ $guia->cliente_documento }}</small>
                        </div>
                    </td>
                    <td>{{ $guia->cliente_direccion ?? $guia->destino }}</td>
                    <td class="text-center">
                        <span class="badge bg-info">{{ $guia->total_productos }}</span>
                    </td>
                    <td class="text-end">
                        <strong>S/ {{ number_format($guia->valor_total ?? 0, 2) }}</strong>
                    </td>
                    <td>
                        @switch($guia->estado)
                            @case('PENDIENTE')
                                <span class="badge bg-warning">
                                    <i class="bi bi-clock me-1"></i>Pendiente
                                </span>
                                @break
                            @case('EN_TRANSITO')
                                <span class="badge bg-info">
                                    <i class="bi bi-truck me-1"></i>En Tránsito
                                </span>
                                @break
                            @case('ENTREGADO')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Entregado
                                </span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ $guia->estado }}</span>
                        @endswitch
                    </td>
                    <td>{{ $guia->usuario }}</td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary" onclick="verDetalle({{ $guia->id }})" title="Ver Detalle">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="descargarPDF({{ $guia->id }})" title="Descargar PDF">
                                <i class="bi bi-file-pdf"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="bi bi-file-earmark-ruled me-2"></i>
                        No hay guías de remisión en el período y estado seleccionado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
// Gráfico de distribución por estado
$(document).ready(function() {
    const ctx = document.getElementById('estadoGuiasChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Pendientes', 'En Tránsito', 'Entregadas'],
            datasets: [{
                data: [
                    {{ $estadisticas['pendientes'] }},
                    {{ $estadisticas['en_transito'] }},
                    {{ $estadisticas['entregadas'] }}
                ],
                backgroundColor: [
                    '#f59e0b',
                    '#06b6d4',
                    '#10b981'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});

function verDetalle(guiaId) {
    mostrarToast('Funcionalidad de detalle en desarrollo', 'info');
}

function descargarPDF(guiaId) {
    window.open(`/guias/${guiaId}/pdf`, '_blank');
}

function exportarPDF() {
    const params = new URLSearchParams(window.location.search);
    window.open(`{{ route('reportes.exportar-pdf', 'reporte-guias') }}?${params.toString()}`, '_blank');
}

function exportarExcel() {
    mostrarToast('Exportación a Excel en desarrollo', 'info');
}
</script>
@endpush
@endsection 