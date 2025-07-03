<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_cliente_id" class="form-label fw-bold">Cliente</label>
            <select class="form-select" id="edit_cliente_id" name="cliente_id">
                <option value="">Cliente General</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ $venta->cliente_id == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombres }} {{ $cliente->apellidos }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_tipo_pago" class="form-label fw-bold">Tipo de Pago</label>
            <select class="form-select" id="edit_tipo_pago" name="tipo_pago">
                <option value="efectivo" {{ $venta->tipo_pago == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                <option value="tarjeta" {{ $venta->tipo_pago == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                <option value="transferencia" {{ $venta->tipo_pago == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                <option value="yape" {{ $venta->tipo_pago == 'yape' ? 'selected' : '' }}>Yape/Plin</option>
            </select>
        </div>
    </div>
</div>
<div class="mb-3">
    <label for="edit_observaciones" class="form-label fw-bold">Observaciones</label>
    <textarea class="form-control" id="edit_observaciones" name="observaciones" rows="3">{{ $venta->observaciones }}</textarea>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="mb-3">
            <label class="form-label fw-bold">Productos de la Venta</label>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Lote</th>
                            <th>Fecha Vencimiento</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalles as $i => $detalle)
                        <tr>
                            <td>
                                <select name="productos[{{ $i }}][producto_id]" class="form-select" required>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}" {{ $detalle->producto_id == $producto->id ? 'selected' : '' }}>{{ $producto->nombre }} ({{ $producto->codigo }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="productos[{{ $i }}][cantidad]" class="form-control" min="1" value="{{ $detalle->cantidad }}" required>
                            </td>
                            <td>
                                <input type="number" name="productos[{{ $i }}][precio_unitario]" class="form-control" step="0.01" value="{{ $detalle->precio_unitario }}" required>
                            </td>
                            <td>
                                <input type="text" name="productos[{{ $i }}][lote]" class="form-control" value="{{ $detalle->lote }}" required>
                            </td>
                            <td>
                                <input type="date" name="productos[{{ $i }}][fecha_vencimiento]" class="form-control" value="{{ $detalle->fecha_vencimiento }}" required>
                            </td>
                            <td>
                                <input type="number" name="productos[{{ $i }}][subtotal]" class="form-control" step="0.01" value="{{ $detalle->subtotal }}" readonly>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="edit_estado" class="form-label fw-bold">Estado</label>
            <select class="form-select" id="edit_estado" name="estado">
                <option value="completada" {{ $venta->estado == 'completada' ? 'selected' : '' }}>Completada</option>
                <option value="pendiente" {{ $venta->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="cancelada" {{ $venta->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="edit_fecha_venta" class="form-label fw-bold">Fecha de Venta</label>
            <input type="datetime-local" class="form-control" id="edit_fecha_venta" name="fecha_venta" value="{{ $venta->fecha ? $venta->fecha->format('Y-m-d\TH:i') : '' }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">Totales</label>
            <div class="border rounded p-2">
                <div>Subtotal: <span id="edit_subtotal">S/ {{ number_format($venta->subtotal, 2) }}</span></div>
                <div>IGV (18%): <span id="edit_igv">S/ {{ number_format($venta->igv, 2) }}</span></div>
                <div>Total: <span id="edit_total">S/ {{ number_format($venta->total, 2) }}</span></div>
            </div>
        </div>
    </div>
</div>
