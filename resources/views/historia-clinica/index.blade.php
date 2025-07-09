@extends('layouts.modern')

@section('title', 'Historia Clínica - Farmacia Magistral')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-heart-pulse me-3"></i>Historia Clínica
        </h1>
        <p class="text-muted mb-0">Gestión de historias clínicas y consultas médicas</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success btn-modern" onclick="nuevaHistoria()">
            <i class="bi bi-plus me-1"></i> Nueva Historia
        </button>
        <button class="btn btn-info btn-modern" onclick="nuevaConsulta()">
            <i class="bi bi-clipboard-plus me-1"></i> Nueva Consulta
        </button>
    </div>
</div>
@endsection

@section('content')
@php
    // Obtener datos reales de las variables pasadas desde el controlador
    $estadisticas = $estadisticas ?? [
        'total' => 0,
        'nuevas_mes' => 0,
        'consultas_hoy' => 0,
        'proximas_citas' => 0
    ];
    $historias = $historias ?? collect();
@endphp

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card primary">
            <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-folder-plus"></i>
            </div>
            <div class="text-primary" style="font-size: 3rem; font-weight: 700;">{{ $estadisticas['total'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">Historias Activas</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card success">
            <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="text-success" style="font-size: 3rem; font-weight: 700;">{{ $estadisticas['consultas_hoy'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">Consultas Hoy</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card warning">
            <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="text-warning" style="font-size: 3rem; font-weight: 700;">{{ $estadisticas['proximas_citas'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">Citas Programadas</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card info">
            <div class="text-info" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-prescription2"></i>
            </div>
            <div class="text-info" style="font-size: 3rem; font-weight: 700;">{{ $estadisticas['nuevas_mes'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">Nuevas del Mes</div>
        </div>
    </div>
</div>

<!-- Búsqueda de Pacientes -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="modern-card">
            <h5 class="mb-3">
                <i class="bi bi-search text-primary me-2"></i>
                Búsqueda de Pacientes
            </h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <input type="text" class="form-control" placeholder="Buscar por nombre, DNI o código..." id="searchPaciente">
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="estadoFiltro">
                        <option value="">Todos los estados</option>
                        <option value="activo">Activos</option>
                        <option value="inactivo">Inactivos</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-primary btn-modern w-100" onclick="buscarPaciente()">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-card" style="height: 100%;">
            <h6 class="mb-3">
                <i class="bi bi-activity text-warning me-2"></i>
                Acciones Rápidas
            </h6>
            <div class="d-grid gap-2">
                <button class="btn btn-success btn-modern btn-sm" onclick="consultaRapida()">
                    <i class="bi bi-plus-circle me-1"></i> Consulta Rápida
                </button>
                <button class="btn btn-info btn-modern btn-sm" onclick="reporteMedico()">
                    <i class="bi bi-file-medical me-1"></i> Reporte Médico
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Historias Clínicas -->
@if($historias->count() > 0)
<div class="modern-table">
    <div class="d-flex justify-content-between align-items-center p-3">
        <h5 class="mb-0">
            <i class="bi bi-folder2-open text-primary me-2"></i>
            Historias Clínicas Recientes
        </h5>
        <button class="btn btn-sm btn-outline-success" onclick="nuevaHistoria()">
            <i class="bi bi-plus"></i> Nueva Historia
        </button>
    </div>
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Número Historia</th>
                <th>Paciente</th>
                <th>Fecha Apertura</th>
                <th>Última Consulta</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historias as $historia)
            <tr>
                <td>
                    <strong>{{ $historia->numero_historia }}</strong>
                    <br><small class="text-muted">Historia principal</small>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-circle text-primary me-2" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong>{{ $historia->cliente->nombres ?? 'Sin nombre' }}</strong>
                            @if($historia->cliente->apellidos)
                            <br><small class="text-muted">{{ $historia->cliente->apellidos }}</small>
                            @endif
                            @if($historia->cliente->dni)
                            <br><small class="text-muted">DNI: {{ $historia->cliente->dni }}</small>
                            @endif
                        </div>
                    </div>
                </td>
                <td>{{ $historia->fecha_apertura ? \Carbon\Carbon::parse($historia->fecha_apertura)->format('d/m/Y') : 'N/A' }}</td>
                <td>
                    @php
                        $ultimaConsulta = $historia->consultas()->latest('fecha_consulta')->first();
                    @endphp
                    @if($ultimaConsulta)
                        {{ \Carbon\Carbon::parse($ultimaConsulta->fecha_consulta)->format('d/m/Y') }}
                    @else
                        <span class="text-muted">Sin consultas</span>
                    @endif
                </td>
                <td>
                    @if($historia->activo)
                        <span class="badge bg-success">Activa</span>
                    @else
                        <span class="badge bg-danger">Inactiva</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" onclick="verHistoria({{ $historia->id }})">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="nuevaConsultaFor({{ $historia->id }})">
                            <i class="bi bi-clipboard-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="verRecetas({{ $historia->id }})">
                            <i class="bi bi-prescription2"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Paginación -->
    @if($historias->hasPages())
    <div class="d-flex justify-content-center p-3">
        {{ $historias->links() }}
    </div>
    @endif
</div>
@else
<div class="modern-card">
    <div class="text-center py-5">
        <i class="bi bi-folder2 text-muted" style="font-size: 4rem;"></i>
        <h4 class="text-muted mt-3">No hay historias clínicas registradas</h4>
        <p class="text-muted">Comienza creando la primera historia clínica</p>
        <button class="btn btn-success btn-modern" onclick="nuevaHistoria()">
            <i class="bi bi-plus me-1"></i> Crear Historia Clínica
        </button>
    </div>
</div>
@endif

<!-- Modal Nueva Historia Clínica -->
<div class="modal fade" id="nuevaHistoriaModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-folder-plus me-2"></i>Nueva Historia Clínica
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaHistoria">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="nombres" required placeholder="Nombres del paciente">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" required placeholder="Apellidos del paciente">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">DNI</label>
                            <input type="text" class="form-control" id="dni" required placeholder="12345678" maxlength="8">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fechaNacimiento" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sexo</label>
                            <select class="form-select" id="sexo" required>
                                <option value="">Seleccionar</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" placeholder="999-888-777">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="paciente@ejemplo.com">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Dirección</label>
                            <textarea class="form-control" id="direccion" rows="2" placeholder="Dirección completa del paciente"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Grupo Sanguíneo</label>
                            <select class="form-select" id="grupoSanguineo">
                                <option value="">Seleccionar</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado Civil</label>
                            <select class="form-select" id="estadoCivil">
                                <option value="">Seleccionar</option>
                                <option value="soltero">Soltero(a)</option>
                                <option value="casado">Casado(a)</option>
                                <option value="divorciado">Divorciado(a)</option>
                                <option value="viudo">Viudo(a)</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Alergias Conocidas</label>
                            <textarea class="form-control" id="alergias" rows="2" placeholder="Medicamentos, alimentos u otras alergias..."></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Antecedentes Médicos</label>
                            <textarea class="form-control" id="antecedentes" rows="3" placeholder="Enfermedades previas, cirugías, tratamientos..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarHistoria()">
                    <i class="bi bi-check me-1"></i>Crear Historia
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Consulta -->
<div class="modal fade" id="nuevaConsultaModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-clipboard-plus me-2"></i>Nueva Consulta Médica
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaConsulta">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Paciente</label>
                            <select class="form-select" id="pacienteConsulta" required>
                                <option value="">Seleccionar paciente</option>
                                <option value="1">Juan Pérez García - DNI: 12345678</option>
                                <option value="2">María González López - DNI: 87654321</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Fecha Consulta</label>
                            <input type="date" class="form-control" id="fechaConsulta" required value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Hora</label>
                            <input type="time" class="form-control" id="horaConsulta" required value="{{ now()->format('H:i') }}">
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="mb-3">Examen Físico</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Peso (kg)</label>
                            <input type="number" step="0.1" class="form-control" id="peso" placeholder="70.5">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Talla (cm)</label>
                            <input type="number" class="form-control" id="talla" placeholder="175">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Presión Arterial</label>
                            <input type="text" class="form-control" id="presionArterial" placeholder="120/80">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Temperatura (°C)</label>
                            <input type="number" step="0.1" class="form-control" id="temperatura" placeholder="36.5">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Frecuencia Cardíaca</label>
                            <input type="number" class="form-control" id="frecuenciaCardiaca" placeholder="72">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Frecuencia Respiratoria</label>
                            <input type="number" class="form-control" id="frecuenciaRespiratoria" placeholder="16">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Saturación O2 (%)</label>
                            <input type="number" class="form-control" id="saturacionO2" placeholder="98">
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="mb-3">Consulta Médica</h6>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Motivo de Consulta</label>
                            <textarea class="form-control" id="motivoConsulta" rows="2" required placeholder="Síntomas o motivo de la visita..."></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Enfermedad Actual</label>
                            <textarea class="form-control" id="enfermedadActual" rows="3" placeholder="Descripción detallada de los síntomas..."></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Examen Físico Detallado</label>
                            <textarea class="form-control" id="examenFisico" rows="3" placeholder="Hallazgos del examen físico..."></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Diagnóstico</label>
                            <textarea class="form-control" id="diagnostico" rows="2" required placeholder="Diagnóstico principal y secundarios..."></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Plan de Tratamiento</label>
                            <textarea class="form-control" id="planTratamiento" rows="3" placeholder="Medicamentos, dosis, duración del tratamiento..."></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observacionesConsulta" rows="2" placeholder="Recomendaciones adicionales..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" onclick="guardarBorradorConsulta()">
                    <i class="bi bi-save me-1"></i>Guardar Borrador
                </button>
                <button type="button" class="btn btn-success" onclick="guardarConsulta()">
                    <i class="bi bi-check me-1"></i>Guardar Consulta
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Historia Clínica -->
<div class="modal fade" id="verHistoriaModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-folder2-open me-2"></i>Historia Clínica
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoHistoria">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info" onclick="editarHistoriaActual()">
                    <i class="bi bi-pencil me-1"></i>Editar
                </button>
                <button type="button" class="btn btn-success" onclick="nuevaConsultaActual()">
                    <i class="bi bi-clipboard-plus me-1"></i>Nueva Consulta
                </button>
                <button type="button" class="btn btn-warning" onclick="verRecetasActual()">
                    <i class="bi bi-prescription2 me-1"></i>Ver Recetas
                </button>
                <button type="button" class="btn btn-primary" onclick="imprimirHistoria()">
                    <i class="bi bi-printer me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Recetas -->
<div class="modal fade" id="verRecetasModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-prescription2 me-2"></i>Recetas Médicas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoRecetas">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="nuevaReceta()">
                    <i class="bi bi-plus me-1"></i>Nueva Receta
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let historiaActual = null;

function nuevaHistoria() {
    document.getElementById('formNuevaHistoria').reset();
    const modal = new bootstrap.Modal(document.getElementById('nuevaHistoriaModal'));
    modal.show();
}

function guardarHistoria() {
    const form = document.getElementById('formNuevaHistoria');
    if (form.checkValidity()) {
        const nombres = document.getElementById('nombres').value;
        const apellidos = document.getElementById('apellidos').value;
        const dni = document.getElementById('dni').value;
        const numeroHistoria = 'HC-' + String(Date.now()).slice(-3);
        
        Swal.fire({
            title: 'Historia Clínica Creada',
            html: `
                <div class="text-start">
                    <p><strong>Número:</strong> ${numeroHistoria}</p>
                    <p><strong>Paciente:</strong> ${nombres} ${apellidos}</p>
                    <p><strong>DNI:</strong> ${dni}</p>
                    <p><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>
                </div>
            `,
            icon: 'success',
            timer: 3000
        }).then(() => {
            bootstrap.Modal.getInstance(document.getElementById('nuevaHistoriaModal')).hide();
        });
    } else {
        Swal.fire('Error', 'Complete todos los campos requeridos', 'error');
    }
}

function nuevaConsulta() {
    document.getElementById('formNuevaConsulta').reset();
    document.getElementById('fechaConsulta').value = '{{ now()->format("Y-m-d") }}';
    document.getElementById('horaConsulta').value = '{{ now()->format("H:i") }}';
    const modal = new bootstrap.Modal(document.getElementById('nuevaConsultaModal'));
    modal.show();
}

function nuevaConsultaFor(historiaId) {
    // Pre-seleccionar el paciente según la historia
    document.getElementById('formNuevaConsulta').reset();
    document.getElementById('pacienteConsulta').value = historiaId;
    document.getElementById('fechaConsulta').value = '{{ now()->format("Y-m-d") }}';
    document.getElementById('horaConsulta').value = '{{ now()->format("H:i") }}';
    const modal = new bootstrap.Modal(document.getElementById('nuevaConsultaModal'));
    modal.show();
}

function guardarBorradorConsulta() {
    const numeroConsulta = 'CON-' + String(Date.now()).slice(-3);
    Swal.fire({
        title: 'Borrador Guardado',
        text: `Consulta ${numeroConsulta} guardada como borrador`,
        icon: 'info',
        timer: 2000
    });
}

function guardarConsulta() {
    const form = document.getElementById('formNuevaConsulta');
    const paciente = document.getElementById('pacienteConsulta').value;
    const motivoConsulta = document.getElementById('motivoConsulta').value;
    const diagnostico = document.getElementById('diagnostico').value;
    
    if (!paciente || !motivoConsulta || !diagnostico) {
        Swal.fire('Error', 'Complete los campos requeridos: Paciente, Motivo y Diagnóstico', 'error');
        return;
    }
    
    const numeroConsulta = 'CON-' + String(Date.now()).slice(-3);
    
    Swal.fire({
        title: 'Consulta Registrada',
        html: `
            <div class="text-start">
                <p><strong>Número:</strong> ${numeroConsulta}</p>
                <p><strong>Fecha:</strong> ${document.getElementById('fechaConsulta').value}</p>
                <p><strong>Paciente:</strong> ${document.getElementById('pacienteConsulta').selectedOptions[0].text}</p>
                <p><strong>Diagnóstico:</strong> ${diagnostico}</p>
            </div>
        `,
        icon: 'success',
        timer: 3000
    }).then(() => {
        bootstrap.Modal.getInstance(document.getElementById('nuevaConsultaModal')).hide();
    });
}

function buscarPaciente() {
    const searchTerm = document.getElementById('searchPaciente').value;
    if (searchTerm) {
        Swal.fire({
            title: 'Buscando...',
            text: `Búsqueda: "${searchTerm}"`,
            icon: 'info',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            // Simular resultados de búsqueda
            Swal.fire({
                title: 'Resultados de Búsqueda',
                html: `
                    <div class="text-start">
                        <div class="border rounded p-3 mb-2">
                            <strong>Juan Pérez García</strong><br>
                            DNI: 12345678<br>
                            <small class="text-muted">Historia: HC-001</small>
                        </div>
                        <div class="border rounded p-3">
                            <strong>María González López</strong><br>
                            DNI: 87654321<br>
                            <small class="text-muted">Historia: HC-002</small>
                        </div>
                    </div>
                `,
                confirmButtonText: 'Cerrar'
            });
        });
    } else {
        Swal.fire('Error', 'Ingrese un término de búsqueda', 'error');
    }
}

function consultaRapida() {
    Swal.fire({
        title: 'Consulta Rápida',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Paciente:</label>
                    <select class="form-select" id="pacienteRapido">
                        <option value="">Seleccionar paciente</option>
                        <option value="1">Juan Pérez García</option>
                        <option value="2">María González López</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Motivo:</label>
                    <input type="text" class="form-control" id="motivoRapido" placeholder="Motivo de consulta">
                </div>
                <div class="mb-3">
                    <label class="form-label">Diagnóstico:</label>
                    <input type="text" class="form-control" id="diagnosticoRapido" placeholder="Diagnóstico">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Registrar Consulta',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Registrada', 'Consulta rápida registrada exitosamente', 'success');
        }
    });
}

function reporteMedico() {
    Swal.fire({
        title: 'Reporte Médico',
        text: 'Seleccione el tipo de reporte:',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Consultas del Día',
        cancelButtonText: 'Historias Activas',
        showDenyButton: true,
        denyButtonText: 'Estadísticas'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Generando...', 'Reporte de consultas del día', 'success');
        } else if (result.isDenied) {
            Swal.fire('Generando...', 'Estadísticas médicas', 'success');
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Generando...', 'Reporte de historias activas', 'success');
        }
    });
}

function verHistoria(id) {
    historiaActual = id;
    
    // Simular datos de la historia clínica
    const historia = {
        numero: 'HC-' + String(id).padStart(3, '0'),
        paciente: id == 1 ? 'Juan Pérez García' : 'María González López',
        dni: id == 1 ? '12345678' : '87654321',
        fechaNacimiento: '15/03/1985',
        edad: 39,
        sexo: 'Masculino',
        grupoSanguineo: 'O+',
        telefono: '999-888-777',
        direccion: 'Av. Principal 123, Lima',
        alergias: 'Penicilina, mariscos',
        antecedentes: 'Hipertensión arterial controlada con medicación',
        consultas: [
            {
                fecha: '{{ now()->subDays(5)->format("d/m/Y") }}',
                motivo: 'Control de presión arterial',
                diagnostico: 'Hipertensión arterial estable',
                tratamiento: 'Enalapril 10mg c/12h'
            },
            {
                fecha: '{{ now()->subDays(15)->format("d/m/Y") }}',
                motivo: 'Dolor de cabeza frecuente',
                diagnostico: 'Cefalea tensional',
                tratamiento: 'Ibuprofeno 400mg PRN'
            }
        ]
    };
    
    const contenido = `
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-person me-1"></i>Datos del Paciente</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Historia N°:</strong> ${historia.numero}</p>
                        <p><strong>Nombre:</strong> ${historia.paciente}</p>
                        <p><strong>DNI:</strong> ${historia.dni}</p>
                        <p><strong>Fecha Nac.:</strong> ${historia.fechaNacimiento}</p>
                        <p><strong>Edad:</strong> ${historia.edad} años</p>
                        <p><strong>Sexo:</strong> ${historia.sexo}</p>
                        <p><strong>Grupo Sang.:</strong> ${historia.grupoSanguineo}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-telephone me-1"></i>Información de Contacto</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Teléfono:</strong> ${historia.telefono}</p>
                        <p><strong>Dirección:</strong> ${historia.direccion}</p>
                        <hr>
                        <p><strong>Alergias:</strong> ${historia.alergias}</p>
                        <p><strong>Antecedentes:</strong> ${historia.antecedentes}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <h6 class="mb-3">Historial de Consultas</h6>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Fecha</th>
                        <th>Motivo de Consulta</th>
                        <th>Diagnóstico</th>
                        <th>Tratamiento</th>
                    </tr>
                </thead>
                <tbody>
                    ${historia.consultas.map(c => `
                        <tr>
                            <td>${c.fecha}</td>
                            <td>${c.motivo}</td>
                            <td>${c.diagnostico}</td>
                            <td>${c.tratamiento}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
    
    document.getElementById('contenidoHistoria').innerHTML = contenido;
    const modal = new bootstrap.Modal(document.getElementById('verHistoriaModal'));
    modal.show();
}

function verRecetas(historiaId) {
    const recetas = [
        {
            fecha: '{{ now()->subDays(5)->format("d/m/Y") }}',
            numero: 'REC-001',
            medicamentos: [
                { nombre: 'Enalapril 10mg', dosis: '1 tableta cada 12 horas', duracion: '30 días' },
                { nombre: 'Hidroclorotiazida 25mg', dosis: '1 tableta al día', duracion: '30 días' }
            ],
            medico: 'Dr. Carlos López',
            estado: 'Activa'
        },
        {
            fecha: '{{ now()->subDays(15)->format("d/m/Y") }}',
            numero: 'REC-002',
            medicamentos: [
                { nombre: 'Ibuprofeno 400mg', dosis: 'Según necesidad', duracion: '5 días' }
            ],
            medico: 'Dr. Carlos López',
            estado: 'Completada'
        }
    ];
    
    const contenido = `
        <div class="mb-3">
            <h6 class="text-primary">Recetas del Paciente</h6>
        </div>
        ${recetas.map(receta => `
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Receta ${receta.numero}</strong> - ${receta.fecha}
                    </div>
                    <span class="badge ${receta.estado === 'Activa' ? 'bg-success' : 'bg-secondary'}">${receta.estado}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Medicamento</th>
                                    <th>Dosis</th>
                                    <th>Duración</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${receta.medicamentos.map(med => `
                                    <tr>
                                        <td>${med.nombre}</td>
                                        <td>${med.dosis}</td>
                                        <td>${med.duracion}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    <small class="text-muted">Médico: ${receta.medico}</small>
                </div>
            </div>
        `).join('')}
    `;
    
    document.getElementById('contenidoRecetas').innerHTML = contenido;
    const modal = new bootstrap.Modal(document.getElementById('verRecetasModal'));
    modal.show();
}

function editarHistoriaActual() {
    if (historiaActual) {
        Swal.fire('Info', 'Función para editar historia #' + historiaActual, 'info');
    }
}

function nuevaConsultaActual() {
    if (historiaActual) {
        nuevaConsultaFor(historiaActual);
        bootstrap.Modal.getInstance(document.getElementById('verHistoriaModal')).hide();
    }
}

function verRecetasActual() {
    if (historiaActual) {
        bootstrap.Modal.getInstance(document.getElementById('verHistoriaModal')).hide();
        setTimeout(() => verRecetas(historiaActual), 300);
    }
}

function imprimirHistoria() {
    if (historiaActual) {
        Swal.fire('Imprimiendo...', 'Generando historia clínica completa', 'success');
    }
}

function nuevaReceta() {
    Swal.fire({
        title: 'Nueva Receta Médica',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Medicamento:</label>
                    <input type="text" class="form-control" id="medicamentoReceta" placeholder="Nombre del medicamento">
                </div>
                <div class="mb-3">
                    <label class="form-label">Dosis:</label>
                    <input type="text" class="form-control" id="dosisReceta" placeholder="Ej: 1 tableta cada 8 horas">
                </div>
                <div class="mb-3">
                    <label class="form-label">Duración:</label>
                    <input type="text" class="form-control" id="duracionReceta" placeholder="Ej: 7 días">
                </div>
                <div class="mb-3">
                    <label class="form-label">Indicaciones:</label>
                    <textarea class="form-control" id="indicacionesReceta" rows="2" placeholder="Instrucciones adicionales"></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Crear Receta',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Receta Creada', 'Nueva receta médica registrada exitosamente', 'success');
        }
    });
}
</script>
@endpush
@endsection 