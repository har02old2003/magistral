@extends('layouts.modern')

@section('title', 'Proformas - Farmacia Magistral')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-file-earmark-text me-3"></i>Proformas
        </h1>
        <p class="text-muted mb-0">Gestión de cotizaciones y presupuestos</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success btn-modern" onclick="nuevaProforma()">
            <i class="bi bi-plus me-1"></i> Nueva Proforma
        </button>
        <button class="btn btn-info btn-modern" onclick="exportarProformas()">
            <i class="bi bi-download me-1"></i> Exportar
        </button>
    </div>
</div>
@endsection

@section('content')
@php
    // Obtener datos reales de las variables pasadas desde el controlador
    $estadisticas = $estadisticas ?? [
        'total' => 0,
        'pendientes' => 0,
        'aceptadas' => 0,
        'convertidas' => 0,
        'monto_mes' => 0
    ];
    $proformas = $proformas ?? collect();
    $clientes = $clientes ?? collect();
@endphp

<!-- Estadísticas -->
<div class="row mb-4 g-4">
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stat-card primary h-100">
            <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="text-primary" style="font-size: 3rem; font-weight: 700;">{{ $estadisticas['total'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">Total Proformas</div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stat-card warning h-100">
            <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="text-warning" style="font-size: 3rem; font-weight: 700;">{{ $estadisticas['pendientes'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">Pendientes</div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stat-card success h-100">
            <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="text-success" style="font-size: 3rem; font-weight: 700;">{{ $estadisticas['aceptadas'] }}</div>
            <div style="color: #6c757d; font-weight: 500;">Aceptadas</div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stat-card info h-100">
            <div class="text-info" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="text-info" style="font-size: 3rem; font-weight: 700;">S/ {{ number_format($estadisticas['monto_mes'], 2) }}</div>
            <div style="color: #6c757d; font-weight: 500;">Valor del Mes</div>
        </div>
    </div>
</div>

<!-- Controles y Filtros -->
<div class="row mb-4">
    <div class="col-lg-8 mb-3">
        <div class="modern-card">
            <h5 class="mb-3">
                <i class="bi bi-funnel text-primary me-2"></i>
                Filtros de Búsqueda
            </h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <input type="text" class="form-control" placeholder="Buscar por número, cliente..." id="searchInput">
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="estadoFilter">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendientes</option>
                        <option value="enviado">Enviadas</option>
                        <option value="aceptado">Aceptadas</option>
                        <option value="rechazado">Rechazadas</option>
                        <option value="convertido">Convertidas</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="clienteFilter">
                        <option value="">Todos los clientes</option>
                        @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nombres }} {{ $cliente->apellidos }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <button class="btn btn-info-modern btn-modern w-100" onclick="limpiarFiltros()">
                        <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="modern-card h-100">
            <h6 class="mb-3">
                <i class="bi bi-lightning text-warning me-2"></i>
                Acciones Rápidas
            </h6>
            <div class="d-grid gap-2">
                <button class="btn btn-success-modern btn-modern" onclick="nuevaProforma()">
                    <i class="bi bi-plus me-1"></i> Nueva Proforma
                </button>
                <button class="btn btn-primary-modern btn-modern" onclick="exportarProformas()">
                    <i class="bi bi-download me-1"></i> Exportar Lista
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Proformas -->
@if($proformas->count() > 0)
<div class="modern-card">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <h5 class="mb-0">
            <i class="bi bi-table text-primary me-2"></i>
            Lista de Proformas
        </h5>
        <span class="badge bg-primary">{{ $proformas->count() }} proformas encontradas</span>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th style="width: 120px;">Número</th>
                    <th>Cliente</th>
                    <th style="width: 100px;">Fecha</th>
                    <th style="width: 120px;">Vencimiento</th>
                    <th style="width: 100px;">Total</th>
                    <th style="width: 120px;">Estado</th>
                    <th style="width: 150px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proformas as $proforma)
                <tr>
                    <td>
                        <strong class="text-primary">{{ $proforma->numero_proforma }}</strong>
                        <br><small class="text-muted">{{ $proforma->created_at->format('d/m/Y') }}</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person text-primary me-2"></i>
                            <div>
                                <strong>{{ $proforma->cliente->nombres ?? 'Sin nombre' }}</strong>
                                @if($proforma->cliente->apellidos)
                                <br><small class="text-muted">{{ $proforma->cliente->apellidos }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $proforma->fecha_proforma ? \Carbon\Carbon::parse($proforma->fecha_proforma)->format('d/m/Y') : 'N/A' }}</td>
                    <td>
                        @if($proforma->fecha_vencimiento)
                            @php
                                $fechaVencimiento = \Carbon\Carbon::parse($proforma->fecha_vencimiento);
                                $hoy = \Carbon\Carbon::now();
                                $claseBadge = $fechaVencimiento->isPast() ? 'bg-danger' : ($fechaVencimiento->diffInDays($hoy) <= 3 ? 'bg-warning' : 'bg-success');
                            @endphp
                            <span class="badge {{ $claseBadge }}-subtle text-{{ $claseBadge }}-emphasis">
                                {{ $fechaVencimiento->format('d/m/Y') }}
                            </span>
                        @else
                            <span class="text-muted">Sin fecha</span>
                        @endif
                    </td>
                    <td><strong class="text-success">S/ {{ number_format($proforma->total, 2) }}</strong></td>
                    <td>
                        @switch($proforma->estado)
                            @case('pendiente')
                                <span class="badge bg-warning badge-modern">
                                    <i class="bi bi-clock me-1"></i>Pendiente
                                </span>
                                @break
                            @case('enviado')
                                <span class="badge bg-info badge-modern">
                                    <i class="bi bi-send me-1"></i>Enviada
                                </span>
                                @break
                            @case('aceptado')
                                <span class="badge bg-success badge-modern">
                                    <i class="bi bi-check-circle me-1"></i>Aceptada
                                </span>
                                @break
                            @case('rechazado')
                                <span class="badge bg-danger badge-modern">
                                    <i class="bi bi-x-circle me-1"></i>Rechazada
                                </span>
                                @break
                            @case('convertido')
                                <span class="badge bg-primary badge-modern">
                                    <i class="bi bi-arrow-right me-1"></i>Convertida
                                </span>
                                @break
                            @default
                                <span class="badge bg-secondary badge-modern">{{ ucfirst($proforma->estado) }}</span>
                        @endswitch
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" title="Ver detalles" onclick="verProforma({{ $proforma->id }})">
                                <i class="bi bi-eye"></i>
                            </button>
                            @if($proforma->estado === 'aceptado')
                            <button type="button" class="btn btn-outline-success btn-sm" title="Convertir a venta" onclick="convertirVenta({{ $proforma->id }})">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                            @endif
                            <button type="button" class="btn btn-outline-info btn-sm" title="Imprimir" onclick="imprimirProforma({{ $proforma->id }})">
                                <i class="bi bi-printer"></i>
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" title="Editar" onclick="editarProforma({{ $proforma->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    @if($proformas->hasPages())
    <div class="d-flex justify-content-center p-3">
        {{ $proformas->links() }}
    </div>
    @endif
</div>
@else
<div class="modern-card">
    <div class="text-center py-5">
        <i class="bi bi-file-earmark-text text-muted" style="font-size: 4rem;"></i>
        <h4 class="text-muted mt-3">No hay proformas registradas</h4>
        <p class="text-muted">Comienza creando tu primera proforma</p>
        <button class="btn btn-success btn-modern" onclick="nuevaProforma()">
            <i class="bi bi-plus me-1"></i> Crear Proforma
        </button>
    </div>
</div>
@endif

<!-- Modal Nueva Proforma -->
<div class="modal fade" id="nuevaProformaModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-plus me-2"></i>Nueva Proforma
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaProforma">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cliente</label>
                            <select class="form-select" id="clienteProforma" required>
                                <option value="">Seleccionar cliente</option>
                                <option value="1">Cliente Test</option>
                                <option value="2">Cliente ABC</option>
                                <option value="3">Cliente XYZ</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Fecha Vencimiento</label>
                            <input type="date" class="form-control" id="fechaVencimiento" required value="{{ now()->addDays(7)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-select" id="tipoProforma">
                                <option value="cotizacion">Cotización</option>
                                <option value="presupuesto">Presupuesto</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observacionesProforma" rows="2" placeholder="Condiciones especiales, descuentos, etc."></textarea>
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="mb-3">Productos</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="productoAgregar">
                                <option value="">Seleccionar producto</option>
                                <option value="1" data-precio="25.50">Paracetamol 500mg - S/ 25.50</option>
                                <option value="2" data-precio="35.00">Ibuprofeno 400mg - S/ 35.00</option>
                                <option value="3" data-precio="45.80">Amoxicilina 250mg - S/ 45.80</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="cantidadAgregar" placeholder="Cantidad" min="1">
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" class="form-control" id="precioAgregar" placeholder="Precio">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success" onclick="agregarProducto()">
                                <i class="bi bi-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tablaProductos">
                            <thead class="table-primary">
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unit.</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="productosProforma">
                                <!-- Productos dinámicos -->
                            </tbody>
                            <tfoot>
                                <tr class="table-info">
                                    <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                                    <td><strong>S/ <span id="totalProforma">0.00</span></strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarProforma()">
                    <i class="bi bi-check me-1"></i>Guardar Proforma
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Proforma -->
<div class="modal fade" id="verProformaModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>Detalle de Proforma
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoProforma">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning" onclick="editarProformaActual()">
                    <i class="bi bi-pencil me-1"></i>Editar
                </button>
                <button type="button" class="btn btn-success" onclick="convertirVentaActual()">
                    <i class="bi bi-arrow-right me-1"></i>Convertir a Venta
                </button>
                <button type="button" class="btn btn-primary" onclick="imprimirProformaActual()">
                    <i class="bi bi-printer me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let proformaActual = null;
let productosSeleccionados = [];

// Cambio de producto para auto-rellenar precio
document.addEventListener('DOMContentLoaded', function() {
    // Configurar filtros y búsqueda
    $('#searchInput').on('input', function() {
        filtrarProformas();
    });

    $('#estadoFilter, #clienteFilter').on('change', function() {
        filtrarProformas();
    });

    // Configurar autocomplete de precios
    const productoSelect = document.getElementById('productoAgregar');
    if (productoSelect) {
        productoSelect.addEventListener('change', function() {
            const precio = this.selectedOptions[0].getAttribute('data-precio');
            document.getElementById('precioAgregar').value = precio || '';
        });
    }

    // Animaciones de entrada
    $('.stat-card').each(function(index) {
        $(this).delay(index * 100).animate({
            opacity: 1,
            transform: 'translateY(0)'
        }, 500);
    });
});

function filtrarProformas() {
    const searchTerm = $('#searchInput').val().toLowerCase();
    const estadoFilter = $('#estadoFilter').val().toLowerCase();
    const clienteFilter = $('#clienteFilter').val();
    
    $('tbody tr').each(function() {
        const row = $(this);
        const texto = row.text().toLowerCase();
        const estadoBadge = row.find('.badge:last').text().toLowerCase();
        const clienteTexto = row.find('td:nth-child(2)').text().toLowerCase();
        
        const coincideTexto = texto.includes(searchTerm);
        const coincideEstado = estadoFilter === '' || estadoBadge.includes(estadoFilter);
        const coincideCliente = clienteFilter === '' || clienteTexto.includes('cliente ' + (clienteFilter === '1' ? 'test' : clienteFilter === '2' ? 'abc' : 'xyz'));
        
        row.toggle(coincideTexto && coincideEstado && coincideCliente);
    });
    
    // Actualizar contador
    const visibleRows = $('tbody tr:visible').length;
    $('.badge.bg-primary').text(visibleRows + ' proforma' + (visibleRows !== 1 ? 's' : '') + ' encontrada' + (visibleRows !== 1 ? 's' : ''));
}

function limpiarFiltros() {
    $('#searchInput').val('');
    $('#estadoFilter').val('');
    $('#clienteFilter').val('');
    filtrarProformas();
}

function editarProforma(id) {
    Swal.fire({
        title: 'Editar Proforma',
        text: 'Función para editar proforma #' + id,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Abrir Editor',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí iría la lógica para abrir el editor
            nuevaProforma(); // Por ahora usa el modal de nueva proforma
        }
    });
}

function nuevaProforma() {
    document.getElementById('formNuevaProforma').reset();
    productosSeleccionados = [];
    actualizarTablaProductos();
    document.getElementById('fechaVencimiento').value = '{{ now()->addDays(7)->format("Y-m-d") }}';
    const modal = new bootstrap.Modal(document.getElementById('nuevaProformaModal'));
    modal.show();
}

function agregarProducto() {
    const productoSelect = document.getElementById('productoAgregar');
    const cantidad = parseInt(document.getElementById('cantidadAgregar').value);
    const precio = parseFloat(document.getElementById('precioAgregar').value);
    
    if (!productoSelect.value || !cantidad || !precio) {
        Swal.fire('Error', 'Complete todos los campos del producto', 'error');
        return;
    }
    
    const producto = {
        id: productoSelect.value,
        nombre: productoSelect.selectedOptions[0].text.split(' - ')[0],
        cantidad: cantidad,
        precio: precio,
        subtotal: cantidad * precio
    };
    
    productosSeleccionados.push(producto);
    actualizarTablaProductos();
    
    // Limpiar campos
    document.getElementById('productoAgregar').value = '';
    document.getElementById('cantidadAgregar').value = '';
    document.getElementById('precioAgregar').value = '';
}

function actualizarTablaProductos() {
    const tbody = document.getElementById('productosProforma');
    tbody.innerHTML = '';
    let total = 0;
    
    productosSeleccionados.forEach((producto, index) => {
        total += producto.subtotal;
        tbody.innerHTML += `
            <tr>
                <td>${producto.nombre}</td>
                <td>${producto.cantidad}</td>
                <td>S/ ${producto.precio.toFixed(2)}</td>
                <td>S/ ${producto.subtotal.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarProducto(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    document.getElementById('totalProforma').textContent = total.toFixed(2);
}

function eliminarProducto(index) {
    productosSeleccionados.splice(index, 1);
    actualizarTablaProductos();
}

function guardarProforma() {
    const form = document.getElementById('formNuevaProforma');
    const cliente = document.getElementById('clienteProforma').value;
    
    if (!cliente || productosSeleccionados.length === 0) {
        Swal.fire('Error', 'Seleccione un cliente y agregue al menos un producto', 'error');
        return;
    }
    
    const total = document.getElementById('totalProforma').textContent;
    const numeroProforma = 'PRF-' + String(Date.now()).slice(-3);
    
    Swal.fire({
        title: 'Proforma Creada',
        html: `
            <div class="text-start">
                <p><strong>Número:</strong> ${numeroProforma}</p>
                <p><strong>Cliente:</strong> ${document.getElementById('clienteProforma').selectedOptions[0].text}</p>
                <p><strong>Total:</strong> S/ ${total}</p>
                <p><strong>Productos:</strong> ${productosSeleccionados.length}</p>
            </div>
        `,
        icon: 'success',
        timer: 3000
    }).then(() => {
        bootstrap.Modal.getInstance(document.getElementById('nuevaProformaModal')).hide();
        
        // Simular recarga para mostrar la nueva proforma
        setTimeout(() => {
            Swal.fire('Guardado', 'La proforma se guardó correctamente', 'success');
        }, 500);
    });
}

function verProforma(id) {
    proformaActual = id;
    
    // Simular datos de la proforma
    const proforma = {
        numero: 'PRF-' + String(id).padStart(3, '0'),
        cliente: 'Cliente Test',
        fecha: '{{ now()->format("d/m/Y") }}',
        vencimiento: '{{ now()->addDays(7)->format("d/m/Y") }}',
        estado: id == 1 ? 'Pendiente' : 'Aprobada',
        productos: [
            {nombre: 'Paracetamol 500mg', cantidad: 2, precio: 25.50, subtotal: 51.00},
            {nombre: 'Ibuprofeno 400mg', cantidad: 1, precio: 35.00, subtotal: 35.00}
        ],
        subtotal: 86.00,
        igv: 15.48,
        total: 101.48,
        observaciones: 'Cotización válida por 7 días'
    };
    
    const contenido = `
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="text-primary">Información General</h5>
                <table class="table table-borderless">
                    <tr><td><strong>Número:</strong></td><td>${proforma.numero}</td></tr>
                    <tr><td><strong>Cliente:</strong></td><td>${proforma.cliente}</td></tr>
                    <tr><td><strong>Fecha:</strong></td><td>${proforma.fecha}</td></tr>
                    <tr><td><strong>Vencimiento:</strong></td><td>${proforma.vencimiento}</td></tr>
                    <tr><td><strong>Estado:</strong></td><td><span class="badge ${proforma.estado === 'Pendiente' ? 'bg-warning' : 'bg-success'}">${proforma.estado}</span></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5 class="text-success">Resumen Financiero</h5>
                <table class="table table-borderless">
                    <tr><td><strong>Subtotal:</strong></td><td>S/ ${proforma.subtotal.toFixed(2)}</td></tr>
                    <tr><td><strong>IGV (18%):</strong></td><td>S/ ${proforma.igv.toFixed(2)}</td></tr>
                    <tr class="table-success"><td><strong>TOTAL:</strong></td><td><strong>S/ ${proforma.total.toFixed(2)}</strong></td></tr>
                </table>
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
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    ${proforma.productos.map(p => `
                        <tr>
                            <td>${p.nombre}</td>
                            <td>${p.cantidad}</td>
                            <td>S/ ${p.precio.toFixed(2)}</td>
                            <td>S/ ${p.subtotal.toFixed(2)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
        
        ${proforma.observaciones ? `
            <div class="alert alert-info">
                <strong>Observaciones:</strong> ${proforma.observaciones}
            </div>
        ` : ''}
    `;
    
    document.getElementById('contenidoProforma').innerHTML = contenido;
    const modal = new bootstrap.Modal(document.getElementById('verProformaModal'));
    modal.show();
}

function convertirVenta(id) {
    convertirVentaModal(id);
}

function convertirVentaModal(id) {
    Swal.fire({
        title: '¿Convertir a venta?',
        text: 'Esta proforma se convertirá en una venta definitiva',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, convertir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const numeroVenta = 'VTA-' + String(Date.now()).slice(-4);
            Swal.fire({
                title: 'Conversión Exitosa',
                html: `
                    <div class="text-center">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <p class="mt-3"><strong>Proforma convertida exitosamente</strong></p>
                        <p>Número de venta: <strong>${numeroVenta}</strong></p>
                        <small class="text-muted">La venta aparecerá en el módulo de ventas</small>
                    </div>
                `,
                icon: 'success'
            });
        }
    });
}

function editarProformaActual() {
    if (proformaActual) {
        editarProforma(proformaActual);
    }
}

function convertirVentaActual() {
    if (proformaActual) {
        convertirVentaModal(proformaActual);
    }
}

function imprimirProforma(id) {
    Swal.fire({
        title: 'Generando PDF...',
        html: `
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <p>Preparando documento de proforma #${id}</p>
            </div>
        `,
        showConfirmButton: false,
        timer: 2000
    }).then(() => {
        Swal.fire('¡Listo!', 'El PDF se ha generado correctamente', 'success');
    });
}

function imprimirProformaActual() {
    if (proformaActual) {
        imprimirProforma(proformaActual);
    }
}

function exportarProformas() {
    Swal.fire({
        title: 'Exportar Proformas',
        text: 'Seleccione el formato de exportación:',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '📊 Excel',
        cancelButtonText: '📄 PDF',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Exportando a Excel...',
                html: '<div class="spinner-border text-success mb-3"></div><p>Generando archivo Excel</p>',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                Swal.fire('¡Exportado!', 'El archivo Excel se descargó correctamente', 'success');
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
                title: 'Exportando a PDF...',
                html: '<div class="spinner-border text-danger mb-3"></div><p>Generando archivo PDF</p>',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                Swal.fire('¡Exportado!', 'El archivo PDF se descargó correctamente', 'success');
            });
        }
    });
}

// Funciones de utilidad
function mostrarNotificacion(tipo, titulo, mensaje) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: tipo,
        title: titulo,
        text: mensaje
    });
}

// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
@endsection 