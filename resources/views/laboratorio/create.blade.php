@extends('layouts.modern')

@section('title', 'Nuevo Medicamento - Laboratorio')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-plus-circle me-3"></i>Nuevo Medicamento
        </h1>
        <p class="text-muted mb-0">Crear proceso de fabricación de medicamento</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-secondary btn-modern" onclick="volver()">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </button>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('laboratorio.store') }}" method="POST" id="formLaboratorio">
    @csrf
    
    <!-- Información General -->
    <div class="modern-card mb-4">
        <h5 class="mb-3">
            <i class="bi bi-info-circle text-primary me-2"></i>
            Información General del Medicamento
        </h5>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nombre_medicamento" class="form-label">Nombre del Medicamento *</label>
                <input type="text" class="form-control" id="nombre_medicamento" name="nombre_medicamento" 
                       value="{{ old('nombre_medicamento') }}" required>
                @error('nombre_medicamento')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="producto_id" class="form-label">Producto Asociado</label>
                <select class="form-select" id="producto_id" name="producto_id">
                    <option value="">Seleccionar producto (opcional)</option>
                    @foreach($productos as $producto)
                    <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                        {{ $producto->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="cantidad_producir" class="form-label">Cantidad a Producir *</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="cantidad_producir" name="cantidad_producir" 
                           value="{{ old('cantidad_producir', 1) }}" min="1" required>
                    <select class="form-select" id="unidad_medida" name="unidad_medida" style="max-width: 120px;">
                        <option value="unidades" {{ old('unidad_medida') == 'unidades' ? 'selected' : '' }}>Unidades</option>
                        <option value="tabletas" {{ old('unidad_medida') == 'tabletas' ? 'selected' : '' }}>Tabletas</option>
                        <option value="capsulas" {{ old('unidad_medida') == 'capsulas' ? 'selected' : '' }}>Cápsulas</option>
                        <option value="ml" {{ old('unidad_medida') == 'ml' ? 'selected' : '' }}>ml</option>
                        <option value="gramos" {{ old('unidad_medida') == 'gramos' ? 'selected' : '' }}>Gramos</option>
                        <option value="mg" {{ old('unidad_medida') == 'mg' ? 'selected' : '' }}>mg</option>
                    </select>
                </div>
                @error('cantidad_producir')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="tiempo_fabricacion_minutos" class="form-label">Tiempo de Fabricación (minutos)</label>
                <input type="number" class="form-control" id="tiempo_fabricacion_minutos" name="tiempo_fabricacion_minutos" 
                       value="{{ old('tiempo_fabricacion_minutos') }}" min="1" placeholder="Ej: 120">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="temperatura_optima" class="form-label">Temperatura Óptima (°C)</label>
                <input type="number" class="form-control" id="temperatura_optima" name="temperatura_optima" 
                       value="{{ old('temperatura_optima') }}" step="0.1" placeholder="Ej: 25.5">
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="equipos_requeridos" class="form-label">Equipos Requeridos</label>
                <input type="text" class="form-control" id="equipos_requeridos" name="equipos_requeridos" 
                       value="{{ old('equipos_requeridos') }}" placeholder="Ej: Balanza, Agitador, Termómetro">
            </div>
        </div>
        
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" 
                      placeholder="Descripción detallada del medicamento...">{{ old('descripcion') }}</textarea>
        </div>
        
        <div class="mb-3">
            <label for="formula_quimica" class="form-label">Fórmula Química</label>
            <input type="text" class="form-control" id="formula_quimica" name="formula_quimica" 
                   value="{{ old('formula_quimica') }}" placeholder="Ej: C9H8O4">
        </div>
        
        <div class="mb-3">
            <label for="instrucciones_generales" class="form-label">Instrucciones Generales</label>
            <textarea class="form-control" id="instrucciones_generales" name="instrucciones_generales" rows="3" 
                      placeholder="Instrucciones generales del proceso...">{{ old('instrucciones_generales') }}</textarea>
        </div>
        
        <div class="mb-3">
            <label for="precauciones_seguridad" class="form-label">Precauciones de Seguridad</label>
            <textarea class="form-control" id="precauciones_seguridad" name="precauciones_seguridad" rows="3" 
                      placeholder="Medidas de seguridad y precauciones...">{{ old('precauciones_seguridad') }}</textarea>
        </div>
    </div>
    
    <!-- Pasos del Proceso -->
    <div class="modern-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="bi bi-list-check text-primary me-2"></i>
                Pasos del Proceso de Fabricación
            </h5>
            <button type="button" class="btn btn-success btn-modern" onclick="agregarPaso()">
                <i class="bi bi-plus me-1"></i> Agregar Paso
            </button>
        </div>
        
        <div id="pasosContainer">
            <!-- Los pasos se agregarán dinámicamente aquí -->
        </div>
        
        <div class="text-center py-3" id="noPasos" style="display: none;">
            <i class="bi bi-list text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-2">No hay pasos definidos</p>
            <p class="text-muted">Haz clic en "Agregar Paso" para comenzar</p>
        </div>
    </div>
    
    <!-- Botones de Acción -->
    <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-secondary btn-modern" onclick="volver()">
            <i class="bi bi-arrow-left me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-success btn-modern">
            <i class="bi bi-check me-1"></i> Crear Medicamento
        </button>
    </div>
</form>

<!-- Template para Paso -->
<template id="pasoTemplate">
    <div class="card mb-3 paso-item">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <span class="badge bg-primary me-2 paso-numero">1</span>
                Paso del Proceso
            </h6>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarPaso(this)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Título del Paso *</label>
                    <input type="text" class="form-control paso-titulo" name="pasos[INDEX][titulo_paso]" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tiempo Estimado (minutos)</label>
                    <input type="number" class="form-control paso-tiempo" name="pasos[INDEX][tiempo_estimado_minutos]" min="1">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Descripción del Paso *</label>
                <textarea class="form-control paso-descripcion" name="pasos[INDEX][descripcion_paso]" rows="2" required></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Instrucciones Detalladas</label>
                <textarea class="form-control paso-instrucciones" name="pasos[INDEX][instrucciones_detalladas]" rows="2"></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Equipos Necesarios</label>
                    <input type="text" class="form-control paso-equipos" name="pasos[INDEX][equipos_necesarios]">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Materiales Requeridos</label>
                    <input type="text" class="form-control paso-materiales" name="pasos[INDEX][materiales_requeridos]">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Observaciones</label>
                <textarea class="form-control paso-observaciones" name="pasos[INDEX][observaciones]" rows="2"></textarea>
            </div>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script>
let pasoIndex = 0;

function agregarPaso() {
    const container = document.getElementById('pasosContainer');
    const template = document.getElementById('pasoTemplate');
    const noPasos = document.getElementById('noPasos');
    
    // Ocultar mensaje de no pasos
    noPasos.style.display = 'none';
    
    // Clonar template
    const pasoElement = template.content.cloneNode(true);
    
    // Actualizar índices
    const inputs = pasoElement.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.name = input.name.replace('INDEX', pasoIndex);
    });
    
    // Actualizar número de paso
    const numeroPaso = pasoElement.querySelector('.paso-numero');
    numeroPaso.textContent = pasoIndex + 1;
    
    container.appendChild(pasoElement);
    pasoIndex++;
}

function eliminarPaso(button) {
    const pasoItem = button.closest('.paso-item');
    pasoItem.remove();
    
    // Reordenar números de paso
    const pasos = document.querySelectorAll('.paso-item');
    pasos.forEach((paso, index) => {
        paso.querySelector('.paso-numero').textContent = index + 1;
    });
    
    // Mostrar mensaje si no hay pasos
    if (pasos.length === 0) {
        document.getElementById('noPasos').style.display = 'block';
    }
}

function volver() {
    window.location.href = '{{ route("laboratorio.index") }}';
}

// Validación del formulario
document.getElementById('formLaboratorio').addEventListener('submit', function(e) {
    const pasos = document.querySelectorAll('.paso-item');
    
    if (pasos.length === 0) {
        e.preventDefault();
        alert('Debe agregar al menos un paso al proceso.');
        return false;
    }
    
    // Validar que todos los campos requeridos estén completos
    let valid = true;
    pasos.forEach((paso, index) => {
        const titulo = paso.querySelector('.paso-titulo').value.trim();
        const descripcion = paso.querySelector('.paso-descripcion').value.trim();
        
        if (!titulo || !descripcion) {
            valid = false;
        }
    });
    
    if (!valid) {
        e.preventDefault();
        alert('Todos los campos marcados con * son obligatorios.');
        return false;
    }
});

// Agregar primer paso automáticamente
document.addEventListener('DOMContentLoaded', function() {
    agregarPaso();
});
</script>
@endpush 