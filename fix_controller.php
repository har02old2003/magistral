<?php
// Script para corregir el archivo VentaController.php
echo 'Corrigiendo VentaController.php...' . PHP_EOL;

\ = 'app/Http/Controllers/VentaController.php';
\ = file_get_contents(\);

// Buscar y reemplazar todas las ocurrencias
\ = '\->subtotal = \->precio_venta * \[\'cantidad\'];';
\ = '\->subtotal = \->precio_venta * \[\'cantidad\'];
                \->lote = \->lote ?? \'SIN_LOTE\';
                \->fecha_vencimiento = \->fecha_vencimiento ?? now()->addYear();';

\ = str_replace(\, \, \);

if (\ !== \) {
    file_put_contents(\, \);
    echo '✅ Archivo corregido exitosamente!' . PHP_EOL;
    echo '📝 Campos lote y fecha_vencimiento agregados al DetalleVenta' . PHP_EOL;
} else {
    echo '⚠️ No se encontraron cambios necesarios' . PHP_EOL;
}
?>
