@extends('layouts.modern')

@section('title', 'Laboratorio - Farmacia Magistral')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-flask me-3"></i>Laboratorio
        </h1>
        <p class="text-muted mb-0">Gestión de fabricación de medicamentos</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success btn-modern" onclick="nuevoLaboratorio()">
            <i class="bi bi-plus me-1"></i> Nuevo Medicamento
        </button>
        <button class="btn btn-info btn-modern" onclick="generarReporte()">
            <i class="bi bi-file-earmark-text me-1"></i> Reportes
        </button>
    </div>
</div>
@endsection

@section('content')
@php
    // Obtener datos reales de las variables pasadas desde el controlador
    $estadisticas = $estadisticas ?? [
        'total' => 0,
        'en_proceso' => 0,
        'completados' => 0,
        'borradores' => 0,
        'hoy' => 0,
        'tiempo_promedio' => '0 minutos'
    ];
    $laboratorios = $laboratorios ?? collect();
@endphp

<!-- Estadísticas del Laboratorio -->
<div class="row mb-4 g-4">
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stat-card primary h-100">
            <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-flask"></i>
            </div>
            <div class="text-primary" style="font-size: 3rem; font-weight: 700;">{{ $estadisticas['total'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">Total Medicamentos</div>
        </div>
    </div>
    
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stat-card warning h-100">
            <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-gear"></i>
            </div>
            <div class="text-warning" style="font-size: 3rem; font-weight: 700;">{{ $estadisticas['en_proceso'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">En Fabricación</div>
        </div>
    </div>
    
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stat-card success h-100">
            <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="text-success" style="font-size: 3rem; font-weight: 700;">{{ $estadisticas['completados'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">Completados</div>
        </div>
    </div>
    
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stat-card info h-100">
            <div class="text-info" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-clock"></i>
            </div>
            <div class="text-info" style="font-size: 2.5rem; font-weight: 700;">{{ $estadisticas['tiempo_promedio'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">Tiempo Promedio</div>
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
                    <input type="text" class="form-control" placeholder="Buscar medicamento, lote..." id="searchInput">
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="estadoFilter">
                        <option value="">Todos los estados</option>
                        <option value="borrador">Borradores</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="completado">Completados</option>
                        <option value="cancelado">Cancelados</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <input type="date" class="form-control" id="fechaFilter" placeholder="Fecha">
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
                <button class="btn btn-success btn-modern btn-sm" onclick="nuevoLaboratorio()">
                    <i class="bi bi-plus me-1"></i> Nuevo Medicamento
                </button>
                <button class="btn btn-primary btn-modern btn-sm" onclick="verEnProceso()">
                    <i class="bi bi-gear me-1"></i> Ver en Proceso
                </button>
                <button class="btn btn-info btn-modern btn-sm" onclick="generarReporte()">
                    <i class="bi bi-file-earmark-text me-1"></i> Reporte General
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Medicamentos en Fabricación -->
@if($laboratorios->count() > 0)
<div class="modern-card">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <h5 class="mb-0">
            <i class="bi bi-table text-primary me-2"></i>
            Medicamentos en Fabricación
        </h5>
        <span class="badge bg-primary">{{ $laboratorios->count() }} medicamentos encontrados</span>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th style="width: 120px;">Lote</th>
                    <th>Medicamento</th>
                    <th style="width: 100px;">Progreso</th>
                    <th style="width: 120px;">Estado</th>
                    <th style="width: 100px;">Cantidad</th>
                    <th style="width: 120px;">Fecha</th>
                    <th style="width: 150px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laboratorios as $laboratorio)
                <tr>
                    <td>
                        <strong class="text-primary">{{ $laboratorio->numero_lote }}</strong>
                        <br><small class="text-muted">{{ $laboratorio->created_at->format('d/m/Y') }}</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-capsule text-primary me-2"></i>
                            <div>
                                <strong>{{ $laboratorio->nombre_medicamento }}</strong>
                                @if($laboratorio->descripcion)
                                <br><small class="text-muted">{{ Str::limit($laboratorio->descripcion, 50) }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $laboratorio->progreso }}%" 
                                 aria-valuenow="{{ $laboratorio->progreso }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <small class="text-muted">{{ $laboratorio->progreso }}%</small>
                    </td>
                    <td>
                        @switch($laboratorio->estado)
                            @case('borrador')
                                <span class="badge bg-secondary badge-modern">
                                    <i class="bi bi-pencil me-1"></i>Borrador
                                </span>
                                @break
                            @case('en_proceso')
                                <span class="badge bg-warning badge-modern">
                                    <i class="bi bi-gear me-1"></i>En Proceso
                                </span>
                                @break
                            @case('completado')
                                <span class="badge bg-success badge-modern">
                                    <i class="bi bi-check-circle me-1"></i>Completado
                                </span>
                                @break
                            @case('cancelado')
                                <span class="badge bg-danger badge-modern">
                                    <i class="bi bi-x-circle me-1"></i>Cancelado
                                </span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        <strong>{{ $laboratorio->cantidad_producir }}</strong>
                        <br><small class="text-muted">{{ $laboratorio->unidad_medida }}</small>
                    </td>
                    <td>
                        @if($laboratorio->fecha_inicio)
                            <small class="text-muted">Inicio: {{ $laboratorio->fecha_inicio->format('d/m/Y H:i') }}</small>
                        @else
                            <small class="text-muted">Sin iniciar</small>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" title="Ver detalles" onclick="verLaboratorio({{ $laboratorio->id }})">
                                <i class="bi bi-eye"></i>
                            </button>
                            @if($laboratorio->estado === 'borrador')
                            <button type="button" class="btn btn-outline-success btn-sm" title="Iniciar proceso" onclick="iniciarProceso({{ $laboratorio->id }})">
                                <i class="bi bi-play"></i>
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" title="Editar" onclick="editarLaboratorio({{ $laboratorio->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            @endif
                            @if($laboratorio->estado === 'en_proceso')
                            <button type="button" class="btn btn-outline-info btn-sm" title="Continuar proceso" onclick="continuarProceso({{ $laboratorio->id }})">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                            @endif
                            @if($laboratorio->estado === 'completado')
                            <button type="button" class="btn btn-outline-info btn-sm" title="Generar reporte" onclick="generarReporteLab({{ $laboratorio->id }})">
                                <i class="bi bi-file-earmark-text"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    @if($laboratorios->hasPages())
    <div class="d-flex justify-content-center p-3">
        {{ $laboratorios->links() }}
    </div>
    @endif
</div>
@else
<div class="modern-card">
    <div class="text-center py-5">
        <i class="bi bi-flask text-muted" style="font-size: 4rem;"></i>
        <h4 class="text-muted mt-3">No hay medicamentos en fabricación</h4>
        <p class="text-muted">Comienza creando tu primer medicamento</p>
        <button class="btn btn-success btn-modern" onclick="nuevoLaboratorio()">
            <i class="bi bi-plus me-1"></i> Crear Medicamento
        </button>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
function nuevoLaboratorio() {
    window.location.href = '{{ route("laboratorio.create") }}';
}

function verLaboratorio(id) {
    window.location.href = `/laboratorio/${id}`;
}

function editarLaboratorio(id) {
    window.location.href = `/laboratorio/${id}/edit`;
}

function iniciarProceso(id) {
    if (confirm('¿Está seguro de iniciar el proceso de fabricación?')) {
        window.location.href = `/laboratorio/${id}/iniciar`;
    }
}

function continuarProceso(id) {
    window.location.href = `/laboratorio/${id}`;
}

function generarReporteLab(id) {
    window.location.href = `/laboratorio/${id}/reporte`;
}

function verEnProceso() {
    document.getElementById('estadoFilter').value = 'en_proceso';
    // Aquí puedes agregar lógica para filtrar
}

function generarReporte() {
    window.location.href = '{{ route("laboratorio.index") }}';
}

function limpiarFiltros() {
    document.getElementById('searchInput').value = '';
    document.getElementById('estadoFilter').value = '';
    document.getElementById('fechaFilter').value = '';
    // Aquí puedes agregar lógica para limpiar filtros
}
</script>
@endpush 