<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategoriaSeeder::class,
            MarcaSeeder::class,
        ]);
        
        // Crear datos de prueba adicionales
        $this->createTestData();
    }
    
    private function createTestData()
    {
        // Crear un proveedor primero
        $proveedor = \App\Models\Proveedor::create([
            'nombre' => 'Farmacéuticos Unidos S.A.',
            'ruc' => '20123456789',
            'telefono' => '014567890',
            'email' => 'ventas@farmaceuticos.com',
            'direccion' => 'Jr. Comercio 456',
            'contacto' => 'María Pérez',
            'activo' => true
        ]);
        
        // Crear productos de prueba
        $categoria = \App\Models\Categoria::first();
        $marca = \App\Models\Marca::first();
        
        if ($categoria && $marca && $proveedor) {
            \App\Models\Producto::create([
                'codigo' => 'MED001',
                'nombre' => 'Paracetamol 500mg',
                'descripcion' => 'Analgésico y antipirético',
                'categoria_id' => $categoria->id,
                'marca_id' => $marca->id,
                'proveedor_id' => $proveedor->id,
                'precio_compra' => 15.00,
                'precio_venta' => 25.00,
                'stock_actual' => 100,
                'stock_minimo' => 10,
                'lote' => 'L001',
                'fecha_vencimiento' => now()->addYear(),
                'activo' => true
            ]);
            
            \App\Models\Producto::create([
                'codigo' => 'MED002',
                'nombre' => 'Ibuprofeno 400mg',
                'descripcion' => 'Antiinflamatorio no esteroideo',
                'categoria_id' => $categoria->id,
                'marca_id' => $marca->id,
                'proveedor_id' => $proveedor->id,
                'precio_compra' => 20.00,
                'precio_venta' => 35.00,
                'stock_actual' => 5, // Stock bajo para prueba
                'stock_minimo' => 10,
                'lote' => 'L002',
                'fecha_vencimiento' => now()->addMonths(6),
                'activo' => true
            ]);
            
            \App\Models\Producto::create([
                'codigo' => 'MED003',
                'nombre' => 'Aspirina 100mg',
                'descripcion' => 'Ácido acetilsalicílico',
                'categoria_id' => $categoria->id,
                'marca_id' => $marca->id,
                'proveedor_id' => $proveedor->id,
                'precio_compra' => 12.00,
                'precio_venta' => 20.00,
                'stock_actual' => 0, // Agotado para prueba
                'stock_minimo' => 15,
                'lote' => 'L003',
                'fecha_vencimiento' => now()->addMonths(3),
                'activo' => true
            ]);
        }
        
        // Crear un cliente de prueba
        \App\Models\Cliente::create([
            'nombres' => 'Juan Carlos',
            'apellidos' => 'García López',
            'documento' => '12345678',
            'tipo_documento' => 'DNI',
            'telefono' => '987654321',
            'email' => 'juan@email.com',
            'direccion' => 'Av. Principal 123',
            'activo' => true
        ]);
    }
}
