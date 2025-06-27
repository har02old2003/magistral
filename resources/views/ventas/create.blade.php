<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Venta - Farmacia Magistral</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
</head>
<body class="modern-dashboard">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Contenido Principal -->
            <main class="col-md-10 ms-sm-auto main-content">
                <!-- Header -->
                <div class="page-header animate__animated animate__fadeInDown">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="page-title">
                                <i class="bi bi-cart-plus me-3"></i>
                                Nueva Venta
                            </h1>
                            <p class="page-subtitle">Registra una nueva venta de productos</p>
                        </div>
                        <div class="col-auto">
                            <div class="header-actions">
                                <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="bi bi-arrow-left me-1"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Venta -->
                <form action="{{ route('ventas.store') }}" method="POST" id="formVenta">
                    @csrf
                    <div class="row g-4">
                        <!-- Información del Cliente -->
                        <div class="col-md-4">
                            <div class="card card-modern">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="bi bi-person me-2"></i>
                                        Información del Cliente
                                    </h5>
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
                                        <textarea class="form-control" name="observaciones" id="observaciones" rows="3" placeholder="Observaciones adicionales..."></textarea>
                                    </div>

                                    <!-- Resumen de Venta -->
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

                        <!-- Productos -->
                        <div class="col-md-8">
                            <div class="card card-modern">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="bi bi-cart me-2"></i>
                                        Productos
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!-- Búsqueda de productos -->
                                    <div class="mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="buscar_producto" class="form-label">Buscar Producto</label>
                                                <input type="text" class="form-control" id="buscar_producto" placeholder="Buscar por nombre o código...">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="select_producto" class="form-label">Seleccionar</label>
                                                <select class="form-select" id="select_producto">
                                                    <option value="">Seleccionar producto</option>
                                                    @foreach($productos as $producto)
                                                    <option value="{{ $producto->id }}" 
                                                            data-precio="{{ $producto->precio_venta }}" 
                                                            data-stock="{{ $producto->stock }}"
                                                            data-nombre="{{ $producto->nombre }}"
                                                            data-codigo="{{ $producto->codigo }}">
                                                        {{ $producto->nombre }} ({{ $producto->codigo }})
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="button" class="btn btn-success w-100" onclick="agregarProducto()">
                                                    <i class="bi bi-plus"></i> Agregar
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tabla de productos seleccionados -->
                                    <div class="table-responsive">
                                        <table class="table table-modern" id="tablaProductos">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Precio</th>
                                                    <th>Cantidad</th>
                                                    <th>Subtotal</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="productosSeleccionados">
                                                <tr id="sinProductos">
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
                                    <button type="button" class="btn btn-secondary me-2" onclick="limpiarVenta()">
                                        <i class="bi bi-trash me-1"></i> Limpiar
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg" id="btnGuardarVenta" disabled>
                                        <i class="bi bi-check-lg me-1"></i> Guardar Venta
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Variables CSS */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --main-bg: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .modern-dashboard {
            font-family: 'Inter', sans-serif;
            background: var(--main-bg);
            min-height: 100vh;
        }

        .main-content {
            padding: 20px;
        }

        .page-header {
            background: white;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background: var(--primary-gradient);
            color: white;
        }

        .page-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0;
        }

        .page-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin: 0;
        }

        .card-modern {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-modern .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 20px;
        }

        .card-modern .card-body {
            padding: 25px;
        }

        .venta-resumen {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .resumen-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 8px;
        }

        .resumen-item.total {
            border-top: 2px solid #dee2e6;
            padding-top: 10px;
            font-size: 1.2rem;
            color: #28a745;
        }

        .table-modern {
            margin: 0;
        }

        .table-modern th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            border: none;
            padding: 15px;
        }

        .table-modern td {
            padding: 15px;
            border: none;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: middle;
        }

        .cantidad-input {
            width: 80px;
            text-align: center;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #e1e5e9;
            padding: 12px 15px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 10px 20px;
        }

        .btn-success {
            background: var(--success-gradient);
            border: none;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }
            
            .page-header {
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
        }
    </style>

    <script>
        let productosVenta = [];
        let contadorProductos = 0;

        // Filtrar productos en tiempo real
        document.getElementById('buscar_producto').addEventListener('keyup', function() {
            const busqueda = this.value.toLowerCase();
            const select = document.getElementById('select_producto');
            const opciones = select.querySelectorAll('option');

            opciones.forEach(opcion => {
                if (opcion.value === '') return;
                
                const texto = opcion.textContent.toLowerCase();
                if (texto.includes(busqueda)) {
                    opcion.style.display = '';
                } else {
                    opcion.style.display = 'none';
                }
            });
        });

        function agregarProducto() {
            const selectProducto = document.getElementById('select_producto');
            const productoId = selectProducto.value;
            
            if (!productoId) {
                alert('Selecciona un producto');
                return;
            }

            const opcionSeleccionada = selectProducto.selectedOptions[0];
            const producto = {
                id: productoId,
                nombre: opcionSeleccionada.dataset.nombre,
                codigo: opcionSeleccionada.dataset.codigo,
                precio: parseFloat(opcionSeleccionada.dataset.precio),
                stock: parseInt(opcionSeleccionada.dataset.stock),
                cantidad: 1
            };

            // Verificar si el producto ya está en la lista
            const productoExistente = productosVenta.find(p => p.id === productoId);
            if (productoExistente) {
                if (productoExistente.cantidad < producto.stock) {
                    productoExistente.cantidad++;
                    actualizarTablaProductos();
                } else {
                    alert('Stock insuficiente');
                }
                return;
            }

            productosVenta.push(producto);
            contadorProductos++;
            actualizarTablaProductos();
            
            // Limpiar selección
            selectProducto.value = '';
            document.getElementById('buscar_producto').value = '';
        }

        function actualizarTablaProductos() {
            const tbody = document.getElementById('productosSeleccionados');
            const sinProductos = document.getElementById('sinProductos');

            if (productosVenta.length === 0) {
                sinProductos.style.display = '';
                document.getElementById('btnGuardarVenta').disabled = true;
                actualizarTotales();
                return;
            }

            sinProductos.style.display = 'none';
            document.getElementById('btnGuardarVenta').disabled = false;

            tbody.innerHTML = '';
            productosVenta.forEach((producto, index) => {
                const subtotal = producto.precio * producto.cantidad;
                
                const fila = `
                    <tr>
                        <td>
                            <div>
                                <strong>${producto.nombre}</strong><br>
                                <small class="text-muted">Código: ${producto.codigo}</small><br>
                                <small class="text-info">Stock: ${producto.stock}</small>
                            </div>
                        </td>
                        <td>S/ ${producto.precio.toFixed(2)}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cambiarCantidad(${index}, -1)">-</button>
                                <input type="number" class="form-control cantidad-input mx-2" value="${producto.cantidad}" 
                                       onchange="actualizarCantidad(${index}, this.value)" min="1" max="${producto.stock}">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cambiarCantidad(${index}, 1)">+</button>
                            </div>
                        </td>
                        <td class="fw-bold">S/ ${subtotal.toFixed(2)}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarProducto(${index})" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });

            actualizarTotales();
        }

        function cambiarCantidad(index, cambio) {
            const producto = productosVenta[index];
            const nuevaCantidad = producto.cantidad + cambio;
            
            if (nuevaCantidad >= 1 && nuevaCantidad <= producto.stock) {
                producto.cantidad = nuevaCantidad;
                actualizarTablaProductos();
            }
        }

        function actualizarCantidad(index, nuevaCantidad) {
            const producto = productosVenta[index];
            nuevaCantidad = parseInt(nuevaCantidad);
            
            if (nuevaCantidad >= 1 && nuevaCantidad <= producto.stock) {
                producto.cantidad = nuevaCantidad;
                actualizarTablaProductos();
            } else {
                actualizarTablaProductos(); // Restaurar valor anterior
            }
        }

        function eliminarProducto(index) {
            productosVenta.splice(index, 1);
            actualizarTablaProductos();
        }

        function actualizarTotales() {
            const subtotal = productosVenta.reduce((sum, producto) => {
                return sum + (producto.precio * producto.cantidad);
            }, 0);

            const igv = subtotal * 0.18;
            const total = subtotal + igv;

            document.getElementById('subtotal').textContent = `S/ ${subtotal.toFixed(2)}`;
            document.getElementById('igv').textContent = `S/ ${igv.toFixed(2)}`;
            document.getElementById('total').textContent = `S/ ${total.toFixed(2)}`;
        }

        function limpiarVenta() {
            if (confirm('¿Estás seguro de que deseas limpiar la venta?')) {
                productosVenta = [];
                contadorProductos = 0;
                actualizarTablaProductos();
                document.getElementById('formVenta').reset();
            }
        }

        // Agregar productos al formulario antes de enviar
        document.getElementById('formVenta').addEventListener('submit', function(e) {
            if (productosVenta.length === 0) {
                e.preventDefault();
                alert('Agrega al menos un producto');
                return;
            }

            // Limpiar campos de productos anteriores
            const camposAnteriores = document.querySelectorAll('input[name^="productos"]');
            camposAnteriores.forEach(campo => campo.remove());

            // Agregar productos como campos ocultos
            productosVenta.forEach((producto, index) => {
                const inputProductoId = document.createElement('input');
                inputProductoId.type = 'hidden';
                inputProductoId.name = `productos[${index}][producto_id]`;
                inputProductoId.value = producto.id;
                this.appendChild(inputProductoId);

                const inputCantidad = document.createElement('input');
                inputCantidad.type = 'hidden';
                inputCantidad.name = `productos[${index}][cantidad]`;
                inputCantidad.value = producto.cantidad;
                this.appendChild(inputCantidad);
            });
        });
    </script>
</body>
</html> 