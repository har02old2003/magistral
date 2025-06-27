<!DOCTYPE html>
<html>
<head>
    <title>Test Marcas</title>
</head>
<body>
    <h1>🔧 DIAGNÓSTICO BÁSICO</h1>
    
    <h2>1. PHP Funciona</h2>
    <p>✅ Si ves esto, PHP está funcionando</p>
    
    <h2>2. Usuario</h2>
    <p>Usuario: {{ auth()->check() ? auth()->user()->name : 'NO AUTENTICADO' }}</p>
    <p>Rol: {{ auth()->check() ? auth()->user()->role : 'SIN ROL' }}</p>
    
    <h2>3. Test Base de Datos</h2>
    @php
        try {
            $conexion = \DB::connection()->getPdo();
            echo "<p>✅ Conexión BD: OK</p>";
            
            $marcas = \DB::table('marcas')->get();
            echo "<p>✅ Consulta marcas: " . $marcas->count() . " registros</p>";
            
            foreach($marcas->take(3) as $marca) {
                echo "<p>- ID: {$marca->id}, Nombre: {$marca->nombre}</p>";
            }
            
        } catch(\Exception $e) {
            echo "<p>❌ Error BD: " . $e->getMessage() . "</p>";
        }
    @endphp
    
    <h2>4. Test Modelo</h2>
    @php
        try {
            $marcasModelo = \App\Models\Marca::all();
            echo "<p>✅ Modelo Marca: " . $marcasModelo->count() . " registros</p>";
        } catch(\Exception $e) {
            echo "<p>❌ Error Modelo: " . $e->getMessage() . "</p>";
        }
    @endphp
    
    <h2>5. Enlaces</h2>
    <a href="/marcas" style="background: blue; color: white; padding: 10px; text-decoration: none;">🔄 Volver a Marcas</a>
    <a href="/dashboard" style="background: green; color: white; padding: 10px; text-decoration: none; margin-left: 10px;">🏠 Dashboard</a>
</body>
</html>
