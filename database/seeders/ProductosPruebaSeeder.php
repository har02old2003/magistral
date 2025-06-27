<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Proveedor;

class ProductosPruebaSeeder extends Seeder
{
    public function run()
    {
        // Obtener o crear categoría
        $categoria = Categoria::first();
        if (!$categoria) {
            $categoria = Categoria::create([
                'nombre' => 'Medicamentos',
                'descripcion' => 'Medicamentos generales',
                'activo' => true
            ]);
        }

        // Obtener o crear marca
        $marca = Marca::first();
        if (!$marca) {
            $marca = Marca::create([
                'nombre' => 'Farmex',
                'descripcion' => 'Marca genérica',
                'activo' => true
            ]);
        }

        // Obtener o crear proveedor
        $proveedor = Proveedor::first();
        if (!$proveedor) {
            $proveedor = Proveedor::create([
                'nombre' => 'Distribuidora Farmacéutica',
                'ruc' => '20123456789',
                'direccion' => 'Av. Principal 123',
                'telefono' => '123456789',
                'email' => 'contacto@distribuidora.com',
                'activo' => true
            ]);
        }

        // Productos de prueba que coinciden con los simulados
        $productos = [
            [
                'codigo' => 'PARA500',
                'nombre' => 'Paracetamol 500mg',
                'precio_venta' => 5.50,
                'stock_actual' => 100
            ],
            [
                'codigo' => 'IBU400',
                'nombre' => 'Ibuprofeno 400mg',
                'precio_venta' => 8.20,
                'stock_actual' => 80
            ],
            [
                'codigo' => 'AMO500',
                'nombre' => 'Amoxicilina 500mg',
                'precio_venta' => 12.00,
                'stock_actual' => 60
            ],
            [
                'codigo' => 'DICLO1',
                'nombre' => 'Diclofenaco Gel 1%',
                'precio_venta' => 15.50,
                'stock_actual' => 40
            ],
            [
                'codigo' => 'OME20',
                'nombre' => 'Omeprazol 20mg',
                'precio_venta' => 18.00,
                'stock_actual' => 50
            ],
            [
                'codigo' => 'ASP100',
                'nombre' => 'Aspirina 100mg',
                'precio_venta' => 3.50,
                'stock_actual' => 120
            ],
            [
                'codigo' => 'META500',
                'nombre' => 'Metamizol 500mg',
                'precio_venta' => 4.80,
                'stock_actual' => 90
            ],
            [
                'codigo' => 'LORA10',
                'nombre' => 'Loratadina 10mg',
                'precio_venta' => 6.90,
                'stock_actual' => 70
            ]
        ];

        $contadorCreados = 0;
        $contadorExistentes = 0;

        foreach ($productos as $prodData) {
            $existente = Producto::where('codigo', $prodData['codigo'])->first();
            
            if (!$existente) {
                Producto::create([
                    'codigo' => $prodData['codigo'],
                    'nombre' => $prodData['nombre'],
                    'presentacion' => 'Caja x 20 tabletas',
                    'precio_compra' => $prodData['precio_venta'] * 0.7,
                    'precio_venta' => $prodData['precio_venta'],
                    'stock_actual' => $prodData['stock_actual'],
                    'stock_minimo' => 10,
                    'categoria_id' => $categoria->id,
                    'marca_id' => $marca->id,
                    'proveedor_id' => $proveedor->id,
                    'activo' => true,
                    'requiere_receta' => false,
                    'lote' => 'LT' . date('Ymd'),
                    'fecha_vencimiento' => date('Y-m-d', strtotime('+2 years'))
                ]);
                $contadorCreados++;
                $this->command->info("✅ Creado: {$prodData['nombre']}");
            } else {
                $contadorExistentes++;
                $this->command->warn("⚠️  Ya existe: {$prodData['nombre']}");
            }
        }

        $this->command->info("\n🎉 Resumen:");
        $this->command->info("- Productos creados: {$contadorCreados}");
        $this->command->info("- Productos ya existentes: {$contadorExistentes}");
        $this->command->info("- Total de productos en BD: " . Producto::count());
    }
} 