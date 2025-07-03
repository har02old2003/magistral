@extends('layouts.modern')

@section('title', 'Venta Directa - PharmaSys Pro')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="bi bi-lightning me-2"></i>Venta Directa</h2>
    <form action="{{ route('ventas.store') }}" method="POST" id="formVentaDirecta">
        @csrf
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-modern">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-person me-2"></i>Cliente</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="cliente_id" class="form-label">Cliente</label>
                            <select class="form-select" name="cliente_id" id="cliente_id">
                                <option value="">Cliente General</option>
                                @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_pago" class="form-label">Tipo de Pago *</label>
                            <select class="form-select" name="tipo_pago" id="tipo_pago" required>
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta</option>
                                <option value="transferencia">Transferencia</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" rows="2"></textarea>
                        </div>
                        <div class="venta-resumen">
                            <div class="resumen-item">
                                <span>Subtotal:</span>
                                <span class="fw-bold" id="subtotal">S/ 0.00</span>
                            </div>
                            <div class="resumen-item">
                                <span>IGV (18%):</span>
                                <span class="fw-bold" id="igv">S/ 0.00</span>
                            </div>
                            <div class="resumen-item total">
                                <span>Total:</span>
                                <span class="fw-bold" id="total">S/ 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-modern">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-cart me-2"></i>Productos</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label for="select_producto" class="form-label">Seleccionar Producto</label>
                                    <select class="form-select" id="select_producto">
                                        <option value="">Seleccionar producto</option>
                                        @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}" data-precio="{{ $producto->precio_venta }}" data-stock="{{ $producto->stock_actual }}" data-nombre="{{ $producto->nombre }}" data-codigo="{{ $producto->codigo }}">
                                            {{ $producto->nombre }} ({{ $producto->codigo }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="cantidad_producto" class="form-label">Cantidad</label>
                                    <input type="number" class="form-control" id="cantidad_producto" min="1" value="1">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-success w-100" onclick="agregarProductoDirecta()">
                                        <i class="bi bi-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-modern" id="tablaProductosDirecta">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="productosSeleccionadosDirecta">
                                    <tr id="sinProductosDirecta">
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-cart-x fs-1"></i>
                                            <p class="mt-2">No hay productos agregados</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-secondary me-2" onclick="limpiarVentaDirecta()">
                            <i class="bi bi-trash me-1"></i> Limpiar
                        </button>
                        <button type="submit" class="btn btn-success btn-lg" id="btnGuardarVentaDirecta" disabled>
                            <i class="bi bi-check-lg me-1"></i> Guardar Venta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Lógica JS para venta directa (similar a la de create.blade.php, pero independiente)
let productosDirecta = [];

function agregarProductoDirecta() {
    const select = document.getElementById('select_producto');
    const cantidadInput = document.getElementById('cantidad_producto');
    const id = select.value;
    const nombre = select.options[select.selectedIndex]?.getAttribute('data-nombre');
    const precio = parseFloat(select.options[select.selectedIndex]?.getAttribute('data-precio'));
    const stock = parseInt(select.options[select.selectedIndex]?.getAttribute('data-stock'));
    const cantidad = parseInt(cantidadInput.value);

    if (!id || !nombre || isNaN(precio) || isNaN(cantidad) || cantidad < 1) {
        alert('Selecciona un producto y cantidad válida.');
        return;
    }
    if (cantidad > stock) {
        alert('Stock insuficiente.');
        return;
    }
    // Verificar si ya está en la lista
    const existente = productosDirecta.find(p => p.id == id);
    if (existente) {
        existente.cantidad += cantidad;
    } else {
        productosDirecta.push({ id, nombre, precio, cantidad });
    }
    renderProductosDirecta();
}

function renderProductosDirecta() {
    const tbody = document.getElementById('productosSeleccionadosDirecta');
    tbody.innerHTML = '';
    let subtotal = 0;
    if (productosDirecta.length === 0) {
        tbody.innerHTML = `<tr id="sinProductosDirecta">
            <td colspan="5" class="text-center text-muted py-4">
                <i class="bi bi-cart-x fs-1"></i>
                <p class="mt-2">No hay productos agregados</p>
            </td>
        </tr>`;
        document.getElementById('btnGuardarVentaDirecta').disabled = true;
    } else {
        productosDirecta.forEach((p, i) => {
            const sub = p.precio * p.cantidad;
            subtotal += sub;
            tbody.innerHTML += `<tr>
                <td>${p.nombre}</td>
                <td>S/ ${p.precio.toFixed(2)}</td>
                <td>${p.cantidad}</td>
                <td>S/ ${sub.toFixed(2)}</td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarProductoDirecta(${i})"><i class="bi bi-trash"></i></button></td>
            </tr>`;
        });
        document.getElementById('btnGuardarVentaDirecta').disabled = false;
    }
    const igv = subtotal * 0.18;
    const total = subtotal + igv;
    document.getElementById('subtotal').innerText = 'S/ ' + subtotal.toFixed(2);
    document.getElementById('igv').innerText = 'S/ ' + igv.toFixed(2);
    document.getElementById('total').innerText = 'S/ ' + total.toFixed(2);
}

function eliminarProductoDirecta(idx) {
    productosDirecta.splice(idx, 1);
    renderProductosDirecta();
}

function limpiarVentaDirecta() {
    productosDirecta = [];
    renderProductosDirecta();
}

// Al enviar el formulario, agregar los productos al form
const formVentaDirecta = document.getElementById('formVentaDirecta');
formVentaDirecta.addEventListener('submit', function(e) {
    if (productosDirecta.length === 0) {
        alert('Agrega al menos un producto.');
        e.preventDefault();
        return;
    }
    // Eliminar inputs previos
    document.querySelectorAll('.input-producto-directa').forEach(el => el.remove());
    productosDirecta.forEach((p, i) => {
        const inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = `productos[${i}][producto_id]`;
        inputId.value = p.id;
        inputId.classList.add('input-producto-directa');
        formVentaDirecta.appendChild(inputId);
        const inputCantidad = document.createElement('input');
        inputCantidad.type = 'hidden';
        inputCantidad.name = `productos[${i}][cantidad]`;
        inputCantidad.value = p.cantidad;
        inputCantidad.classList.add('input-producto-directa');
        formVentaDirecta.appendChild(inputCantidad);
    });
});
renderProductosDirecta();
</script>
@endpush
@endsection 