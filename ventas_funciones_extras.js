// 🎫 GENERAR TICKET DE VENTA PROFESIONAL
function generarTicketVenta(venta, datosVenta) {
    try {
        console.log('🎫 Generando ticket para venta:', venta);
        
        const fechaActual = new Date().toLocaleString('es-PE', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        
        const numeroVenta = venta.numero_ticket || `V${Date.now()}`;
        
        // Generar HTML de productos
        let productosHTML = '';
        carritoVenta.forEach(item => {
            const subtotal = item.precio * item.cantidad;
            productosHTML += `
                <tr>
                    <td style="text-align: left; padding: 8px; border-bottom: 1px dotted #999;">
                        <strong>${item.nombre}</strong><br>
                        <small style="color: #666;">Código: ${item.codigo}</small><br>
                        <small style="color: #666;">Marca: ${item.marca}</small>
                    </td>
                    <td style="text-align: center; padding: 8px; border-bottom: 1px dotted #999; font-weight: bold;">
                        ${item.cantidad}
                    </td>
                    <td style="text-align: right; padding: 8px; border-bottom: 1px dotted #999;">
                        S/ ${item.precio.toFixed(2)}
                    </td>
                    <td style="text-align: right; padding: 8px; border-bottom: 1px dotted #999; font-weight: bold;">
                        S/ ${subtotal.toFixed(2)}
                    </td>
                </tr>
            `;
        });

        // Obtener información del cliente
        const clienteSelect = document.getElementById('clienteSelect');
        let clienteNombre = 'Cliente General';
        if (clienteSelect && clienteSelect.value && clienteSelect.value !== 'general') {
            clienteNombre = clienteSelect.options[clienteSelect.selectedIndex].text;
        }

        // Crear HTML completo del ticket
        const ticketHTML = `
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Ticket de Venta - ${numeroVenta}</title>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    
                    body {
                        font-family: 'Courier New', monospace;
                        font-size: 12px;
                        line-height: 1.4;
                        color: #000;
                        background: white;
                        padding: 20px;
                    }
                    
                    .ticket {
                        max-width: 350px;
                        margin: 0 auto;
                        background: white;
                        padding: 20px;
                        border: 2px solid #000;
                    }
                    
                    .header {
                        text-align: center;
                        border-bottom: 2px solid #000;
                        padding-bottom: 15px;
                        margin-bottom: 20px;
                    }
                    
                    .logo {
                        font-size: 18px;
                        font-weight: bold;
                        margin-bottom: 10px;
                        text-transform: uppercase;
                        letter-spacing: 2px;
                    }
                    
                    .empresa-info {
                        font-size: 11px;
                        line-height: 1.3;
                    }
                    
                    .venta-info {
                        margin: 15px 0;
                        padding: 10px 0;
                        border-bottom: 1px dotted #666;
                    }
                    
                    .info-row {
                        display: flex;
                        justify-content: space-between;
                        margin: 5px 0;
                    }
                    
                    .info-label {
                        font-weight: bold;
                    }
                    
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 15px 0;
                    }
                    
                    th {
                        background: #f0f0f0;
                        border: 1px solid #000;
                        padding: 8px;
                        text-align: center;
                        font-weight: bold;
                        font-size: 10px;
                        text-transform: uppercase;
                    }
                    
                    td {
                        padding: 8px;
                        font-size: 11px;
                    }
                    
                    .totales {
                        border-top: 2px solid #000;
                        padding-top: 15px;
                        margin-top: 20px;
                    }
                    
                    .total-row {
                        display: flex;
                        justify-content: space-between;
                        margin: 8px 0;
                        padding: 3px 0;
                    }
                    
                    .total-final {
                        font-size: 16px;
                        font-weight: bold;
                        border-top: 1px solid #000;
                        border-bottom: 1px solid #000;
                        padding: 10px 0;
                        margin: 10px 0;
                    }
                    
                    .footer {
                        text-align: center;
                        margin-top: 20px;
                        padding-top: 15px;
                        border-top: 1px dotted #666;
                        font-size: 11px;
                    }
                    
                    .mensaje-final {
                        font-weight: bold;
                        margin: 10px 0;
                    }
                    
                    .sistema-info {
                        font-size: 10px;
                        color: #666;
                        margin-top: 15px;
                    }
                    
                    @media print {
                        body {
                            margin: 0;
                            padding: 10px;
                        }
                        
                        .ticket {
                            border: none;
                            padding: 0;
                            max-width: none;
                        }
                        
                        .no-print {
                            display: none !important;
                        }
                    }
                    
                    .btn-container {
                        text-align: center;
                        margin: 20px 0;
                        background: #f8f9fa;
                        padding: 15px;
                        border-radius: 8px;
                    }
                    
                    .btn {
                        background: #007bff;
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        margin: 5px;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 14px;
                    }
                    
                    .btn:hover {
                        background: #0056b3;
                    }
                    
                    .btn-success {
                        background: #28a745;
                    }
                    
                    .btn-success:hover {
                        background: #1e7e34;
                    }
                </style>
            </head>
            <body>
                <div class="ticket">
                    <!-- Header -->
                    <div class="header">
                        <div class="logo">🏥 FARMACIA MAGISTRAL</div>
                        <div class="empresa-info">
                            RUC: 20123456789<br>
                            Av. Principal 123 - Lima, Perú<br>
                            Teléfono: (01) 234-5678<br>
                            Email: ventas@farmaciamagistral.com
                        </div>
                    </div>

                    <!-- Información de la Venta -->
                    <div class="venta-info">
                        <div class="info-row">
                            <span class="info-label">TICKET DE VENTA:</span>
                            <span>${numeroVenta}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">FECHA:</span>
                            <span>${fechaActual}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">CLIENTE:</span>
                            <span>${clienteNombre}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">VENDEDOR:</span>
                            <span>Sistema</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">TIPO DE PAGO:</span>
                            <span>${datosVenta.tipo_pago.toUpperCase()}</span>
                        </div>
                    </div>

                    <!-- Productos -->
                    <table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cant.</th>
                                <th>Precio</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${productosHTML}
                        </tbody>
                    </table>

                    <!-- Totales -->
                    <div class="totales">
                        <div class="total-row">
                            <span>SUBTOTAL:</span>
                            <span>S/ ${datosVenta.subtotal.toFixed(2)}</span>
                        </div>
                        <div class="total-row">
                            <span>IGV (18%):</span>
                            <span>S/ ${datosVenta.igv.toFixed(2)}</span>
                        </div>
                        <div class="total-row total-final">
                            <span>TOTAL A PAGAR:</span>
                            <span>S/ ${datosVenta.total.toFixed(2)}</span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="footer">
                        <div class="mensaje-final">
                            ¡GRACIAS POR SU COMPRA!<br>
                            💊 Su salud es nuestra prioridad 💊
                        </div>
                        
                        <div style="margin: 15px 0; font-size: 10px;">
                            • Conserve este ticket para cualquier reclamo<br>
                            • Válido por 30 días para cambios<br>
                            • No se aceptan devoluciones de medicamentos
                        </div>
                        
                        <div class="sistema-info">
                            Sistema POS - Farmacia Magistral<br>
                            Ticket generado el ${fechaActual}<br>
                            ID de transacción: ${numeroVenta}
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="btn-container no-print">
                    <button class="btn btn-success" onclick="window.print()">
                        🖨️ Imprimir Ticket
                    </button>
                    <button class="btn" onclick="window.close()">
                        ❌ Cerrar
                    </button>
                </div>

                <script>
                    // Auto-imprimir después de cargar
                    window.onload = function() {
                        console.log('🎫 Ticket cargado, preparando para imprimir...');
                        
                        // Imprimir automáticamente después de 1 segundo
                        setTimeout(() => {
                            window.print();
                        }, 1000);
                    };
                </script>
            </body>
            </html>
        `;

        // Abrir ticket en nueva ventana
        const ticketWindow = window.open('', '_blank', 'width=400,height=700,scrollbars=yes,resizable=yes');
        
        if (ticketWindow) {
            ticketWindow.document.write(ticketHTML);
            ticketWindow.document.close();
            
            console.log('🎫 Ticket generado y abierto en nueva ventana');
            mostrarMensaje('success', 'Ticket generado correctamente');
        } else {
            console.error('❌ No se pudo abrir la ventana del ticket');
            mostrarMensaje('error', 'Error al abrir ventana del ticket. Verifica que no esté bloqueado por el navegador.');
        }

    } catch (error) {
        console.error('❌ Error al generar ticket:', error);
        mostrarMensaje('error', 'Error al generar el ticket de venta');
    }
} 