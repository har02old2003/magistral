<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_nombre" class="form-label fw-bold">Nombre del Producto *</label>
            <input type="text" class="form-control" id="edit_nombre" name="nombre" required value="{{ $producto->nombre }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_codigo" class="form-label fw-bold">Código *</label>
            <input type="text" class="form-control" id="edit_codigo" name="codigo" required value="{{ $producto->codigo }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_categoria_id" class="form-label fw-bold">Categoría *</label>
            <select class="form-select" id="edit_categoria_id" name="categoria_id" required>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_marca_id" class="form-label fw-bold">Marca *</label>
            <select class="form-select" id="edit_marca_id" name="marca_id" required>
                @foreach($marcas as $marca)
                    <option value="{{ $marca->id }}" {{ $producto->marca_id == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="edit_precio_compra" class="form-label fw-bold">Precio Compra *</label>
            <input type="number" step="0.01" class="form-control" id="edit_precio_compra" name="precio_compra" required value="{{ $producto->precio_compra }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="edit_precio_venta" class="form-label fw-bold">Precio Venta *</label>
            <input type="number" step="0.01" class="form-control" id="edit_precio_venta" name="precio_venta" required value="{{ $producto->precio_venta }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="edit_stock_actual" class="form-label fw-bold">Stock Actual *</label>
            <input type="number" class="form-control" id="edit_stock_actual" name="stock_actual" required value="{{ $producto->stock_actual }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="edit_stock_minimo" class="form-label fw-bold">Stock Mínimo *</label>
            <input type="number" class="form-control" id="edit_stock_minimo" name="stock_minimo" required value="{{ $producto->stock_minimo }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="edit_lote" class="form-label fw-bold">Lote *</label>
            <input type="text" class="form-control" id="edit_lote" name="lote" required value="{{ $producto->lote }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="edit_fecha_vencimiento" class="form-label fw-bold">Fecha de Vencimiento *</label>
            <input type="date" class="form-control" id="edit_fecha_vencimiento" name="fecha_vencimiento" required value="{{ $producto->fecha_vencimiento }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_meses_vencimiento" class="form-label fw-bold">Meses de Vencimiento *</label>
            <select class="form-select" id="edit_meses_vencimiento" name="meses_vencimiento" required>
                <option value="12" {{ $producto->meses_vencimiento == '12' ? 'selected' : '' }}>12 meses</option>
                <option value="18" {{ $producto->meses_vencimiento == '18' ? 'selected' : '' }}>18 meses</option>
                <option value="24" {{ $producto->meses_vencimiento == '24' ? 'selected' : '' }}>24 meses</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_proveedor_id" class="form-label fw-bold">Proveedor *</label>
            <select class="form-select" id="edit_proveedor_id" name="proveedor_id" required>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}" {{ $producto->proveedor_id == $proveedor->id ? 'selected' : '' }}>{{ $proveedor->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_registro_sanitario" class="form-label fw-bold">Registro Sanitario</label>
            <input type="text" class="form-control" id="edit_registro_sanitario" name="registro_sanitario" value="{{ $producto->registro_sanitario }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_presentacion" class="form-label fw-bold">Presentación</label>
            <input type="text" class="form-control" id="edit_presentacion" name="presentacion" value="{{ $producto->presentacion }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="edit_principio_activo" class="form-label fw-bold">Principio Activo</label>
            <input type="text" class="form-control" id="edit_principio_activo" name="principio_activo" value="{{ $producto->principio_activo }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="edit_concentracion" class="form-label fw-bold">Concentración</label>
            <input type="text" class="form-control" id="edit_concentracion" name="concentracion" value="{{ $producto->concentracion }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="edit_laboratorio" class="form-label fw-bold">Laboratorio</label>
            <input type="text" class="form-control" id="edit_laboratorio" name="laboratorio" value="{{ $producto->laboratorio }}">
        </div>
    </div>
</div>
<div class="mb-3">
    <label for="edit_descripcion" class="form-label fw-bold">Descripción</label>
    <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="2">{{ $producto->descripcion }}</textarea>
</div>
