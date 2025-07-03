@extends('layouts.modern')

@section('title', 'Guías y Transferencias - Reportes')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-truck me-3"></i>Guías y Transferencias
        </h1>
        <p class="text-muted mb-0">Reporte completo de guías de remisión y movimientos de transferencia</p>
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
        <div class="col-md-4">
            <label class="form-label">Fecha Inicio</label>
            <input type="date" class="form-control" name="fecha_inicio" value="{{ $fechaInicio }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Fecha Fin</label>
            <input type="date" class="form-control" name="fecha_fin" value="{{ $fechaFin }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-modern me-2">
                <i class="bi bi-search me-1"></i> Filtrar
            </button>
            <a href="{{ route('reportes.guias-transferencias') }}" class="btn btn-outline-secondary">
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
                <i class="bi bi-file-earmark-ruled"></i>
            </div>
            <div class="stat-value">{{ $estadisticas['total_guias'] }}</div>
            <div class="stat-label">Total Guías</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <div class="stat-value">{{ $estadisticas['total_transferencias'] }}</div>
            <div class="stat-label">Transferencias</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="bi bi-clock"></i>
            </div>
            <div class="stat-value">{{ $estadisticas['guias_pendientes'] }}</div>
            <div class="stat-label">Pendientes</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-value">{{ $estadisticas['guias_entregadas'] }}</div>
            <div class="stat-label">Entregadas</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-8 col-sm-12 mb-3">
        <div class="stat-card danger">
            <div class="stat-icon danger">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-value">S/ {{ number_format($estadisticas['valor_total_transferencias'], 2) }}</div>
            <div class="stat-label">Valor Total Transferencias</div>
        </div>
    </div>
</div>

<!-- Guías de Remisión -->
<div class="modern-card mb-4">
    <h5 class="mb-3">
        <i class="bi bi-file-earmark-ruled text-primary me-2"></i>
        Guías de Remisión
    </h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-primary">
                <tr>
                    <th>N° Guía</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Destino</th>
                    <th>Estado</th>
                    <th>Usuario</th>
                    <th>Observaciones</th>
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
                    <td>{{ $guia->destino }}</td>
                    <td>
                        @switch($guia->estado)
                            @case('PENDIENTE')
                                <span class="badge bg-warning">Pendiente</span>
                                @break
                            @case('EN_TRANSITO')
                                <span class="badge bg-info">En Tránsito</span>
                                @break
                            @case('ENTREGADO')
                                <span class="badge bg-success">Entregado</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ $guia->estado }}</span>
                        @endswitch
                    </td>
                    <td>{{ $guia->usuario }}</td>
                    <td>{{ $guia->observaciones ?? 'Sin observaciones' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="bi bi-file-earmark-ruled me-2"></i>
                        No hay guías de remisión en el período seleccionado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Transferencias -->
<div class="modern-card">
    <h5 class="mb-3">
        <i class="bi bi-arrow-left-right text-success me-2"></i>
        Movimientos de Transferencia
    </h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-success">
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Motivo</th>
                    <th>Costo Total</th>
                    <th>Usuario</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transferencias as $transferencia)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($transferencia->fecha_movimiento)->format('d/m/Y H:i') }}</td>
                    <td>
                        <div>
                            <strong>{{ $transferencia->producto_nombre }}</strong><br>
                            <small class="text-muted">Código: {{ $transferencia->producto_codigo }}</small>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-primary">{{ $transferencia->cantidad }} unidades</span>
                    </td>
                    <td>{{ $transferencia->motivo ?? 'Transferencia general' }}</td>
                    <td class="text-end">
                        <strong>S/ {{ number_format(($transferencia->cantidad * ($transferencia->precio_costo ?? 0)), 2) }}</strong>
                    </td>
                    <td>{{ $transferencia->usuario }}</td>
                    <td>{{ $transferencia->observaciones ?? 'Sin observaciones' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="bi bi-arrow-left-right me-2"></i>
                        No hay transferencias en el período seleccionado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function exportarPDF() {
    const fechaInicio = '{{ $fechaInicio }}';
    const fechaFin = '{{ $fechaFin }}';
    window.open(`{{ route('reportes.exportar-pdf', 'guias-transferencias') }}?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`, '_blank');
}

function exportarExcel() {
    mostrarToast('Exportación a Excel en desarrollo', 'info');
}
</script>
@endpush
@endsection 