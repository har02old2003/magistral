@extends('layouts.modern')

@section('title', 'Detalle Laboratorio - Farmacia Magistral')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-flask me-3"></i>{{ $laboratorio->nombre_medicamento }}
        </h1>
        <p class="text-muted mb-0">Lote: {{ $laboratorio->numero_lote }} | Progreso: {{ $laboratorio->progreso }}%</p>
    </div>
    <div class="d-flex gap-2">
        @if($laboratorio->estado === 'borrador')
        <button class="btn btn-success btn-modern" onclick="iniciarProceso()">
            <i class="bi bi-play me-1"></i> Iniciar Proceso
        </button>
        <button class="btn btn-warning btn-modern" onclick="editarLaboratorio()">
            <i class="bi bi-pencil me-1"></i> Editar
        </button>
        @endif
        @if($laboratorio->estado === 'completado')
        <button class="btn btn-info btn-modern" onclick="generarReporte()">
            <i class="bi bi-file-earmark-text me-1"></i> Generar Reporte
        </button>
        @endif
        <button class="btn btn-secondary btn-modern" onclick="volver()">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </button>
    </div>
</div>
@endsection

@section('content')
<!-- Información General del Medicamento -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="modern-card">
            <h5 class="mb-3">
                <i class="bi bi-info-circle text-primary me-2"></i>
                Información del Medicamento
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Número de Lote:</strong></td>
                            <td>{{ $laboratorio->numero_lote }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nombre:</strong></td>
                            <td>{{ $laboratorio->nombre_medicamento }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cantidad a Producir:</strong></td>
                            <td>{{ $laboratorio->cantidad_producir }} {{ $laboratorio->unidad_medida }}</td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td>
                                @switch($laboratorio->estado)
                                    @case('borrador')
                                        <span class="badge bg-secondary">Borrador</span>
                                        @break
                                    @case('en_proceso')
                                        <span class="badge bg-warning">En Proceso</span>
                                        @break
                                    @case('completado')
                                        <span class="badge bg-success">Completado</span>
                                        @break
                                    @case('cancelado')
                                        <span class="badge bg-danger">Cancelado</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Temperatura Óptima:</strong></td>
                            <td>{{ $laboratorio->temperatura_optima ? $laboratorio->temperatura_optima . '°C' : 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tiempo Estimado:</strong></td>
                            <td>{{ $laboratorio->tiempo_fabricacion_minutos ? $laboratorio->tiempo_fabricacion_minutos . ' minutos' : 'No especificado' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Equipos Requeridos:</strong></td>
                            <td>{{ $laboratorio->equipos_requeridos ?: 'No especificados' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Creado por:</strong></td>
                            <td>{{ $laboratorio->usuario->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            @if($laboratorio->descripcion)
            <div class="mt-3">
                <strong>Descripción:</strong>
                <p class="text-muted">{{ $laboratorio->descripcion }}</p>
            </div>
            @endif
            
            @if($laboratorio->formula_quimica)
            <div class="mt-3">
                <strong>Fórmula Química:</strong>
                <p class="text-muted">{{ $laboratorio->formula_quimica }}</p>
            </div>
            @endif
            
            @if($laboratorio->precauciones_seguridad)
            <div class="mt-3">
                <strong>Precauciones de Seguridad:</strong>
                <div class="alert alert-warning">
                    {{ $laboratorio->precauciones_seguridad }}
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="modern-card">
            <h6 class="mb-3">
                <i class="bi bi-graph-up text-success me-2"></i>
                Progreso del Proceso
            </h6>
            
            <div class="text-center mb-3">
                <div class="display-4 text-primary">{{ $laboratorio->progreso }}%</div>
                <div class="progress mb-2" style="height: 10px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: {{ $laboratorio->progreso }}%" 
                         aria-valuenow="{{ $laboratorio->progreso }}" 
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <small class="text-muted">{{ $laboratorio->pasos->where('completado', true)->count() }} de {{ $laboratorio->pasos->count() }} pasos completados</small>
            </div>
            
            @if($laboratorio->fecha_inicio)
            <div class="mb-3">
                <strong>Fecha de Inicio:</strong><br>
                <small class="text-muted">{{ $laboratorio->fecha_inicio->format('d/m/Y H:i') }}</small>
            </div>
            @endif
            
            @if($laboratorio->fecha_fin)
            <div class="mb-3">
                <strong>Fecha de Finalización:</strong><br>
                <small class="text-muted">{{ $laboratorio->fecha_fin->format('d/m/Y H:i') }}</small>
            </div>
            @endif
            
            @if($laboratorio->fecha_inicio)
            <div class="mb-3">
                <strong>Tiempo Transcurrido:</strong><br>
                <small class="text-muted">{{ $laboratorio->tiempo_transcurrido }}</small>
            </div>
            @endif
            
            @if($laboratorio->estado === 'en_proceso')
            <div class="d-grid">
                <button class="btn btn-primary btn-modern" onclick="continuarProceso()">
                    <i class="bi bi-arrow-right me-1"></i> Continuar Proceso
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Pasos del Proceso de Fabricación -->
<div class="modern-card">
    <h5 class="mb-3">
        <i class="bi bi-list-check text-primary me-2"></i>
        Pasos del Proceso de Fabricación
    </h5>
    
    @if($laboratorio->pasos->count() > 0)
    <div class="row">
        @foreach($laboratorio->pasos as $paso)
        <div class="col-md-6 mb-3">
            <div class="card {{ $paso->completado ? 'border-success' : 'border-secondary' }} h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <span class="badge {{ $paso->completado ? 'bg-success' : 'bg-secondary' }} me-2">
                            {{ $paso->orden_paso }}
                        </span>
                        {{ $paso->titulo_paso }}
                    </h6>
                    @if($paso->completado)
                    <i class="bi bi-check-circle text-success"></i>
                    @endif
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $paso->descripcion_paso }}</p>
                    
                    @if($paso->instrucciones_detalladas)
                    <div class="mb-2">
                        <strong>Instrucciones:</strong>
                        <p class="text-muted small">{{ $paso->instrucciones_detalladas }}</p>
                    </div>
                    @endif
                    
                    @if($paso->tiempo_estimado_minutos)
                    <div class="mb-2">
                        <strong>Tiempo Estimado:</strong>
                        <span class="text-muted">{{ $paso->tiempo_estimado_minutos }} minutos</span>
                    </div>
                    @endif
                    
                    @if($paso->equipos_necesarios)
                    <div class="mb-2">
                        <strong>Equipos:</strong>
                        <span class="text-muted">{{ $paso->equipos_necesarios }}</span>
                    </div>
                    @endif
                    
                    @if($paso->materiales_requeridos)
                    <div class="mb-2">
                        <strong>Materiales:</strong>
                        <p class="text-muted small">{{ $paso->materiales_requeridos }}</p>
                    </div>
                    @endif
                    
                    @if($paso->completado)
                    <div class="alert alert-success small">
                        <strong>Completado:</strong> {{ $paso->fecha_completado->format('d/m/Y H:i') }}
                        @if($paso->usuarioCompleto)
                        <br><small>Por: {{ $paso->usuarioCompleto->name }}</small>
                        @endif
                        @if($paso->notas_completado)
                        <br><small>Notas: {{ $paso->notas_completado }}</small>
                        @endif
                    </div>
                    @elseif($laboratorio->estado === 'en_proceso')
                    <div class="d-grid">
                        <button class="btn btn-success btn-sm" onclick="completarPaso({{ $paso->id }})">
                            <i class="bi bi-check me-1"></i> Marcar como Completado
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-4">
        <i class="bi bi-list text-muted" style="font-size: 3rem;"></i>
        <p class="text-muted mt-2">No hay pasos definidos para este proceso</p>
    </div>
    @endif
</div>

<!-- Modal para Completar Paso -->
<div class="modal fade" id="completarPasoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle me-2"></i>Completar Paso
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCompletarPaso">
                    <input type="hidden" id="pasoId" name="paso_id">
                    <div class="mb-3">
                        <label for="notas" class="form-label">Notas (opcional)</label>
                        <textarea class="form-control" id="notas" name="notas" rows="3" placeholder="Agregar observaciones sobre la completación del paso..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="confirmarCompletarPaso()">
                    <i class="bi bi-check me-1"></i>Completar Paso
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function iniciarProceso() {
    if (confirm('¿Está seguro de iniciar el proceso de fabricación?')) {
        window.location.href = '{{ route("laboratorio.iniciar", $laboratorio) }}';
    }
}

function editarLaboratorio() {
    window.location.href = '{{ route("laboratorio.edit", $laboratorio) }}';
}

function continuarProceso() {
    // Scroll al primer paso pendiente
    const primerPasoPendiente = document.querySelector('.card:not(.border-success)');
    if (primerPasoPendiente) {
        primerPasoPendiente.scrollIntoView({ behavior: 'smooth' });
    }
}

function generarReporte() {
    window.location.href = '{{ route("laboratorio.reporte", $laboratorio) }}';
}

function volver() {
    window.location.href = '{{ route("laboratorio.index") }}';
}

function completarPaso(pasoId) {
    document.getElementById('pasoId').value = pasoId;
    new bootstrap.Modal(document.getElementById('completarPasoModal')).show();
}

function confirmarCompletarPaso() {
    const pasoId = document.getElementById('pasoId').value;
    const notas = document.getElementById('notas').value;
    
    fetch(`/laboratorio/{{ $laboratorio->id }}/paso/${pasoId}/completar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            notas: notas
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al completar el paso');
    });
}
</script>
@endpush 