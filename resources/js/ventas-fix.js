// Fix para el botón Procesar Venta
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Sistema de Ventas Fix cargado');
    
    // Asegurar que el botón esté siempre habilitado cuando se abre el modal
    const modal = document.getElementById('nuevaVentaModal');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function() {
            console.log('📦 Modal de ventas abierto');
            
            // Forzar habilitación del botón
            const btnProcesar = document.getElementById('btnProcesarVenta');
            if (btnProcesar) {
                btnProcesar.disabled = false;
                btnProcesar.style.opacity = '1';
                btnProcesar.style.cursor = 'pointer';
                btnProcesar.style.pointerEvents = 'auto';
                
                console.log('✅ Botón Procesar Venta habilitado');
                
                // Agregar evento click directo si no está funcionando
                btnProcesar.onclick = function() {
                    console.log('🔄 Procesando venta...');
                    
                    // Validaciones mínimas
                    if (typeof carritoVenta === 'undefined' || carritoVenta.length === 0) {
                        alert('⚠️ Agrega productos al carrito primero');
                        return;
                    }
                    
                    const clienteSelect = document.getElementById('clienteSelect');
                    if (!clienteSelect || !clienteSelect.value) {
                        alert('⚠️ Selecciona un cliente');
                        clienteSelect.focus();
                        return;
                    }
                    
                    // Llamar a la función original si existe
                    if (typeof procesarVenta === 'function') {
                        procesarVenta();
                    } else {
                        alert('✅ Botón funcionando! Función procesarVenta no encontrada');
                    }
                };
            }
        });
    }
    
    // Override de la función actualizarCarrito para no deshabilitar el botón
    if (typeof window.actualizarCarrito === 'function') {
        const originalActualizarCarrito = window.actualizarCarrito;
        window.actualizarCarrito = function() {
            originalActualizarCarrito();
            
            // Forzar habilitación del botón después de actualizar
            const btnProcesar = document.getElementById('btnProcesarVenta');
            if (btnProcesar) {
                btnProcesar.disabled = false;
            }
        };
    }
    
    // Test del botón cada 2 segundos
    setInterval(function() {
        const btnProcesar = document.getElementById('btnProcesarVenta');
        if (btnProcesar && btnProcesar.disabled) {
            console.log('🔧 Botón deshabilitado detectado, habilitando...');
            btnProcesar.disabled = false;
            btnProcesar.style.opacity = '1';
            btnProcesar.style.cursor = 'pointer';
        }
    }, 2000);
});

// Función de emergencia para habilitar el botón
window.habilitarBotonVenta = function() {
    const btnProcesar = document.getElementById('btnProcesarVenta');
    if (btnProcesar) {
        btnProcesar.disabled = false;
        btnProcesar.style.opacity = '1';
        btnProcesar.style.cursor = 'pointer';
        btnProcesar.style.pointerEvents = 'auto';
        console.log('✅ Botón habilitado manualmente');
        alert('✅ Botón "Procesar Venta" habilitado');
    }
}; 