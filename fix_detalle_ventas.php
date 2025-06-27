<?php
// Script para corregir el archivo VentaController.php
// Agregar campos lote y fecha_vencimiento al DetalleVenta

$file = 'app/Http/Controllers/VentaController.php';
$content = file_get_contents($file);

// Buscar y reemplazar en el método storeAjax
$search = '$detalle->subtotal = $producto->precio_venta * $item[\'cantidad\'];
                $detalle->save();';

$replace = '$detalle->subtotal = $producto->precio_venta * $item[\'cantidad\'];
                $detalle->lote = $producto->lote ?? \'SIN_LOTE\';
                $detalle->fecha_vencimiento = $producto->fecha_vencimiento ?? now()->addYear();
                $detalle->save();';

$newContent = str_replace($search, $replace, $content);

file_put_contents($file, $newContent);

echo "✅ Archivo corregido - Campos lote y fecha_vencimiento agregados\n"; 