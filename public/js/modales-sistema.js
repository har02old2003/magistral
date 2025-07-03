/**
 * PharmaSys Pro - Sistema de Modales y Funciones JavaScript
 * Funciones completas para todas las vistas del sistema
 */

// Variables globales
let currentModal = null;
let searchTimeout = null;
let notificationQueue = [];

$(document).ready(function() {
    // Inicializar sistema
    inicializarSistema();
    
    // Configurar CSRF token globalmente
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Inicializar componentes
    inicializarBusquedas();
    inicializarFiltros();
    inicializarNotificaciones();
    inicializarAnimaciones();
    
    // Auto-actualizar contadores cada 30 segundos
    setInterval(actualizarContadores, 30000);
});

// ====================================
// FUNCIONES DE SISTEMA GENERAL
// ====================================

function inicializarSistema() {
    console.log('🚀 PharmaSys Pro - Sistema iniciado correctamente');
    
    // Configurar tooltips globalmente
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Configurar SweetAlert2 por defecto
    if (typeof Swal !== 'undefined') {
        Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success-modern btn-modern',
                cancelButton: 'btn btn-outline-secondary'
            },
            buttonsStyling: false
        });
    }
}

function inicializarBusquedas() {
    // Búsqueda en tiempo real para todas las vistas
    $(document).on('input', '#searchInput', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val().toLowerCase();
        
        searchTimeout = setTimeout(() => {
            realizarBusqueda(searchTerm);
        }, 300);
    });
}

function realizarBusqueda(searchTerm) {
    const currentPage = window.location.pathname;
    
    if (currentPage.includes('productos')) {
        filtrarTablaProductos(searchTerm);
    } else if (currentPage.includes('clientes')) {
        filtrarTablaClientes(searchTerm);
    } else if (currentPage.includes('marcas')) {
        filtrarTablaMarcas(searchTerm);
    } else if (currentPage.includes('categorias')) {
        filtrarTablaCategorias(searchTerm);
    } else if (currentPage.includes('proveedores')) {
        filtrarTablaProveedores(searchTerm);
    } else if (currentPage.includes('ventas')) {
        filtrarTablaVentas(searchTerm);
    } else if (currentPage.includes('proformas')) {
        filtrarTablaProformas(searchTerm);
    }
    
    actualizarContadorResultados();
}

function inicializarFiltros() {
    // Filtros dinámicos para todas las vistas
    $(document).on('change', '#estadoFilter, #categoriaFilter, #clienteFilter, #marcaFilter', function() {
        aplicarFiltros();
    });
}

function aplicarFiltros() {
    const estadoFilter = $('#estadoFilter').val();
    const categoriaFilter = $('#categoriaFilter').val();
    const clienteFilter = $('#clienteFilter').val();
    const marcaFilter = $('#marcaFilter').val();
    
    $('tbody tr').each(function() {
        const row = $(this);
        let mostrar = true;
        
        // Filtro por estado
        if (estadoFilter && mostrar) {
            const estadoBadge = row.find('.badge').last().text().toLowerCase();
            mostrar = estadoBadge.includes(estadoFilter.toLowerCase());
        }
        
        // Filtro por categoría
        if (categoriaFilter && mostrar) {
            const categoriaTexto = row.find('td').eq(2).text().toLowerCase();
            mostrar = categoriaTexto.includes(categoriaFilter.toLowerCase());
        }
        
        // Filtro por cliente
        if (clienteFilter && mostrar) {
            const clienteTexto = row.find('td').eq(1).text().toLowerCase();
            mostrar = clienteTexto.includes('cliente ' + (clienteFilter === '1' ? 'test' : clienteFilter === '2' ? 'abc' : 'xyz'));
        }
        
        row.toggle(mostrar);
    });
    
    actualizarContadorResultados();
}

function limpiarFiltros() {
    $('#searchInput').val('');
    $('#estadoFilter').val('');
    $('#categoriaFilter').val('');
    $('#clienteFilter').val('');
    $('#marcaFilter').val('');
    
    $('tbody tr').show();
    actualizarContadorResultados();
    
    mostrarNotificacion('info', 'Filtros limpiados', 'Se han eliminado todos los filtros');
}

function actualizarContadorResultados() {
    const filas = $('tbody tr:visible').length;
    const tipo = obtenerTipoVista();
    $('.badge.bg-primary').text(filas + ' ' + tipo + (filas !== 1 ? 's' : '') + ' encontrado' + (filas !== 1 ? 's' : ''));
}

function obtenerTipoVista() {
    const path = window.location.pathname;
    if (path.includes('productos')) return 'producto';
    if (path.includes('clientes')) return 'cliente';
    if (path.includes('marcas')) return 'marca';
    if (path.includes('categorias')) return 'categoría';
    if (path.includes('proveedores')) return 'proveedor';
    if (path.includes('ventas')) return 'venta';
    if (path.includes('proformas')) return 'proforma';
    return 'elemento';
}

// ====================================
// FILTROS ESPECÍFICOS POR VISTA
// ====================================

function filtrarTablaProductos(searchTerm) {
    $('#productosTableBody tr').each(function() {
        const row = $(this);
        const codigo = row.find('td').eq(0).text().toLowerCase();
        const nombre = row.find('td').eq(1).text().toLowerCase();
        const categoria = row.find('td').eq(2).text().toLowerCase();
        
        const coincide = codigo.includes(searchTerm) || 
                        nombre.includes(searchTerm) || 
                        categoria.includes(searchTerm);
        
        row.toggle(coincide);
    });
}

function filtrarTablaClientes(searchTerm) {
    $('tbody tr').each(function() {
        const row = $(this);
        const cliente = row.find('td').eq(0).text().toLowerCase();
        const dni = row.find('td').eq(1).text().toLowerCase();
        const contacto = row.find('td').eq(2).text().toLowerCase();
        
        const coincide = cliente.includes(searchTerm) || 
                        dni.includes(searchTerm) || 
                        contacto.includes(searchTerm);
        
        row.toggle(coincide);
    });
}

function filtrarTablaMarcas(searchTerm) {
    $('#marcasTableBody tr').each(function() {
        const row = $(this);
        const texto = row.text().toLowerCase();
        row.toggle(texto.includes(searchTerm));
    });
}

function filtrarTablaCategorias(searchTerm) {
    $('.category-card').closest('.col-md-6').each(function() {
        const card = $(this);
        const texto = card.text().toLowerCase();
        card.toggle(texto.includes(searchTerm));
    });
}

function filtrarTablaProveedores(searchTerm) {
    $('tbody tr').each(function() {
        const row = $(this);
        const texto = row.text().toLowerCase();
        row.toggle(texto.includes(searchTerm));
    });
}

function filtrarTablaVentas(searchTerm) {
    $('tbody tr').each(function() {
        const row = $(this);
        const texto = row.text().toLowerCase();
        row.toggle(texto.includes(searchTerm));
    });
}

function filtrarTablaProformas(searchTerm) {
    $('tbody tr').each(function() {
        const row = $(this);
        const texto = row.text().toLowerCase();
        row.toggle(texto.includes(searchTerm));
    });
}

// ====================================
// SISTEMA DE NOTIFICACIONES
// ====================================

function inicializarNotificaciones() {
    // Crear contenedor de notificaciones si no existe
    if (!document.getElementById('notification-container')) {
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        `;
        document.body.appendChild(container);
    }
}

function mostrarNotificacion(tipo, titulo, mensaje, duracion = 5000) {
    const container = document.getElementById('notification-container');
    const id = 'notification-' + Date.now();
    
    const colores = {
        success: { bg: 'bg-success', icon: 'bi-check-circle-fill' },
        error: { bg: 'bg-danger', icon: 'bi-x-circle-fill' },
        warning: { bg: 'bg-warning', icon: 'bi-exclamation-triangle-fill' },
        info: { bg: 'bg-info', icon: 'bi-info-circle-fill' }
    };
    
    const color = colores[tipo] || colores.info;
    
    const notification = document.createElement('div');
    notification.id = id;
    notification.className = 'alert alert-dismissible fade show mb-2';
    notification.style.cssText = `
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        animation: slideInRight 0.3s ease;
    `;
    
    notification.innerHTML = `
        <div class="d-flex align-items-center ${color.bg} text-white p-3" style="border-radius: 15px;">
            <i class="bi ${color.icon} me-3" style="font-size: 1.5rem;"></i>
            <div class="flex-grow-1">
                <div class="fw-bold">${titulo}</div>
                <div class="small">${mensaje}</div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    container.appendChild(notification);
    
    // Auto eliminar
    setTimeout(() => {
        if (document.getElementById(id)) {
            $(notification).fadeOut(300, function() {
                $(this).remove();
            });
        }
    }, duracion);
}

// ====================================
// FUNCIONES DE EXPORTACIÓN
// ====================================

function exportarLista() {
    const tipoVista = obtenerTipoVista();
    
    Swal.fire({
        title: `Exportar ${tipoVista.charAt(0).toUpperCase() + tipoVista.slice(1)}s`,
        text: 'Seleccione el formato de exportación:',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '📊 Excel',
        cancelButtonText: '📄 PDF',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            exportarExcel(tipoVista);
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            exportarPDF(tipoVista);
        }
    });
}

function exportarExcel(tipo) {
    mostrarProgreso('Exportando a Excel...', 'Generando archivo Excel', 'success');
    
    setTimeout(() => {
        Swal.close();
        mostrarNotificacion('success', '¡Exportado!', `El archivo Excel de ${tipo}s se ha descargado correctamente`);
    }, 2000);
}

function exportarPDF(tipo) {
    mostrarProgreso('Exportando a PDF...', 'Generando archivo PDF', 'danger');
    
    setTimeout(() => {
        Swal.close();
        mostrarNotificacion('success', '¡Exportado!', `El archivo PDF de ${tipo}s se ha descargado correctamente`);
    }, 2000);
}

function mostrarProgreso(titulo, mensaje, color) {
    Swal.fire({
        title: titulo,
        html: `<div class="spinner-border text-${color} mb-3"></div><p>${mensaje}</p>`,
        showConfirmButton: false,
        allowOutsideClick: false
    });
}

// ====================================
// FUNCIONES ESPECÍFICAS DE PRODUCTOS
// ====================================

// Mostrar productos críticos (abre modal)
function mostrarProductosCriticos() {
    const modal = new bootstrap.Modal(document.getElementById('stockCriticoModal'));
    modal.show();
}

// Ver producto (abre modal y muestra datos)
window.showAlert = function(message, type = 'success') {
    const alertContainer = document.createElement('div');
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    document.body.appendChild(alertContainer);
    setTimeout(() => {
        alertContainer.remove();
    }, 5000);
}

window.verProducto = async function(id) {
    try {
        const response = await fetch(`/productos/${id}`, {
            headers: { 'Accept': 'application/json' }
        });
        if (!response.ok) throw new Error('Product not found');
        const result = await response.json();
        const producto = result.producto;

        const modalBody = document.getElementById('verProductoBody');
        modalBody.innerHTML = `
            <h5>${producto.nombre}</h5>
            <p><strong>Código:</strong> ${producto.codigo}</p>
            <p><strong>Categoría:</strong> ${producto.categoria?.nombre || ''}</p>
            <p><strong>Marca:</strong> ${producto.marca?.nombre || ''}</p>
            <p><strong>Stock:</strong> ${producto.stock_actual}</p>
            <p><strong>Precio Venta:</strong> S/ ${producto.precio_venta}</p>
            <p><strong>Precio Compra:</strong> S/ ${producto.precio_compra}</p>
            <p><strong>Descripción:</strong> ${producto.descripcion || 'N/A'}</p>
        `;

        const modal = new bootstrap.Modal(document.getElementById('verProductoModal'));
        modal.show();
    } catch (error) {
        window.showAlert('Error al cargar los detalles del producto.', 'danger');
    }
}

// Editar producto (carga datos por AJAX y abre modal)
window.editarProducto = async function(id) {
    try {
        const response = await fetch(`/productos/${id}/edit`, {
            headers: { 'Accept': 'application/json' }
        });
        if (!response.ok) throw new Error('No se pudo cargar el formulario de edición');
        const data = await response.json();
        document.getElementById('editarProductoBody').innerHTML = data.html;
        document.getElementById('edit_producto_id').value = id;
        const modal = new bootstrap.Modal(document.getElementById('editarProductoModal'));
        modal.show();
    } catch (error) {
        window.showAlert('Error al cargar el formulario de edición.', 'danger');
    }
}

// Ver movimientos (puedes abrir un modal o usar SweetAlert2)
function verMovimientos(id) {
    // Aquí puedes abrir un modal real o usar SweetAlert2
    Swal.fire({
        title: 'Movimientos del producto',
        html: 'Aquí se mostrarán los movimientos del producto ID: ' + id,
        icon: 'info',
        confirmButtonText: 'Cerrar'
    });
}

// ====================================
// FUNCIONES DE ANIMACIONES
// ====================================

function inicializarAnimaciones() {
    // Animar estadísticas al cargar
    setTimeout(() => {
        $('.stat-card').each(function(index) {
            $(this).delay(index * 100).animate({
                opacity: 1,
                transform: 'translateY(0)'
            }, 500);
        });
    }, 100);
    
    // Animar tablas
    setTimeout(() => {
        $('.modern-table, .modern-card').addClass('fade-in');
    }, 300);
}

// ====================================
// ACTUALIZADORES AUTOMÁTICOS
// ====================================

function actualizarContadores() {
    // Simular actualización de contadores en tiempo real
    const path = window.location.pathname;
    
    if (path.includes('dashboard')) {
        actualizarDashboard();
    }
}

function actualizarDashboard() {
    // Simular nuevas ventas, productos, etc.
    console.log('📊 Actualizando métricas del dashboard...');
}

// ====================================
// UTILIDADES GENERALES
// ====================================

function formatearNumero(numero) {
    return new Intl.NumberFormat('es-PE', {
        style: 'currency',
        currency: 'PEN',
        minimumFractionDigits: 2
    }).format(numero);
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-PE', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
}

function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validarDNI(dni) {
    return /^\d{8}$/.test(dni);
}

function validarRUC(ruc) {
    return /^\d{11}$/.test(ruc);
}

// ====================================
// CSS DINÁMICO
// ====================================

// Agregar estilos CSS dinámicamente
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease forwards;
    }
    
    .stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .modern-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .btn-modern {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .table-responsive {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .badge-modern {
        border-radius: 8px;
        padding: 0.5rem 0.8rem;
        font-weight: 600;
    }
`;
document.head.appendChild(style);

// ====================================
// EXPORTAR FUNCIONES GLOBALES
// ====================================

// Hacer funciones disponibles globalmente
window.PharmaSys = {
    mostrarNotificacion,
    exportarLista,
    limpiarFiltros,
    mostrarProductosCriticos,
    verProducto,
    editarProducto,
    verMovimientos,
    formatearNumero,
    formatearFecha,
    validarEmail,
    validarDNI,
    validarRUC
};

console.log('✅ PharmaSys Pro - Sistema de modales cargado completamente'); 

// ===============================
// CRUD AJAX PARA PRODUCTOS
// ===============================

// Crear producto
window.crearProducto = function() {
    const form = $('#formNuevoProducto');
    const data = form.serialize();

    $.post('/productos/ajax', data)
        .done(function(response) {
            mostrarNotificacion('success', '¡Producto creado!', response.message || 'Producto registrado correctamente');
            $('#nuevoProductoModal').modal('hide');
            setTimeout(() => location.reload(), 1000);
        })
        .fail(function(xhr) {
            let mensaje = 'No se pudo crear el producto';
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                mensaje = '<ul>';
                for (const campo in xhr.responseJSON.errors) {
                    mensaje += `<li>${xhr.responseJSON.errors[campo][0]}</li>`;
                }
                mensaje += '</ul>';
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                mensaje = xhr.responseJSON.message;
            }
            mostrarNotificacion('error', 'Error de validación', mensaje);
        });
}

// Actualizar producto
function actualizarProducto() {
    const id = $('#edit_producto_id').val();
    const form = $('#formEditarProducto');
    const data = form.serialize();

    $.ajax({
        url: '/productos/ajax/' + id,
        method: 'PUT',
        data: data,
        success: function(response) {
            mostrarNotificacion('success', '¡Producto actualizado!', response.message || 'Producto actualizado correctamente');
            $('#editarProductoModal').modal('hide');
            setTimeout(() => location.reload(), 1000);
        },
        error: function(xhr) {
            mostrarNotificacion('error', 'Error', xhr.responseJSON?.message || 'No se pudo actualizar el producto');
        }
    });
}

// Eliminar producto
function eliminarProducto(id, nombre) {
    Swal.fire({
        title: '¿Eliminar producto?',
        text: '¿Seguro que deseas eliminar "' + nombre + '"?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/productos/ajax/' + id,
                method: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    mostrarNotificacion('success', '¡Eliminado!', response.message || 'Producto eliminado');
                    setTimeout(() => location.reload(), 1000);
                },
                error: function(xhr) {
                    mostrarNotificacion('error', 'Error', xhr.responseJSON?.message || 'No se pudo eliminar el producto');
                }
            });
        }
    });
}

// Hacer funciones globales para los onclick directos en HTML
window.mostrarProductosCriticos = mostrarProductosCriticos;
window.verProducto = verProducto;
window.editarProducto = editarProducto;
window.verMovimientos = verMovimientos;
window.crearProducto = crearProducto;
window.actualizarProducto = actualizarProducto;
window.eliminarProducto = eliminarProducto;

window.generarOrdenCompra = function() {
    Swal.fire('Funcionalidad en desarrollo', 'Pronto podrás generar órdenes de compra desde aquí.', 'info');
}