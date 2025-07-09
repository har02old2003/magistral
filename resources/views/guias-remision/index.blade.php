@extends('layouts.modern')

@section('title', 'Guías de Remisión - Farmacia Magistral')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-truck me-3"></i>Guías de Remisión
        </h1>
        <p class="text-muted mb-0">Control de traslados y guías de remisión</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success btn-modern" onclick="nuevaGuia()">
            <i class="bi bi-plus me-1"></i> Nueva Guía
        </button>
        <button class="btn btn-info btn-modern" onclick="exportarGuias()">
            <i class="bi bi-download me-1"></i> Exportar
        </button>
    </div>
</div>
@endsection

@section('content')
<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card primary">
            <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="text-primary" style="font-size: 3rem; font-weight: 700;">18</div>
            <div style="color: #6c757d; font-weight: 500;">Total Guías</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card warning">
            <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="text-warning" style="font-size: 3rem; font-weight: 700;">6</div>
            <div style="color: #6c757d; font-weight: 500;">En Tránsito</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card success">
            <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="text-success" style="font-size: 3rem; font-weight: 700;">10</div>
            <div style="color: #6c757d; font-weight: 500;">Entregadas</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card info">
            <div class="text-info" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-geo-alt"></i>
            </div>
            <div class="text-info" style="font-size: 3rem; font-weight: 700;">3</div>
            <div style="color: #6c757d; font-weight: 500;">Destinos Activos</div>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="modern-card">
            <h5 class="mb-3">
                <i class="bi bi-funnel text-primary me-2"></i>
                Filtros de Búsqueda
            </h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <input type="text" class="form-control" placeholder="Buscar guía..." id="searchGuia">
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="estadoFilter">
                        <option value="">Todos los estados</option>
                        <option value="emitida">Emitidas</option>
                        <option value="transito">En Tránsito</option>
                        <option value="entregada">Entregadas</option>
                        <option value="anulada">Anuladas</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <input type="date" class="form-control" id="fechaFiltro">
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
        <div class="modern-card" style="height: 100%;">
            <h6 class="mb-3">
                <i class="bi bi-lightning text-warning me-2"></i>
                Acciones Rápidas
            </h6>
            <div class="d-grid gap-2">
                <button class="btn btn-primary btn-modern btn-sm" onclick="reporteGuias()">
                    <i class="bi bi-graph-up me-1"></i> Reporte de Guías
                </button>
                <button class="btn btn-warning btn-modern btn-sm" onclick="seguimientoEnvios()">
                    <i class="bi bi-geo-alt-fill me-1"></i> Seguimiento
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Guías de Remisión -->
<div class="modern-table">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Número Guía</th>
                <th>Destinatario</th>
                <th>Destino</th>
                <th>Fecha Emisión</th>
                <th>Estado</th>
                <th>Transportista</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>GR-001</strong>
                    <br><small class="text-muted">Traslado por venta</small>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-circle text-primary me-2"></i>
                        <div>
                            <strong>Cliente ABC</strong>
                            <br><small class="text-muted">RUC: 20123456789</small>
                        </div>
                    </div>
                </td>
                <td>
                    <i class="bi bi-geo-alt me-1"></i>Lima, Perú
                    <br><small class="text-muted">Av. Principal 123</small>
                </td>
                <td>{{ now()->format('d/m/Y') }}</td>
                <td>
                    <span class="badge bg-warning">En Tránsito</span>
                </td>
                <td>
                    <i class="bi bi-truck me-1"></i>Transportes Lima
                    <br><small class="text-muted">Placa: ABC-123</small>
                </td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" onclick="verGuia(1)">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="actualizarEstado(1)">
                            <i class="bi bi-arrow-up-circle"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="imprimirGuia(1)">
                            <i class="bi bi-printer"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>GR-002</strong>
                    <br><small class="text-muted">Traslado interno</small>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-building text-primary me-2"></i>
                        <div>
                            <strong>Sucursal Norte</strong>
                            <br><small class="text-muted">Almacén 002</small>
                        </div>
                    </div>
                </td>
                <td>
                    <i class="bi bi-geo-alt me-1"></i>Callao, Perú
                    <br><small class="text-muted">Jr. Comercio 456</small>
                </td>
                <td>{{ now()->subDay()->format('d/m/Y') }}</td>
                <td>
                    <span class="badge bg-success">Entregada</span>
                </td>
                <td>
                    <i class="bi bi-truck me-1"></i>Servicio Propio
                    <br><small class="text-muted">Placa: XYZ-789</small>
                </td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" onclick="verGuia(2)">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="confirmarRecepcion(2)">
                            <i class="bi bi-check-square"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="imprimirGuia(2)">
                            <i class="bi bi-printer"></i>
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal Nueva Guía -->
<div class="modal fade" id="nuevaGuiaModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-plus me-2"></i>Nueva Guía de Remisión
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaGuia">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Traslado</label>
                            <select class="form-select" id="tipoTraslado" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="venta">Venta</option>
                                <option value="compra">Compra</option>
                                <option value="traslado_interno">Traslado Interno</option>
                                <option value="devolucion">Devolución</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Traslado</label>
                            <input type="date" class="form-control" id="fechaTraslado" required value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Destinatario</label>
                            <select class="form-select" id="destinatario" required>
                                <option value="">Seleccionar destinatario</option>
                                <option value="1">Cliente ABC - RUC: 20123456789</option>
                                <option value="2">Sucursal Norte - Almacén 002</option>
                                <option value="3">Proveedor XYZ - RUC: 20987654321</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Transportista</label>
                            <select class="form-select" id="transportista" required>
                                <option value="">Seleccionar transportista</option>
                                <option value="1">Transportes Lima</option>
                                <option value="2">Servicio Propio</option>
                                <option value="3">Olva Courier</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Dirección de Destino</label>
                            <textarea class="form-control" id="direccionDestino" rows="2" required placeholder="Dirección completa de entrega"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Placa del Vehículo</label>
                            <input type="text" class="form-control" id="placaVehiculo" placeholder="Ej: ABC-123">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Conductor</label>
                            <input type="text" class="form-control" id="conductor" placeholder="Nombre del conductor">
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="mb-3">Productos a Trasladar</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <select class="form-select" id="productoAgregar">
                                <option value="">Seleccionar producto</option>
                                <option value="1">Paracetamol 500mg</option>
                                <option value="2">Ibuprofeno 400mg</option>
                                <option value="3">Amoxicilina 250mg</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="cantidadAgregar" placeholder="Cantidad" min="1">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="loteAgregar" placeholder="Lote (opcional)">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success" onclick="agregarProductoGuia()">
                                <i class="bi bi-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tablaProductosGuia">
                            <thead class="table-primary">
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Lote</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="productosGuia">
                                <!-- Productos dinámicos -->
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarGuia()">
                    <i class="bi bi-check me-1"></i>Guardar Guía
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Guía -->
<div class="modal fade" id="verGuiaModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>Detalle de Guía de Remisión
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoGuia">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning" onclick="editarGuiaActual()">
                    <i class="bi bi-pencil me-1"></i>Editar
                </button>
                <button type="button" class="btn btn-success" onclick="actualizarEstadoActual()">
                    <i class="bi bi-arrow-up me-1"></i>Actualizar Estado
                </button>
                <button type="button" class="btn btn-primary" onclick="imprimirGuiaActual()">
                    <i class="bi bi-printer me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let guiaActual = null;
let productosGuiaSeleccionados = [];

function nuevaGuia() {
    document.getElementById('formNuevaGuia').reset();
    productosGuiaSeleccionados = [];
    actualizarTablaProductosGuia();
    document.getElementById('fechaTraslado').value = '{{ now()->format("Y-m-d") }}';
    const modal = new bootstrap.Modal(document.getElementById('nuevaGuiaModal'));
    modal.show();
}

function agregarProductoGuia() {
    const productoSelect = document.getElementById('productoAgregar');
    const cantidad = parseInt(document.getElementById('cantidadAgregar').value);
    const lote = document.getElementById('loteAgregar').value;
    
    if (!productoSelect.value || !cantidad) {
        Swal.fire('Error', 'Seleccione un producto y cantidad', 'error');
        return;
    }
    
    const producto = {
        id: productoSelect.value,
        nombre: productoSelect.selectedOptions[0].text,
        cantidad: cantidad,
        lote: lote || 'N/A'
    };
    
    productosGuiaSeleccionados.push(producto);
    actualizarTablaProductosGuia();
    
    // Limpiar campos
    document.getElementById('productoAgregar').value = '';
    document.getElementById('cantidadAgregar').value = '';
    document.getElementById('loteAgregar').value = '';
}

function actualizarTablaProductosGuia() {
    const tbody = document.getElementById('productosGuia');
    tbody.innerHTML = '';
    
    productosGuiaSeleccionados.forEach((producto, index) => {
        tbody.innerHTML += `
            <tr>
                <td>${producto.nombre}</td>
                <td>${producto.cantidad}</td>
                <td>${producto.lote}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarProductoGuia(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
}

function eliminarProductoGuia(index) {
    productosGuiaSeleccionados.splice(index, 1);
    actualizarTablaProductosGuia();
}

function guardarGuia() {
    const form = document.getElementById('formNuevaGuia');
    const tipoTraslado = document.getElementById('tipoTraslado').value;
    const destinatario = document.getElementById('destinatario').value;
    
    if (!tipoTraslado || !destinatario || productosGuiaSeleccionados.length === 0) {
        Swal.fire('Error', 'Complete todos los campos requeridos y agregue al menos un producto', 'error');
        return;
    }
    
    const numeroGuia = 'GR-' + String(Date.now()).slice(-3);
    
    Swal.fire({
        title: 'Guía de Remisión Creada',
        html: `
            <div class="text-start">
                <p><strong>Número:</strong> ${numeroGuia}</p>
                <p><strong>Tipo:</strong> ${tipoTraslado.replace('_', ' ').toUpperCase()}</p>
                <p><strong>Destinatario:</strong> ${document.getElementById('destinatario').selectedOptions[0].text}</p>
                <p><strong>Productos:</strong> ${productosGuiaSeleccionados.length}</p>
            </div>
        `,
        icon: 'success',
        timer: 3000
    }).then(() => {
        bootstrap.Modal.getInstance(document.getElementById('nuevaGuiaModal')).hide();
    });
}

function verGuia(id) {
    guiaActual = id;
    
    // Simular datos de la guía
    const guia = {
        numero: 'GR-' + String(id).padStart(3, '0'),
        tipo: 'Traslado por venta',
        destinatario: 'Cliente ABC',
        direccion: 'Av. Principal 123, Lima, Perú',
        fechaEmision: '{{ now()->format("d/m/Y") }}',
        fechaTraslado: '{{ now()->format("d/m/Y") }}',
        estado: 'En Tránsito',
        transportista: 'Transportes Lima',
        placa: 'ABC-123',
        conductor: 'Juan Pérez',
        productos: [
            {nombre: 'Paracetamol 500mg', cantidad: 50, lote: 'L001'},
            {nombre: 'Ibuprofeno 400mg', cantidad: 30, lote: 'L002'}
        ]
    };
    
    const contenido = `
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-info-circle me-1"></i>Información General</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Número:</strong> ${guia.numero}</p>
                        <p><strong>Tipo:</strong> ${guia.tipo}</p>
                        <p><strong>Estado:</strong> <span class="badge bg-warning">${guia.estado}</span></p>
                        <p><strong>Fecha Emisión:</strong> ${guia.fechaEmision}</p>
                        <p><strong>Fecha Traslado:</strong> ${guia.fechaTraslado}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-truck me-1"></i>Información de Envío</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Destinatario:</strong> ${guia.destinatario}</p>
                        <p><strong>Dirección:</strong> ${guia.direccion}</p>
                        <p><strong>Transportista:</strong> ${guia.transportista}</p>
                        <p><strong>Placa:</strong> ${guia.placa}</p>
                        <p><strong>Conductor:</strong> ${guia.conductor}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <h6 class="mb-3">Productos a Trasladar</h6>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Lote</th>
                    </tr>
                </thead>
                <tbody>
                    ${guia.productos.map(p => `
                        <tr>
                            <td>${p.nombre}</td>
                            <td>${p.cantidad}</td>
                            <td>${p.lote}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
    
    document.getElementById('contenidoGuia').innerHTML = contenido;
    const modal = new bootstrap.Modal(document.getElementById('verGuiaModal'));
    modal.show();
}

function actualizarEstado(id) {
    Swal.fire({
        title: 'Actualizar Estado',
        text: 'Seleccione el nuevo estado:',
        input: 'select',
        inputOptions: {
            'emitida': 'Emitida',
            'transito': 'En Tránsito',
            'entregada': 'Entregada',
            'anulada': 'Anulada'
        },
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Estado Actualizado', `Guía #${id} - ${result.value.toUpperCase()}`, 'success');
        }
    });
}

function confirmarRecepcion(id) {
    Swal.fire({
        title: '¿Confirmar recepción?',
        text: 'Esta acción marcará la guía como entregada',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Confirmado', 'Recepción confirmada exitosamente', 'success');
        }
    });
}

function imprimirGuia(id) {
    Swal.fire('Imprimiendo...', 'Generando guía de remisión #' + id, 'info');
}

function editarGuiaActual() {
    if (guiaActual) {
        Swal.fire('Info', 'Función para editar guía #' + guiaActual, 'info');
    }
}

function actualizarEstadoActual() {
    if (guiaActual) {
        actualizarEstado(guiaActual);
    }
}

function imprimirGuiaActual() {
    if (guiaActual) {
        imprimirGuia(guiaActual);
    }
}

function limpiarFiltros() {
    document.getElementById('searchGuia').value = '';
    document.getElementById('estadoFilter').value = '';
    document.getElementById('fechaFiltro').value = '';
}

function reporteGuias() {
    Swal.fire('Generando...', 'Reporte de guías de remisión en proceso', 'info');
}

function seguimientoEnvios() {
    Swal.fire('Seguimiento', 'Función de seguimiento de envíos', 'info');
}

function exportarGuias() {
    Swal.fire({
        title: 'Exportar Guías',
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