<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $venta->numero_ticket ?? $venta->id }} - Farmacia Magistral</title>
    <style>
        @media print {
            @page {
                size: 80mm auto;
                margin: 5mm;
            }
            body {
                width: 70mm;
                font-size: 12px;
            }
        }

        body {
            font-family: 'Courier New', monospace;
            max-width: 300px;
            margin: 0 auto;
            padding: 10px;
            background: white;
            color: black;
        }

        .ticket-header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .farmacia-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .ticket-info {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .productos-table {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .producto-row {
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px dotted #ccc;
        }

        .producto-nombre {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .producto-detalle {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .totales {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .total-final {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .ticket-footer {
            text-align: center;
            font-size: 10px;
            margin-top: 10px;
        }

        .no-print {
            text-align: center;
            margin: 20px 0;
        }

        @media screen {
            body {
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                margin: 20px auto;
                background: white;
                max-width: 350px;
            }
        }
    </style>
</head>
<body>
    <!-- Botones de acción (no se imprimen) -->
    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; margin-right: 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Imprimir
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer;">
            ❌ Cerrar
        </button>
    </div>

    <!-- Header del Ticket -->
    <div class="ticket-header">
        <div class="farmacia-name">FARMACIA MAGISTRAL</div>
        <div>Sistema de Gestión</div>
        <div>📞 Teléfono: (01) 123-4567</div>
        <div>📧 Email: info@farmaciamagistral.com</div>
    </div>

    <!-- Información del Ticket -->
    <div class="ticket-info">
        <div class="info-row">
            <span>Ticket:</span>
            <span><strong>#{{ $venta->numero_ticket ?? str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</strong></span>
        </div>
        <div class="info-row">
            <span>Fecha:</span>
            <span>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span>Cliente:</span>
            <span>{{ $venta->cliente->nombre ?? 'Cliente General' }}</span>
        </div>
        @if($venta->cliente && $venta->cliente->documento)
        <div class="info-row">
            <span>{{ $venta->cliente->tipo_documento ?? 'DOC' }}:</span>
            <span>{{ $venta->cliente->documento }}</span>
        </div>
        @endif
        <div class="info-row">
            <span>Vendedor:</span>
            <span>{{ $venta->user->name ?? 'Sistema' }}</span>
        </div>
        <div class="info-row">
            <span>Pago:</span>
            <span>{{ ucfirst($venta->tipo_pago ?? 'Efectivo') }}</span>
        </div>
    </div>

    <!-- Productos -->
    <div class="productos-table">
        <div style="font-weight: bold; text-align: center; margin-bottom: 8px;">PRODUCTOS</div>
        
        @foreach($venta->detalles as $detalle)
        <div class="producto-row">
            <div class="producto-nombre">{{ $detalle->producto->nombre ?? 'Producto' }}</div>
            <div class="producto-detalle">
                <span>{{ $detalle->cantidad }} x S/ {{ number_format($detalle->precio_unitario, 2) }}</span>
                <span><strong>S/ {{ number_format($detalle->subtotal, 2) }}</strong></span>
            </div>
            @if($detalle->producto->codigo)
            <div style="font-size: 10px; color: #666;">Código: {{ $detalle->producto->codigo }}</div>
            @endif
        </div>
        @endforeach

        <div style="text-align: right; margin-top: 8px; font-size: 11px; color: #666;">
            Total Items: {{ $venta->detalles->count() }} | Total Unidades: {{ $venta->detalles->sum('cantidad') }}
        </div>
    </div>

    <!-- Totales -->
    <div class="totales">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>S/ {{ number_format($venta->subtotal, 2) }}</span>
        </div>
        <div class="total-row">
            <span>IGV (18%):</span>
            <span>S/ {{ number_format($venta->igv, 2) }}</span>
        </div>
        <div class="total-row total-final">
            <span>TOTAL:</span>
            <span>S/ {{ number_format($venta->total, 2) }}</span>
        </div>
    </div>

    <!-- Observaciones -->
    @if($venta->observaciones)
    <div style="margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 10px;">
        <div style="font-weight: bold; margin-bottom: 5px;">OBSERVACIONES:</div>
        <div style="font-size: 11px;">{{ $venta->observaciones }}</div>
    </div>
    @endif

    <!-- Footer -->
    <div class="ticket-footer">
        <div>¡Gracias por su compra!</div>
        <div>Conserve su ticket</div>
        <div style="margin-top: 5px;">---</div>
        <div>{{ now()->format('d/m/Y H:i:s') }}</div>
        <div>Sistema Farmacia Magistral v1.0</div>
    </div>

    <script>
        // Auto-imprimir si se abre en ventana nueva
        if (window.opener) {
            // Pequeño delay para que se cargue completamente
            setTimeout(() => {
                window.print();
            }, 1000);
        }
    </script>
</body>
</html> 