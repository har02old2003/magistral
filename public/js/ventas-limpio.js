console.log('✅ Sistema POS iniciando...');

var carritoVenta = [];
var productosEncontradosCache = [];
var timerBusqueda = null;

function getCSRFToken() {
    var metaToken = document.querySelector('meta[name="csrf-token"]');
    return metaToken ? metaToken.getAttribute('content') : '';
}

function buscarProducto() {
    var input = document.getElementById('buscarProducto');
    if (!input) return;
    var termino = input.value.trim();
    if (!termino) {
        mostrarMensaje('warning', 'Ingrese un término de búsqueda');
        return;
    }
    ejecutarBusqueda(termino);
}

function buscarProductoEnter(event) {
    if (event && event.key === 'Enter') {
        event.preventDefault();
        buscarProducto();
    }
}

function buscarProductoTiempoReal(event) {
    if (timerBusqueda) clearTimeout(timerBusqueda);
    var termino = event.target.value.trim();
    if (!termino) {
        ocultarResultados();
        return;
    }
    timerBusqueda = setTimeout(function() {
        if (termino.length >= 2) ejecutarBusqueda(termino);
    }, 800);
}

function ejecutarBusqueda(termino) {
    fetch('/ventas-buscar-producto?q=' + encodeURIComponent(termino), {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCSRFToken()
        },
        credentials: 'same-origin'
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(productos) {
        productosEncontradosCache = productos;
        mostrarProductos(productos);
    })
    .catch(function(error) {
        console.error('Error:', error);
        mostrarMensaje('error', 'Error en la búsqueda');
        ocultarResultados();
    });
}

function mostrarProductos(productos) {
    var contenedor = document.getElementById('listaProductosEncontrados');
    var seccion = document.getElementById('productosEncontrados');
    if (!contenedor || !seccion) return;

    if (productos.length === 0) {
        mostrarMensaje('info', 'No se encontraron productos');
        seccion.style.display = 'none';
        return;
    }

    var html = '';
    productos.forEach(function(producto) {
        var precio = parseFloat(producto.precio) || 0;
        var stock = parseInt(producto.stock) || 0;
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
    var producto = productosEncontradosCache.find(function(p) {
        return p.id == productoId;
    });
    if (!producto) return;

    var cantidadInput = document.getElementById('cantidadProducto');
    var cantidad = parseInt(cantidadInput ? cantidadInput.value : 1) || 1;

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
    var tbody = document.getElementById('carritoBody');
    var cantidadItems = document.getElementById('cantidadItems');
    var btnProcesar = document.getElementById('btnProcesarVenta');
    
    if (!tbody || !cantidadItems) return;

    if (carritoVenta.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><i class="bi bi-cart-x fs-1"></i><br>Carrito vacío</td></tr>';
        cantidadItems.textContent = '0 items';
        calcularTotales();
        return;
    }

    var html = '';
    carritoVenta.forEach(function(item, index) {
        var subtotal = item.precio * item.cantidad;
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
    cantidadItems.textContent = carritoVenta.reduce(function(sum, item) {
        return sum + item.cantidad;
    }, 0) + ' items';
    
    if (btnProcesar) {
        btnProcesar.disabled = false;
        btnProcesar.innerHTML = '<i class="bi bi-check-circle me-2"></i>Procesar Venta';
    }
    
    calcularTotales();
}

function cambiarCantidad(index, cambio) {
    if (index < 0 || index >= carritoVenta.length) return;
    var item = carritoVenta[index];
    var nuevaCantidad = item.cantidad + cambio;
    if (nuevaCantidad <= 0) {
        eliminarDelCarrito(index);
        return;
    }
    item.cantidad = nuevaCantidad;
    actualizarCarrito();
}

function eliminarDelCarrito(index) {
    if (index >= 0 && index < carritoVenta.length) {
        var item = carritoVenta[index];
        carritoVenta.splice(index, 1);
        actualizarCarrito();
        mostrarMensaje('info', item.nombre + ' eliminado');
    }
}

function calcularTotales() {
    var subtotal = carritoVenta.reduce(function(sum, item) {
        return sum + (item.precio * item.cantidad);
    }, 0);
    var igv = subtotal * 0.18;
    var total = subtotal + igv;
    
    var subtotalEl = document.getElementById('subtotalVenta');
    var igvEl = document.getElementById('igvVenta');
    var totalEl = document.getElementById('totalVenta');
    
    if (subtotalEl) subtotalEl.textContent = 'S/ ' + subtotal.toFixed(2);
    if (igvEl) igvEl.textContent = 'S/ ' + igv.toFixed(2);
    if (totalEl) totalEl.textContent = 'S/ ' + total.toFixed(2);
}

function limpiarVenta() {
    carritoVenta = [];
    actualizarCarrito();
    var buscarInput = document.getElementById('buscarProducto');
    var cantidadInput = document.getElementById('cantidadProducto');
    var clienteSelect = document.getElementById('clienteSelect');
    if (buscarInput) buscarInput.value = '';
    if (cantidadInput) cantidadInput.value = '1';
    if (clienteSelect) clienteSelect.value = '';
    ocultarResultados();
    mostrarMensaje('info', 'Venta limpiada');
    
    var btnProcesar = document.getElementById('btnProcesarVenta');
    if (btnProcesar) {
        btnProcesar.disabled = false;
    }
}

function seleccionarCliente() {
    var select = document.getElementById('clienteSelect');
    var infoDiv = document.getElementById('infoCliente');
    var infoSpan = document.getElementById('clienteInfo');
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
    var clienteSelect = document.getElementById('clienteSelect');
    var tipoPago = document.getElementById('tipoPago');
    if (!clienteSelect || !clienteSelect.value) {
        mostrarMensaje('warning', 'Selecciona un cliente');
        return;
    }

    var btnProcesar = document.getElementById('btnProcesarVenta');
    var originalText = btnProcesar.innerHTML;
    btnProcesar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Procesando...';
    btnProcesar.disabled = true;

    var subtotal = carritoVenta.reduce(function(sum, item) {
        return sum + (item.precio * item.cantidad);
    }, 0);
    var igv = subtotal * 0.18;
    var total = subtotal + igv;

    var ventaData = {
        cliente_id: clienteSelect.value === 'general' ? null : clienteSelect.value,
        tipo_pago: tipoPago ? tipoPago.value : 'efectivo',
        productos: carritoVenta.map(function(item) {
            return {
                producto_id: item.id,
                cantidad: item.cantidad,
                precio_unitario: item.precio
            };
        }),
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
    .then(function(response) {
        if (!response.ok) {
            throw new Error('Error ' + response.status);
        }
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            mostrarMensaje('success', 'Venta procesada exitosamente!');
            generarTicketVenta(data.venta, ventaData);
            
            setTimeout(function() {
                limpiarVenta();
                var modal = bootstrap.Modal.getInstance(document.getElementById('nuevaVentaModal'));
                if (modal) modal.hide();
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            }, 2000);
        } else {
            mostrarMensaje('error', data.message || 'Error al procesar la venta');
        }
    })
    .catch(function(error) {
        mostrarMensaje('error', 'Error: ' + error.message);
    })
    .finally(function() {
        btnProcesar.innerHTML = originalText;
        btnProcesar.disabled = false;
    });
}

function generarTicketVenta(venta, datosVenta) {
    try {
        var fechaActual = new Date().toLocaleString('es-PE');
        var numeroVenta = venta.numero_ticket || 'V' + Date.now();
        
        var productosHTML = '';
        carritoVenta.forEach(function(item) {
            var subtotal = item.precio * item.cantidad;
            productosHTML += '<tr><td>' + item.nombre + '</td><td>' + item.cantidad + '</td><td>S/ ' + item.precio.toFixed(2) + '</td><td>S/ ' + subtotal.toFixed(2) + '</td></tr>';
        });

        var clienteSelect = document.getElementById('clienteSelect');
        var clienteNombre = 'Cliente General';
        if (clienteSelect && clienteSelect.value && clienteSelect.value !== 'general') {
            clienteNombre = clienteSelect.options[clienteSelect.selectedIndex].text;
        }

        var ticketHTML = '<!DOCTYPE html><html><head><title>Ticket ' + numeroVenta + '</title><style>body{font-family:monospace;width:350px;margin:0 auto;padding:10px}.header{text-align:center;border-bottom:2px solid #333;padding:10px 0;margin-bottom:15px}.info{margin-bottom:15px}.info div{margin-bottom:5px}table{width:100%;border-collapse:collapse;margin:10px 0}th,td{padding:5px;text-align:left;border-bottom:1px solid #ddd}.totales{border-top:2px solid #333;padding-top:10px;margin-top:15px}.total-final{font-weight:bold;font-size:1.2em}.footer{text-align:center;margin-top:20px;padding-top:10px;border-top:1px solid #333;font-size:0.9em}@media print{.buttons{display:none}}</style></head><body><div class="header"><h2>FARMACIA MAGISTRAL</h2><p>RUC: 20123456789<br>Jr. Salud 123, Lima - Peru<br>Tel: (01) 234-5678</p><h3>TICKET: ' + numeroVenta + '</h3></div><div class="info"><div><strong>Fecha:</strong> ' + fechaActual + '</div><div><strong>Cliente:</strong> ' + clienteNombre + '</div><div><strong>Vendedor:</strong> Sistema POS</div><div><strong>Tipo de Pago:</strong> ' + datosVenta.tipo_pago.toUpperCase() + '</div></div><table><thead><tr><th>Producto</th><th>Cant.</th><th>Precio</th><th>Total</th></tr></thead><tbody>' + productosHTML + '</tbody></table><div class="totales"><div>Subtotal: S/ ' + datosVenta.subtotal.toFixed(2) + '</div><div>IGV (18%): S/ ' + datosVenta.igv.toFixed(2) + '</div><div class="total-final">TOTAL: S/ ' + datosVenta.total.toFixed(2) + '</div></div><div class="footer"><p>Gracias por su compra!<br>Su salud es nuestra prioridad</p><p>Conserve este ticket para garantias</p></div><div class="buttons" style="text-align:center;margin:20px 0"><button onclick="window.print()" style="margin:5px;padding:10px 20px">Imprimir</button><button onclick="window.close()" style="margin:5px;padding:10px 20px">Cerrar</button></div><script>setTimeout(function(){window.print()},1000);window.addEventListener("afterprint",function(){setTimeout(function(){window.close()},2000)});</script></body></html>';

        var ticketWindow = window.open('', '_blank', 'width=400,height=600');
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
    var seccion = document.getElementById('productosEncontrados');
    if (seccion) seccion.style.display = 'none';
}

function mostrarMensaje(tipo, mensaje) {
    var alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-' + (tipo === 'error' ? 'danger' : tipo) + ' alert-dismissible fade show position-fixed';
    alertDiv.style.cssText = 'top:20px;right:20px;z-index:9999;min-width:300px';
    alertDiv.innerHTML = mensaje + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    document.body.appendChild(alertDiv);
    setTimeout(function() {
        if (alertDiv.parentNode) alertDiv.remove();
    }, 4000);
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
    var modal = new bootstrap.Modal(document.getElementById('modalCerrarSesion'));
    modal.show();
}
function ejecutarCerrarSesion() { 
    document.getElementById('logout-form-ventas').submit();
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Sistema POS cargado correctamente');
    var modal = document.getElementById('nuevaVentaModal');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function() {
            var buscarInput = document.getElementById('buscarProducto');
            if (buscarInput) buscarInput.focus();
            
            carritoVenta = [];
            actualizarCarrito();
            
            var buscarInputReset = document.getElementById('buscarProducto');
            var cantidadInput = document.getElementById('cantidadProducto');
            var clienteSelect = document.getElementById('clienteSelect');
            if (buscarInputReset) buscarInputReset.value = '';
            if (cantidadInput) cantidadInput.value = '1';
            if (clienteSelect) clienteSelect.value = '';
            ocultarResultados();
            
            var btnProcesar = document.getElementById('btnProcesarVenta');
            if (btnProcesar) {
                btnProcesar.disabled = false;
                btnProcesar.style.opacity = '1';
                btnProcesar.style.cursor = 'pointer';
            }
        });
    }
}); 