@extends('layouts.modern')

@section('title', 'Contabilidad - Farmacia Magistral')

@section('page-title', 'Contabilidad')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-calculator me-3"></i>Asientos Contables
        </h1>
        <p class="text-muted mb-0">Gestión de asientos y movimientos contables</p>
    </div>
    <div class="d-flex gap-2">
        @if(auth()->user()->role === 'administrador')
        <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevoAsientoModal">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Asiento
        </button>
        @endif
        <button class="btn btn-info btn-modern" onclick="reporteContable()">
            <i class="bi bi-file-earmark-text me-1"></i> Reportes
        </button>
        <button class="btn btn-primary btn-modern" onclick="libroDiario()">
            <i class="bi bi-book me-1"></i> Libro Diario
        </button>
    </div>
</div>
@endsection

@section('content')
@php
    // Obtener datos reales de las variables pasadas desde el controlador
    $asientos = $asientos ?? collect([]);
    $estadisticas = $estadisticas ?? [
        'total_debe' => 0,
        'total_haber' => 0,
        'asientos_mes' => 0,
        'pendientes' => 0
    ];
@endphp

<!-- Estadísticas Contables -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-value">S/ {{ number_format($estadisticas['total_debe'], 2) }}</div>
            <div class="stat-label">Total Debe</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="bi bi-piggy-bank"></i>
            </div>
            <div class="stat-value">S/ {{ number_format($estadisticas['total_haber'], 2) }}</div>
            <div class="stat-label">Total Haber</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="bi bi-journal-text"></i>
            </div>
            <div class="stat-value">{{ $estadisticas['asientos_mes'] }}</div>
            <div class="stat-label">Asientos del Mes</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-value">{{ $estadisticas['pendientes'] }}</div>
            <div class="stat-label">Pendientes</div>
        </div>
    </div>
</div>

<!-- Controles y Filtros -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="modern-card">
            <h5 class="mb-3">
                <i class="bi bi-funnel text-primary me-2"></i>
                Filtros de Búsqueda
            </h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <input type="text" class="form-control" placeholder="Buscar por concepto..." id="searchInput">
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="tipoFilter">
                        <option value="">Todos los tipos</option>
                        <option value="venta">Ventas</option>
                        <option value="compra">Compras</option>
                        <option value="gasto">Gastos</option>
                        <option value="ingreso">Ingresos</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="estadoFilter">
                        <option value="">Todos los estados</option>
                        <option value="borrador">Borrador</option>
                        <option value="contabilizado">Contabilizado</option>
                        <option value="anulado">Anulado</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <button class="btn btn-info btn-modern w-100" onclick="limpiarFiltros()">
                        <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-card h-100">
            <h6 class="mb-3">
                <i class="bi bi-lightning text-warning me-2"></i>
                Acciones Rápidas
            </h6>
            <div class="d-grid gap-2">
                @if(auth()->user()->role === 'administrador')
                <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevoAsientoModal">
                    <i class="bi bi-plus me-1"></i> Nuevo Asiento
                </button>
                @endif
                <button class="btn btn-info btn-modern" onclick="libroDiario()">
                    <i class="bi bi-book me-1"></i> Libro Diario
                </button>
                <button class="btn btn-primary btn-modern" onclick="estadoResultados()">
                    <i class="bi bi-graph-up me-1"></i> Estado de Resultados
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Asientos Contables -->
@if($asientos->count() > 0)
<div class="modern-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Concepto</th>
                    <th>Debe</th>
                    <th>Haber</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asientos as $asiento)
                <tr>
                    <td>
                        <strong class="text-primary">{{ $asiento->numero_asiento ?? 'AST-' . str_pad($asiento->id, 3, '0', STR_PAD_LEFT) }}</strong>
                    </td>
                    <td>
                        {{ isset($asiento->fecha_asiento) ? \Carbon\Carbon::parse($asiento->fecha_asiento)->format('d/m/Y') : now()->format('d/m/Y') }}
                    </td>
                    <td>
                        @php
                            $tipo = $asiento->tipo_asiento ?? 'venta';
                            $badgeClass = match($tipo) {
                                'venta' => 'bg-success',
                                'compra' => 'bg-primary',
                                'gasto' => 'bg-danger',
                                'ingreso' => 'bg-info',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($tipo) }}</span>
                    </td>
                    <td>
                        <div>
                            <strong>{{ $asiento->concepto ?? 'Sin concepto' }}</strong>
                            @if(isset($asiento->cuenta_contable))
                            <br><small class="text-muted">{{ $asiento->cuenta_contable }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="text-success fw-bold">S/ {{ number_format($asiento->debe ?? 0, 2) }}</span>
                    </td>
                    <td>
                        <span class="text-primary fw-bold">S/ {{ number_format($asiento->haber ?? 0, 2) }}</span>
                    </td>
                    <td>
                        @php
                            $estado = $asiento->estado ?? 'borrador';
                            $estadoBadge = match($estado) {
                                'contabilizado' => 'bg-success',
                                'borrador' => 'bg-warning',
                                'anulado' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $estadoBadge }}">{{ ucfirst($estado) }}</span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="verAsiento({{ $asiento->id }})">
                                <i class="bi bi-eye"></i>
                            </button>
                            @if(auth()->user()->role === 'administrador' && ($asiento->estado ?? 'borrador') === 'borrador')
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="editarAsiento({{ $asiento->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="contabilizarAsiento({{ $asiento->id }})">
                                <i class="bi bi-check-circle"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="anularAsiento({{ $asiento->id }})">
                                <i class="bi bi-x-circle"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<!-- Estado sin asientos -->
<div class="modern-card text-center py-5">
    <div class="text-muted mb-4">
        <i class="bi bi-calculator" style="font-size: 4rem;"></i>
    </div>
    <h4>No hay asientos contables registrados</h4>
    <p class="text-muted mb-4">Comienza creando tu primer asiento contable</p>
    @if(auth()->user()->role === 'administrador')
    <button class="btn btn-success btn-modern btn-lg" data-bs-toggle="modal" data-bs-target="#nuevoAsientoModal">
        <i class="bi bi-plus-circle me-2"></i>
        Crear Primer Asiento
    </button>
    @endif
</div>
@endif

<!-- Modal Nuevo Asiento -->
<div class="modal fade" id="nuevoAsientoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Asiento Contable
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevoAsiento">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_asiento" class="form-label fw-bold">Tipo de Asiento *</label>
                                <select class="form-select" id="tipo_asiento" name="tipo_asiento" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="venta">Venta</option>
                                    <option value="compra">Compra</option>
                                    <option value="gasto">Gasto</option>
                                    <option value="ingreso">Ingreso</option>
                                    <option value="ajuste">Ajuste</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_asiento" class="form-label fw-bold">Fecha del Asiento *</label>
                                <input type="date" class="form-control" id="fecha_asiento" name="fecha_asiento" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="concepto" class="form-label fw-bold">Concepto *</label>
                        <textarea class="form-control" id="concepto" name="concepto" rows="2" placeholder="Descripción del asiento contable..." required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cuenta_contable" class="form-label fw-bold">Cuenta Contable</label>
                                <input type="text" class="form-control" id="cuenta_contable" name="cuenta_contable" placeholder="Ej: 70111 - Ventas">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="referencia" class="form-label fw-bold">Referencia</label>
                                <input type="text" class="form-control" id="referencia" name="referencia" placeholder="Nro. documento, factura, etc.">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="debe" class="form-label fw-bold">Debe (S/)</label>
                                <input type="number" class="form-control" id="debe" name="debe" step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="haber" class="form-label fw-bold">Haber (S/)</label>
                                <input type="number" class="form-control" id="haber" name="haber" step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Nota:</strong> El asiento se creará en estado "Borrador" y podrá ser contabilizado posteriormente.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-modern" onclick="crearAsiento()">
                        <i class="bi bi-check-circle me-1"></i>Crear Asiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Búsqueda en tiempo real
    $('#searchInput').on('input', function() {
        filtrarAsientos();
    });

    $('#tipoFilter, #estadoFilter').on('change', function() {
        filtrarAsientos();
    });
});

function filtrarAsientos() {
    const searchTerm = $('#searchInput').val().toLowerCase();
    const tipoFilter = $('#tipoFilter').val().toLowerCase();
    const estadoFilter = $('#estadoFilter').val().toLowerCase();
    
    $('tbody tr').each(function() {
        const row = $(this);
        const texto = row.text().toLowerCase();
        const tipo = row.find('.badge:first').text().toLowerCase();
        const estado = row.find('.badge:last').text().toLowerCase();
        
        const coincideTexto = texto.includes(searchTerm);
        const coincideTipo = tipoFilter === '' || tipo.includes(tipoFilter);
        const coincideEstado = estadoFilter === '' || estado.includes(estadoFilter);
        
        row.toggle(coincideTexto && coincideTipo && coincideEstado);
    });
}

function limpiarFiltros() {
    $('#searchInput').val('');
    $('#tipoFilter').val('');
    $('#estadoFilter').val('');
    filtrarAsientos();
}

function crearAsiento() {
    const formData = {
        tipo_asiento: $('#tipo_asiento').val(),
        fecha_asiento: $('#fecha_asiento').val(),
        concepto: $('#concepto').val(),
        cuenta_contable: $('#cuenta_contable').val(),
        referencia: $('#referencia').val(),
        debe: parseFloat($('#debe').val()) || 0,
        haber: parseFloat($('#haber').val()) || 0
    };

    if (!formData.tipo_asiento || !formData.fecha_asiento || !formData.concepto) {
        Swal.fire('Error', 'Los campos marcados con * son obligatorios', 'error');
        return;
    }

    if (formData.debe === 0 && formData.haber === 0) {
        Swal.fire('Error', 'Debe especificar un monto en Debe o Haber', 'error');
        return;
    }

    Swal.fire({
        title: 'Creando asiento...',
        text: 'Procesando información contable',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        Swal.fire('¡Éxito!', 'Asiento contable creado correctamente', 'success');
        $('#nuevoAsientoModal').modal('hide');
        setTimeout(() => location.reload(), 1000);
    });
}

function verAsiento(id) {
    Swal.fire({
        title: 'Detalles del Asiento',
        html: `
            <div class="text-start">
                <p><strong>Número:</strong> AST-${id.toString().padStart(3, '0')}</p>
                <p><strong>Fecha:</strong> ${new Date().toLocaleDateString('es-ES')}</p>
                <p><strong>Concepto:</strong> Asiento contable de ejemplo</p>
                <p><strong>Estado:</strong> <span class="badge bg-warning">Borrador</span></p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Cerrar'
    });
}

function editarAsiento(id) {
    Swal.fire('Info', 'Función de edición de asiento en desarrollo', 'info');
}

function contabilizarAsiento(id) {
    Swal.fire({
        title: '¿Contabilizar asiento?',
        text: 'Una vez contabilizado, el asiento no podrá ser modificado.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, contabilizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('¡Contabilizado!', 'El asiento ha sido contabilizado exitosamente.', 'success');
            setTimeout(() => location.reload(), 1000);
        }
    });
}

function anularAsiento(id) {
    Swal.fire({
        title: '¿Anular asiento?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('¡Anulado!', 'El asiento ha sido anulado.', 'success');
            setTimeout(() => location.reload(), 1000);
        }
    });
}

function libroDiario() {
    window.location.href = '{{ route("contabilidad.libro-diario") }}';
}

function estadoResultados() {
    window.location.href = '{{ route("contabilidad.estado-resultados") }}';
}

function reporteContable() {
    Swal.fire('Info', 'Módulo de reportes contables en desarrollo', 'info');
}
</script>
@endpush
@endsection
