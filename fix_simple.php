<?php
echo "Corrigiendo VentaController.php...\n";

$file = 'app/Http/Controllers/VentaController.php';
$content = file_get_contents($file);

// Reemplazar ambas ocurrencias
$search = '$detalle->subtotal = $producto->precio_venta * $item[\'cantidad\'];
                $detalle->save();';

$replace = '$detalle->subtotal = $producto->precio_venta * $item[\'cantidad\'];
                $detalle->lote = $producto->lote ?? \'SIN_LOTE\';
                $detalle->fecha_vencimiento = $producto->fecha_vencimiento ?? now()->addYear();
                $detalle->save();';

$newContent = str_replace($search, $replace, $content);

file_put_contents($file, $newContent);
echo "✅ Archivo corregido exitosamente!\n";
echo "📝 Campos agregados: lote y fecha_vencimiento\n";
?> 