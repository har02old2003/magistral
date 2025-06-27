<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Clientes - Farmacia Magistral</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #667eea; }
        .header h1 { color: #667eea; font-size: 22px; font-weight: bold; margin-bottom: 5px; }
        .header h2 { color: #666; font-size: 14px; margin-bottom: 8px; }
        .stats { display: table; width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .stat-item { display: table-cell; width: 25%; text-align: center; padding: 12px; background: #f8f9fa; border: 1px solid #dee2e6; }
        .stat-number { font-size: 18px; font-weight: bold; color: #667eea; margin-bottom: 3px; }
        .stat-label { font-size: 10px; color: #666; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #667eea; color: white; padding: 10px 6px; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        td { padding: 8px 6px; border-bottom: 1px solid #dee2e6; font-size: 10px; }
        tr:nth-child(even) { background: #f8f9fa; }
        .badge { padding: 3px 6px; border-radius: 3px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .badge-activo { background: #28a745; color: white; }
        .badge-inactivo { background: #dc3545; color: white; }
        .footer { margin-top: 25px; padding-top: 15px; border-top: 1px solid #dee2e6; text-align: center; font-size: 9px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>👥 FARMACIA MAGISTRAL</h1>
        <h2>Lista de Clientes Registrados</h2>
        <p>Generado el {{ $fecha }}</p>
    </div>
    
    <div class="stats">
        <div class="stat-item">
            <div class="stat-number">{{ $total }}</div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $activos }}</div>
            <div class="stat-label">Activos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $inactivos }}</div>
            <div class="stat-label">Inactivos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $con_email }}</div>
            <div class="stat-label">Con Email</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Documento</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
            <tr>
                <td><strong>{{ $cliente->nombres }} {{ $cliente->apellidos }}</strong></td>
                <td>{{ $cliente->tipo_documento }}: {{ $cliente->documento }}</td>
                <td>{{ $cliente->telefono ?? 'N/A' }}</td>
                <td>{{ $cliente->email ?? 'N/A' }}</td>
                <td>
                    @if($cliente->activo)
                        <span class="badge badge-activo">Activo</span>
                    @else
                        <span class="badge badge-inactivo">Inactivo</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($cliente->created_at)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p><strong>Farmacia Magistral</strong> - Sistema de Gestión</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
