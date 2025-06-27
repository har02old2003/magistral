function generarTicketVenta(venta, datosVenta) {
    const fecha = new Date().toLocaleString('es-PE', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });

    const ticketHTML = `
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta - ${venta.numero_ticket}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', 'Lucida Console', monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
            background: white;
            color: #333;
            line-height: 1.4;
        }
        
        .ticket {
            border: 2px solid #2c3e50;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            text-align: center;
            padding: 15px 10px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .farmacia-info {
            font-size: 10px;
            margin-bottom: 8px;
            opacity: 0.9;
        }
        
        .ticket-number {
            background: #e74c3c;
            color: white;
            padding: 8px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            display: inline-block;
            margin-top: 5px;
        }
        
        .content {
            padding: 15px 10px;
            background: #f8f9fa;
        }
        
        .section {
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-radius: 5px;
            border-left: 4px solid #3498db;
        }
        
        .section-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .info-label {
            font-weight: bold;
            color: #34495e;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
        }
        
        .products-table th {
            background: #34495e;
            color: white;
            padding: 8px 4px;
            text-align: center;
            font-size: 9px;
            text-transform: uppercase;
        }
        
        .products-table td {
            padding: 6px 4px;
            border-bottom: 1px solid #ecf0f1;
            text-align: center;
        }
        
        .products-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .product-name {
            text-align: left !important;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .totals {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 15px 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        .total-final {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid rgba(255,255,255,0.3);
            padding-top: 8px;
            margin-top: 8px;
        }
        
        .footer {
            background: #34495e;
            color: white;
            text-align: center;
            padding: 12px 10px;
            font-size: 9px;
        }
        
        .footer-message {
            margin-bottom: 8px;
            font-style: italic;
        }
        
        .qr-section {
            text-align: center;
            margin: 10px 0;
            padding: 10px;
            background: #ecf0f1;
            border-radius: 5px;
        }
        
        .buttons {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
        }
        
        .btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            margin: 0 5px;
            font-size: 12px;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }
        
        .btn-print {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        
        .divider {
            border: none;
            border-top: 2px dashed #bdc3c7;
            margin: 15px 0;
        }
        
        @media print {
            body { width: auto; margin: 0; padding: 5px; }
            .buttons { display: none; }
            .ticket { border: 1px solid #333; }
            * { -webkit-print-color-adjust: exact !important; color-adjust: exact !important; }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <!-- Header con logo y datos de farmacia -->
        <div class="header">
            <div class="logo">🏥 FARMACIA MAGISTRAL</div>
            <div class="farmacia-info">
                RUC: 20123456789 | Reg. Sanitario: DIGEMID-0001<br>
                Jr. Salud 123, Lima - Perú | Tel: (01) 234-5678<br>
                www.farmaciamagistral.com | ventas@farmaciamagistral.com
            </div>
            <div class="ticket-number">TICKET: \${venta.numero_ticket}</div>
        </div>

        <!-- Contenido principal -->
        <div class="content">
            <!-- Información de la venta -->
            <div class="section">
                <div class="section-title">📋 Información de Venta</div>
                <div class="info-row">
                    <span class="info-label">Fecha y Hora:</span>
                    <span>\${fecha}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Cliente:</span>
                    <span>\${venta.cliente || 'Cliente General'}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Vendedor:</span>
                    <span>\${venta.vendedor || 'Sistema POS'}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tipo de Pago:</span>
                    <span>\${datosVenta.tipo_pago.toUpperCase()}</span>
                </div>
            </div>

            <!-- Productos vendidos -->
            <div class="section">
                <div class="section-title">🛒 Productos Vendidos</div>
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cant.</th>
                            <th>P. Unit.</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        \${carritoVenta.map(item => \`
                            <tr>
                                <td class="product-name">\${item.nombre}<br><small style="color: #7f8c8d;">\${item.marca} - \${item.codigo}</small></td>
                                <td>\${item.cantidad}</td>
                                <td>S/ \${item.precio.toFixed(2)}</td>
                                <td><strong>S/ \${(item.precio * item.cantidad).toFixed(2)}</strong></td>
                            </tr>
                        \`).join('')}
                    </tbody>
                </table>
            </div>

            <!-- Totales -->
            <div class="totals">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>S/ \${datosVenta.subtotal.toFixed(2)}</span>
                </div>
                <div class="total-row">
                    <span>IGV (18%):</span>
                    <span>S/ \${datosVenta.igv.toFixed(2)}</span>
                </div>
                <div class="total-row total-final">
                    <span>TOTAL A PAGAR:</span>
                    <span>S/ \${datosVenta.total.toFixed(2)}</span>
                </div>
            </div>

            <hr class="divider">

            <!-- QR Code section (simulado) -->
            <div class="qr-section">
                <div style="font-size: 40px; margin-bottom: 5px;">⬜</div>
                <div style="font-size: 8px; color: #7f8c8d;">
                    Código QR para verificación<br>
                    Ticket: \${venta.numero_ticket}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-message">
                🌟 ¡Gracias por su compra! 🌟<br>
                Su salud es nuestra prioridad
            </div>
            <div style="font-size: 8px; opacity: 0.8;">
                ⚠️ Conserve este ticket para garantías y devoluciones<br>
                📞 Consultas: WhatsApp (01) 987-654-321<br>
                ⏰ Horario: Lun-Dom 7:00AM - 11:00PM
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="buttons">
        <button class="btn btn-print" onclick="window.print()">
            🖨️ Imprimir Ticket
        </button>
        <button class="btn" onclick="window.close()">
            ✖️ Cerrar
        </button>
    </div>

    <script>
        // Auto-imprimir después de 2 segundos
        setTimeout(function() {
            window.print();
        }, 2000);
        
        // Cerrar automáticamente después de imprimir
        window.addEventListener('afterprint', function() {
            setTimeout(function() {
                window.close();
            }, 3000);
        });
    </script>
</body>
</html>
    `;

    const ticketWindow = window.open('', '_blank', 'width=350,height=700,scrollbars=yes,resizable=yes');
    if (ticketWindow) {
        ticketWindow.document.write(ticketHTML);
        ticketWindow.document.close();
        ticketWindow.focus();
    }
} 