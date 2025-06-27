<?php

require_once 'bootstrap/app.php';

use App\Models\Producto;
use Illuminate\Http\Request;

echo "🔍 PROBANDO BÚSQUEDA DE PRODUCTOS:\n\n";

// Simular la consulta que hace el controlador
$termino = 'para';

echo "Término de búsqueda: '$termino'\n";

$productos = Producto::with(['categoria', 'marca'])
                    ->where('activo', true)
                    ->where('stock_actual', '>', 0)
                    ->where(function($query) use ($termino) {
                        $query->where('nombre', 'like', "%{$termino}%")
                              ->orWhere('codigo', 'like', "%{$termino}%");
                    })
                    ->limit(10)
                    ->get();

echo "Productos encontrados: " . $productos->count() . "\n\n";

foreach ($productos as $producto) {
    echo "ID: " . $producto->id . "\n";
    echo "Código: " . $producto->codigo . "\n";
    echo "Nombre: " . $producto->nombre . "\n";
    echo "Precio: " . $producto->precio_venta . "\n";
    echo "Stock: " . $producto->stock_actual . "\n";
    echo "Marca: " . ($producto->marca->nombre ?? 'Sin marca') . "\n";
    echo "Categoría: " . ($producto->categoria->nombre ?? 'Sin categoría') . "\n";
    echo "-----\n";
}

// Probar también el formato JSON que devuelve el controlador
$json_result = $productos->map(function($producto) {
    return [
        'id' => $producto->id,
        'codigo' => $producto->codigo,
        'nombre' => $producto->nombre,
        'marca' => $producto->marca->nombre ?? '',
        'precio' => $producto->precio_venta,
        'stock' => $producto->stock_actual,
        'categoria' => $producto->categoria->nombre ?? ''
    ];
});

echo "\nJSON Result:\n";
echo json_encode($json_result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); 