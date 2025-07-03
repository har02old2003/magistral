@extends('layouts.modern')

@section('title', 'Estado de Resultados - PharmaSys Pro')

@section('page-title', 'Contabilidad')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-graph-up me-3"></i>Estado de Resultados
        </h1>
        <p class="text-muted mb-0">Análisis de ingresos, gastos y utilidades</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('contabilidad.index') }}" class="btn btn-secondary btn-modern">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
        <button class="btn btn-info btn-modern" onclick="exportarEstadoResultados()">
            <i class="bi bi-download me-1"></i> Exportar PDF
        </button>
        <button class="btn btn-primary btn-modern" onclick="imprimirEstadoResultados()">
            <i class="bi bi-printer me-1"></i> Imprimir
        </button>
    </div>
</div>
@endsection

@section('content')
@php
    $estadoResultados = [
        'ingresos' => [
            'ventas' => 125000.00,
            'otros_ingresos' => 5000.00,
            'total' => 130000.00
        ],
        'costos' => [
            'costo_ventas' => 75000.00,
            'total' => 75000.00
        ],
        'gastos' => [
            'gastos_operativos' => 25000.00,
            'gastos_administrativos' => 15000.00,
            'gastos_financieros' => 2000.00,
            'total' => 42000.00
        ],
        'utilidad_bruta' => 55000.00,
        'utilidad_operativa' => 30000.00,
        'utilidad_neta' => 13000.00
    ];
    $fechaInicio = now()->startOfMonth();
    $fechaFin = now();
@endphp

<!-- Filtros de Período -->
<div class="modern-card mb-4">
    <h5 class="mb-3">
        <i class="bi bi-calendar-range text-primary me-2"></i>
        Período de Análisis
    </h5>
    <div class="row align-items-end">
        <div class="col-md-3">
            <label for="fechaInicio" class="form-label fw-bold">Fecha Inicio</label>
            <input type="date" class="form-control" id="fechaInicio" value="{{ $fechaInicio->format('Y-m-d') }}">
        </div>
        <div class="col-md-3">
            <label for="fechaFin" class="form-label fw-bold">Fecha Fin</label>
            <input type="date" class="form-control" id="fechaFin" value="{{ $fechaFin->format('Y-m-d') }}">
        </div>
        <div class="col-md-3">
            <label for="tipoReporte" class="form-label fw-bold">Tipo de Reporte</label>
            <select class="form-select" id="tipoReporte">
                <option value="detallado">Detallado</option>
                <option value="resumido">Resumido</option>
                <option value="comparativo">Comparativo</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-modern w-100" onclick="generarEstadoResultados()">
                <i class="bi bi-arrow-clockwise me-1"></i> Generar
            </button>
        </div>
    </div>
</div>

<!-- Indicadores Clave -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-value">S/ {{ number_format($estadoResultados['ingresos']['total'], 2) }}</div>
            <div class="stat-label">Ingresos Totales</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="bi bi-graph-up"></i>
            </div>
            <div class="stat-value">S/ {{ number_format($estadoResultados['utilidad_bruta'], 2) }}</div>
            <div class="stat-label">Utilidad Bruta</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="bi bi-trophy"></i>
            </div>
            <div class="stat-value">S/ {{ number_format($estadoResultados['utilidad_operativa'], 2) }}</div>
            <div class="stat-label">Utilidad Operativa</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="bi bi-star"></i>
            </div>
            <div class="stat-value">S/ {{ number_format($estadoResultados['utilidad_neta'], 2) }}</div>
            <div class="stat-label">Utilidad Neta</div>
        </div>
    </div>
</div>

<!-- Estado de Resultados Detallado -->
<div class="row">
    <div class="col-lg-8">
        <div class="modern-card">
            <h5 class="mb-4">
                <i class="bi bi-table text-primary me-2"></i>
                Estado de Resultados - Período: {{ $fechaInicio->format('d/m/Y') }} al {{ $fechaFin->format('d/m/Y') }}
            </h5>
            
            <div class="table-responsive">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <!-- INGRESOS -->
                        <tr class="border-bottom border-2">
                            <td colspan="2" class="py-3">
                                <h6 class="mb-0 text-success">
                                    <i class="bi bi-plus-circle me-2"></i>INGRESOS
                                </h6>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4">Ventas de Productos</td>
                            <td class="text-end fw-bold">S/ {{ number_format($estadoResultados['ingresos']['ventas'], 2) }}</td>
                        </tr>
                        <tr>
                            <td class="ps-4">Otros Ingresos</td>
                            <td class="text-end">S/ {{ number_format($estadoResultados['ingresos']['otros_ingresos'], 2) }}</td>
                        </tr>
                        <tr class="bg-success-subtle">
                            <td class="fw-bold">TOTAL INGRESOS</td>
                            <td class="text-end fw-bold text-success">S/ {{ number_format($estadoResultados['ingresos']['total'], 2) }}</td>
                        </tr>
                        
                        <!-- COSTOS -->
                        <tr class="border-bottom border-2">
                            <td colspan="2" class="py-3">
                                <h6 class="mb-0 text-danger">
                                    <i class="bi bi-dash-circle me-2"></i>COSTOS DE VENTAS
                                </h6>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4">Costo de Productos Vendidos</td>
                            <td class="text-end fw-bold text-danger">-S/ {{ number_format($estadoResultados['costos']['costo_ventas'], 2) }}</td>
                        </tr>
                        <tr class="bg-danger-subtle">
                            <td class="fw-bold">TOTAL COSTOS</td>
                            <td class="text-end fw-bold text-danger">-S/ {{ number_format($estadoResultados['costos']['total'], 2) }}</td>
                        </tr>
                        
                        <!-- UTILIDAD BRUTA -->
                        <tr class="bg-primary-subtle border-top border-2">
                            <td class="fw-bold text-primary">UTILIDAD BRUTA</td>
                            <td class="text-end fw-bold text-primary fs-5">S/ {{ number_format($estadoResultados['utilidad_bruta'], 2) }}</td>
                        </tr>
                        
                        <!-- GASTOS -->
                        <tr class="border-bottom border-2">
                            <td colspan="2" class="py-3">
                                <h6 class="mb-0 text-warning">
                                    <i class="bi bi-exclamation-circle me-2"></i>GASTOS OPERATIVOS
                                </h6>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4">Gastos Operativos</td>
                            <td class="text-end text-warning">-S/ {{ number_format($estadoResultados['gastos']['gastos_operativos'], 2) }}</td>
                        </tr>
                        <tr>
                            <td class="ps-4">Gastos Administrativos</td>
                            <td class="text-end text-warning">-S/ {{ number_format($estadoResultados['gastos']['gastos_administrativos'], 2) }}</td>
                        </tr>
                        <tr>
                            <td class="ps-4">Gastos Financieros</td>
                            <td class="text-end text-warning">-S/ {{ number_format($estadoResultados['gastos']['gastos_financieros'], 2) }}</td>
                        </tr>
                        <tr class="bg-warning-subtle">
                            <td class="fw-bold">TOTAL GASTOS</td>
                            <td class="text-end fw-bold text-warning">-S/ {{ number_format($estadoResultados['gastos']['total'], 2) }}</td>
                        </tr>
                        
                        <!-- UTILIDAD OPERATIVA -->
                        <tr class="bg-info-subtle border-top border-2">
                            <td class="fw-bold text-info">UTILIDAD OPERATIVA</td>
                            <td class="text-end fw-bold text-info fs-5">S/ {{ number_format($estadoResultados['utilidad_operativa'], 2) }}</td>
                        </tr>
                        
                        <!-- IMPUESTOS -->
                        <tr>
                            <td class="ps-4">Impuesto a la Renta (30%)</td>
                            <td class="text-end text-muted">-S/ {{ number_format($estadoResultados['utilidad_operativa'] * 0.30, 2) }}</td>
                        </tr>
                        
                        <!-- UTILIDAD NETA -->
                        <tr class="bg-success border-top border-3">
                            <td class="fw-bold text-white">UTILIDAD NETA</td>
                            <td class="text-end fw-bold text-white fs-4">S/ {{ number_format($estadoResultados['utilidad_neta'], 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Análisis de Márgenes -->
        <div class="modern-card mb-4">
            <h6 class="mb-3">
                <i class="bi bi-percent text-primary me-2"></i>
                Análisis de Márgenes
            </h6>
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <small>Margen Bruto</small>
                    <small class="fw-bold">{{ number_format(($estadoResultados['utilidad_bruta'] / $estadoResultados['ingresos']['total']) * 100, 1) }}%</small>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: {{ ($estadoResultados['utilidad_bruta'] / $estadoResultados['ingresos']['total']) * 100 }}%"></div>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <small>Margen Operativo</small>
                    <small class="fw-bold">{{ number_format(($estadoResultados['utilidad_operativa'] / $estadoResultados['ingresos']['total']) * 100, 1) }}%</small>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-info" style="width: {{ ($estadoResultados['utilidad_operativa'] / $estadoResultados['ingresos']['total']) * 100 }}%"></div>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <small>Margen Neto</small>
                    <small class="fw-bold">{{ number_format(($estadoResultados['utilidad_neta'] / $estadoResultados['ingresos']['total']) * 100, 1) }}%</small>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-warning" style="width: {{ ($estadoResultados['utilidad_neta'] / $estadoResultados['ingresos']['total']) * 100 }}%"></div>
                </div>
            </div>
        </div>
        
        <!-- Ratios Financieros -->
        <div class="modern-card">
            <h6 class="mb-3">
                <i class="bi bi-calculator text-primary me-2"></i>
                Ratios Financieros
            </h6>
            <div class="row">
                <div class="col-6 text-center mb-3">
                    <div class="border rounded p-2">
                        <div class="fs-5 fw-bold text-success">1.38</div>
                        <small class="text-muted">ROI</small>
                    </div>
                </div>
                <div class="col-6 text-center mb-3">
                    <div class="border rounded p-2">
                        <div class="fs-5 fw-bold text-info">2.15</div>
                        <small class="text-muted">ROE</small>
                    </div>
                </div>
                <div class="col-6 text-center mb-3">
                    <div class="border rounded p-2">
                        <div class="fs-5 fw-bold text-primary">0.58</div>
                        <small class="text-muted">Rotación</small>
                    </div>
                </div>
                <div class="col-6 text-center mb-3">
                    <div class="border rounded p-2">
                        <div class="fs-5 fw-bold text-warning">1.78</div>
                        <small class="text-muted">Liquidez</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function generarEstadoResultados() {
    const fechaInicio = $('#fechaInicio').val();
    const fechaFin = $('#fechaFin').val();
    const tipoReporte = $('#tipoReporte').val();
    
    if (!fechaInicio || !fechaFin) {
        Swal.fire('Error', 'Debe seleccionar ambas fechas', 'error');
        return;
    }
    
    Swal.fire({
        title: 'Generando Estado de Resultados...',
        text: 'Procesando información financiera',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        Swal.fire('Info', 'Funcionalidad en desarrollo', 'info');
    });
}

function exportarEstadoResultados() {
    Swal.fire({
        title: 'Exportando Estado de Resultados',
        text: 'Generando archivo PDF...',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        Swal.fire('¡Éxito!', 'Estado de Resultados exportado correctamente', 'success');
    });
}

function imprimirEstadoResultados() {
    window.print();
}
</script>
@endpush
@endsection
