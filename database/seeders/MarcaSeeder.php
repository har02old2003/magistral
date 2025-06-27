<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Marca;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marcas = [
            [
                'nombre' => 'Bayer',
                'descripcion' => 'Laboratorio farmacéutico internacional',
                'activo' => true
            ],
            [
                'nombre' => 'Pfizer',
                'descripcion' => 'Empresa farmacéutica multinacional',
                'activo' => true
            ],
            [
                'nombre' => 'GSK',
                'descripcion' => 'GlaxoSmithKline',
                'activo' => true
            ],
            [
                'nombre' => 'Novartis',
                'descripcion' => 'Compañía farmacéutica suiza',
                'activo' => true
            ],
            [
                'nombre' => 'Roche',
                'descripcion' => 'Laboratorio farmacéutico suizo',
                'activo' => true
            ],
            [
                'nombre' => 'Abbott',
                'descripcion' => 'Empresa de productos de salud',
                'activo' => true
            ],
            [
                'nombre' => 'Sanofi',
                'descripcion' => 'Empresa farmacéutica francesa',
                'activo' => true
            ],
            [
                'nombre' => 'Johnson & Johnson',
                'descripcion' => 'Compañía multinacional de productos de salud',
                'activo' => true
            ],
            [
                'nombre' => 'Merck',
                'descripcion' => 'Compañía farmacéutica alemana',
                'activo' => true
            ],
            [
                'nombre' => 'Genérico',
                'descripcion' => 'Medicamentos genéricos',
                'activo' => true
            ]
        ];

        foreach ($marcas as $marca) {
            Marca::create($marca);
        }
    }
}
