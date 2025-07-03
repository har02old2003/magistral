<div class="alert alert-info mb-3">
    <strong>Nota:</strong> Solo puedes modificar los productos de la venta. Si necesitas cambiar el cliente o tipo de pago, anula esta venta y crea una nueva.<br>
    Puedes cambiar el <strong>estado</strong> de la venta aquí.
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <label class="form-label">Cliente</label>
        <input type="text" class="form-control" value="{{ $venta->cliente->nombres ?? 'General' }}" readonly>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tipo de Pago</label>
        <input type="text" class="form-control" value="{{ ucfirst($venta->tipo_pago) }}" readonly>
    </div>
    <div class="col-md-4">
        <label class="form-label">Observaciones</label>
        <input type="text" class="form-control" value="{{ $venta->observaciones }}" readonly>
    </div>
</div>
<form id="formEditarVenta" onsubmit="actualizarVenta(event, {{ isset($venta->id) ? $venta->id : '' }})">
    <div class="mb-3">
        <label class="form-label">Estado</label>
        <select class="form-select" name="estado" required>
            <option value="completada" {{ $venta->estado == 'completada' ? 'selected' : '' }}>Completada</option>
            <option value="cancelada" {{ $venta->estado == 'cancelada' ? 'selected' : '' }}>Anulada</option>
            <option value="pendiente" {{ $venta->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
        </select>
    </div>
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label for="producto_id_editar" class="form-label">Producto</label>
            <select class="form-select" id="producto_id_editar">
                <option value="">Selecciona un producto</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}" data-lote="{{ $producto->lote ?? '' }}" data-fecha_vencimiento="{{ $producto->fecha_vencimiento ?? '' }}" data-precio="{{ $producto->precio_venta }}">{{ $producto->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="lote_editar" class="form-label">Lote</label>
            <input type="text" class="form-control" id="lote_editar" readonly>
        </div>
        <div class="col-md-2">
            <label for="fecha_vencimiento_editar" class="form-label">Vencimiento</label>
            <input type="date" class="form-control" id="fecha_vencimiento_editar" readonly>
        </div>
        <div class="col-md-2">
            <label for="precio_unitario_editar" class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" id="precio_unitario_editar" readonly>
        </div>
        <div class="col-md-1">
            <label for="cantidad_editar" class="form-label">Cant.</label>
            <input type="number" class="form-control" id="cantidad_editar" min="1">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-primary w-100" id="agregarProductoEditarBtn">Agregar</button>
        </div>
    </div>
    <div class="mt-4">
        <h6>Productos de la Venta</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle" id="tablaProductosEditarVenta">
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
                <label for="subtotal_editar" class="form-label">Subtotal</label>
                <input type="text" class="form-control" id="subtotal_editar" name="subtotal" readonly>
            </div>
            <div class="mb-2">
                <label for="igv_editar" class="form-label">IGV (18%)</label>
                <input type="text" class="form-control" id="igv_editar" name="igv" readonly>
            </div>
            <div class="mb-2">
                <label for="total_editar" class="form-label">Total</label>
                <input type="text" class="form-control" id="total_editar" name="total" readonly>
            </div>
        </div>
    </div>
    <input type="hidden" name="productos_json" id="productos_json_editar">
    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Actualizar Venta</button>
    </div>
</form>

<script>
// Precargar productos actuales de la venta en el array JS
let productosEditarVenta = [
    @foreach($detalles as $detalle)
    {
        producto_id: '{{ $detalle->producto_id }}',
        producto_nombre: '{{ $detalle->producto->nombre ?? '' }}',
        lote: '{{ $detalle->lote }}',
        fecha_vencimiento: '{{ $detalle->fecha_vencimiento }}',
        precio_unitario: '{{ $detalle->precio_unitario }}',
        cantidad: '{{ $detalle->cantidad }}'
    },
    @endforeach
];

function renderTablaEditar() {
    const tablaBody = document.querySelector('#tablaProductosEditarVenta tbody');
    tablaBody.innerHTML = '';
    let subtotal = 0;
    productosEditarVenta.forEach((item, idx) => {
        const sub = (parseFloat(item.precio_unitario) * parseInt(item.cantidad));
        subtotal += sub;
        const tr = document.createElement('tr');
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
            <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarProductoEditarVenta(${idx})"><i class="bi bi-trash"></i></button></td>
        `;
        tablaBody.appendChild(tr);
    });
    const igv = subtotal * 0.18;
    const total = subtotal + igv;
    document.getElementById('subtotal_editar').value = subtotal.toFixed(2);
    document.getElementById('igv_editar').value = igv.toFixed(2);
    document.getElementById('total_editar').value = total.toFixed(2);
    document.getElementById('productos_json_editar').value = JSON.stringify(productosEditarVenta);
}

window.eliminarProductoEditarVenta = function(idx) {
    productosEditarVenta.splice(idx, 1);
    renderTablaEditar();
}

document.addEventListener('DOMContentLoaded', function() {
    renderTablaEditar();
    const productoSelect = document.getElementById('producto_id_editar');
    const loteInput = document.getElementById('lote_editar');
    const fechaVencInput = document.getElementById('fecha_vencimiento_editar');
    const precioInput = document.getElementById('precio_unitario_editar');
    const cantidadInput = document.getElementById('cantidad_editar');
    const agregarBtn = document.getElementById('agregarProductoEditarBtn');

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
        productosEditarVenta.push({
            producto_id, producto_nombre, lote, fecha_vencimiento, precio_unitario, cantidad
        });
        renderTablaEditar();
        productoSelect.value = '';
        loteInput.value = '';
        fechaVencInput.value = '';
        precioInput.value = '';
        cantidadInput.value = '';
    });
});
</script> 