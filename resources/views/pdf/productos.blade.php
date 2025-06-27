<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos - Farmacia Magistral</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        
        .header h1 {
            color: #667eea;
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .header h2 {
            color: #666;
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .header .fecha {
            color: #888;
            font-size: 12px;
        }
        
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        
        .stat-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .productos-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .productos-table th {
            background: #667eea;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .productos-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
        }
        
        .productos-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-codigo {
            background: #6c757d;
            color: white;
        }
        
        .badge-categoria {
            background: #17a2b8;
            color: white;
        }
        
        .stock-disponible {
            color: #28a745;
            font-weight: bold;
        }
        
        .stock-bajo {
            color: #ffc107;
            font-weight: bold;
        }
        
        .stock-agotado {
            color: #dc3545;
            font-weight: bold;
        }
        
        .precio {
            font-weight: bold;
            color: #495057;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .nombre-producto {
            font-weight: bold;
            color: #212529;
        }
        
        .marca-producto {
            color: #6c757d;
            font-style: italic;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 FARMACIA MAGISTRAL</h1>
        <h2>Lista de Productos en Inventario</h2>
        <p class="fecha">Generado el {{ $fecha }}</p>
    </div>
    
    <div class="stats">
        <div class="stat-item">
            <div class="stat-number">{{ $total }}</div>
            <div class="stat-label">Total Productos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $disponibles }}</div>
            <div class="stat-label">Disponibles</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stock_bajo }}</div>
            <div class="stat-label">Stock Bajo</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $agotados }}</div>
            <div class="stat-label">Agotados</div>
        </div>
    </div>
    
    <table class="productos-table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Marca</th>
                <th>Stock</th>
                <th>Precio Compra</th>
                <th>Precio Venta</th>
                <th>Lote</th>
                <th>Vencimiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td>
                    <span class="badge badge-codigo">{{ $producto->codigo }}</span>
                </td>
                <td>
                    <div class="nombre-producto">{{ $producto->nombre }}</div>
                    @if($producto->presentacion)
                        <div class="marca-producto">{{ $producto->presentacion }}</div>
                    @endif
                </td>
                <td>
                    <span class="badge badge-categoria">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</span>
                </td>
                <td>{{ $producto->marca->nombre ?? 'Sin marca' }}</td>
                <td>
                    @if($producto->stock_actual > 10)
                        <span class="stock-disponible">{{ $producto->stock_actual }}</span>
                    @elseif($producto->stock_actual > 0)
                        <span class="stock-bajo">{{ $producto->stock_actual }}</span>
                    @else
                        <span class="stock-agotado">{{ $producto->stock_actual }}</span>
                    @endif
                    unidades
                </td>
                <td class="precio">S/ {{ number_format($producto->precio_compra, 2) }}</td>
                <td class="precio">S/ {{ number_format($producto->precio_venta, 2) }}</td>
                <td>{{ $producto->lote ?? 'N/A' }}</td>
                <td>
                    @if($producto->fecha_vencimiento)
                        {{ \Carbon\Carbon::parse($producto->fecha_vencimiento)->format('d/m/Y') }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p><strong>Farmacia Magistral</strong> - Sistema de Gestión de Inventario</p>
        <p>Documento generado automáticamente el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Total de productos listados: {{ $productos->count() }}</p>
    </div>
</body>
</html> 