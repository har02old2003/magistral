@extends('layouts.modern')

@section('title', 'Reportes - PharmaSys Pro')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-graph-up me-3"></i>Centro de Reportes
        </h1>
        <p class="text-muted mb-0">Análisis y reportes del sistema farmacéutico</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success btn-modern" onclick="reportePersonalizado()">
            <i class="bi bi-plus me-1"></i> Reporte Personalizado
        </button>
        <button class="btn btn-info btn-modern" onclick="exportarTodo()">
            <i class="bi bi-download me-1"></i> Exportar Todo
        </button>
    </div>
</div>
@endsection

@section('content')
<!-- Reportes Rápidos -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-card text-center report-card">
            <div class="text-primary mb-3" style="font-size: 3rem;">
                <i class="bi bi-cart-check"></i>
            </div>
            <h5>Reporte de Ventas</h5>
            <p class="text-muted">Análisis de ventas diarias, semanales y mensuales</p>
            <button class="btn btn-primary btn-modern" onclick="reporteVentas()">
                <i class="bi bi-eye me-1"></i> Ver Reporte
            </button>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-card text-center report-card">
            <div class="text-success mb-3" style="font-size: 3rem;">
                <i class="bi bi-boxes"></i>
            </div>
            <h5>Inventario y Stock</h5>
            <p class="text-muted">Control de stock, productos por vencer y kardex</p>
            <button class="btn btn-success btn-modern" onclick="reporteInventario()">
                <i class="bi bi-eye me-1"></i> Ver Reporte
            </button>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-card text-center report-card">
            <div class="text-info mb-3" style="font-size: 3rem;">
                <i class="bi bi-people"></i>
            </div>
            <h5>Clientes y Pacientes</h5>
            <p class="text-muted">Análisis de clientes, historias clínicas y consultas</p>
            <button class="btn btn-info btn-modern" onclick="reporteClientes()">
                <i class="bi bi-eye me-1"></i> Ver Reporte
            </button>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-card text-center report-card">
            <div class="text-warning mb-3" style="font-size: 3rem;">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <h5>Reporte Financiero</h5>
            <p class="text-muted">Ingresos, egresos, utilidades y flujo de caja</p>
            <button class="btn btn-warning btn-modern" onclick="reporteFinanciero()">
                <i class="bi bi-eye me-1"></i> Ver Reporte
            </button>
        </div>
    </div>
</div>

<!-- Reportes Especializados -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="modern-card">
            <h5 class="mb-3">
                <i class="bi bi-clipboard-data text-primary me-2"></i>
                Reportes Especializados
            </h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-grid">
                        <button class="btn btn-outline-primary" onclick="reporteKardex()">
                            <i class="bi bi-file-earmark-spreadsheet me-2"></i>
                            Kardex Valorizado
                        </button>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-grid">
                        <button class="btn btn-outline-success" onclick="reporteVencimientos()">
                            <i class="bi bi-calendar-x me-2"></i>
                            Productos por Vencer
                        </button>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-grid">
                        <button class="btn btn-outline-warning" onclick="reporteTopProductos()">
                            <i class="bi bi-trophy me-2"></i>
                            Top Productos Vendidos
                        </button>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-grid">
                        <button class="btn btn-outline-info" onclick="reporteProveedores()">
                            <i class="bi bi-truck me-2"></i>
                            Análisis de Proveedores
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="modern-card" style="height: 100%;">
            <h6 class="mb-3">
                <i class="bi bi-calendar-range text-warning me-2"></i>
                Filtros de Fecha
            </h6>
            <div class="mb-3">
                <label class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" id="fechaInicio" value="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" id="fechaFin" value="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary btn-modern btn-sm" onclick="aplicarFiltros()">
                    <i class="bi bi-funnel me-1"></i> Aplicar Filtros
                </button>
                <button class="btn btn-outline-secondary btn-sm" onclick="limpiarFiltros()">
                    <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reportes Recientes -->
<div class="modern-table">
    <div class="d-flex justify-content-between align-items-center p-3">
        <h5 class="mb-0">
            <i class="bi bi-clock-history text-primary me-2"></i>
            Reportes Generados Recientemente
        </h5>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" style="width: auto;">
                <option>Todos los tipos</option>
                <option>Ventas</option>
                <option>Inventario</option>
                <option>Financiero</option>
            </select>
        </div>
    </div>
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Tipo de Reporte</th>
                <th>Fecha Generación</th>
                <th>Usuario</th>
                <th>Período</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cart-check text-primary me-2"></i>
                        <strong>Reporte de Ventas Diario</strong>
                    </div>
                </td>
                <td>{{ now()->format('d/m/Y H:i') }}</td>
                <td>{{ auth()->user()->name ?? 'Admin' }}</td>
                <td>{{ now()->format('d/m/Y') }}</td>
                <td><span class="badge bg-success">Completado</span></td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" onclick="verReporte(1)">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="descargarReporte(1)">
                            <i class="bi bi-download"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-boxes text-success me-2"></i>
                        <strong>Kardex Mensual</strong>
                    </div>
                </td>
                <td>{{ now()->subHour()->format('d/m/Y H:i') }}</td>
                <td>{{ auth()->user()->name ?? 'Admin' }}</td>
                <td>{{ now()->format('m/Y') }}</td>
                <td><span class="badge bg-success">Completado</span></td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" onclick="verReporte(2)">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="descargarReporte(2)">
                            <i class="bi bi-download"></i>
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@push('styles')
<style>
.report-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}
.report-card:hover {
    transform: translateY(-5px);
    border-left-color: var(--bs-primary);
}
</style>
@endpush

<!-- Modal Reporte Personalizado -->
<div class="modal fade" id="reportePersonalizadoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-gear me-2"></i>Reporte Personalizado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formReportePersonalizado">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Reporte</label>
                            <select class="form-select" id="tipoReporte" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="ventas">Ventas</option>
                                <option value="inventario">Inventario</option>
                                <option value="clientes">Clientes</option>
                                <option value="financiero">Financiero</option>
                                <option value="productos">Productos</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Período</label>
                            <select class="form-select" id="periodoReporte">
                                <option value="personalizado">Personalizado</option>
                                <option value="hoy">Hoy</option>
                                <option value="semana">Esta Semana</option>
                                <option value="mes">Este Mes</option>
                                <option value="trimestre">Este Trimestre</option>
                                <option value="año">Este Año</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" class="form-control" id="fechaInicioPersonalizado" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Fin</label>
                            <input type="date" class="form-control" id="fechaFinPersonalizado" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Formato de Salida</label>
                            <select class="form-select" id="formatoSalida">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Agrupar por</label>
                            <select class="form-select" id="agruparPor">
                                <option value="dia">Día</option>
                                <option value="semana">Semana</option>
                                <option value="mes">Mes</option>
                                <option value="categoria">Categoría</option>
                                <option value="producto">Producto</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Filtros Adicionales</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="incluirGraficos">
                                <label class="form-check-label" for="incluirGraficos">
                                    Incluir gráficos estadísticos
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="incluirResumen">
                                <label class="form-check-label" for="incluirResumen">
                                    Incluir resumen ejecutivo
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="incluirDetalles">
                                <label class="form-check-label" for="incluirDetalles">
                                    Incluir detalles completos
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" onclick="previsualizarReporte()">
                    <i class="bi bi-eye me-1"></i>Previsualizar
                </button>
                <button type="button" class="btn btn-success" onclick="generarReportePersonalizado()">
                    <i class="bi bi-file-earmark me-1"></i>Generar Reporte
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Reporte -->
<div class="modal fade" id="verReporteModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-graph-up me-2"></i>Visualizar Reporte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoReporte">
                <!-- Contenido dinámico del reporte -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning" onclick="editarReporteActual()">
                    <i class="bi bi-pencil me-1"></i>Editar
                </button>
                <button type="button" class="btn btn-success" onclick="descargarReporteActual()">
                    <i class="bi bi-download me-1"></i>Descargar
                </button>
                <button type="button" class="btn btn-primary" onclick="compartirReporte()">
                    <i class="bi bi-share me-1"></i>Compartir
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let reporteActual = null;

function reporteVentas() {
    generarReporteEspecifico('ventas', 'Reporte de Ventas');
}

function reporteInventario() {
    generarReporteEspecifico('inventario', 'Reporte de Inventario');
}

function reporteClientes() {
    generarReporteEspecifico('clientes', 'Reporte de Clientes');
}

function reporteFinanciero() {
    generarReporteEspecifico('financiero', 'Reporte Financiero');
}

function generarReporteEspecifico(tipo, titulo) {
    Swal.fire({
        title: titulo,
        text: 'Seleccione las opciones del reporte:',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Generar PDF',
        cancelButtonText: 'Generar Excel',
        showDenyButton: true,
        denyButtonText: 'Ver en Pantalla'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Generando...', `${titulo} en formato PDF`, 'success');
        } else if (result.isDenied) {
            mostrarReporteEnPantalla(tipo, titulo);
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Generando...', `${titulo} en formato Excel`, 'success');
        }
    });
}

function mostrarReporteEnPantalla(tipo, titulo) {
    reporteActual = { tipo: tipo, titulo: titulo };
    
    let contenido = '';
    
    switch(tipo) {
        case 'ventas':
            contenido = generarReporteVentas();
            break;
        case 'inventario':
            contenido = generarReporteInventario();
            break;
        case 'clientes':
            contenido = generarReporteClientes();
            break;
        case 'financiero':
            contenido = generarReporteFinanciero();
            break;
    }
    
    document.getElementById('contenidoReporte').innerHTML = contenido;
    const modal = new bootstrap.Modal(document.getElementById('verReporteModal'));
    modal.show();
}

function generarReporteVentas() {
    return `
        <div class="row mb-4">
            <div class="col-md-12">
                <h4 class="text-primary mb-3">Reporte de Ventas - {{ now()->format('d/m/Y') }}</h4>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h5>Total Vendido</h5>
                                <h3 class="text-primary">S/ 2,450.00</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h5>N° Ventas</h5>
                                <h3 class="text-success">18</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h5>Ticket Promedio</h5>
                                <h3 class="text-warning">S/ 136.11</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <h5>Productos Vendidos</h5>
                                <h3 class="text-info">145</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>Hora</th>
                        <th>N° Venta</th>
                        <th>Cliente</th>
                        <th>Productos</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>09:30</td><td>V-001</td><td>Cliente ABC</td><td>3</td><td>S/ 85.50</td></tr>
                    <tr><td>10:15</td><td>V-002</td><td>Cliente XYZ</td><td>2</td><td>S/ 125.00</td></tr>
                    <tr><td>11:20</td><td>V-003</td><td>Cliente 123</td><td>5</td><td>S/ 200.75</td></tr>
                </tbody>
            </table>
        </div>
    `;
}

function generarReporteInventario() {
    return `
        <div class="row mb-4">
            <div class="col-md-12">
                <h4 class="text-success mb-3">Reporte de Inventario - {{ now()->format('d/m/Y') }}</h4>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h5>Total Productos</h5>
                                <h3 class="text-primary">245</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h5>Stock Bajo</h5>
                                <h3 class="text-warning">8</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <h5>Por Vencer</h5>
                                <h3 class="text-danger">3</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h5>Valor Total</h5>
                                <h3 class="text-success">S/ 15,750</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-success">
                    <tr>
                        <th>Producto</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Precio</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Paracetamol 500mg</td><td>150</td><td>20</td><td>S/ 25.50</td><td>S/ 3,825.00</td></tr>
                    <tr><td>Ibuprofeno 400mg</td><td>85</td><td>15</td><td>S/ 35.00</td><td>S/ 2,975.00</td></tr>
                    <tr><td>Amoxicilina 250mg</td><td>65</td><td>10</td><td>S/ 45.80</td><td>S/ 2,977.00</td></tr>
                </tbody>
            </table>
        </div>
    `;
}

function generarReporteClientes() {
    return `
        <div class="row mb-4">
            <div class="col-md-12">
                <h4 class="text-info mb-3">Reporte de Clientes - {{ now()->format('d/m/Y') }}</h4>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h5>Total Clientes</h5>
                                <h3 class="text-primary">127</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h5>Clientes Activos</h5>
                                <h3 class="text-success">98</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h5>Nuevos Este Mes</h5>
                                <h3 class="text-warning">15</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <h5>Historias Clínicas</h5>
                                <h3 class="text-info">78</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-info">
                    <tr>
                        <th>Cliente</th>
                        <th>DNI</th>
                        <th>Últma Compra</th>
                        <th>Total Compras</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>María González</td><td>12345678</td><td>{{ now()->format('d/m/Y') }}</td><td>S/ 450.00</td><td><span class="badge bg-success">Activo</span></td></tr>
                    <tr><td>Carlos López</td><td>87654321</td><td>{{ now()->subDays(2)->format('d/m/Y') }}</td><td>S/ 280.50</td><td><span class="badge bg-success">Activo</span></td></tr>
                    <tr><td>Ana Torres</td><td>11223344</td><td>{{ now()->subWeek()->format('d/m/Y') }}</td><td>S/ 125.75</td><td><span class="badge bg-warning">Regular</span></td></tr>
                </tbody>
            </table>
        </div>
    `;
}

function generarReporteFinanciero() {
    return `
        <div class="row mb-4">
            <div class="col-md-12">
                <h4 class="text-warning mb-3">Reporte Financiero - {{ now()->format('d/m/Y') }}</h4>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h5>Ingresos</h5>
                                <h3 class="text-success">S/ 2,450.00</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <h5>Egresos</h5>
                                <h3 class="text-danger">S/ 380.00</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h5>Utilidad</h5>
                                <h3 class="text-primary">S/ 2,070.00</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h5>Margen</h5>
                                <h3 class="text-warning">84.5%</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h6>Movimientos de Ingresos</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>Concepto</th><th>Monto</th></tr></thead>
                        <tbody>
                            <tr><td>Ventas del día</td><td class="text-success">+S/ 2,450.00</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <h6>Movimientos de Egresos</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>Concepto</th><th>Monto</th></tr></thead>
                        <tbody>
                            <tr><td>Compra suministros</td><td class="text-danger">-S/ 150.00</td></tr>
                            <tr><td>Gastos operativos</td><td class="text-danger">-S/ 230.00</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
}

function reporteKardex() {
    Swal.fire({
        title: 'Kardex Valorizado',
        text: '¿Qué tipo de kardex desea generar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Kardex General',
        cancelButtonText: 'Por Producto',
        showDenyButton: true,
        denyButtonText: 'Por Categoría'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Generando...', 'Kardex general valorizado', 'success');
        } else if (result.isDenied) {
            Swal.fire('Generando...', 'Kardex por categoría', 'success');
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Generando...', 'Kardex por producto específico', 'success');
        }
    });
}

function reporteVencimientos() {
    Swal.fire({
        title: 'Productos por Vencer',
        text: 'Seleccione el período de alerta:',
        input: 'select',
        inputOptions: {
            '7': 'Próximos 7 días',
            '15': 'Próximos 15 días',
            '30': 'Próximos 30 días',
            '60': 'Próximos 60 días'
        },
        showCancelButton: true,
        confirmButtonText: 'Generar Reporte'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Generando...', `Productos por vencer en ${result.value} días`, 'success');
        }
    });
}

function reporteTopProductos() {
    Swal.fire({
        title: 'Top Productos Vendidos',
        text: 'Configure el reporte:',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Período:</label>
                    <select class="form-select" id="periodoTop">
                        <option value="dia">Hoy</option>
                        <option value="semana">Esta Semana</option>
                        <option value="mes">Este Mes</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cantidad de productos:</label>
                    <select class="form-select" id="cantidadTop">
                        <option value="10">Top 10</option>
                        <option value="20">Top 20</option>
                        <option value="50">Top 50</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Generar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Generando...', 'Top productos más vendidos', 'success');
        }
    });
}

function reporteProveedores() {
    Swal.fire('Generando...', 'Análisis de proveedores en proceso', 'success');
}

function reportePersonalizado() {
    const modal = new bootstrap.Modal(document.getElementById('reportePersonalizadoModal'));
    modal.show();
}

function previsualizarReporte() {
    const tipo = document.getElementById('tipoReporte').value;
    if (!tipo) {
        Swal.fire('Error', 'Seleccione un tipo de reporte', 'error');
        return;
    }
    
    Swal.fire({
        title: 'Previsualización',
        html: `
            <div class="text-center">
                <i class="bi bi-eye text-primary" style="font-size: 3rem;"></i>
                <p class="mt-3">Previsualización del reporte de <strong>${tipo}</strong></p>
                <p class="text-muted">Esta funcionalidad estará disponible próximamente</p>
            </div>
        `,
        width: 500
    });
}

function generarReportePersonalizado() {
    const form = document.getElementById('formReportePersonalizado');
    const tipo = document.getElementById('tipoReporte').value;
    const formato = document.getElementById('formatoSalida').value;
    
    if (!tipo) {
        Swal.fire('Error', 'Complete todos los campos requeridos', 'error');
        return;
    }
    
    Swal.fire({
        title: 'Reporte Generado',
        html: `
            <div class="text-center">
                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                <p class="mt-3">Reporte personalizado generado exitosamente</p>
                <p><strong>Tipo:</strong> ${tipo.toUpperCase()}</p>
                <p><strong>Formato:</strong> ${formato.toUpperCase()}</p>
            </div>
        `,
        icon: 'success'
    }).then(() => {
        bootstrap.Modal.getInstance(document.getElementById('reportePersonalizadoModal')).hide();
    });
}

function aplicarFiltros() {
    const fechaInicio = document.getElementById('fechaInicio').value;
    const fechaFin = document.getElementById('fechaFin').value;
    
    if (fechaInicio && fechaFin) {
        Swal.fire('Filtros aplicados', `Período: ${fechaInicio} - ${fechaFin}`, 'success');
    } else {
        Swal.fire('Error', 'Seleccione ambas fechas', 'error');
    }
}

function limpiarFiltros() {
    document.getElementById('fechaInicio').value = '{{ now()->format("Y-m-d") }}';
    document.getElementById('fechaFin').value = '{{ now()->format("Y-m-d") }}';
    Swal.fire('Filtros limpiados', 'Fechas restablecidas', 'info');
}

function verReporte(id) {
    const reportes = {
        1: { tipo: 'ventas', titulo: 'Reporte de Ventas Diario' },
        2: { tipo: 'inventario', titulo: 'Kardex Mensual' }
    };
    
    const reporte = reportes[id];
    if (reporte) {
        mostrarReporteEnPantalla(reporte.tipo, reporte.titulo);
    }
}

function descargarReporte(id) {
    Swal.fire({
        title: 'Descargar Reporte',
        text: 'Seleccione el formato:',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'PDF',
        cancelButtonText: 'Excel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Descargando...', `Reporte #${id} en formato PDF`, 'success');
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Descargando...', `Reporte #${id} en formato Excel`, 'success');
        }
    });
}

function editarReporteActual() {
    if (reporteActual) {
        Swal.fire('Info', `Editar reporte de ${reporteActual.tipo}`, 'info');
    }
}

function descargarReporteActual() {
    if (reporteActual) {
        Swal.fire('Descargando...', `${reporteActual.titulo} en proceso`, 'success');
    }
}

function compartirReporte() {
    Swal.fire({
        title: 'Compartir Reporte',
        html: `
            <div class="mb-3">
                <label class="form-label">Email del destinatario:</label>
                <input type="email" class="form-control" id="emailDestino" placeholder="usuario@ejemplo.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Mensaje (opcional):</label>
                <textarea class="form-control" id="mensajeCompartir" rows="3" placeholder="Adjunto el reporte solicitado..."></textarea>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Enviado', 'Reporte compartido exitosamente', 'success');
        }
    });
}

function exportarTodo() {
    Swal.fire({
        title: 'Exportar Todos los Reportes',
        text: 'Esta acción generará un archivo comprimido con todos los reportes disponibles',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Exportar ZIP',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Generando...', 'Empaquetando todos los reportes', 'success');
        }
    });
}
</script>
@endpush
@endsection 