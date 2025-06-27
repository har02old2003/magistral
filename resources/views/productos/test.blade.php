<!DOCTYPE html>
<html>
<head>
    <title>Test Productos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>🔧 DIAGNÓSTICO DE PRODUCTOS</h1>
    
    <div style="background: #f0f0f0; padding: 15px; margin: 10px 0; border-radius: 5px;">
        <h2>1. Test de PHP básico:</h2>
        <p class="success">✅ PHP está funcionando - Fecha actual: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <div style="background: #f0f0f0; padding: 15px; margin: 10px 0; border-radius: 5px;">
        <h2>2. Test de Base de Datos:</h2>
        <?php
            try {
                $conexion = DB::connection()->getPdo();
                echo '<p class="success">✅ Conexión a BD: OK</p>';
                
                $count = DB::table('productos')->count();
                echo '<p class="success">✅ Productos en BD: ' . $count . '</p>';
                
                if ($count > 0) {
                    $productos_raw = DB::table('productos')->limit(3)->get();
                    echo '<p class="success">✅ Primeros productos:</p>';
                    foreach($productos_raw as $prod) {
                        echo '<li>' . $prod->nombre . ' (Stock: ' . $prod->stock_actual . ')</li>';
                    }
                }
                
            } catch(Exception $e) {
                echo '<p class="error">❌ Error BD: ' . $e->getMessage() . '</p>';
            }
        ?>
    </div>

    <div style="background: #f0f0f0; padding: 15px; margin: 10px 0; border-radius: 5px;">
        <h2>3. Test de Variables del Controlador:</h2>
        @if(isset($productos))
            <p class="success">✅ Variable $productos existe</p>
            <p>Tipo: {{ gettype($productos) }}</p>
            @if(is_countable($productos))
                <p>Cantidad: {{ count($productos) }}</p>
            @else
                <p class="error">❌ $productos no es countable</p>
            @endif
        @else
            <p class="error">❌ Variable $productos NO existe</p>
        @endif

        @if(isset($categorias))
            <p class="success">✅ Variable $categorias existe ({{ count($categorias) }})</p>
        @else
            <p class="error">❌ Variable $categorias NO existe</p>
        @endif

        @if(isset($error))
            <p class="error">❌ Error del controlador: {{ $error }}</p>
        @endif
    </div>

    <div style="background: #f0f0f0; padding: 15px; margin: 10px 0; border-radius: 5px;">
        <h2>4. Test de Modelos:</h2>
        <?php
            try {
                $prod_model = App\Models\Producto::count();
                echo '<p class="success">✅ Modelo Producto: ' . $prod_model . ' registros</p>';
            } catch(Exception $e) {
                echo '<p class="error">❌ Error Modelo Producto: ' . $e->getMessage() . '</p>';
            }

            try {
                $cat_model = App\Models\Categoria::count();
                echo '<p class="success">✅ Modelo Categoria: ' . $cat_model . ' registros</p>';
            } catch(Exception $e) {
                echo '<p class="error">❌ Error Modelo Categoria: ' . $e->getMessage() . '</p>';
            }
        ?>
    </div>

    <hr>
    <p><strong>Si ves toda esta información, el problema no es de conexión ni de datos básicos.</strong></p>
    <p><a href="{{ route('productos.index') }}" style="background: blue; color: white; padding: 10px; text-decoration: none;">🔄 Refrescar página</a></p>
</body>
</html> 