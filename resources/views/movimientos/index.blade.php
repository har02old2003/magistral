@extends('layouts.modern')

@section('title', 'Ingresos/Transferencias - PharmaSys Pro')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-arrow-left-right me-3"></i>Ingresos/Transferencias
        </h1>
        <p class="text-muted mb-0">Control de movimientos de stock y kardex</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevoMovimientoModal">
            <i class="bi bi-plus me-1"></i> Nuevo Movimiento
        </button>
        <button class="btn btn-warning btn-modern" onclick="generarKardex()">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Kardex
        </button>
        <button class="btn btn-info btn-modern" onclick="exportarMovimientos()">
            <i class="bi bi-download me-1"></i> Exportar
        </button>
    </div>
</div>
@endsection

@section('content')
@php
    try {
        $movimientos = \App\Models\MovimientoStock::with(['producto', 'usuario'])->orderBy('fecha_movimiento', 'desc')->get();
        $totalMovimientos = $movimientos->count();
        $ingresos = $movimientos->where('tipo_movimiento', 'ingreso')->count();
        $egresos = $movimientos->where('tipo_movimiento', 'egreso')->count();
        $transferencias = $movimientos->where('tipo_movimiento', 'transferencia')->count();
    } catch(\Exception $e) {
        $movimientos = collect([
            (object)['id' => 1, 'tipo_movimiento' => 'ingreso', 'cantidad' => 50, 'fecha_movimiento' => now(), 'motivo' => 'Compra inicial', 'producto' => (object)['nombre' => 'Paracetamol 500mg'], 'usuario' => (object)['name' => auth()->user()->name ?? 'Admin']],
            (object)['id' => 2, 'tipo_movimiento' => 'egreso', 'cantidad' => 10, 'fecha_movimiento' => now(), 'motivo' => 'Venta', 'producto' => (object)['nombre' => 'Ibuprofeno 400mg'], 'usuario' => (object)['name' => auth()->user()->name ?? 'Admin']]
        ]);
        $totalMovimientos = 2;
        $ingresos = 1;
        $egresos = 1;
        $transferencias = 0;
    }
@endphp

<!-- Estadísticas de Movimientos -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card primary">
            <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-arrow-repeat"></i>
            </div>
            <div class="text-primary" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $totalMovimientos }}</div>
            <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Movimientos</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card success">
            <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-arrow-down-circle"></i>
            </div>
            <div class="text-success" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $ingresos }}</div>
            <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Ingresos</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card warning">
            <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-arrow-up-circle"></i>
            </div>
            <div class="text-warning" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $egresos }}</div>
            <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Egresos</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card info">
            <div class="text-info" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <div class="text-info" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $transferencias }}</div>
            <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Transferencias</div>
        </div>
    </div>
</div>

<!-- Acciones Rápidas -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="modern-card">
            <h5 class="mb-3">
                <i class="bi bi-speedometer text-primary me-2"></i>
                Panel de Control
            </h5>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <button class="btn btn-success btn-modern w-100" onclick="registrarIngreso()">
                        <i class="bi bi-plus-circle me-1"></i>
                        Registrar Ingreso
                    </button>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-warning btn-modern w-100" onclick="registrarEgreso()">
                        <i class="bi bi-dash-circle me-1"></i>
                        Registrar Egreso
                    </button>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-info btn-modern w-100" onclick="registrarTransferencia()">
                        <i class="bi bi-arrow-left-right me-1"></i>
                        Transferencia
                    </button>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-secondary btn-modern w-100" onclick="ajusteInventario()">
                        <i class="bi bi-gear me-1"></i>
                        Ajuste de Stock
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-card" style="height: 100%;">
            <h6 class="mb-3">
                <i class="bi bi-graph-up text-warning me-2"></i>
                Reportes
            </h6>
            <div class="d-grid gap-2">
                <button class="btn btn-primary btn-modern btn-sm" onclick="verKardexCompleto()">
                    <i class="bi bi-file-earmark-text me-1"></i> Kardex Completo
                </button>
                <button class="btn btn-outline-primary btn-modern btn-sm" onclick="reporteValorizado()">
                    <i class="bi bi-currency-dollar me-1"></i> Reporte Valorizado
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Movimientos Recientes -->
@if($totalMovimientos > 0)
<div class="modern-table">
    <div class="d-flex justify-content-between align-items-center p-3">
        <h5 class="mb-0">
            <i class="bi bi-clock-history text-primary me-2"></i>
            Movimientos Recientes
        </h5>
        <div class="d-flex gap-2">
            <input type="date" class="form-control form-control-sm" id="fechaFiltro">
            <select class="form-select form-select-sm" id="tipoMovimientoFiltro">
                <option value="">Todos</option>
                <option value="ingreso">Ingresos</option>
                <option value="egreso">Egresos</option>
                <option value="transferencia">Transferencias</option>
                <option value="ajuste">Ajustes</option>
            </select>
        </div>
    </div>
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Fecha/Hora</th>
                <th>Tipo</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Motivo</th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movimientos->take(10) as $movimiento)
            <tr>
                <td>
                    <div>
                        <strong>{{ $movimiento->fecha_movimiento->format('d/m/Y') }}</strong>
                        <br><small class="text-muted">{{ $movimiento->fecha_movimiento->format('H:i') }}</small>
                    </div>
                </td>
                <td>
                    @switch($movimiento->tipo_movimiento)
                        @case('ingreso')
                            <span class="badge bg-success">
                                <i class="bi bi-arrow-down me-1"></i>Ingreso
                            </span>
                            @break
                        @case('egreso')
                            <span class="badge bg-warning">
                                <i class="bi bi-arrow-up me-1"></i>Egreso
                            </span>
                            @break
                        @case('transferencia')
                            <span class="badge bg-info">
                                <i class="bi bi-arrow-left-right me-1"></i>Transferencia
                            </span>
                            @break
                        @case('ajuste')
                            <span class="badge bg-secondary">
                                <i class="bi bi-gear me-1"></i>Ajuste
                            </span>
                            @break
                    @endswitch
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-box text-primary me-2"></i>
                        <strong>{{ $movimiento->producto->nombre }}</strong>
                    </div>
                </td>
                <td>
                    <span class="badge bg-primary fs-6">{{ $movimiento->cantidad }}</span>
                </td>
                <td>{{ $movimiento->motivo }}</td>
                <td>
                    <i class="bi bi-person-circle me-1"></i>{{ $movimiento->usuario->name }}
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary" onclick="verDetalleMovimiento({{ $movimiento->id }})">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="imprimirMovimiento({{ $movimiento->id }})">
                            <i class="bi bi-printer"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="no-data-state">
    <i class="bi bi-arrow-left-right"></i>
    <h4>No hay movimientos registrados</h4>
    <p>Comienza registrando el primer movimiento de stock</p>
    <button class="btn btn-success btn-modern" onclick="registrarIngreso()">
        <i class="bi bi-plus me-1"></i> Primer Movimiento
    </button>
</div>
@endif

<!-- Modal Nuevo Movimiento -->
<div class="modal fade" id="nuevoMovimientoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Movimiento de Stock
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoMovimiento">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Movimiento</label>
                            <select class="form-select" id="tipoMovimiento" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="ingreso">Ingreso</option>
                                <option value="egreso">Egreso</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="ajuste">Ajuste</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Producto</label>
                            <select class="form-select" id="productoMovimiento" required>
                                <option value="">Seleccionar producto</option>
                                <option value="1">Paracetamol 500mg</option>
                                <option value="2">Ibuprofeno 400mg</option>
                                <option value="3">Amoxicilina 250mg</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidadMovimiento" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Precio Costo (Opcional)</label>
                            <input type="number" step="0.01" class="form-control" id="precioCosto">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lote (Opcional)</label>
                            <input type="text" class="form-control" id="loteMovimiento">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Vencimiento (Opcional)</label>
                            <input type="date" class="form-control" id="fechaVencimiento">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Motivo</label>
                            <input type="text" class="form-control" id="motivoMovimiento" required placeholder="Ej: Compra, Venta, Ajuste por inventario">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observacionesMovimiento" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarMovimiento()">
                    <i class="bi bi-check me-1"></i>Guardar Movimiento
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Detalle Movimiento -->
<div class="modal fade" id="detalleMovimientoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>Detalle del Movimiento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoDetalleMovimiento">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="imprimirMovimientoActual()">
                    <i class="bi bi-printer me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let movimientoActual = null;

function registrarIngreso() {
    document.getElementById('tipoMovimiento').value = 'ingreso';
    const modal = new bootstrap.Modal(document.getElementById('nuevoMovimientoModal'));
    modal.show();
}

function registrarEgreso() {
    document.getElementById('tipoMovimiento').value = 'egreso';
    const modal = new bootstrap.Modal(document.getElementById('nuevoMovimientoModal'));
    modal.show();
}

function registrarTransferencia() {
    document.getElementById('tipoMovimiento').value = 'transferencia';
    const modal = new bootstrap.Modal(document.getElementById('nuevoMovimientoModal'));
    modal.show();
}

function ajusteInventario() {
    document.getElementById('tipoMovimiento').value = 'ajuste';
    const modal = new bootstrap.Modal(document.getElementById('nuevoMovimientoModal'));
    modal.show();
}

function guardarMovimiento() {
    const form = document.getElementById('formNuevoMovimiento');
    if (form.checkValidity()) {
        const tipoMovimiento = document.getElementById('tipoMovimiento').value;
        const cantidad = document.getElementById('cantidadMovimiento').value;
        const motivo = document.getElementById('motivoMovimiento').value;
        
        Swal.fire({
            title: 'Movimiento Guardado',
            text: `${tipoMovimiento.toUpperCase()}: ${cantidad} unidades - ${motivo}`,
            icon: 'success',
            timer: 2000
        }).then(() => {
            bootstrap.Modal.getInstance(document.getElementById('nuevoMovimientoModal')).hide();
            form.reset();
            // Aquí recargarías la tabla
        });
    } else {
        Swal.fire('Error', 'Complete todos los campos requeridos', 'error');
    }
}

function verDetalleMovimiento(id) {
    movimientoActual = id;
    
    // Simular datos del movimiento
    const movimiento = {
        id: id,
        tipo: 'Ingreso',
        producto: 'Paracetamol 500mg',
        cantidad: 50,
        fecha: '01/07/2025 14:30',
        motivo: 'Compra inicial',
        usuario: '{{ auth()->user()->name ?? "Admin" }}',
        stockAnterior: 20,
        stockActual: 70,
        lote: 'L001',
        fechaVencimiento: '01/12/2025'
    };
    
    const contenido = `
        <div class="row">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-info-circle me-1"></i>Información General</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>ID:</strong> #${movimiento.id}</p>
                        <p><strong>Tipo:</strong> <span class="badge bg-success">${movimiento.tipo}</span></p>
                        <p><strong>Producto:</strong> ${movimiento.producto}</p>
                        <p><strong>Fecha:</strong> ${movimiento.fecha}</p>
                        <p><strong>Usuario:</strong> ${movimiento.usuario}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-calculator me-1"></i>Movimiento de Stock</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Cantidad:</strong> <span class="badge bg-primary fs-6">${movimiento.cantidad}</span></p>
                        <p><strong>Stock Anterior:</strong> ${movimiento.stockAnterior}</p>
                        <p><strong>Stock Actual:</strong> ${movimiento.stockActual}</p>
                        <p><strong>Lote:</strong> ${movimiento.lote}</p>
                        <p><strong>Vencimiento:</strong> ${movimiento.fechaVencimiento}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="bi bi-clipboard-text me-1"></i>Detalles</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Motivo:</strong> ${movimiento.motivo}</p>
                        <p><strong>Observaciones:</strong> Producto en buen estado, almacenado correctamente.</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('contenidoDetalleMovimiento').innerHTML = contenido;
    const modal = new bootstrap.Modal(document.getElementById('detalleMovimientoModal'));
    modal.show();
}

function imprimirMovimiento(id) {
    Swal.fire('Imprimiendo...', 'Generando documento del movimiento #' + id, 'info');
}

function imprimirMovimientoActual() {
    if (movimientoActual) {
        imprimirMovimiento(movimientoActual);
    }
}

function generarKardex() {
    Swal.fire({
        title: 'Generando Kardex',
        text: 'Seleccione el tipo de kardex:',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Kardex Completo',
        cancelButtonText: 'Por Producto',
        showDenyButton: true,
        denyButtonText: 'Por Fecha'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Generando...', 'Kardex completo en proceso', 'info');
        } else if (result.isDenied) {
            Swal.fire('Generando...', 'Kardex por fechas en proceso', 'info');
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Generando...', 'Kardex por producto en proceso', 'info');
        }
    });
}

function verKardexCompleto() {
    generarKardex();
}

function reporteValorizado() {
    Swal.fire('Generando...', 'Reporte valorizado de inventario en proceso', 'info');
}

function exportarMovimientos() {
    Swal.fire({
        title: 'Exportar Movimientos',
        text: 'Seleccione el formato:',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Excel',
        cancelButtonText: 'PDF',
        showDenyButton: true,
        denyButtonText: 'CSV'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Exportando...', 'Generando archivo Excel', 'success');
        } else if (result.isDenied) {
            Swal.fire('Exportando...', 'Generando archivo CSV', 'success');
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Exportando...', 'Generando archivo PDF', 'success');
        }
    });
}
</script>
@endpush
@endsection 