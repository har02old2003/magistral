@extends('layouts.modern')

@section('title', 'Caja - PharmaSys Pro')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-cash-coin me-3"></i>Control de Caja
        </h1>
        <p class="text-muted mb-0">Gestión de ingresos, egresos y arqueo de caja</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success btn-modern" onclick="abrirCaja()">
            <i class="bi bi-unlock me-1"></i> Abrir Caja
        </button>
        <button class="btn btn-warning btn-modern" onclick="cerrarCaja()">
            <i class="bi bi-lock me-1"></i> Cerrar Caja
        </button>
        <button class="btn btn-info btn-modern" onclick="arquearCaja()">
            <i class="bi bi-calculator me-1"></i> Arqueo
        </button>
    </div>
</div>
@endsection

@section('content')
@php
    // Obtener datos reales de las variables pasadas desde el controlador
    $saldoActual = $saldoActual ?? 0;
    $totalIngresos = $totalIngresos ?? 0;
    $totalEgresos = $totalEgresos ?? 0;
    $cajaAbierta = $cajaAbierta ?? false;
    $montoInicial = $montoInicial ?? 0;
    $movimientosHoy = $movimientosHoy ?? collect();
    
    // Calcular estadísticas de ventas del día
    $ventasHoy = \App\Models\Venta::whereDate('fecha', today())->count();
    $totalVendidoHoy = \App\Models\Venta::whereDate('fecha', today())->sum('total');
@endphp

<!-- Estado de Caja -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="modern-card text-center">
            <h3 class="text-primary mb-3">
                <i class="bi bi-cash-stack"></i> Estado de Caja
            </h3>
            <div class="display-4 text-success mb-2">S/ {{ number_format($saldoActual, 2) }}</div>
            <p class="text-muted">Saldo Actual</p>
            <div class="row">
                <div class="col-6">
                    <div class="text-success">
                        <strong>Ingresos Hoy</strong><br>
                        S/ {{ number_format($totalIngresos, 2) }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-warning">
                        <strong>Egresos Hoy</strong><br>
                        S/ {{ number_format($totalEgresos, 2) }}
                    </div>
                </div>
            </div>
            @if($cajaAbierta)
                <div class="mt-3">
                    <span class="badge bg-success">Caja Abierta</span>
                    <small class="d-block text-muted">Monto inicial: S/ {{ number_format($montoInicial, 2) }}</small>
                </div>
            @else
                <div class="mt-3">
                    <span class="badge bg-danger">Caja Cerrada</span>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="modern-card">
            <h5 class="mb-3">
                <i class="bi bi-graph-up text-primary me-2"></i>
                Resumen del Día
            </h5>
            <div class="row mb-3">
                <div class="col-6">
                    <div class="stat-card info">
                        <div class="text-info" style="font-size: 2rem;">{{ $ventasHoy }}</div>
                        <div style="font-size: 0.9rem;">Ventas</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-card success">
                        <div class="text-success" style="font-size: 2rem;">S/ {{ number_format($totalVendidoHoy, 2) }}</div>
                        <div style="font-size: 0.9rem;">Total Vendido</div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary btn-modern w-100" onclick="verReporteDiario()">
                <i class="bi bi-file-earmark-text me-1"></i> Ver Reporte Completo
            </button>
        </div>
    </div>
</div>

<!-- Movimientos Recientes -->
<div class="modern-table">
    <div class="d-flex justify-content-between align-items-center p-3">
        <h5 class="mb-0">
            <i class="bi bi-clock-history text-primary me-2"></i>
            Movimientos de Hoy
        </h5>
        <button class="btn btn-sm btn-outline-primary" onclick="nuevoMovimiento()">
            <i class="bi bi-plus"></i> Nuevo Movimiento
        </button>
    </div>
    @if($movimientosHoy->count() > 0)
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Hora</th>
                <th>Tipo</th>
                <th>Concepto</th>
                <th>Monto</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movimientosHoy as $movimiento)
            <tr>
                <td>{{ $movimiento->fecha_movimiento->format('H:i') }}</td>
                <td>
                    @if(in_array($movimiento->tipo_movimiento, ['ingreso', 'apertura']))
                        <span class="badge bg-success">Ingreso</span>
                    @else
                        <span class="badge bg-warning">Egreso</span>
                    @endif
                </td>
                <td>{{ $movimiento->observaciones }}</td>
                <td class="{{ in_array($movimiento->tipo_movimiento, ['ingreso', 'apertura']) ? 'text-success' : 'text-warning' }}">
                    {{ in_array($movimiento->tipo_movimiento, ['ingreso', 'apertura']) ? '+' : '-' }}S/ {{ number_format($movimiento->precio_costo, 2) }}
                </td>
                <td>{{ $movimiento->usuario->name ?? 'Sistema' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="text-center py-4">
        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
        <p class="text-muted mt-2">No hay movimientos registrados hoy</p>
    </div>
    @endif
</div>

<!-- Modal Arqueo de Caja -->
<div class="modal fade" id="arqueoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-calculator me-2"></i>Arqueo de Caja
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Sistema</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr><td>Saldo Inicial:</td><td class="text-end">S/ 500.00</td></tr>
                                <tr><td>Ingresos del Día:</td><td class="text-end text-success">+S/ 850.00</td></tr>
                                <tr><td>Egresos del Día:</td><td class="text-end text-danger">-S/ 150.00</td></tr>
                                <tr class="table-primary"><td><strong>Saldo Sistema:</strong></td><td class="text-end"><strong>S/ 1,200.00</strong></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">Conteo Físico</h6>
                        <form id="formArqueo">
                            <div class="mb-2">
                                <label class="form-label">Billetes de 200:</label>
                                <input type="number" class="form-control form-control-sm" id="billetes200" onchange="calcularTotal()">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Billetes de 100:</label>
                                <input type="number" class="form-control form-control-sm" id="billetes100" onchange="calcularTotal()">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Billetes de 50:</label>
                                <input type="number" class="form-control form-control-sm" id="billetes50" onchange="calcularTotal()">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Billetes de 20:</label>
                                <input type="number" class="form-control form-control-sm" id="billetes20" onchange="calcularTotal()">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Billetes de 10:</label>
                                <input type="number" class="form-control form-control-sm" id="billetes10" onchange="calcularTotal()">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Monedas:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="monedas" onchange="calcularTotal()">
                            </div>
                            <hr>
                            <div class="alert alert-info">
                                <strong>Total Físico: S/ <span id="totalFisico">0.00</span></strong>
                            </div>
                            <div id="diferencia" class="alert" style="display: none;">
                                <strong>Diferencia: S/ <span id="montoDiferencia">0.00</span></strong>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarArqueo()">
                    <i class="bi bi-check me-1"></i>Guardar Arqueo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Movimiento -->
<div class="modal fade" id="nuevoMovimientoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Movimiento de Caja
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoMovimientoCaja">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Movimiento</label>
                        <select class="form-select" id="tipoMovimientoCaja" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="ingreso">Ingreso</option>
                            <option value="egreso">Egreso</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Concepto</label>
                        <select class="form-select" id="conceptoMovimiento" required>
                            <option value="">Seleccionar concepto</option>
                            <option value="venta">Venta</option>
                            <option value="compra">Compra de suministros</option>
                            <option value="gasto">Gasto operativo</option>
                            <option value="prestamo">Préstamo</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto</label>
                        <input type="number" step="0.01" class="form-control" id="montoMovimiento" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcionMovimiento" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Documento Referencia (Opcional)</label>
                        <input type="text" class="form-control" id="documentoReferencia" placeholder="Ej: Boleta, Factura, Recibo">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarMovimientoCaja()">
                    <i class="bi bi-check me-1"></i>Guardar Movimiento
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reporte Diario -->
<div class="modal fade" id="reporteDiarioModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-bar-graph me-2"></i>Reporte Diario de Caja
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h5 class="text-primary">Saldo Inicial</h5>
                                <h3>S/ 500.00</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h5 class="text-success">Total Ingresos</h5>
                                <h3>S/ 850.00</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h5 class="text-warning">Total Egresos</h5>
                                <h3>S/ 150.00</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">Detalle de Ingresos</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead><tr><th>Hora</th><th>Concepto</th><th>Monto</th></tr></thead>
                                <tbody>
                                    <tr><td>09:30</td><td>Venta #001</td><td class="text-success">+S/ 45.50</td></tr>
                                    <tr><td>10:15</td><td>Venta #002</td><td class="text-success">+S/ 78.00</td></tr>
                                    <tr><td>11:20</td><td>Venta #003</td><td class="text-success">+S/ 125.50</td></tr>
                                    <tr><td>14:10</td><td>Venta #004</td><td class="text-success">+S/ 95.00</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning mb-3">Detalle de Egresos</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead><tr><th>Hora</th><th>Concepto</th><th>Monto</th></tr></thead>
                                <tbody>
                                    <tr><td>11:00</td><td>Compra suministros</td><td class="text-warning">-S/ 25.00</td></tr>
                                    <tr><td>15:30</td><td>Pago servicios</td><td class="text-warning">-S/ 75.00</td></tr>
                                    <tr><td>16:45</td><td>Gasto operativo</td><td class="text-warning">-S/ 50.00</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Saldo Final del Día: S/ 1,200.00</strong>
                        </div>
                        <div class="col-md-6 text-end">
                            <strong>Fecha: {{ now()->format('d/m/Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="imprimirReporte()">
                    <i class="bi bi-printer me-1"></i>Imprimir
                </button>
                <button type="button" class="btn btn-success" onclick="exportarReporte()">
                    <i class="bi bi-download me-1"></i>Exportar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const SALDO_SISTEMA = 1200.00;

function abrirCaja() {
    Swal.fire({
        title: 'Abrir Caja',
        text: 'Ingrese el monto inicial de caja:',
        input: 'number',
        inputPlaceholder: 'Ej: 100.00',
        showCancelButton: true,
        confirmButtonText: 'Abrir Caja',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value || value < 0) {
                return 'Ingrese un monto válido';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Caja Abierta',
                text: `Caja abierta exitosamente con S/ ${result.value}`,
                icon: 'success',
                timer: 2000
            });
        }
    });
}

function cerrarCaja() {
    Swal.fire({
        title: '¿Cerrar Caja?',
        text: 'Esta acción cerrará la caja del día. ¿Desea realizar un arqueo antes?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Cerrar con Arqueo',
        cancelButtonText: 'Cancelar',
        showDenyButton: true,
        denyButtonText: 'Cerrar sin Arqueo'
    }).then((result) => {
        if (result.isConfirmed) {
            arquearCaja();
        } else if (result.isDenied) {
            Swal.fire('Cerrada', 'Caja cerrada exitosamente', 'success');
        }
    });
}

function arquearCaja() {
    const modal = new bootstrap.Modal(document.getElementById('arqueoModal'));
    modal.show();
}

function calcularTotal() {
    const billetes200 = (document.getElementById('billetes200').value || 0) * 200;
    const billetes100 = (document.getElementById('billetes100').value || 0) * 100;
    const billetes50 = (document.getElementById('billetes50').value || 0) * 50;
    const billetes20 = (document.getElementById('billetes20').value || 0) * 20;
    const billetes10 = (document.getElementById('billetes10').value || 0) * 10;
    const monedas = parseFloat(document.getElementById('monedas').value || 0);
    
    const totalFisico = billetes200 + billetes100 + billetes50 + billetes20 + billetes10 + monedas;
    const diferencia = totalFisico - SALDO_SISTEMA;
    
    document.getElementById('totalFisico').textContent = totalFisico.toFixed(2);
    
    const divDiferencia = document.getElementById('diferencia');
    const montoDiferencia = document.getElementById('montoDiferencia');
    
    if (Math.abs(diferencia) > 0.01) {
        divDiferencia.style.display = 'block';
        montoDiferencia.textContent = diferencia.toFixed(2);
        
        if (diferencia > 0) {
            divDiferencia.className = 'alert alert-success';
        } else {
            divDiferencia.className = 'alert alert-danger';
        }
    } else {
        divDiferencia.style.display = 'none';
    }
}

function guardarArqueo() {
    const totalFisico = parseFloat(document.getElementById('totalFisico').textContent);
    const diferencia = totalFisico - SALDO_SISTEMA;
    
    Swal.fire({
        title: 'Arqueo Completado',
        html: `
            <div class="text-start">
                <p><strong>Saldo Sistema:</strong> S/ ${SALDO_SISTEMA.toFixed(2)}</p>
                <p><strong>Saldo Físico:</strong> S/ ${totalFisico.toFixed(2)}</p>
                <p><strong>Diferencia:</strong> <span class="${diferencia >= 0 ? 'text-success' : 'text-danger'}">S/ ${diferencia.toFixed(2)}</span></p>
            </div>
        `,
        icon: Math.abs(diferencia) < 0.01 ? 'success' : 'warning',
        confirmButtonText: 'Aceptar'
    }).then(() => {
        bootstrap.Modal.getInstance(document.getElementById('arqueoModal')).hide();
    });
}

function verReporteDiario() {
    const modal = new bootstrap.Modal(document.getElementById('reporteDiarioModal'));
    modal.show();
}

function nuevoMovimiento() {
    document.getElementById('formNuevoMovimientoCaja').reset();
    const modal = new bootstrap.Modal(document.getElementById('nuevoMovimientoModal'));
    modal.show();
}

function guardarMovimientoCaja() {
    const form = document.getElementById('formNuevoMovimientoCaja');
    if (form.checkValidity()) {
        const tipo = document.getElementById('tipoMovimientoCaja').value;
        const concepto = document.getElementById('conceptoMovimiento').value;
        const monto = document.getElementById('montoMovimiento').value;
        
        Swal.fire({
            title: 'Movimiento Registrado',
            text: `${tipo.toUpperCase()}: ${concepto} - S/ ${monto}`,
            icon: 'success',
            timer: 2000
        }).then(() => {
            bootstrap.Modal.getInstance(document.getElementById('nuevoMovimientoModal')).hide();
            form.reset();
        });
    } else {
        Swal.fire('Error', 'Complete todos los campos requeridos', 'error');
    }
}

function imprimirReporte() {
    Swal.fire('Imprimiendo...', 'Generando reporte diario de caja', 'info');
}

function exportarReporte() {
    Swal.fire({
        title: 'Exportar Reporte',
        text: 'Seleccione el formato:',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Excel',
        cancelButtonText: 'PDF'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Exportando...', 'Generando archivo Excel', 'success');
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Exportando...', 'Generando archivo PDF', 'success');
        }
    });
}
</script>
@endpush
@endsection 