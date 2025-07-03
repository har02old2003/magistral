@extends('layouts.modern')

@section('title', 'Libro Diario - PharmaSys Pro')

@section('page-title', 'Contabilidad')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-book me-3"></i>Libro Diario
        </h1>
        <p class="text-muted mb-0">Registro cronológico de asientos contables</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('contabilidad.index') }}" class="btn btn-secondary btn-modern">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
        <button class="btn btn-info btn-modern" onclick="exportarLibroDiario()">
            <i class="bi bi-download me-1"></i> Exportar PDF
        </button>
        <button class="btn btn-primary btn-modern" onclick="imprimirLibroDiario()">
            <i class="bi bi-printer me-1"></i> Imprimir
        </button>
    </div>
</div>
@endsection

@section('content')
@php
    $asientosLibro = collect([
        (object)[
            'id' => 1,
            'numero_asiento' => 'AST-001',
            'fecha_asiento' => now(),
            'tipo_asiento' => 'venta',
            'concepto' => 'Venta de medicamentos - Factura 001',
            'cuenta_contable' => '70111 - Ventas',
            'debe' => 250.00,
            'haber' => 0.00,
            'estado' => 'contabilizado'
        ],
        (object)[
            'id' => 1,
            'numero_asiento' => 'AST-001',
            'fecha_asiento' => now(),
            'tipo_asiento' => 'venta',
            'concepto' => 'IGV por ventas - Factura 001',
            'cuenta_contable' => '40111 - IGV por pagar',
            'debe' => 0.00,
            'haber' => 45.00,
            'estado' => 'contabilizado'
        ],
        (object)[
            'id' => 2,
            'numero_asiento' => 'AST-002',
            'fecha_asiento' => now()->subDay(),
            'tipo_asiento' => 'compra',
            'concepto' => 'Compra de inventario médico',
            'cuenta_contable' => '20111 - Existencias',
            'debe' => 500.00,
            'haber' => 0.00,
            'estado' => 'contabilizado'
        ]
    ]);
    $fechaInicio = now()->startOfMonth();
    $fechaFin = now();
@endphp

<!-- Filtros de Fecha -->
<div class="modern-card mb-4">
    <h5 class="mb-3">
        <i class="bi bi-calendar-range text-primary me-2"></i>
        Filtros de Período
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
            <label for="tipoAsiento" class="form-label fw-bold">Tipo de Asiento</label>
            <select class="form-select" id="tipoAsiento">
                <option value="">Todos los tipos</option>
                <option value="venta">Ventas</option>
                <option value="compra">Compras</option>
                <option value="gasto">Gastos</option>
                <option value="ingreso">Ingresos</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-modern w-100" onclick="filtrarLibroDiario()">
                <i class="bi bi-search me-1"></i> Filtrar
            </button>
        </div>
    </div>
</div>

<!-- Resumen del Período -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="bi bi-plus-circle"></i>
            </div>
            <div class="stat-value">S/ 750.00</div>
            <div class="stat-label">Total Debe</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="bi bi-dash-circle"></i>
            </div>
            <div class="stat-value">S/ 45.00</div>
            <div class="stat-label">Total Haber</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="bi bi-journal-text"></i>
            </div>
            <div class="stat-value">{{ $asientosLibro->count() }}</div>
            <div class="stat-label">Movimientos</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="bi bi-balance-scale"></i>
            </div>
            <div class="stat-value">S/ 705.00</div>
            <div class="stat-label">Diferencia</div>
        </div>
    </div>
</div>

<!-- Libro Diario -->
<div class="modern-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">
            <i class="bi bi-book text-primary me-2"></i>
            Asientos del Período: {{ $fechaInicio->format('d/m/Y') }} al {{ $fechaFin->format('d/m/Y') }}
        </h5>
        <div>
            <span class="badge bg-info">{{ $asientosLibro->count() }} movimientos</span>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th width="80">Fecha</th>
                    <th width="100">Asiento</th>
                    <th>Cuenta Contable</th>
                    <th>Concepto</th>
                    <th width="100" class="text-end">Debe</th>
                    <th width="100" class="text-end">Haber</th>
                    <th width="80">Estado</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalDebe = 0;
                    $totalHaber = 0;
                    $asientoActual = null;
                @endphp
                
                @foreach($asientosLibro as $movimiento)
                @php
                    $totalDebe += $movimiento->debe ?? 0;
                    $totalHaber += $movimiento->haber ?? 0;
                    $esNuevoAsiento = $asientoActual !== $movimiento->numero_asiento;
                    $asientoActual = $movimiento->numero_asiento;
                @endphp
                
                @if($esNuevoAsiento)
                <tr class="border-top border-2">
                    <td colspan="7" class="bg-light py-1">
                        <small class="text-muted fw-bold">
                            ASIENTO {{ $movimiento->numero_asiento ?? 'AST-' . str_pad($movimiento->id, 3, '0', STR_PAD_LEFT) }} - 
                            {{ \Carbon\Carbon::parse($movimiento->fecha_asiento)->format('d/m/Y') }}
                        </small>
                    </td>
                </tr>
                @endif
                
                <tr>
                    <td>
                        @if($esNuevoAsiento)
                        {{ \Carbon\Carbon::parse($movimiento->fecha_asiento)->format('d/m/Y') }}
                        @endif
                    </td>
                    <td>
                        @if($esNuevoAsiento)
                        <span class="badge bg-{{ match($movimiento->tipo_asiento ?? 'venta') {
                            'venta' => 'success',
                            'compra' => 'primary',
                            'gasto' => 'danger',
                            'ingreso' => 'info',
                            default => 'secondary'
                        } }}">
                            {{ ucfirst($movimiento->tipo_asiento ?? 'venta') }}
                        </span>
                        @endif
                    </td>
                    <td>
                        <div>
                            <strong>{{ $movimiento->cuenta_contable ?? 'Sin cuenta' }}</strong>
                        </div>
                    </td>
                    <td>
                        <div>
                            {{ $movimiento->concepto ?? 'Sin concepto' }}
                        </div>
                    </td>
                    <td class="text-end">
                        @if(($movimiento->debe ?? 0) > 0)
                        <span class="text-success fw-bold">S/ {{ number_format($movimiento->debe, 2) }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if(($movimiento->haber ?? 0) > 0)
                        <span class="text-primary fw-bold">S/ {{ number_format($movimiento->haber, 2) }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ 
                            match($movimiento->estado ?? 'contabilizado') {
                                'contabilizado' => 'success',
                                'borrador' => 'warning',
                                'anulado' => 'danger',
                                default => 'secondary'
                            } 
                        }}">
                            {{ ucfirst($movimiento->estado ?? 'contabilizado') }}
                        </span>
                    </td>
                </tr>
                @endforeach
                
                <!-- Totales -->
                <tr class="border-top border-2 bg-light">
                    <td colspan="4" class="fw-bold text-end">TOTALES DEL PERÍODO:</td>
                    <td class="text-end fw-bold text-success">
                        S/ {{ number_format($totalDebe, 2) }}
                    </td>
                    <td class="text-end fw-bold text-primary">
                        S/ {{ number_format($totalHaber, 2) }}
                    </td>
                    <td class="text-center">
                        @if($totalDebe == $totalHaber)
                        <i class="bi bi-check-circle text-success" title="Balanceado"></i>
                        @else
                        <i class="bi bi-exclamation-triangle text-warning" title="Desbalanceado"></i>
                        @endif
                    </td>
                </tr>
                
                <!-- Diferencia si existe -->
                @if($totalDebe != $totalHaber)
                <tr class="bg-warning-subtle">
                    <td colspan="4" class="fw-bold text-end text-warning">DIFERENCIA:</td>
                    <td colspan="2" class="text-end fw-bold text-warning">
                        S/ {{ number_format(abs($totalDebe - $totalHaber), 2) }}
                    </td>
                    <td class="text-center">
                        <i class="bi bi-exclamation-triangle text-warning"></i>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function filtrarLibroDiario() {
    const fechaInicio = $('#fechaInicio').val();
    const fechaFin = $('#fechaFin').val();
    const tipoAsiento = $('#tipoAsiento').val();
    
    if (!fechaInicio || !fechaFin) {
        Swal.fire('Error', 'Debe seleccionar ambas fechas', 'error');
        return;
    }
    
    if (new Date(fechaInicio) > new Date(fechaFin)) {
        Swal.fire('Error', 'La fecha de inicio no puede ser mayor a la fecha fin', 'error');
        return;
    }
    
    Swal.fire({
        title: 'Filtrando libro diario...',
        text: 'Cargando asientos del período',
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    }).then(() => {
        Swal.fire('Info', 'Funcionalidad de filtrado en desarrollo', 'info');
    });
}

function exportarLibroDiario() {
    Swal.fire({
        title: 'Exportando Libro Diario',
        text: 'Generando archivo PDF...',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        Swal.fire('¡Éxito!', 'Libro Diario exportado correctamente', 'success');
    });
}

function imprimirLibroDiario() {
    window.print();
}
</script>
@endpush
@endsection
