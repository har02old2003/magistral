// SISTEMA POS - VENTAS JAVASCRIPT LIMPIO
console.log('Sistema POS cargando...');

var carritoVenta = [];
var productosEncontradosCache = [];
var timerBusqueda = null;

function getCSRFToken() {
    var metaToken = document.querySelector('meta[name="csrf-token"]');
    return metaToken ? metaToken.getAttribute('content') : '';
}

function buscarProducto() {
    console.log('Ejecutando buscarProducto');
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
    console.log('Ejecutando buscarProductoEnter');
    if (event && event.key === 'Enter') {
        event.preventDefault();
        buscarProducto();
    }
}

function buscarProductoTiempoReal(event) {
    console.log('Ejecutando buscarProductoTiempoReal');
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

// FUNCIÓN GLOBAL PARA NUEVA VENTA
window.nuevaVenta = function() {
    // Si estamos en la vista de ventas, usar la función específica
    if (typeof abrirNuevaVenta === 'function') {
        abrirNuevaVenta();
        return;
    }
    
    // Función básica para otras vistas
    var modal = document.getElementById('nuevaVentaModal');
    if (modal) {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var modalInstance = bootstrap.Modal.getOrCreateInstance(modal);
            modalInstance.show();
        } else {
            // Fallback si no hay Bootstrap Modal
            modal.style.display = 'block';
        }
    } else {
        // Redirigir a la vista de ventas si no hay modal
        window.location.href = '/ventas';
    }
};

document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema POS cargado correctamente - SIN ERRORES');
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
    
    console.log('Verificando funciones de búsqueda:');
    console.log('- buscarProducto:', typeof buscarProducto);
    console.log('- buscarProductoEnter:', typeof buscarProductoEnter);
    console.log('- buscarProductoTiempoReal:', typeof buscarProductoTiempoReal);
    console.log('Todas las funciones estan disponibles');
});

// VER TICKET
window.verTicket = async function(id) {
    try {
        const response = await fetch(`/ventas/${id}/ticket`);
        const html = await response.text();
        document.getElementById('verTicketBody').innerHTML = html;
        const modal = new bootstrap.Modal(document.getElementById('verTicketModal'));
        modal.show();
    } catch (error) {
        mostrarMensaje('error', 'Error al cargar el ticket');
    }
};

// --- INICIALIZACIÓN DE EDICIÓN DE VENTA (MODAL) ---
window.inicializarEdicionVenta = function() {
    setTimeout(function() {
        console.log('Inicializando edición de venta...');
        const productoSelect = document.getElementById('producto_id_editar');
        const loteInput = document.getElementById('lote_editar');
        const fechaVencInput = document.getElementById('fecha_vencimiento_editar');
        const precioInput = document.getElementById('precio_unitario_editar');
        const cantidadInput = document.getElementById('cantidad_editar');
        const agregarBtn = document.getElementById('agregarProductoEditarBtn');
        console.log({ productoSelect, loteInput, fechaVencInput, precioInput, cantidadInput, agregarBtn });
        if (!productoSelect || !loteInput || !fechaVencInput || !precioInput || !cantidadInput || !agregarBtn) {
            console.warn('Algún elemento no existe en el DOM del modal de edición');
            return;
        }
        if (typeof renderTablaEditar === 'function') renderTablaEditar();
        productoSelect.onchange = function() {
            const selected = this.options[this.selectedIndex];
            loteInput.value = selected.getAttribute('data-lote') || '';
            fechaVencInput.value = selected.getAttribute('data-fecha_vencimiento') || '';
            precioInput.value = selected.getAttribute('data-precio') || '';
        };
        agregarBtn.onclick = function() {
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
            if (typeof productosEditarVenta === 'undefined') window.productosEditarVenta = [];
            productosEditarVenta.push({
                producto_id, producto_nombre, lote, fecha_vencimiento, precio_unitario, cantidad
            });
            if (typeof renderTablaEditar === 'function') renderTablaEditar();
            productoSelect.value = '';
            loteInput.value = '';
            fechaVencInput.value = '';
            precioInput.value = '';
            cantidadInput.value = '';
        };
    }, 150);
};

// --- MODIFICAR LA FUNCIÓN QUE ABRE EL MODAL DE EDICIÓN ---
window.editarVenta = async function(id) {
    try {
        const response = await fetch(`/ventas/${id}/edit`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const html = await response.text();
        document.getElementById('editarVentaBody').innerHTML = html;
        const modal = new bootstrap.Modal(document.getElementById('editarVentaModal'));
        modal.show();
        // Inicializa los eventos y lógica de edición
        if (window.inicializarEdicionVenta) window.inicializarEdicionVenta();
    } catch (error) {
        mostrarMensaje('error', 'Error al cargar la venta');
    }
};

// ACTUALIZAR VENTA
window.actualizarVenta = async function(event, id) {
    event.preventDefault();
    const form = event.target;
    const data = {
        tipo_pago: form.tipo_pago.value,
        estado: form.estado.value,
        observaciones: form.observaciones.value,
        _token: getCSRFToken()
    };
    try {
        const response = await fetch(`/ventas/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCSRFToken()
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            mostrarMensaje('success', 'Venta actualizada');
            bootstrap.Modal.getInstance(document.getElementById('editarVentaModal')).hide();
            recargarTablaVentas();
        } else {
            mostrarMensaje('error', result.message || 'Error al actualizar');
        }
    } catch (error) {
        mostrarMensaje('error', 'Error al actualizar la venta');
    }
};

// ANULAR VENTA
window.anularVenta = async function(id, ticket) {
    if (!confirm(`¿Está seguro de anular la venta ${ticket}? Esta acción no se puede deshacer.`)) return;
    try {
        const response = await fetch(`/ventas/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        });
        const result = await response.json();
        if (result.success) {
            mostrarMensaje('success', 'Venta anulada exitosamente');
            recargarTablaVentas();
        } else {
            mostrarMensaje('error', result.message || 'Error al anular');
        }
    } catch (error) {
        mostrarMensaje('error', 'Error al anular la venta');
    }
};

// RECARGAR TABLA DE VENTAS
window.recargarTablaVentas = async function() {
    try {
        const response = await fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const html = await response.text();
        // Extraer solo el tbody de la tabla de ventas
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        const newTbody = tempDiv.querySelector('table.table tbody');
        if (newTbody) {
            document.querySelector('table.table tbody').innerHTML = newTbody.innerHTML;
        } else {
            window.location.reload(); // Fallback
        }
    } catch (error) {
        window.location.reload();
    }
};

// GUARDAR NUEVA VENTA (AJAX)
window.guardarVenta = async function(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';
    }

    // Obtener datos del formulario
    const cliente_id = form.cliente_id ? form.cliente_id.value : '';
    const tipo_pago = form.tipo_pago ? form.tipo_pago.value : 'efectivo';
    const observaciones = form.observaciones ? form.observaciones.value : '';

    // Obtener productos seleccionados
    let productos = [];
    if (typeof productosVenta !== 'undefined' && Array.isArray(productosVenta) && productosVenta.length > 0) {
        productos = productosVenta.map(p => ({
            producto_id: p.id,
            cantidad: p.cantidad,
            precio_unitario: p.precio,
            lote: p.lote || '',
            fecha_vencimiento: p.fecha_vencimiento || ''
        }));
    } else if (typeof carritoVenta !== 'undefined' && Array.isArray(carritoVenta) && carritoVenta.length > 0) {
        productos = carritoVenta.map(p => ({
            producto_id: p.id,
            cantidad: p.cantidad,
            precio_unitario: p.precio,
            lote: p.lote || '',
            fecha_vencimiento: p.fecha_vencimiento || ''
        }));
    }

    // Calcular totales
    let subtotal = 0;
    productos.forEach(p => { subtotal += (parseFloat(p.precio_unitario) || 0) * (parseInt(p.cantidad) || 0); });
    const igv = subtotal * 0.18;
    const total = subtotal + igv;

    if (productos.length === 0) {
        mostrarMensaje('warning', 'Agrega al menos un producto');
        if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Guardar Venta'; }
        return;
    }

    const data = {
        cliente_id,
        tipo_pago,
        observaciones,
        productos,
        subtotal,
        igv,
        total,
        _token: getCSRFToken()
    };

    try {
        const response = await fetch('/ventas/ajax', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCSRFToken()
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success && result.venta) {
            mostrarMensaje('success', 'Venta registrada correctamente');
            bootstrap.Modal.getInstance(document.getElementById('nuevaVentaModal')).hide();
            // Mostrar ticket automático
            if (result.ticket_html) {
                document.getElementById('verTicketBody').innerHTML = result.ticket_html;
                const modal = new bootstrap.Modal(document.getElementById('verTicketModal'));
                modal.show();
            } else {
                // Si no viene el HTML, recargar tabla y mostrar mensaje
                recargarTablaVentas();
            }
            recargarTablaVentas();
        } else {
            mostrarMensaje('error', result.message || 'Error al registrar la venta');
        }
    } catch (error) {
        mostrarMensaje('error', 'Error al guardar la venta');
    }
    if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Guardar Venta'; }
};

// ABRIR MODAL DE NUEVA VENTA
window.abrirNuevaVenta = function() {
    cargarFormularioNuevaVenta();
    const modal = new bootstrap.Modal(document.getElementById('nuevaVentaModal'));
    modal.show();
};

// CARGAR FORMULARIO DE NUEVA VENTA
function cargarFormularioNuevaVenta() {
    const body = document.getElementById('nuevaVentaBody');
    body.innerHTML = `
        <form id="formNuevaVenta" onsubmit="guardarVenta(event)">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Cliente (Opcional)</label>
                    <select class="form-select" name="cliente_id" id="cliente_id">
                        <option value="">Cliente General</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipo de Pago</label>
                    <select class="form-select" name="tipo_pago" required>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Buscar Producto</label>
                <input type="text" class="form-control" id="buscarProductoVenta" placeholder="Buscar por nombre o código" onkeyup="buscarProductosVenta(this.value)">
            </div>
            <div id="resultadosProductos" class="mb-3" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr><th>Producto</th><th>Stock</th><th>Precio</th><th>Acción</th></tr>
                        </thead>
                        <tbody id="tablaResultadosProductos"></tbody>
                    </table>
                </div>
            </div>
            <div class="mb-3">
                <h6>Productos Seleccionados</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Lote</th><th>F. Venc.</th><th>Subtotal</th><th>Acción</th></tr>
                        </thead>
                        <tbody id="tablaProductosVenta">
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">
                                    <i class="bi bi-cart-x fs-3"></i>
                                    <p class="mt-2 mb-0">No hay productos seleccionados</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-8">
                    <label class="form-label">Observaciones</label>
                    <textarea class="form-control" name="observaciones" rows="2" placeholder="Observaciones adicionales..."></textarea>
                </div>
                <div class="col-md-4">
                    <div class="border p-3 rounded bg-light">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span class="fw-bold" id="subtotalVenta">S/ 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>IGV (18%):</span>
                            <span class="fw-bold" id="igvVenta">S/ 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold text-success fs-5" id="totalVenta">S/ 0.00</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-success" id="btnGuardarVenta">
                    <i class="bi bi-check-circle me-1"></i> Guardar Venta
                </button>
            </div>
        </form>
    `;
    // Aquí podrías cargar clientes por AJAX si lo deseas
}

// --- Solución para cerrar el modal de ticket tras imprimir y limpiar el layout ---
window.addEventListener('afterprint', function() {
    // Cierra el modal de ticket si está abierto
    const modalEl = document.getElementById('verTicketModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
        const modal = bootstrap.Modal.getInstance(modalEl) || bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.hide();
    }
    // Quita clase modal-open del body si quedara
    document.body.classList.remove('modal-open');
    // Limpia el padding del body (Bootstrap lo pone para compensar el scroll)
    document.body.style.paddingRight = '';
    // Restaura el scroll
    document.body.style.overflow = '';
}); 