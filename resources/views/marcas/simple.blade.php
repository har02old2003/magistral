<!DOCTYPE html>
<html>
<head>
    <title>Marcas Simple</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>✅ Marcas - Vista Simple</h1>
    
    <p><strong>Total marcas:</strong> {{ isset($marcas) ? count($marcas) : 'NO DEFINIDO' }}</p>
    
    @if(isset($marcas) && count($marcas) > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Activo</th>
                    <th>Creado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($marcas as $marca)
                <tr>
                    <td>{{ $marca->id }}</td>
                    <td><strong>{{ $marca->nombre }}</strong></td>
                    <td>{{ $marca->descripcion ?? 'Sin descripción' }}</td>
                    <td>{{ $marca->activo ? 'SÍ' : 'NO' }}</td>
                    <td>{{ $marca->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: red;">❌ No hay marcas o error en la consulta</p>
    @endif
    
    <div style="margin-top: 20px;">
        <a href="/marcas/test" style="background: orange; color: white; padding: 10px; text-decoration: none;">🔧 Test Diagnóstico</a>
        <a href="/marcas" style="background: blue; color: white; padding: 10px; text-decoration: none; margin-left: 10px;">🔄 Vista Principal</a>
        <a href="/dashboard" style="background: green; color: white; padding: 10px; text-decoration: none; margin-left: 10px;">🏠 Dashboard</a>
    </div>
</body>
</html> 