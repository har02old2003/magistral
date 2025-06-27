<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear administrador
        User::create([
            'name' => 'Administrador Farmacia',
            'email' => 'admin@farmacia.com',
            'password' => Hash::make('admin123'),
            'role' => 'administrador',
            'activo' => true,
            'email_verified_at' => now(),
        ]);

        // Crear empleado
        User::create([
            'name' => 'Empleado Farmacia',
            'email' => 'empleado@farmacia.com',
            'password' => Hash::make('empleado123'),
            'role' => 'empleado',
            'activo' => true,
            'email_verified_at' => now(),
        ]);

        // Crear empleado adicional
        User::create([
            'name' => 'María González',
            'email' => 'maria@farmacia.com',
            'password' => Hash::make('empleado123'),
            'role' => 'empleado',
            'activo' => true,
            'email_verified_at' => now(),
        ]);
    }
}
