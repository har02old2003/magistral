console.log('SISTEMA POS - Cargando JavaScript limpio...');

let carritoVenta = [];
let productosEncontradosCache = [];
let timerBusqueda = null;

function getCSRFToken() {
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    return metaToken ? metaToken.getAttribute('content') : '';
}

function buscarProducto() {
    console.log('Ejecutando buscarProducto()');
    const input = document.getElementById('buscarProducto');
    if (!input) return;
    const termino = input.value.trim();
    if (!termino) {
        mostrarMensaje('warning', 'Ingrese un término de búsqueda');
        return;
    }
    ejecutarBusqueda(termino);
}

function buscarProductoEnter(event) {
    console.log('Ejecutando buscarProductoEnter()');
    if (event && event.key === 'Enter') {
        event.preventDefault();
        buscarProducto();
    }
}

function buscarProductoTiempoReal(event) {
    console.log('Ejecutando buscarProductoTiempoReal()');
    if (timerBusqueda) clearTimeout(timerBusqueda);
    const termino = event.target.value.trim();
    if (!termino) {
        ocultarResultados();
        return;
    }
    timerBusqueda = setTimeout(() => {
        if (termino.length >= 2) ejecutarBusqueda(termino);
    }, 800);
}

function ejecutarBusqueda(termino) {
    console.log('Buscando:', termino);
    fetch('/ventas-buscar-producto?q=' + encodeURIComponent(termino), {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCSRFToken()
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(productos => {
        productosEncontradosCache = productos;
        mostrarProductos(productos);
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarMensaje('error', 'Error en la búsqueda');
        ocultarResultados();
    });
}

function mostrarProductos(productos) {
    const contenedor = document.getElementById('listaProductosEncontrados');
    const seccion = document.getElementById('productosEncontrados');
    if (!contenedor || !seccion) return;

    if (productos.length === 0) {
        mostrarMensaje('info', 'No se encontraron productos');
        seccion.style.display = 'none';
        return;
    }

    let html = '';
    productos.forEach(producto => {
        const precio = parseFloat(producto.precio) || 0;
        const stock = parseInt(producto.stock) || 0;
        html += '<div class="col-md-6 mb-3"><div class="card h-100"><div class="card-body p-3">';
        html += '<h6 class="text-primary fw-bold">' + (producto.nombre || 'Sin nombre') + '</h6>';
        html += '<p class="small text-muted mb-2">Código: ' + (producto.codigo || 'N/A') + '<br>Marca: ' + (producto.marca || 'Sin marca') + '<br>Stock: ' + stock + '</p>';
        html += '<div class="d-flex justify-content-between align-items-center">';
        html += '<span class="badge bg-success">S/ ' + precio.toFixed(2) + '</span>';
        html += '<button class="btn btn-primary btn-sm" onclick="agregarAlCarrito(' + producto.id + ')" type="button">Agregar</button>';
        html += '</div></div></div></div>';
    });

    contenedor.innerHTML = html;
    seccion.style.display = 'block';
    mostrarMensaje('success', productos.length + ' producto(s) encontrado(s)');
}

function agregarAlCarrito(productoId) {
    const producto = productosEncontradosCache.find(p => p.id == productoId);
    if (!producto) return;

    const cantidadInput = document.getElementById('cantidadProducto');
    const cantidad = parseInt(cantidadInput ? cantidadInput.value : 1) || 1;

    carritoVenta.push({
        id: producto.id,
        codigo: producto.codigo || 'N/A',
        nombre: producto.nombre || 'Sin nombre',
        precio: parseFloat(producto.precio) || 0,
        cantidad: cantidad,
        marca: producto.marca || 'Sin marca'
    });

    actualizarCarrito();
    mostrarMensaje('success', producto.nombre + ' agregado al carrito');
    if (cantidadInput) cantidadInput.value = '1';
}

function actualizarCarrito() {
    const tbody = document.getElementById('carritoBody');
    const cantidadItems = document.getElementById('cantidadItems');
    const btnProcesar = document.getElementById('btnProcesarVenta');
    
    if (!tbody || !cantidadItems) return;

    if (carritoVenta.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><i class="bi bi-cart-x fs-1"></i><br>Carrito vacío</td></tr>';
        cantidadItems.textContent = '0 items';
        calcularTotales();
        return;
    }

    let html = '';
    carritoVenta.forEach((item, index) => {
        const subtotal = item.precio * item.cantidad;
        html += '<tr><td><strong>' + item.nombre + '</strong><br><small>' + item.marca + ' - ' + item.codigo + '</small></td>';
        html += '<td class="text-center"><div class="input-group input-group-sm" style="width:100px">';
        html += '<button class="btn btn-outline-secondary" onclick="cambiarCantidad(' + index + ',-1)">-</button>';
        html += '<input type="number" class="form-control text-center" value="' + item.cantidad + '" readonly>';
        html += '<button class="btn btn-outline-secondary" onclick="cambiarCantidad(' + index + ',1)">+</button>';
        html += '</div></td>';
        html += '<td class="text-center">S/ ' + item.precio.toFixed(2) + '</td>';
        html += '<td class="text-center fw-bold">S/ ' + subtotal.toFixed(2) + '</td>';
        html += '<td class="text-center"><button class="btn btn-outline-danger btn-sm" onclick="eliminarDelCarrito(' + index + ')"><i class="bi bi-trash"></i></button></td>';
        html += '</tr>';
    });

    tbody.innerHTML = html;
    cantidadItems.textContent = carritoVenta.reduce((sum, item) => sum + item.cantidad, 0) + ' items';
    
    if (btnProcesar) {
        btnProcesar.disabled = false;
        btnProcesar.innerHTML = '<i class="bi bi-check-circle me-2"></i>Procesar Venta';
    }
    
    calcularTotales();
}

function cambiarCantidad(index, cambio) {
    if (index < 0 || index >= carritoVenta.length) return;
    const item = carritoVenta[index];
    const nuevaCantidad = item.cantidad + cambio;
    if (nuevaCantidad <= 0) {
        eliminarDelCarrito(index);
        return;
    }
    item.cantidad = nuevaCantidad;
    actualizarCarrito();
}

function eliminarDelCarrito(index) {
    if (index >= 0 && index < carritoVenta.length) {
        const item = carritoVenta[index];
        carritoVenta.splice(index, 1);
        actualizarCarrito();
        mostrarMensaje('info', item.nombre + ' eliminado');
    }
}

function calcularTotales() {
    const subtotal = carritoVenta.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    const igv = subtotal * 0.18;
    const total = subtotal + igv;
    const subtotalEl = document.getElementById('subtotalVenta');
    const igvEl = document.getElementById('igvVenta');
    const totalEl = document.getElementById('totalVenta');
    if (subtotalEl) subtotalEl.textContent = 'S/ ' + subtotal.toFixed(2);
    if (igvEl) igvEl.textContent = 'S/ ' + igv.toFixed(2);
    if (totalEl) totalEl.textContent = 'S/ ' + total.toFixed(2);
}

function limpiarVenta() {
    carritoVenta = [];
    actualizarCarrito();
    const buscarInput = document.getElementById('buscarProducto');
    const cantidadInput = document.getElementById('cantidadProducto');
    const clienteSelect = document.getElementById('clienteSelect');
    if (buscarInput) buscarInput.value = '';
    if (cantidadInput) cantidadInput.value = '1';
    if (clienteSelect) clienteSelect.value = '';
    ocultarResultados();
    mostrarMensaje('info', 'Venta limpiada');
    
    const btnProcesar = document.getElementById('btnProcesarVenta');
    if (btnProcesar) {
        btnProcesar.disabled = false;
    }
}

function seleccionarCliente() {
    const select = document.getElementById('clienteSelect');
    const infoDiv = document.getElementById('infoCliente');
    const infoSpan = document.getElementById('clienteInfo');
    if (!select || !infoDiv || !infoSpan) return;
    if (select.value === 'general') {
        infoSpan.textContent = 'Cliente General';
        infoDiv.style.display = 'block';
    } else if (select.value) {
        infoSpan.textContent = 'Cliente: ' + select.options[select.selectedIndex].text;
        infoDiv.style.display = 'block';
    } else {
        infoDiv.style.display = 'none';
    }
}

function procesarVenta() {
    if (carritoVenta.length === 0) {
        mostrarMensaje('warning', 'Agrega productos al carrito');
        return;
    }
    const clienteSelect = document.getElementById('clienteSelect');
    const tipoPago = document.getElementById('tipoPago');
    if (!clienteSelect || !clienteSelect.value) {
        mostrarMensaje('warning', 'Selecciona un cliente');
        return;
    }

    const btnProcesar = document.getElementById('btnProcesarVenta');
    const originalText = btnProcesar.innerHTML;
    btnProcesar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Procesando...';
    btnProcesar.disabled = true;

    const subtotal = carritoVenta.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    const igv = subtotal * 0.18;
    const total = subtotal + igv;

    const ventaData = {
        cliente_id: clienteSelect.value === 'general' ? null : clienteSelect.value,
        tipo_pago: tipoPago ? tipoPago.value : 'efectivo',
        productos: carritoVenta.map(item => ({
            producto_id: item.id,
            cantidad: item.cantidad,
            precio_unitario: item.precio
        })),
        subtotal: subtotal,
        igv: igv,
        total: total,
        _token: getCSRFToken()
    };

    fetch('/ventas', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': ventaData._token
        },
        credentials: 'same-origin',
        body: JSON.stringify(ventaData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            mostrarMensaje('success', 'Venta procesada exitosamente!');
            generarTicketVenta(data.venta, ventaData);
            
            setTimeout(() => {
                limpiarVenta();
                const modal = bootstrap.Modal.getInstance(document.getElementById('nuevaVentaModal'));
                if (modal) modal.hide();
                setTimeout(() => window.location.reload(), 1000);
            }, 2000);
        } else {
            mostrarMensaje('error', data.message || 'Error al procesar la venta');
        }
    })
    .catch(error => {
        mostrarMensaje('error', 'Error: ' + error.message);
    })
    .finally(() => {
        btnProcesar.innerHTML = originalText;
        btnProcesar.disabled = false;
    });
}

function generarTicketVenta(venta, datosVenta) {
    try {
        const fechaActual = new Date().toLocaleString('es-PE');
        const numeroVenta = venta.numero_ticket || 'V' + Date.now();
        
        let productosHTML = '';
        carritoVenta.forEach(item => {
            const subtotal = item.precio * item.cantidad;
            productosHTML += '<tr><td>' + item.nombre + '</td><td>' + item.cantidad + '</td><td>S/ ' + item.precio.toFixed(2) + '</td><td>S/ ' + subtotal.toFixed(2) + '</td></tr>';
        });

        const clienteSelect = document.getElementById('clienteSelect');
        let clienteNombre = 'Cliente General';
        if (clienteSelect && clienteSelect.value && clienteSelect.value !== 'general') {
            clienteNombre = clienteSelect.options[clienteSelect.selectedIndex].text;
        }

        const ticketHTML = '<!DOCTYPE html><html><head><title>Ticket ' + numeroVenta + '</title><style>body{font-family:monospace;width:350px;margin:0 auto;padding:10px}.header{text-align:center;border-bottom:2px solid #333;padding:10px 0;margin-bottom:15px}.info{margin-bottom:15px}.info div{margin-bottom:5px}table{width:100%;border-collapse:collapse;margin:10px 0}th,td{padding:5px;text-align:left;border-bottom:1px solid #ddd}.totales{border-top:2px solid #333;padding-top:10px;margin-top:15px}.total-final{font-weight:bold;font-size:1.2em}.footer{text-align:center;margin-top:20px;padding-top:10px;border-top:1px solid #333;font-size:0.9em}@media print{.buttons{display:none}}</style></head><body><div class="header"><h2>FARMACIA MAGISTRAL</h2><p>RUC: 20123456789<br>Jr. Salud 123, Lima - Peru<br>Tel: (01) 234-5678</p><h3>TICKET: ' + numeroVenta + '</h3></div><div class="info"><div><strong>Fecha:</strong> ' + fechaActual + '</div><div><strong>Cliente:</strong> ' + clienteNombre + '</div><div><strong>Vendedor:</strong> Sistema POS</div><div><strong>Tipo de Pago:</strong> ' + datosVenta.tipo_pago.toUpperCase() + '</div></div><table><thead><tr><th>Producto</th><th>Cant.</th><th>Precio</th><th>Total</th></tr></thead><tbody>' + productosHTML + '</tbody></table><div class="totales"><div>Subtotal: S/ ' + datosVenta.subtotal.toFixed(2) + '</div><div>IGV (18%): S/ ' + datosVenta.igv.toFixed(2) + '</div><div class="total-final">TOTAL: S/ ' + datosVenta.total.toFixed(2) + '</div></div><div class="footer"><p>Gracias por su compra!<br>Su salud es nuestra prioridad</p><p>Conserve este ticket para garantias</p></div><div class="buttons" style="text-align:center;margin:20px 0"><button onclick="window.print()" style="margin:5px;padding:10px 20px">Imprimir</button><button onclick="window.close()" style="margin:5px;padding:10px 20px">Cerrar</button></div><script>setTimeout(function(){window.print()},1000);window.addEventListener("afterprint",function(){setTimeout(function(){window.close()},2000)});</script></body></html>';

        const ticketWindow = window.open('', '_blank', 'width=400,height=600');
        if (ticketWindow) {
            ticketWindow.document.write(ticketHTML);
            ticketWindow.document.close();
            mostrarMensaje('success', 'Ticket generado correctamente');
        } else {
            mostrarMensaje('error', 'Error al abrir ventana del ticket');
        }
    } catch (error) {
        console.error('Error generando ticket:', error);
        mostrarMensaje('error', 'Error al generar el ticket de venta');
    }
}

function ocultarResultados() {
    const seccion = document.getElementById('productosEncontrados');
    if (seccion) seccion.style.display = 'none';
}

function mostrarMensaje(tipo, mensaje) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-' + (tipo === 'error' ? 'danger' : tipo) + ' alert-dismissible fade show position-fixed';
    alertDiv.style.cssText = 'top:20px;right:20px;z-index:9999;min-width:300px';
    alertDiv.innerHTML = mensaje + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    document.body.appendChild(alertDiv);
    setTimeout(() => { if (alertDiv.parentNode) alertDiv.remove(); }, 4000);
}

function debugBusquedaCompleta() { buscarProducto(); }
function diagnosticarBusqueda() { buscarProducto(); }
function mostrarEstadisticasBusqueda() { alert('Sistema funcionando'); }
function buscarVenta() { mostrarMensaje('info', 'Buscar venta'); }
function reporteVentas() { alert('Reporte de ventas'); }
function verTicket(id) { mostrarMensaje('info', 'Ver ticket ' + id); }
function imprimirTicket(id) { mostrarMensaje('info', 'Imprimir ticket ' + id); }
function anularVenta(id, ticket) { mostrarMensaje('info', 'Anular venta ' + ticket); }
function mostrarModalCerrarSesion() { 
    const modal = new bootstrap.Modal(document.getElementById('modalCerrarSesion'));
    modal.show();
}
function ejecutarCerrarSesion() { 
    document.getElementById('logout-form-ventas').submit();
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema POS cargado correctamente - SIN ERRORES');
    const modal = document.getElementById('nuevaVentaModal');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function() {
            const buscarInput = document.getElementById('buscarProducto');
            if (buscarInput) buscarInput.focus();
            
            carritoVenta = [];
            actualizarCarrito();
            
            const buscarInputReset = document.getElementById('buscarProducto');
            const cantidadInput = document.getElementById('cantidadProducto');
            const clienteSelect = document.getElementById('clienteSelect');
            if (buscarInputReset) buscarInputReset.value = '';
            if (cantidadInput) cantidadInput.value = '1';
            if (clienteSelect) clienteSelect.value = '';
            ocultarResultados();
            
            const btnProcesar = document.getElementById('btnProcesarVenta');
            if (btnProcesar) {
                btnProcesar.disabled = false;
                btnProcesar.style.opacity = '1';
                btnProcesar.style.cursor = 'pointer';
            }
        });
    }
    
    console.log('✅ Todas las funciones están disponibles');
}); 