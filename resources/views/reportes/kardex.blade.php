@extends('layouts.modern')

@section('title', 'Reporte Kardex - Reportes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-file-earmark-spreadsheet me-3"></i>Reporte Kardex
        </h1>
        <p class="text-muted mb-0">Movimientos detallados de productos por período</p>
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
            <label class="form-label">Fecha Inicio</label>
            <input type="date" class="form-control" name="fecha_inicio" value="{{ $fechaInicio ?? now()->subDays(30)->format('Y-m-d') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Fecha Fin</label>
            <input type="date" class="form-control" name="fecha_fin" value="{{ $fechaFin ?? now()->format('Y-m-d') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Producto</label>
            <select class="form-select" name="producto_id">
                <option value="">Todos los productos</option>
                @if(isset($productos))
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" {{ request('producto_id') == $producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-modern me-2">
                <i class="bi bi-search me-1"></i> Filtrar
            </button>
            <a href="{{ route('reportes.kardex') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Resumen del Período -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="modern-card">
            <h5 class="mb-3">
                <i class="bi bi-calendar-range text-info me-2"></i>
                Resumen del Período: {{ isset($fechaInicio) ? \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') : now()->subDays(30)->format('d/m/Y') }} - {{ isset($fechaFin) ? \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') : now()->format('d/m/Y') }}
            </h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded">
                        <h4 class="text-primary">{{ isset($movimientos) ? $movimientos->count() : 0 }}</h4>
                        <p class="mb-0">Total Movimientos</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded">
                        <h4 class="text-success">{{ isset($movimientos) ? $movimientos->where('tipo_movimiento', 'SALIDA')->sum('cantidad') : 0 }}</h4>
                        <p class="mb-0">Unidades Vendidas</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded">
                        <h4 class="text-info">{{ isset($movimientos) ? $movimientos->where('tipo_movimiento', 'INGRESO')->sum('cantidad') : 0 }}</h4>
                        <p class="mb-0">Unidades Ingresadas</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded">
                        <h4 class="text-warning">S/ {{ isset($movimientos) ? number_format($movimientos->sum('total'), 2) : '0.00' }}</h4>
                        <p class="mb-0">Valor Total Movimientos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detalle de Movimientos -->
<div class="modern-card">
    <h5 class="mb-3">
        <i class="bi bi-table text-primary me-2"></i>
        Detalle de Movimientos Kardex
    </h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Código</th>
                    <th>Tipo Movimiento</th>
                    <th>Cantidad</th>
                    <th>Precio Unit.</th>
                    <th>Total</th>
                    <th>Usuario</th>
                    <th>Stock Resultante</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($movimientos) && $movimientos->count() > 0)
                    @foreach($movimientos as $movimiento)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y H:i') }}</td>
                        <td>
                            <div>
                                <strong>{{ $movimiento->producto ?? 'Sin producto' }}</strong>
                            </div>
                        </td>
                        <td><strong>{{ $movimiento->codigo ?? 'N/A' }}</strong></td>
                        <td>
                            @switch($movimiento->tipo_movimiento ?? 'DESCONOCIDO')
                                @case('SALIDA')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-arrow-up me-1"></i>SALIDA
                                    </span>
                                    @break
                                @case('INGRESO')
                                    <span class="badge bg-success">
                                        <i class="bi bi-arrow-down me-1"></i>INGRESO
                                    </span>
                                    @break
                                @case('TRANSFERENCIA')
                                    <span class="badge bg-warning">
                                        <i class="bi bi-arrow-left-right me-1"></i>TRANSFERENCIA
                                    </span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $movimiento->tipo_movimiento ?? 'DESCONOCIDO' }}</span>
                            @endswitch
                        </td>
                        <td class="text-center">
                            <span class="badge {{ ($movimiento->tipo_movimiento ?? '') == 'SALIDA' ? 'bg-danger' : 'bg-success' }}">
                                {{ (($movimiento->tipo_movimiento ?? '') == 'SALIDA' ? '-' : '+') . ($movimiento->cantidad ?? 0) }}
                            </span>
                        </td>
                        <td class="text-end">S/ {{ number_format($movimiento->precio_unitario ?? 0, 2) }}</td>
                        <td class="text-end">
                            <strong>S/ {{ number_format($movimiento->total ?? 0, 2) }}</strong>
                        </td>
                        <td>{{ $movimiento->usuario ?? 'Sin usuario' }}</td>
                        <td class="text-center">
                            @php
                                // Calcular stock resultante (esto debería venir de la lógica del controlador)
                                $stockResultante = $movimiento->stock_resultante ?? 'N/A';
                            @endphp
                            <span class="badge bg-info">{{ $stockResultante }}</span>
                        </td>
                    </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="bi bi-file-earmark-spreadsheet me-2"></i>
                        No hay movimientos en el período seleccionado
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Gráfico de Movimientos (si hay datos) -->
@if(isset($movimientos) && $movimientos->count() > 0)
<div class="modern-card mt-4">
    <h5 class="mb-3">
        <i class="bi bi-graph-up text-success me-2"></i>
        Tendencia de Movimientos
    </h5>
    <div class="row">
        <div class="col-md-8">
            <canvas id="movimientosChart" width="400" height="200"></canvas>
        </div>
        <div class="col-md-4">
            <h6>Resumen por Tipo:</h6>
            <ul class="list-unstyled">
                <li class="d-flex justify-content-between py-1">
                    <span><i class="bi bi-circle-fill text-danger me-2"></i>Salidas:</span>
                    <strong>{{ $movimientos->where('tipo_movimiento', 'SALIDA')->count() }}</strong>
                </li>
                <li class="d-flex justify-content-between py-1">
                    <span><i class="bi bi-circle-fill text-success me-2"></i>Ingresos:</span>
                    <strong>{{ $movimientos->where('tipo_movimiento', 'INGRESO')->count() }}</strong>
                </li>
                <li class="d-flex justify-content-between py-1">
                    <span><i class="bi bi-circle-fill text-warning me-2"></i>Transferencias:</span>
                    <strong>{{ $movimientos->where('tipo_movimiento', 'TRANSFERENCIA')->count() }}</strong>
                </li>
            </ul>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
@if(isset($movimientos) && $movimientos->count() > 0)
// Preparar datos para el gráfico
$(document).ready(function() {
    const ctx = document.getElementById('movimientosChart').getContext('2d');
    
    // Crear datos de ejemplo para el gráfico
    const labels = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
    const salidas = [12, 19, 3, 5, 2, 3, 7];
    const ingresos = [2, 3, 20, 5, 1, 4, 6];
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Salidas',
                    data: salidas,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Ingresos',
                    data: ingresos,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
@endif

function exportarPDF() {
    const params = new URLSearchParams(window.location.search);
    window.open(`{{ route('reportes.exportar-pdf', 'kardex') }}?${params.toString()}`, '_blank');
}

function exportarExcel() {
    mostrarToast('Exportación a Excel en desarrollo', 'info');
}
</script>
@endpush
@endsection 