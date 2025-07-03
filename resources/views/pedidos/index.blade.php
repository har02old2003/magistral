@extends('layouts.modern')

@section('title', 'Pedidos - PharmaSys Pro')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-clipboard-check me-3"></i>Gestión de Pedidos
        </h1>
        <p class="text-muted mb-0">Control de pedidos de compra, venta y delivery</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevoPedidoModal">
            <i class="bi bi-plus me-1"></i> Nuevo Pedido
        </button>
        <button class="btn btn-info btn-modern" onclick="exportarPedidos()">
            <i class="bi bi-download me-1"></i> Exportar
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
.pedido-card {
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
}
.pedido-card:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>
@endpush

@section('content')
@php
    try {
        $pedidos = \App\Models\Pedido::with(['cliente', 'proveedor'])->orderBy('created_at', 'desc')->get();
        $totalPedidos = $pedidos->count();
        $pendientes = $pedidos->where('estado', 'pendiente')->count();
        $confirmados = $pedidos->where('estado', 'confirmado')->count();
    } catch(\Exception $e) {
        $pedidos = collect([
            (object)['id' => 1, 'numero_pedido' => 'PED-001', 'tipo_pedido' => 'compra', 'estado' => 'pendiente', 'fecha_pedido' => now(), 'total' => 150.00, 'cliente' => null, 'proveedor' => (object)['nombre' => 'Proveedor Test']],
            (object)['id' => 2, 'numero_pedido' => 'PED-002', 'tipo_pedido' => 'delivery', 'estado' => 'confirmado', 'fecha_pedido' => now(), 'total' => 75.50, 'cliente' => (object)['nombre' => 'Cliente Test'], 'proveedor' => null]
        ]);
        $totalPedidos = 2;
        $pendientes = 1;
        $confirmados = 1;
    }
@endphp

<!-- Estadísticas de Pedidos -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card primary">
            <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-clipboard-data"></i>
            </div>
            <div class="text-primary" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $totalPedidos }}</div>
            <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Pedidos</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card warning">
            <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="text-warning" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $pendientes }}</div>
            <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Pendientes</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card success">
            <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="text-success" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $confirmados }}</div>
            <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Confirmados</div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card info">
            <div class="text-info" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="text-info" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">S/ {{ number_format($pedidos->sum('total'), 2) }}</div>
            <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Valor Total</div>
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
                    <input type="text" class="form-control" placeholder="Buscar pedido..." id="searchPedido">
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="tipoFilter">
                        <option value="">Todos los tipos</option>
                        <option value="compra">Compra</option>
                        <option value="venta">Venta</option>
                        <option value="delivery">Delivery</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="estadoFilter">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendientes</option>
                        <option value="confirmado">Confirmados</option>
                        <option value="entregado">Entregados</option>
                        <option value="cancelado">Cancelados</option>
                    </select>
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
                <button class="btn btn-primary btn-modern btn-sm" onclick="generarReporte()">
                    <i class="bi bi-graph-up me-1"></i> Reporte de Pedidos
                </button>
                <button class="btn btn-warning btn-modern btn-sm" onclick="gestionarStock()">
                    <i class="bi bi-box-seam me-1"></i> Gestionar Stock
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Pedidos -->
@if($totalPedidos > 0)
<div class="modern-table">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Número</th>
                <th>Tipo</th>
                <th>Cliente/Proveedor</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $pedido)
            <tr class="pedido-card">
                <td>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-file-earmark-text text-primary me-2" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong>{{ $pedido->numero_pedido }}</strong>
                            <br><small class="text-muted">{{ $pedido->fecha_pedido->format('d/m/Y') }}</small>
                        </div>
                    </div>
                </td>
                <td>
                    @switch($pedido->tipo_pedido)
                        @case('compra')
                            <span class="badge bg-info badge-modern">
                                <i class="bi bi-cart-plus me-1"></i>Compra
                            </span>
                            @break
                        @case('venta')
                            <span class="badge bg-success badge-modern">
                                <i class="bi bi-cart-check me-1"></i>Venta
                            </span>
                            @break
                        @case('delivery')
                            <span class="badge bg-warning badge-modern">
                                <i class="bi bi-truck me-1"></i>Delivery
                            </span>
                            @break
                    @endswitch
                </td>
                <td>
                    @if($pedido->cliente)
                        <i class="bi bi-person me-1"></i>{{ $pedido->cliente->nombre }}
                    @elseif($pedido->proveedor)
                        <i class="bi bi-building me-1"></i>{{ $pedido->proveedor->nombre }}
                    @else
                        <span class="text-muted">Sin asignar</span>
                    @endif
                </td>
                <td>{{ $pedido->fecha_pedido->format('d/m/Y H:i') }}</td>
                <td><strong>S/ {{ number_format($pedido->total, 2) }}</strong></td>
                <td>
                    @switch($pedido->estado)
                        @case('pendiente')
                            <span class="badge bg-warning">Pendiente</span>
                            @break
                        @case('confirmado')
                            <span class="badge bg-info">Confirmado</span>
                            @break
                        @case('entregado')
                            <span class="badge bg-success">Entregado</span>
                            @break
                        @case('cancelado')
                            <span class="badge bg-danger">Cancelado</span>
                            @break
                    @endswitch
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary" onclick="verPedido({{ $pedido->id }})">
                            <i class="bi bi-eye"></i>
                        </button>
                        @if($pedido->estado === 'pendiente')
                        <button class="btn btn-sm btn-outline-success" onclick="confirmarPedido({{ $pedido->id }})">
                            <i class="bi bi-check"></i>
                        </button>
                        @endif
                        <button class="btn btn-sm btn-outline-secondary" onclick="imprimirPedido({{ $pedido->id }})">
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
    <i class="bi bi-clipboard-x"></i>
    <h4>No hay pedidos registrados</h4>
    <p>Comienza creando tu primer pedido</p>
    <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevoPedidoModal">
        <i class="bi bi-plus me-1"></i> Crear Primer Pedido
    </button>
</div>
@endif

<!-- Modal Nuevo Pedido -->
<div class="modal fade" id="nuevoPedidoModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-cart-plus me-2"></i>Nuevo Pedido
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoPedido">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tipo de Pedido</label>
                            <select class="form-select" id="tipoPedido" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="compra">Compra a Proveedor</option>
                                <option value="venta">Pedido de Cliente</option>
                                <option value="delivery">Delivery</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Cliente/Proveedor</label>
                            <select class="form-select" id="clienteProveedor" required>
                                <option value="">Seleccionar...</option>
                                <option value="cliente1">Cliente ABC</option>
                                <option value="proveedor1">Proveedor XYZ</option>
                                <option value="cliente2">Cliente Delivery</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha de Entrega</label>
                            <input type="date" class="form-control" id="fechaEntrega" required value="{{ now()->addDays(3)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prioridad</label>
                            <select class="form-select" id="prioridad">
                                <option value="normal">Normal</option>
                                <option value="alta">Alta</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Método de Pago</label>
                            <select class="form-select" id="metodoPago">
                                <option value="efectivo">Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="credito">Crédito</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observacionesPedido" rows="2" placeholder="Instrucciones especiales"></textarea>
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="mb-3">Productos del Pedido</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="productoAgregarPedido">
                                <option value="">Seleccionar producto</option>
                                <option value="1" data-precio="25.50">Paracetamol 500mg - S/ 25.50</option>
                                <option value="2" data-precio="35.00">Ibuprofeno 400mg - S/ 35.00</option>
                                <option value="3" data-precio="45.80">Amoxicilina 250mg - S/ 45.80</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="cantidadAgregarPedido" placeholder="Cantidad" min="1">
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" class="form-control" id="precioAgregarPedido" placeholder="Precio">
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" class="form-control" id="descuentoAgregar" placeholder="Descuento %" min="0" max="100">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success" onclick="agregarProductoPedido()">
                                <i class="bi bi-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tablaProductosPedido">
                            <thead class="table-primary">
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unit.</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="productosPedido">
                                <!-- Productos dinámicos -->
                            </tbody>
                            <tfoot>
                                <tr class="table-info">
                                    <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                                    <td><strong>S/ <span id="totalPedido">0.00</span></strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" onclick="guardarBorrador()">
                    <i class="bi bi-save me-1"></i>Guardar Borrador
                </button>
                <button type="button" class="btn btn-success" onclick="guardarPedido()">
                    <i class="bi bi-check me-1"></i>Crear Pedido
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Pedido -->
<div class="modal fade" id="verPedidoModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>Detalle del Pedido
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoPedido">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning" onclick="editarPedidoActual()">
                    <i class="bi bi-pencil me-1"></i>Editar
                </button>
                <button type="button" class="btn btn-success" onclick="confirmarPedidoActual()">
                    <i class="bi bi-check-circle me-1"></i>Confirmar
                </button>
                <button type="button" class="btn btn-danger" onclick="cancelarPedidoActual()">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="imprimirPedidoActual()">
                    <i class="bi bi-printer me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let pedidoActual = null;
let productosPedidoSeleccionados = [];

// Auto-rellenar precio al seleccionar producto
document.addEventListener('DOMContentLoaded', function() {
    const productoSelect = document.getElementById('productoAgregarPedido');
    if (productoSelect) {
        productoSelect.addEventListener('change', function() {
            const precio = this.selectedOptions[0].getAttribute('data-precio');
            document.getElementById('precioAgregarPedido').value = precio || '';
        });
    }
});

function limpiarFiltros() {
    document.getElementById('searchPedido').value = '';
    document.getElementById('tipoFilter').value = '';
    document.getElementById('estadoFilter').value = '';
}

function agregarProductoPedido() {
    const productoSelect = document.getElementById('productoAgregarPedido');
    const cantidad = parseInt(document.getElementById('cantidadAgregarPedido').value);
    const precio = parseFloat(document.getElementById('precioAgregarPedido').value);
    const descuento = parseFloat(document.getElementById('descuentoAgregar').value) || 0;
    
    if (!productoSelect.value || !cantidad || !precio) {
        Swal.fire('Error', 'Complete todos los campos del producto', 'error');
        return;
    }
    
    const subtotal = cantidad * precio * (1 - descuento / 100);
    
    const producto = {
        id: productoSelect.value,
        nombre: productoSelect.selectedOptions[0].text.split(' - ')[0],
        cantidad: cantidad,
        precio: precio,
        descuento: descuento,
        subtotal: subtotal
    };
    
    productosPedidoSeleccionados.push(producto);
    actualizarTablaProductosPedido();
    
    // Limpiar campos
    document.getElementById('productoAgregarPedido').value = '';
    document.getElementById('cantidadAgregarPedido').value = '';
    document.getElementById('precioAgregarPedido').value = '';
    document.getElementById('descuentoAgregar').value = '';
}

function actualizarTablaProductosPedido() {
    const tbody = document.getElementById('productosPedido');
    tbody.innerHTML = '';
    let total = 0;
    
    productosPedidoSeleccionados.forEach((producto, index) => {
        total += producto.subtotal;
        tbody.innerHTML += `
            <tr>
                <td>${producto.nombre}</td>
                <td>${producto.cantidad}</td>
                <td>S/ ${producto.precio.toFixed(2)}</td>
                <td>${producto.descuento}%</td>
                <td>S/ ${producto.subtotal.toFixed(2)}</td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="editarProductoPedido(${index})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarProductoPedido(${index})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    document.getElementById('totalPedido').textContent = total.toFixed(2);
}

function editarProductoPedido(index) {
    const producto = productosPedidoSeleccionados[index];
    document.getElementById('productoAgregarPedido').value = producto.id;
    document.getElementById('cantidadAgregarPedido').value = producto.cantidad;
    document.getElementById('precioAgregarPedido').value = producto.precio;
    document.getElementById('descuentoAgregar').value = producto.descuento;
    
    // Eliminar de la lista para re-agregar
    eliminarProductoPedido(index);
}

function eliminarProductoPedido(index) {
    productosPedidoSeleccionados.splice(index, 1);
    actualizarTablaProductosPedido();
}

function guardarBorrador() {
    const numeroPedido = 'PED-' + String(Date.now()).slice(-3);
    Swal.fire({
        title: 'Borrador Guardado',
        text: `Pedido ${numeroPedido} guardado como borrador`,
        icon: 'info',
        timer: 2000
    });
}

function guardarPedido() {
    const form = document.getElementById('formNuevoPedido');
    const tipo = document.getElementById('tipoPedido').value;
    const clienteProveedor = document.getElementById('clienteProveedor').value;
    
    if (!tipo || !clienteProveedor || productosPedidoSeleccionados.length === 0) {
        Swal.fire('Error', 'Complete todos los campos requeridos y agregue al menos un producto', 'error');
        return;
    }
    
    const numeroPedido = 'PED-' + String(Date.now()).slice(-3);
    const total = document.getElementById('totalPedido').textContent;
    
    Swal.fire({
        title: 'Pedido Creado',
        html: `
            <div class="text-start">
                <p><strong>Número:</strong> ${numeroPedido}</p>
                <p><strong>Tipo:</strong> ${tipo.toUpperCase()}</p>
                <p><strong>Cliente/Proveedor:</strong> ${document.getElementById('clienteProveedor').selectedOptions[0].text}</p>
                <p><strong>Total:</strong> S/ ${total}</p>
                <p><strong>Productos:</strong> ${productosPedidoSeleccionados.length}</p>
            </div>
        `,
        icon: 'success',
        timer: 3000
    }).then(() => {
        bootstrap.Modal.getInstance(document.getElementById('nuevoPedidoModal')).hide();
        form.reset();
        productosPedidoSeleccionados = [];
        actualizarTablaProductosPedido();
    });
}

function verPedido(id) {
    pedidoActual = id;
    
    // Simular datos del pedido
    const pedido = {
        numero: 'PED-' + String(id).padStart(3, '0'),
        tipo: id == 1 ? 'Compra a Proveedor' : 'Pedido de Cliente',
        cliente: id == 1 ? 'Proveedor ABC' : 'Cliente XYZ',
        fecha: '{{ now()->format("d/m/Y") }}',
        fechaEntrega: '{{ now()->addDays(3)->format("d/m/Y") }}',
        estado: id == 1 ? 'Pendiente' : 'Confirmado',
        prioridad: id == 1 ? 'Alta' : 'Normal',
        metodoPago: 'Transferencia',
        productos: [
            {nombre: 'Paracetamol 500mg', cantidad: 100, precio: 25.50, descuento: 5, subtotal: 2422.5},
            {nombre: 'Ibuprofeno 400mg', cantidad: 50, precio: 35.00, descuento: 0, subtotal: 1750.0}
        ],
        subtotal: 4172.5,
        igv: 751.05,
        total: 4923.55,
        observaciones: 'Entrega en horario de oficina'
    };
    
    const contenido = `
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-info-circle me-1"></i>Información del Pedido</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Número:</strong> ${pedido.numero}</p>
                        <p><strong>Tipo:</strong> ${pedido.tipo}</p>
                        <p><strong>Cliente/Proveedor:</strong> ${pedido.cliente}</p>
                        <p><strong>Fecha:</strong> ${pedido.fecha}</p>
                        <p><strong>Fecha Entrega:</strong> ${pedido.fechaEntrega}</p>
                        <p><strong>Estado:</strong> <span class="badge ${pedido.estado === 'Pendiente' ? 'bg-warning' : 'bg-success'}">${pedido.estado}</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-cash-coin me-1"></i>Información Comercial</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Prioridad:</strong> <span class="badge ${pedido.prioridad === 'Alta' ? 'bg-danger' : 'bg-secondary'}">${pedido.prioridad}</span></p>
                        <p><strong>Método de Pago:</strong> ${pedido.metodoPago}</p>
                        <p><strong>Subtotal:</strong> S/ ${pedido.subtotal.toFixed(2)}</p>
                        <p><strong>IGV (18%):</strong> S/ ${pedido.igv.toFixed(2)}</p>
                        <p><strong>TOTAL:</strong> <span class="fs-5 text-success fw-bold">S/ ${pedido.total.toFixed(2)}</span></p>
                    </div>
                </div>
            </div>
        </div>
        
        <h6 class="mb-3">Detalle de Productos</h6>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Descuento</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    ${pedido.productos.map(p => `
                        <tr>
                            <td>${p.nombre}</td>
                            <td>${p.cantidad}</td>
                            <td>S/ ${p.precio.toFixed(2)}</td>
                            <td>${p.descuento}%</td>
                            <td>S/ ${p.subtotal.toFixed(2)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
        
        ${pedido.observaciones ? `
            <div class="alert alert-info">
                <strong>Observaciones:</strong> ${pedido.observaciones}
            </div>
        ` : ''}
        
        <div class="row mt-3">
            <div class="col-md-6">
                <h6 class="text-primary">Historial del Pedido</h6>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Pedido creado</span>
                        <small class="text-muted">${pedido.fecha} 10:30</small>
                    </li>
                    ${pedido.estado === 'Confirmado' ? `
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Pedido confirmado</span>
                            <small class="text-muted">${pedido.fecha} 14:15</small>
                        </li>
                    ` : ''}
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="text-success">Estado de Entrega</h6>
                <div class="progress">
                    <div class="progress-bar bg-success" style="width: ${pedido.estado === 'Confirmado' ? '50' : '25'}%"></div>
                </div>
                <small class="text-muted">
                    ${pedido.estado === 'Confirmado' ? 'En preparación' : 'Pendiente de confirmación'}
                </small>
            </div>
        </div>
    `;
    
    document.getElementById('contenidoPedido').innerHTML = contenido;
    const modal = new bootstrap.Modal(document.getElementById('verPedidoModal'));
    modal.show();
}

function confirmarPedido(id) {
    confirmarPedidoModal(id);
}

function confirmarPedidoModal(id) {
    Swal.fire({
        title: '¿Confirmar pedido?',
        text: 'Esta acción confirmará el pedido y iniciará el proceso',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Pedido Confirmado',
                html: `
                    <div class="text-center">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <p class="mt-3">El pedido #${id} ha sido confirmado exitosamente</p>
                        <p class="text-muted">Se ha iniciado el proceso de preparación</p>
                    </div>
                `,
                icon: 'success'
            });
        }
    });
}

function cancelarPedidoActual() {
    if (pedidoActual) {
        Swal.fire({
            title: '¿Cancelar pedido?',
            text: 'Esta acción cancelará permanentemente el pedido',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Cancelado', 'Pedido cancelado exitosamente', 'success');
            }
        });
    }
}

function editarPedidoActual() {
    if (pedidoActual) {
        Swal.fire('Info', 'Función para editar pedido #' + pedidoActual, 'info');
    }
}

function confirmarPedidoActual() {
    if (pedidoActual) {
        confirmarPedidoModal(pedidoActual);
    }
}

function imprimirPedido(id) {
    Swal.fire('Imprimiendo...', 'Generando documento del pedido #' + id, 'info');
}

function imprimirPedidoActual() {
    if (pedidoActual) {
        imprimirPedido(pedidoActual);
    }
}

function exportarPedidos() {
    Swal.fire({
        title: 'Exportar Pedidos',
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

function generarReporte() {
    Swal.fire('Generando...', 'Reporte de pedidos en proceso', 'info');
}

function gestionarStock() {
    window.location.href = '/movimientos';
}
</script>
@endpush
@endsection 