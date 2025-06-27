<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Proveedores</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #667eea; }
        .header h1 { color: #667eea; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #667eea; color: white; padding: 8px; font-size: 10px; }
        td { padding: 6px; border-bottom: 1px solid #ddd; font-size: 10px; }
        .badge-activo { background: #28a745; color: white; padding: 2px 4px; border-radius: 2px; }
        .badge-inactivo { background: #dc3545; color: white; padding: 2px 4px; border-radius: 2px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚚 FARMACIA MAGISTRAL - PROVEEDORES</h1>
        <p>Generado el {{ $fecha }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Empresa</th>
                <th>RUC</th>
                <th>Contacto</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proveedores as $proveedor)
            <tr>
                <td><strong>{{ $proveedor->nombre }}</strong></td>
                <td>{{ $proveedor->ruc }}</td>
                <td>{{ $proveedor->contacto ?? 'N/A' }}</td>
                <td>{{ $proveedor->telefono ?? 'N/A' }}</td>
                <td>{{ $proveedor->email ?? 'N/A' }}</td>
                <td>
                    @if($proveedor->activo)
                        <span class="badge-activo">Activo</span>
                    @else
                        <span class="badge-inactivo">Inactivo</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px; text-align: center; font-size: 9px;">
        <p>Farmacia Magistral - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
