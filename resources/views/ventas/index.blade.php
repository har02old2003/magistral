@extends('layouts.modern')

@section('title', 'Ventas - PharmaSys Pro')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="mb-0">
            <i class="bi bi-receipt me-3"></i>Ventas
        </h1>
        <p class="text-muted mb-0">Historial y gestión de ventas</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#nuevaVentaModal">
            <i class="bi bi-plus-circle me-1"></i> Nueva Venta
        </button>
        <button class="btn btn-info btn-modern" onclick="exportarVentas()">
            <i class="bi bi-download me-1"></i> Exportar
        </button>
    </div>
</div>
@endsection

@section('content')
<!-- Estadísticas de Ventas -->
<div class="row mb-4 g-4">
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stat-card primary h-100">
            <div class="text-primary" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="text-primary" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $ventasHoy ?? 0 }}</div>
            <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Ventas Hoy</div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stat-card success h-100">
            <div class="text-success" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-cash-coin"></i>
            </div>
            <div class="text-success" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">S/ {{ number_format($montoHoy ?? 0, 2) }}</div>
            <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Monto Hoy</div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stat-card info h-100">
            <div class="text-info" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-calendar3"></i>
            </div>
            <div class="text-info" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">{{ $ventasMes ?? 0 }}</div>
            <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Ventas Mes</div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="stat-card warning h-100">
            <div class="text-warning" style="font-size: 3rem; margin-bottom: 1rem;">
                <i class="bi bi-graph-up"></i>
            </div>
            <div class="text-warning" style="font-size: 3rem; font-weight: 700; margin: 1rem 0;">S/ {{ number_format($promedioVenta ?? 0, 2) }}</div>
            <div style="color: #6c757d; font-size: 1.1rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Promedio Venta</div>
        </div>
    </div>
</div>

<!-- Tabla de Ventas -->
<div class="modern-card">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <h5 class="mb-0">
            <i class="bi bi-table text-primary me-2"></i>
            Lista de Ventas
        </h5>
        <span class="badge bg-primary">{{ $ventas->total() }} ventas registradas</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th># Ticket</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $venta)
                <tr>
                    <td>{{ $venta->numero_ticket }}</td>
                    <td>{{ $venta->fecha ? $venta->fecha->format('d/m/Y H:i') : '' }}</td>
                    <td>{{ $venta->cliente->nombres ?? 'General' }}</td>
                    <td>{{ $venta->user->name ?? '-' }}</td>
                    <td>S/ {{ number_format($venta->total, 2) }}</td>
                    <td>
                        @if($venta->estado === 'anulada')
                            <span class="badge bg-danger">Anulada</span>
                        @else
                            <span class="badge bg-success">Completada</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="verTicket({{ $venta->id }})"><i class="bi bi-receipt"></i></button>
                        <button class="btn btn-sm btn-primary" onclick="editarVenta({{ $venta->id }})"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-danger" onclick="anularVenta({{ $venta->id }}, '{{ $venta->numero_ticket }}')"><i class="bi bi-x-circle"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No hay ventas registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">
        {{ $ventas->links() }}
    </div>
</div>

<!-- Modal Nueva Venta -->
<div class="modal fade" id="nuevaVentaModal" tabindex="-1" aria-labelledby="nuevaVentaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="nuevaVentaModalLabel"><i class="bi bi-plus-circle me-2"></i>Nueva Venta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="nuevaVentaBody">
        <form id="formNuevaVenta" method="POST" action="{{ route('ventas.store') }}">
            @csrf
            <div class="mb-3">
                <label for="cliente_id" class="form-label">Cliente</label>
                <select class="form-select" id="cliente_id" name="cliente_id">
                    <option value="">Selecciona un cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nombres }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                <select class="form-select" id="tipo_pago" name="tipo_pago" required>
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="transferencia">Transferencia</option>
                </select>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="producto_id" class="form-label">Producto</label>
                    <select class="form-select" id="producto_id">
                        <option value="">Selecciona un producto</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}" data-lote="{{ $producto->lote ?? '' }}" data-fecha_vencimiento="{{ $producto->fecha_vencimiento ?? '' }}" data-precio="{{ $producto->precio_venta }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="lote" class="form-label">Lote</label>
                    <input type="text" class="form-control" id="lote">
                </div>
                <div class="col-md-2">
                    <label for="fecha_vencimiento" class="form-label">Vencimiento</label>
                    <input type="date" class="form-control" id="fecha_vencimiento" readonly>
                </div>
                <div class="col-md-2">
                    <label for="precio_unitario" class="form-label">Precio</label>
                    <input type="number" step="0.01" class="form-control" id="precio_unitario">
                </div>
                <div class="col-md-1">
                    <label for="cantidad" class="form-label">Cant.</label>
                    <input type="number" class="form-control" id="cantidad" min="1">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-primary w-100" id="agregarProductoBtn">Agregar</button>
                </div>
            </div>
            <div class="mt-4">
                <h6>Productos agregados</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle" id="tablaProductosVenta">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Lote</th>
                                <th>Vencimiento</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4 ms-auto">
                    <div class="mb-2">
                        <label for="subtotal" class="form-label">Subtotal</label>
                        <input type="text" class="form-control" id="subtotal" name="subtotal" readonly>
                    </div>
                    <div class="mb-2">
                        <label for="igv" class="form-label">IGV (18%)</label>
                        <input type="text" class="form-control" id="igv" name="igv" readonly>
                    </div>
                    <div class="mb-2">
                        <label for="total" class="form-label">Total</label>
                        <input type="text" class="form-control" id="total" name="total" readonly>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
            </div>
            <input type="hidden" name="productos_json" id="productos_json">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Registrar Venta</button>
            </div>
        </form>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productoSelect = document.getElementById('producto_id');
            const loteInput = document.getElementById('lote');
            const fechaVencInput = document.getElementById('fecha_vencimiento');
            const precioInput = document.getElementById('precio_unitario');
            const cantidadInput = document.getElementById('cantidad');
            const agregarBtn = document.getElementById('agregarProductoBtn');
            const tablaBody = document.querySelector('#tablaProductosVenta tbody');
            const subtotalInput = document.getElementById('subtotal');
            const igvInput = document.getElementById('igv');
            const totalInput = document.getElementById('total');
            const productosJsonInput = document.getElementById('productos_json');
            let productosVenta = [];

            function limpiarCamposProducto() {
                productoSelect.value = '';
                loteInput.value = '';
                fechaVencInput.value = '';
                precioInput.value = '';
                cantidadInput.value = '';
            }

            function renderTabla() {
                tablaBody.innerHTML = '';
                let subtotal = 0;
                productosVenta.forEach((item, idx) => {
                    const tr = document.createElement('tr');
                    const sub = (item.precio_unitario * item.cantidad);
                    subtotal += sub;
                    tr.innerHTML = `
                        <td>
                            <input type="hidden" name="productos[${idx}][producto_id]" value="${item.producto_id}">
                            <input type="hidden" name="productos[${idx}][lote]" value="${item.lote}">
                            <input type="hidden" name="productos[${idx}][fecha_vencimiento]" value="${item.fecha_vencimiento}">
                            <input type="hidden" name="productos[${idx}][precio_unitario]" value="${item.precio_unitario}">
                            <input type="hidden" name="productos[${idx}][cantidad]" value="${item.cantidad}">
                            ${item.producto_nombre}
                        </td>
                        <td>${item.lote}</td>
                        <td>${item.fecha_vencimiento}</td>
                        <td>S/ ${parseFloat(item.precio_unitario).toFixed(2)}</td>
                        <td>${item.cantidad}</td>
                        <td>S/ ${(sub).toFixed(2)}</td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarProductoVenta(${idx})"><i class="bi bi-trash"></i></button></td>
                    `;
                    tablaBody.appendChild(tr);
                });
                const igv = subtotal * 0.18;
                const total = subtotal + igv;
                subtotalInput.value = subtotal.toFixed(2);
                igvInput.value = igv.toFixed(2);
                totalInput.value = total.toFixed(2);
                productosJsonInput.value = JSON.stringify(productosVenta);
            }

            window.eliminarProductoVenta = function(idx) {
                productosVenta.splice(idx, 1);
                renderTabla();
            }

            productoSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                loteInput.value = selected.getAttribute('data-lote') || '';
                fechaVencInput.value = selected.getAttribute('data-fecha_vencimiento') || '';
                precioInput.value = selected.getAttribute('data-precio') || '';
            });

            agregarBtn.addEventListener('click', function() {
                const producto_id = productoSelect.value;
                const producto_nombre = productoSelect.options[productoSelect.selectedIndex]?.text || '';
                const lote = loteInput.value;
                const fecha_vencimiento = fechaVencInput.value;
                const precio_unitario = precioInput.value;
                const cantidad = cantidadInput.value;
                if (!producto_id || !precio_unitario || !cantidad || cantidad < 1) {
                    alert('Completa los datos del producto');
                    return;
                }
                productosVenta.push({
                    producto_id, producto_nombre, lote, fecha_vencimiento, precio_unitario, cantidad
                });
                renderTabla();
                limpiarCamposProducto();
            });

            // Al enviar el formulario, validar que haya productos
            document.getElementById('formNuevaVenta').addEventListener('submit', function(e) {
                if (productosVenta.length === 0) {
                    e.preventDefault();
                    alert('Agrega al menos un producto a la venta.');
                }
            });
        });
        </script>
      </div>
    </div>
  </div>
</div>

<!-- Modal Editar Venta -->
<div class="modal fade" id="editarVentaModal" tabindex="-1" aria-labelledby="editarVentaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="editarVentaModalLabel"><i class="bi bi-pencil me-2"></i>Editar Venta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="editarVentaBody">
        <!-- Aquí se cargará el formulario de edición por AJAX -->
      </div>
    </div>
  </div>
</div>

<!-- Modal Ver Ticket -->
<div class="modal fade" id="verTicketModal" tabindex="-1" aria-labelledby="verTicketModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="verTicketModalLabel"><i class="bi bi-receipt me-2"></i>Detalle de Venta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="verTicketBody">
        <!-- Aquí se cargará el ticket por AJAX -->
      </div>
    </div>
  </div>
</div>
@endsection

<script>
function verTicket(id) {
    // Abre el modal y carga el ticket por AJAX
    const modal = new bootstrap.Modal(document.getElementById('verTicketModal'));
    document.getElementById('verTicketBody').innerHTML = '<div class="text-center p-4">Cargando...</div>';
    fetch(`/ventas/${id}/ticket`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('verTicketBody').innerHTML = html;
        })
        .catch(() => {
            document.getElementById('verTicketBody').innerHTML = '<div class="text-danger p-4">Error al cargar el ticket.</div>';
        });
    modal.show();
}

function editarVenta(id) {
    // Abre el modal y carga el formulario de edición por AJAX
    const modal = new bootstrap.Modal(document.getElementById('editarVentaModal'));
    document.getElementById('editarVentaBody').innerHTML = '<div class="text-center p-4">Cargando...</div>';
    fetch(`/ventas/${id}/edit`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('editarVentaBody').innerHTML = html;
        })
        .catch(() => {
            document.getElementById('editarVentaBody').innerHTML = '<div class="text-danger p-4">Error al cargar la edición.</div>';
        });
    modal.show();
}

function anularVenta(id, ticket) {
    if(confirm('¿Seguro que deseas anular la venta ' + ticket + '?')) {
        // Formulario oculto para enviar el DELETE
        let form = document.getElementById('formAnularVenta');
        if (!form) {
            form = document.createElement('form');
            form.id = 'formAnularVenta';
            form.method = 'POST';
            form.style.display = 'none';
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
        }
        form.action = `/ventas/${id}`;
        form.submit();
    }
}
</script>

{{-- <script src="{{ asset('js/ventas.js') }}"></script> --}}
