@extends('layouts.modern')

@section('title', 'Reporte Laboratorio - ' . $laboratorio->nombre_medicamento)

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-file-earmark-text me-3"></i>Reporte de Laboratorio
        </h1>
        <p class="text-muted mb-0">{{ $laboratorio->nombre_medicamento }} - Lote: {{ $laboratorio->numero_lote }}</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-info btn-modern" onclick="imprimirReporte()">
            <i class="bi bi-printer me-1"></i> Imprimir
        </button>
        <button class="btn btn-success btn-modern" onclick="exportarPDF()">
            <i class="bi bi-file-pdf me-1"></i> Exportar PDF
        </button>
        <button class="btn btn-secondary btn-modern" onclick="volver()">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="modern-card mb-4">
    <div class="row">
        <div class="col-md-8">
            <h4 class="text-primary mb-3">
                <i class="bi bi-flask me-2"></i>Información del Medicamento
            </h4>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Nombre:</strong></td>
                            <td>{{ $laboratorio->nombre_medicamento }}</td>
                        </tr>
                        <tr>
                            <td><strong>Número de Lote:</strong></td>
                            <td><span class="badge bg-primary">{{ $laboratorio->numero_lote }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Fórmula Química:</strong></td>
                            <td>{{ $laboratorio->formula_quimica ?: 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cantidad Producida:</strong></td>
                            <td>{{ $laboratorio->cantidad_producir }} {{ $laboratorio->unidad_medida }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
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
                        <tr>
                            <td><strong>Temperatura Óptima:</strong></td>
                            <td>{{ $laboratorio->temperatura_optima ? $laboratorio->temperatura_optima . '°C' : 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tiempo de Fabricación:</strong></td>
                            <td>{{ $laboratorio->tiempo_fabricacion_minutos ? $laboratorio->tiempo_fabricacion_minutos . ' minutos' : 'No especificado' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Responsable:</strong></td>
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
            
            @if($laboratorio->precauciones_seguridad)
            <div class="mt-3">
                <strong>Precauciones de Seguridad:</strong>
                <div class="alert alert-warning">
                    {{ $laboratorio->precauciones_seguridad }}
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-md-4">
            <div class="text-center">
                <div class="display-4 text-primary mb-2">{{ $laboratorio->progreso }}%</div>
                <div class="progress mb-3" style="height: 15px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: {{ $laboratorio->progreso }}%" 
                         aria-valuenow="{{ $laboratorio->progreso }}" 
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <p class="text-muted">{{ $laboratorio->pasos->where('completado', true)->count() }} de {{ $laboratorio->pasos->count() }} pasos completados</p>
            </div>
            
            @if($laboratorio->fecha_inicio)
            <div class="mt-3">
                <strong>Fecha de Inicio:</strong><br>
                <small class="text-muted">{{ $laboratorio->fecha_inicio->format('d/m/Y H:i') }}</small>
            </div>
            @endif
            
            @if($laboratorio->fecha_fin)
            <div class="mt-2">
                <strong>Fecha de Finalización:</strong><br>
                <small class="text-muted">{{ $laboratorio->fecha_fin->format('d/m/Y H:i') }}</small>
            </div>
            @endif
            
            @if($laboratorio->fecha_inicio)
            <div class="mt-2">
                <strong>Tiempo Total:</strong><br>
                <small class="text-muted">{{ $laboratorio->tiempo_transcurrido }}</small>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Cronología de Pasos -->
<div class="modern-card mb-4">
    <h4 class="text-primary mb-3">
        <i class="bi bi-list-check me-2"></i>Cronología del Proceso de Fabricación
    </h4>
    
    @if($laboratorio->pasos->count() > 0)
    <div class="timeline">
        @foreach($laboratorio->pasos as $paso)
        <div class="timeline-item {{ $paso->completado ? 'completed' : 'pending' }}">
            <div class="timeline-marker">
                @if($paso->completado)
                    <i class="bi bi-check-circle-fill text-success"></i>
                @else
                    <i class="bi bi-circle text-muted"></i>
                @endif
            </div>
            <div class="timeline-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <span class="badge bg-primary me-2">{{ $paso->orden_paso }}</span>
                            {{ $paso->titulo_paso }}
                        </h6>
                        @if($paso->completado)
                            <span class="badge bg-success">Completado</span>
                        @else
                            <span class="badge bg-secondary">Pendiente</span>
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
                        
                        <div class="row">
                            @if($paso->tiempo_estimado_minutos)
                            <div class="col-md-4">
                                <strong>Tiempo Estimado:</strong><br>
                                <small class="text-muted">{{ $paso->tiempo_estimado_minutos }} minutos</small>
                            </div>
                            @endif
                            
                            @if($paso->equipos_necesarios)
                            <div class="col-md-4">
                                <strong>Equipos:</strong><br>
                                <small class="text-muted">{{ $paso->equipos_necesarios }}</small>
                            </div>
                            @endif
                            
                            @if($paso->materiales_requeridos)
                            <div class="col-md-4">
                                <strong>Materiales:</strong><br>
                                <small class="text-muted">{{ $paso->materiales_requeridos }}</small>
                            </div>
                            @endif
                        </div>
                        
                        @if($paso->completado)
                        <div class="alert alert-success mt-3">
                            <strong>Completado:</strong> {{ $paso->fecha_completado->format('d/m/Y H:i') }}
                            @if($paso->usuarioCompleto)
                            <br><small>Por: {{ $paso->usuarioCompleto->name }}</small>
                            @endif
                            @if($paso->notas_completado)
                            <br><small>Notas: {{ $paso->notas_completado }}</small>
                            @endif
                        </div>
                        @endif
                    </div>
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

<!-- Resumen y Conclusiones -->
<div class="modern-card">
    <h4 class="text-primary mb-3">
        <i class="bi bi-clipboard-data me-2"></i>Resumen y Conclusiones
    </h4>
    
    <div class="row">
        <div class="col-md-6">
            <h6>Estadísticas del Proceso:</h6>
            <ul class="list-unstyled">
                <li><i class="bi bi-check-circle text-success me-2"></i>Total de pasos: {{ $laboratorio->pasos->count() }}</li>
                <li><i class="bi bi-check-circle text-success me-2"></i>Pasos completados: {{ $laboratorio->pasos->where('completado', true)->count() }}</li>
                <li><i class="bi bi-clock text-warning me-2"></i>Pasos pendientes: {{ $laboratorio->pasos->where('completado', false)->count() }}</li>
                <li><i class="bi bi-percent text-info me-2"></i>Progreso general: {{ $laboratorio->progreso }}%</li>
            </ul>
        </div>
        
        <div class="col-md-6">
            <h6>Información de Calidad:</h6>
            <ul class="list-unstyled">
                <li><i class="bi bi-calendar text-primary me-2"></i>Fecha de creación: {{ $laboratorio->created_at->format('d/m/Y H:i') }}</li>
                @if($laboratorio->fecha_inicio)
                <li><i class="bi bi-play-circle text-success me-2"></i>Inicio del proceso: {{ $laboratorio->fecha_inicio->format('d/m/Y H:i') }}</li>
                @endif
                @if($laboratorio->fecha_fin)
                <li><i class="bi bi-check-circle text-success me-2"></i>Finalización: {{ $laboratorio->fecha_fin->format('d/m/Y H:i') }}</li>
                @endif
                <li><i class="bi bi-person text-info me-2"></i>Responsable: {{ $laboratorio->usuario->name }}</li>
            </ul>
        </div>
    </div>
    
    @if($laboratorio->estado === 'completado')
    <div class="alert alert-success mt-3">
        <h6><i class="bi bi-check-circle me-2"></i>Proceso Completado Exitosamente</h6>
        <p class="mb-0">El medicamento {{ $laboratorio->nombre_medicamento }} ha sido fabricado correctamente según las especificaciones establecidas.</p>
    </div>
    @elseif($laboratorio->estado === 'en_proceso')
    <div class="alert alert-warning mt-3">
        <h6><i class="bi bi-gear me-2"></i>Proceso en Curso</h6>
        <p class="mb-0">El proceso de fabricación está en desarrollo. Se han completado {{ $laboratorio->pasos->where('completado', true)->count() }} de {{ $laboratorio->pasos->count() }} pasos.</p>
    </div>
    @elseif($laboratorio->estado === 'borrador')
    <div class="alert alert-info mt-3">
        <h6><i class="bi bi-pencil me-2"></i>Proceso en Borrador</h6>
        <p class="mb-0">El proceso de fabricación está en fase de planificación. Pendiente de iniciar.</p>
    </div>
    @endif
</div>

@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 20px;
    width: 30px;
    height: 30px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
}

.timeline-marker i {
    font-size: 1.2rem;
}

.timeline-content {
    margin-left: 20px;
}

.timeline-item.completed .timeline-marker {
    background: #d4edda;
}

.timeline-item.pending .timeline-marker {
    background: #f8f9fa;
}

@media print {
    .btn {
        display: none !important;
    }
    
    .modern-card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
function imprimirReporte() {
    window.print();
}

function exportarPDF() {
    // Aquí puedes implementar la exportación a PDF
    alert('Función de exportación a PDF en desarrollo');
}

function volver() {
    window.location.href = '{{ route("laboratorio.show", $laboratorio) }}';
}
</script>
@endpush 