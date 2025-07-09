@extends('layouts.modern')

@section('title', 'Delivery - Farmacia Magistral')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-scooter me-3"></i>Sistema de Delivery
        </h1>
        <p class="text-muted mb-0">Gestión de entregas y repartidores</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success btn-modern" onclick="nuevoDelivery()">
            <i class="bi bi-plus me-1"></i> Nuevo Delivery
        </button>
        <button class="btn btn-warning btn-modern" onclick="asignarRepartidor()">
            <i class="bi bi-person-plus me-1"></i> Asignar Repartidor
        </button>
        <button class="btn btn-info btn-modern" onclick="trackingDelivery()">
            <i class="bi bi-geo-alt me-1"></i> Tracking
        </button>
    </div>
</div>
@endsection

@section('content')
@php
    // Obtener datos reales de las variables pasadas desde el controlador
    $estadisticas = $estadisticas ?? [
        'hoy' => [
            'total' => 0,
            'entregados' => 0,
            'pendientes' => 0,
            'en_ruta' => 0
        ],
        'mes' => [
            'total' => 0,
            'ingresos' => 0
        ]
    ];
    $deliveries = $deliveries ?? collect();
    $repartidores = $repartidores ?? collect();
    $clientes = $clientes ?? collect();
    
    // Obtener repartidores activos
    $repartidoresActivos = $repartidores->whereIn('role', ['repartidor', 'empleado']);
@endphp

<!-- Estadísticas de Delivery -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card primary">
            <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="text-primary" style="font-size: 3rem; font-weight: 700;">{{ $estadisticas['hoy']['total'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">Entregas Hoy</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card warning">
            <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="text-warning" style="font-size: 3rem; font-weight: 700;">{{ $estadisticas['hoy']['en_ruta'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">En Camino</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card success">
            <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="text-success" style="font-size: 3rem; font-weight: 700;">{{ $estadisticas['hoy']['entregados'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">Entregadas</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card info">
            <div class="text-info" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-people"></i>
            </div>
            <div class="text-info" style="font-size: 3rem; font-weight: 700;">{{ $repartidoresActivos->count() }}</div>
            <div style="color: #6c757d; font-weight: 500;">Repartidores Activos</div>
        </div>
    </div>
</div>

<!-- Panel de Control -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="modern-card">
            <h5 class="mb-3">
                <i class="bi bi-speedometer text-primary me-2"></i>
                Estado de Repartidores
            </h5>
            @if($repartidoresActivos->count() > 0)
            <div class="row">
                @foreach($repartidoresActivos->take(4) as $repartidor)
                @php
                    $deliveriesHoy = \App\Models\Delivery::where('repartidor_id', $repartidor->id)
                        ->whereDate('fecha_programada', today())
                        ->count();
                    $estado = $deliveriesHoy > 0 ? 'En Ruta' : 'Disponible';
                    $claseBadge = $deliveriesHoy > 0 ? 'bg-warning' : 'bg-success';
                    $claseIcono = $deliveriesHoy > 0 ? 'bi-person-exclamation text-warning' : 'bi-person-check text-success';
                @endphp
                <div class="col-md-3 mb-3">
                    <div class="card border-{{ $deliveriesHoy > 0 ? 'warning' : 'success' }}">
                        <div class="card-body text-center">
                            <i class="bi {{ $claseIcono }}" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">{{ $repartidor->name }}</h6>
                            <span class="badge {{ $claseBadge }}">{{ $estado }}</span>
                            <p class="small mb-0">{{ $deliveriesHoy }} entregas hoy</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-4">
                <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">No hay repartidores registrados</p>
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-card" style="height: 100%;">
            <h6 class="mb-3">
                <i class="bi bi-lightning text-warning me-2"></i>
                Acciones Rápidas
            </h6>
            <div class="d-grid gap-2">
                <button class="btn btn-primary btn-modern btn-sm" onclick="verMapa()">
                    <i class="bi bi-map me-1"></i> Ver Mapa en Tiempo Real
                </button>
                <button class="btn btn-success btn-modern btn-sm" onclick="reporteDelivery()">
                    <i class="bi bi-graph-up me-1"></i> Reporte de Entregas
                </button>
                <button class="btn btn-warning btn-modern btn-sm" onclick="gestionarRepartidores()">
                    <i class="bi bi-people me-1"></i> Gestionar Repartidores
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Entregas -->
@if($deliveries->count() > 0)
<div class="modern-table">
    <div class="d-flex justify-content-between align-items-center p-3">
        <h5 class="mb-0">
            <i class="bi bi-truck text-primary me-2"></i>
            Entregas del Día
        </h5>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" style="width: auto;" id="estadoFilter">
                <option value="">Todos los estados</option>
                <option value="programado">Programados</option>
                <option value="asignado">Asignados</option>
                <option value="en_ruta">En camino</option>
                <option value="entregado">Entregados</option>
                <option value="no_entregado">No entregados</option>
            </select>
            <button class="btn btn-sm btn-outline-primary" onclick="actualizarLista()">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
    </div>
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Código</th>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Repartidor</th>
                <th>Hora Estimada</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deliveries as $delivery)
            <tr>
                <td>
                    <strong>{{ $delivery->codigo_delivery }}</strong>
                    @if($delivery->venta)
                    <br><small class="text-muted">Venta #{{ $delivery->venta->numero_venta }}</small>
                    @elseif($delivery->pedido)
                    <br><small class="text-muted">Pedido #{{ $delivery->pedido->numero_pedido }}</small>
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-circle text-primary me-2"></i>
                        <div>
                            <strong>{{ $delivery->cliente->nombres ?? 'Sin nombre' }}</strong>
                            @if($delivery->cliente->apellidos)
                            <br><small class="text-muted">{{ $delivery->cliente->apellidos }}</small>
                            @endif
                            @if($delivery->telefono_contacto)
                            <br><small class="text-muted">Telf: {{ $delivery->telefono_contacto }}</small>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($delivery->direccion_entrega, 30) }}
                    @if($delivery->referencia_direccion)
                    <br><small class="text-muted">{{ Str::limit($delivery->referencia_direccion, 25) }}</small>
                    @endif
                </td>
                <td>
                    @if($delivery->repartidor)
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-badge text-success me-2"></i>
                        <div>
                            <strong>{{ $delivery->repartidor->name }}</strong>
                        </div>
                    </div>
                    @else
                    <span class="text-muted">Sin asignar</span>
                    @endif
                </td>
                <td>
                    @if($delivery->fecha_programada)
                    {{ \Carbon\Carbon::parse($delivery->fecha_programada)->format('H:i') }}
                    <br><small class="text-muted">{{ \Carbon\Carbon::parse($delivery->fecha_programada)->format('d/m/Y') }}</small>
                    @else
                    <span class="text-muted">Sin fecha</span>
                    @endif
                </td>
                <td>
                    @switch($delivery->estado)
                        @case('programado')
                            <span class="badge bg-secondary">Programado</span>
                            @break
                        @case('asignado')
                            <span class="badge bg-info">Asignado</span>
                            @break
                        @case('en_ruta')
                            <span class="badge bg-warning">En Ruta</span>
                            @break
                        @case('entregado')
                            <span class="badge bg-success">Entregado</span>
                            @break
                        @case('no_entregado')
                            <span class="badge bg-danger">No Entregado</span>
                            @break
                        @default
                            <span class="badge bg-secondary">{{ ucfirst($delivery->estado) }}</span>
                    @endswitch
                </td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" onclick="verDelivery({{ $delivery->id }})">
                            <i class="bi bi-eye"></i>
                        </button>
                        @if($delivery->estado === 'programado')
                        <button class="btn btn-sm btn-outline-success" onclick="asignarRepartidor({{ $delivery->id }})">
                            <i class="bi bi-person-plus"></i>
                        </button>
                        @endif
                        @if($delivery->estado === 'asignado')
                        <button class="btn btn-sm btn-outline-warning" onclick="iniciarRuta({{ $delivery->id }})">
                            <i class="bi bi-play"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Paginación -->
    @if($deliveries->hasPages())
    <div class="d-flex justify-content-center p-3">
        {{ $deliveries->links() }}
    </div>
    @endif
</div>
@else
<div class="modern-card">
    <div class="text-center py-5">
        <i class="bi bi-truck text-muted" style="font-size: 4rem;"></i>
        <h4 class="text-muted mt-3">No hay deliveries registrados</h4>
        <p class="text-muted">Comienza creando tu primer delivery</p>
        <button class="btn btn-success btn-modern" onclick="nuevoDelivery()">
            <i class="bi bi-plus me-1"></i> Crear Delivery
        </button>
    </div>
</div>
@endif

<!-- Modal Nuevo Delivery -->
<div class="modal fade" id="nuevoDeliveryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Delivery
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoDelivery">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cliente</label>
                            <select class="form-select" id="clienteDelivery" required>
                                <option value="">Seleccionar cliente</option>
                                <option value="1">María González - 999-888-777</option>
                                <option value="2">Carlos Mendoza - 888-777-666</option>
                                <option value="3">Ana Torres - 777-666-555</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Repartidor</label>
                            <select class="form-select" id="repartidorDelivery" required>
                                <option value="">Seleccionar repartidor</option>
                                <option value="1">Miguel Ángel (Disponible)</option>
                                <option value="2">Luis García (Disponible)</option>
                                <option value="3">Ana Torres (En Descanso)</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Dirección de Entrega</label>
                            <textarea class="form-control" id="direccionDelivery" rows="2" required placeholder="Dirección completa de entrega"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono de Contacto</label>
                            <input type="tel" class="form-control" id="telefonoDelivery" required placeholder="999-888-777">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora Estimada de Entrega</label>
                            <input type="time" class="form-control" id="horaEstimada" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prioridad</label>
                            <select class="form-select" id="prioridadDelivery">
                                <option value="normal">Normal</option>
                                <option value="alta">Alta</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Costo de Delivery</label>
                            <input type="number" step="0.01" class="form-control" id="costoDelivery" value="5.00">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observacionesDelivery" rows="2" placeholder="Instrucciones especiales para la entrega"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarDelivery()">
                    <i class="bi bi-check me-1"></i>Crear Delivery
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Delivery -->
<div class="modal fade" id="verDeliveryModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>Detalle de Delivery
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoDelivery">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning" onclick="editarDeliveryActual()">
                    <i class="bi bi-pencil me-1"></i>Editar
                </button>
                <button type="button" class="btn btn-success" onclick="confirmarEntregaActual()">
                    <i class="bi bi-check me-1"></i>Confirmar Entrega
                </button>
                <button type="button" class="btn btn-info" onclick="trackingDeliveryActual()">
                    <i class="bi bi-geo-alt me-1"></i>Tracking
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let deliveryActual = null;

function nuevoDelivery() {
    document.getElementById('formNuevoDelivery').reset();
    document.getElementById('horaEstimada').value = '{{ now()->addHour()->format("H:i") }}';
    const modal = new bootstrap.Modal(document.getElementById('nuevoDeliveryModal'));
    modal.show();
}

function guardarDelivery() {
    const form = document.getElementById('formNuevoDelivery');
    if (form.checkValidity()) {
        const cliente = document.getElementById('clienteDelivery').selectedOptions[0].text;
        const repartidor = document.getElementById('repartidorDelivery').selectedOptions[0].text;
        const codigoDelivery = 'DEL-' + String(Date.now()).slice(-3);
        
        Swal.fire({
            title: 'Delivery Creado',
            html: `
                <div class="text-start">
                    <p><strong>Código:</strong> ${codigoDelivery}</p>
                    <p><strong>Cliente:</strong> ${cliente}</p>
                    <p><strong>Repartidor:</strong> ${repartidor}</p>
                    <p><strong>Hora Estimada:</strong> ${document.getElementById('horaEstimada').value}</p>
                </div>
            `,
            icon: 'success',
            timer: 3000
        }).then(() => {
            bootstrap.Modal.getInstance(document.getElementById('nuevoDeliveryModal')).hide();
        });
    } else {
        Swal.fire('Error', 'Complete todos los campos requeridos', 'error');
    }
}

function verDelivery(id) {
    deliveryActual = id;
    
    // Simular datos del delivery
    const delivery = {
        codigo: 'DEL-' + String(id).padStart(3, '0'),
        cliente: 'María González',
        telefono: '999-888-777',
        direccion: 'Av. Brasil 123, Magdalena del Mar',
        repartidor: 'Miguel Ángel',
        moto: 'ABC-123',
        horaEstimada: '15:30',
        estado: 'En Camino',
        prioridad: 'Normal',
        costo: 5.00,
        observaciones: 'Entregar en recepción del edificio',
        historial: [
            {hora: '14:30', evento: 'Asignado a repartidor'},
            {hora: '14:45', evento: 'Salió para entrega'},
            {hora: '15:15', evento: 'En camino al destino'}
        ]
    };
    
    const contenido = `
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-info-circle me-1"></i>Información del Delivery</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Código:</strong> ${delivery.codigo}</p>
                                <p><strong>Cliente:</strong> ${delivery.cliente}</p>
                                <p><strong>Teléfono:</strong> ${delivery.telefono}</p>
                                <p><strong>Dirección:</strong> ${delivery.direccion}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Repartidor:</strong> ${delivery.repartidor}</p>
                                <p><strong>Vehículo:</strong> ${delivery.moto}</p>
                                <p><strong>Hora Estimada:</strong> ${delivery.horaEstimada}</p>
                                <p><strong>Estado:</strong> <span class="badge bg-warning">${delivery.estado}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-currency-dollar me-1"></i>Detalles Adicionales</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Prioridad:</strong> ${delivery.prioridad}</p>
                        <p><strong>Costo:</strong> S/ ${delivery.costo.toFixed(2)}</p>
                        <p><strong>Observaciones:</strong></p>
                        <p class="text-muted">${delivery.observaciones}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <h6 class="mb-3">Historial de Seguimiento</h6>
        <div class="timeline">
            ${delivery.historial.map(evento => `
                <div class="timeline-item">
                    <div class="timeline-marker bg-primary"></div>
                    <div class="timeline-content">
                        <h6 class="timeline-title">${evento.evento}</h6>
                        <p class="timeline-text">${evento.hora}</p>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
    
    document.getElementById('contenidoDelivery').innerHTML = contenido;
    const modal = new bootstrap.Modal(document.getElementById('verDeliveryModal'));
    modal.show();
}

function confirmarEntrega(id) {
    Swal.fire({
        title: '¿Confirmar entrega?',
        text: 'Esta acción marcará el delivery como entregado',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirmar Entrega',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Entregado', 'Delivery confirmado como entregado', 'success');
        }
    });
}

function iniciarEntrega(id) {
    Swal.fire('Iniciado', 'Entrega iniciada exitosamente', 'success');
}

function trackingEnTiempoReal(id) {
    Swal.fire({
        title: 'Tracking en Tiempo Real',
        html: `
            <div class="text-center">
                <i class="bi bi-geo-alt text-primary" style="font-size: 3rem;"></i>
                <p class="mt-3">Ubicación actual del repartidor</p>
                <p><strong>Av. Brasil 100</strong></p>
                <p class="text-muted">Última actualización: hace 2 minutos</p>
                <div class="progress mt-3">
                    <div class="progress-bar bg-success" style="width: 75%"></div>
                </div>
                <p class="mt-2"><small>75% del recorrido completado</small></p>
            </div>
        `,
        showConfirmButton: false,
        timer: 5000
    });
}

function reasignarRepartidor(id) {
    Swal.fire({
        title: 'Reasignar Repartidor',
        text: 'Seleccione el nuevo repartidor:',
        input: 'select',
        inputOptions: {
            '1': 'Miguel Ángel (Disponible)',
            '2': 'Luis García (Disponible)',
            '3': 'Ana Torres (En Descanso)'
        },
        showCancelButton: true,
        confirmButtonText: 'Reasignar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Reasignado', 'Delivery reasignado exitosamente', 'success');
        }
    });
}

function asignarRepartidor(id) {
    Swal.fire({
        title: 'Asignar Repartidor',
        text: 'Función para asignar repartidores automáticamente',
        icon: 'info'
    });
}

function trackingDelivery() {
    Swal.fire({
        title: 'Sistema de Tracking',
        text: 'Panel de seguimiento en tiempo real',
        icon: 'info'
    });
}

function verMapa() {
    Swal.fire({
        title: 'Mapa en Tiempo Real',
        html: `
            <div class="text-center">
                <i class="bi bi-map text-info" style="font-size: 4rem;"></i>
                <p class="mt-3">Función para visualizar mapa con todos los repartidores</p>
                <p class="text-muted">Integración con Google Maps / OpenStreetMap</p>
            </div>
        `,
        width: 600
    });
}

function reporteDelivery() {
    Swal.fire('Generando...', 'Reporte de entregas en proceso', 'info');
}

function gestionarRepartidores() {
    Swal.fire('Info', 'Función para gestionar repartidores', 'info');
}

function actualizarLista() {
    Swal.fire('Actualizando...', 'Lista de entregas actualizada', 'success');
}

function editarDeliveryActual() {
    if (deliveryActual) {
        Swal.fire('Info', 'Función para editar delivery #' + deliveryActual, 'info');
    }
}

function confirmarEntregaActual() {
    if (deliveryActual) {
        confirmarEntrega(deliveryActual);
    }
}

function trackingDeliveryActual() {
    if (deliveryActual) {
        trackingEnTiempoReal(deliveryActual);
    }
}
</script>
@endpush

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -30px;
    top: 17px;
    width: 2px;
    height: 100%;
    background: #dee2e6;
}
</style>
@endpush
@endsection 