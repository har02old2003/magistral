<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nombre" class="form-label fw-bold">Nombre del Producto *</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="codigo" class="form-label fw-bold">Código *</label>
            <input type="text" class="form-control" id="codigo" name="codigo" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="categoria_id" class="form-label fw-bold">Categoría *</label>
            <select class="form-select" id="categoria_id" name="categoria_id" required>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="marca_id" class="form-label fw-bold">Marca *</label>
            <select class="form-select" id="marca_id" name="marca_id" required>
                @foreach($marcas as $marca)
                    <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="precio_compra" class="form-label fw-bold">Precio Compra *</label>
            <input type="number" step="0.01" class="form-control" id="precio_compra" name="precio_compra" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="precio_venta" class="form-label fw-bold">Precio Venta *</label>
            <input type="number" step="0.01" class="form-control" id="precio_venta" name="precio_venta" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="stock_actual" class="form-label fw-bold">Stock Inicial *</label>
            <input type="number" class="form-control" id="stock_actual" name="stock_actual" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="stock_minimo" class="form-label fw-bold">Stock Mínimo *</label>
            <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="lote" class="form-label fw-bold">Lote *</label>
            <input type="text" class="form-control" id="lote" name="lote" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="fecha_vencimiento" class="form-label fw-bold">Fecha de Vencimiento *</label>
            <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="meses_vencimiento" class="form-label fw-bold">Meses de Vencimiento *</label>
            <select class="form-select" id="meses_vencimiento" name="meses_vencimiento" required>
                <option value="12">12 meses</option>
                <option value="18">18 meses</option>
                <option value="24">24 meses</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="proveedor_id" class="form-label fw-bold">Proveedor *</label>
            <select class="form-select" id="proveedor_id" name="proveedor_id" required>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="registro_sanitario" class="form-label fw-bold">Registro Sanitario</label>
            <input type="text" class="form-control" id="registro_sanitario" name="registro_sanitario">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="presentacion" class="form-label fw-bold">Presentación</label>
            <input type="text" class="form-control" id="presentacion" name="presentacion">
        </div>
    </div>
</div>
<div class="mb-3">
    <label for="descripcion" class="form-label fw-bold">Descripción</label>
    <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="principio_activo" class="form-label fw-bold">Principio Activo</label>
            <input type="text" class="form-control" id="principio_activo" name="principio_activo">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="concentracion" class="form-label fw-bold">Concentración</label>
            <input type="text" class="form-control" id="concentracion" name="concentracion">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="laboratorio" class="form-label fw-bold">Laboratorio</label>
            <input type="text" class="form-control" id="laboratorio" name="laboratorio">
        </div>
    </div>
</div>
