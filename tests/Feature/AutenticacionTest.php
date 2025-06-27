<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('la página de login se carga correctamente', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
});

test('el administrador puede iniciar sesión', function () {
    // Crear usuario administrador
    $admin = User::create([
        'name' => 'Administrador Test',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role' => 'administrador',
        'activo' => true,
    ]);

    // Intentar iniciar sesión
    $response = $this->post('/login', [
        'email' => 'admin@test.com',
        'password' => 'password',
    ]);

    // Verificar redirección al dashboard
    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($admin);
});

test('el empleado puede iniciar sesión', function () {
    // Crear usuario empleado
    $empleado = User::create([
        'name' => 'Empleado Test',
        'email' => 'empleado@test.com',
        'password' => Hash::make('password'),
        'role' => 'empleado',
        'activo' => true,
    ]);

    // Intentar iniciar sesión
    $response = $this->post('/login', [
        'email' => 'empleado@test.com',
        'password' => 'password',
    ]);

    // Verificar redirección al dashboard
    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($empleado);
});

test('un usuario inactivo no puede iniciar sesión', function () {
    // Crear usuario inactivo
    User::create([
        'name' => 'Usuario Inactivo',
        'email' => 'inactivo@test.com',
        'password' => Hash::make('password'),
        'role' => 'empleado',
        'activo' => false,
    ]);

    // Intentar iniciar sesión
    $response = $this->post('/login', [
        'email' => 'inactivo@test.com',
        'password' => 'password',
    ]);

    // Verificar que no se puede autenticar
    $this->assertGuest();
    $response->assertSessionHasErrors();
});

test('la ruta principal redirige al dashboard', function () {
    $response = $this->get('/');
    $response->assertRedirect('/dashboard');
});

test('el usuario puede cerrar sesión', function () {
    $user = User::create([
        'name' => 'Usuario Test',
        'email' => 'user@test.com',
        'password' => Hash::make('password'),
        'role' => 'empleado',
        'activo' => true,
    ]);

    $this->actingAs($user);
    
    $response = $this->post('/logout');
    
    $this->assertGuest();
    $response->assertRedirect('/login');
});
