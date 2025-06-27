<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Analgésicos',
                'descripcion' => 'Medicamentos para aliviar el dolor',
                'activo' => true
            ],
            [
                'nombre' => 'Antibióticos',
                'descripcion' => 'Medicamentos para tratar infecciones bacterianas',
                'activo' => true
            ],
            [
                'nombre' => 'Antiinflamatorios',
                'descripcion' => 'Medicamentos para reducir la inflamación',
                'activo' => true
            ],
            [
                'nombre' => 'Antihistamínicos',
                'descripcion' => 'Medicamentos para tratar alergias',
                'activo' => true
            ],
            [
                'nombre' => 'Vitaminas y Suplementos',
                'descripcion' => 'Vitaminas, minerales y suplementos nutricionales',
                'activo' => true
            ],
            [
                'nombre' => 'Medicamentos Cardiovasculares',
                'descripcion' => 'Medicamentos para el corazón y sistema circulatorio',
                'activo' => true
            ],
            [
                'nombre' => 'Medicamentos Respiratorios',
                'descripcion' => 'Medicamentos para tratar problemas respiratorios',
                'activo' => true
            ],
            [
                'nombre' => 'Medicamentos Digestivos',
                'descripcion' => 'Medicamentos para el sistema digestivo',
                'activo' => true
            ],
            [
                'nombre' => 'Productos de Higiene',
                'descripcion' => 'Productos para el cuidado personal e higiene',
                'activo' => true
            ],
            [
                'nombre' => 'Productos Dermatológicos',
                'descripcion' => 'Medicamentos y productos para la piel',
                'activo' => true
            ]
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
