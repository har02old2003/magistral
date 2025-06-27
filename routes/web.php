<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UsuarioController;

// Ruta principal redirige al dashboard
Route::get('/', function () {
    return redirect('/dashboard');
});

// Rutas de autenticación
require __DIR__.'/auth.php';

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/notificaciones', [DashboardController::class, 'notificaciones'])->name('dashboard.notificaciones');
    Route::get('/dashboard/stock-bajo', [DashboardController::class, 'stockBajo'])->name('dashboard.stock-bajo');
    Route::get('/dashboard/proximos-vencer', [DashboardController::class, 'proximosVencer'])->name('dashboard.proximos-vencer');
    
    // Productos - Todos los usuarios pueden ver, solo admin puede modificar
    Route::resource('productos', ProductoController::class);
    Route::get('/productos/para-ventas', [ProductoController::class, 'paraVentas'])->name('productos.para-ventas');
    Route::post('/productos/{producto}/stock', [ProductoController::class, 'actualizarStock'])->name('productos.stock');
    Route::get('/productos-exportar', [ProductoController::class, 'exportar'])->name('productos.exportar');
    Route::get('/productos-generar-codigo', [ProductoController::class, 'generarCodigo'])->name('productos.generar-codigo');
    
    // Ruta temporal para verificar datos del producto
    Route::get('/debug-producto/{id}', function($id) {
        try {
            $producto = \App\Models\Producto::with(['categoria', 'marca', 'proveedor'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'debug' => 'Datos del producto para modal de edición',
                'producto' => [
                    'id' => $producto->id,
                    'codigo' => $producto->codigo,
                    'nombre' => $producto->nombre,
                    'lote' => $producto->lote,
                    'fecha_vencimiento' => $producto->fecha_vencimiento,
                    'meses_vencimiento' => $producto->meses_vencimiento,
                    'presentacion' => $producto->presentacion,
                    'categoria_id' => $producto->categoria_id,
                    'marca_id' => $producto->marca_id,
                    'proveedor_id' => $producto->proveedor_id,
                    'activo' => $producto->activo,
                    'requiere_receta' => $producto->requiere_receta,
                    'todos_los_campos' => $producto->toArray()
                ]
            ]);
        } catch(Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    })->name('debug.producto');
    
    // Ruta de diagnóstico para productos
    Route::get('/productos/diagnostico', function() {
        try {
            // Test básico de PHP
            $testPhp = "✅ PHP funciona";
            
            // Test de conexión a BD
            $testBd = "❌ Error BD";
            try {
                \DB::connection()->getPdo();
                $testBd = "✅ BD conectada";
            } catch(Exception $e) {
                $testBd = "❌ BD error: " . $e->getMessage();
            }
            
            // Test de productos
            $productos = [];
            $testProductos = "❌ Error productos";
            try {
                $productos = \App\Models\Producto::with(['categoria', 'marca'])->get();
                $testProductos = "✅ Productos: " . $productos->count() . " encontrados";
            } catch(Exception $e) {
                $testProductos = "❌ Productos error: " . $e->getMessage();
            }
            
            // Test de vista base
            $testVista = "✅ Vista funcionando";
            
            return view('productos.diagnostico', compact('testPhp', 'testBd', 'testProductos', 'productos', 'testVista'));
            
        } catch(Exception $e) {
            return response("Error completo: " . $e->getMessage(), 500);
        }
    })->name('productos.diagnostico');
    
    // Ventas - Todos los usuarios pueden crear ventas
    Route::resource('ventas', VentaController::class);
    Route::get('/ventas/{venta}/ticket', [VentaController::class, 'ticket'])->name('ventas.ticket');
    Route::get('/ventas-buscar-producto', [VentaController::class, 'buscarProducto'])->name('ventas.buscar-producto');
    Route::get('/ventas-reportes', [VentaController::class, 'reportes'])->name('ventas.reportes');
    Route::get('/ventas-datos-reportes', [VentaController::class, 'datosReportes'])->name('ventas.datos-reportes');
    Route::get('/ventas-exportar', [VentaController::class, 'exportar'])->name('ventas.exportar');
    
    // Clientes - Todos pueden ver y crear, solo admin eliminar
    Route::resource('clientes', ClienteController::class);
    Route::get('/clientes-exportar', [ClienteController::class, 'exportar'])->name('clientes.exportar');
    
    // Ruta de diagnóstico de clientes
    Route::get('/clientes/diagnostico', function() {
        return view('clientes.diagnostico');
    })->name('clientes.diagnostico');
    
    // Ruta temporal para probar clientes sin errores SQL
    Route::get('/clientes/simple', function() {
        return view('clientes.simple');
    })->middleware('auth')->name('clientes.simple');
    
    // Marcas - Todos pueden ver
    Route::resource('marcas', MarcaController::class);
    Route::get('/marcas-exportar', [MarcaController::class, 'exportar'])->name('marcas.exportar');
    Route::get('/marcas-generar-codigo', [MarcaController::class, 'generarCodigo'])->name('marcas.generar-codigo');
    
    // Ruta de diagnóstico para marcas
    Route::get('/marcas/diagnostico', function() {
        try {
            // Test básico de PHP
            $testPhp = "✅ PHP funciona";
            
            // Test de conexión a BD
            $testBd = "❌ Error BD";
            try {
                \DB::connection()->getPdo();
                $testBd = "✅ BD conectada";
            } catch(Exception $e) {
                $testBd = "❌ BD error: " . $e->getMessage();
            }
            
            // Test de marcas
            $marcas = [];
            $testMarcas = "❌ Error marcas";
            try {
                $marcas = \App\Models\Marca::withCount('productos')->get();
                $testMarcas = "✅ Marcas: " . $marcas->count() . " encontradas";
            } catch(Exception $e) {
                $testMarcas = "❌ Marcas error: " . $e->getMessage();
            }
            
            // Test de vista base
            $testVista = "✅ Vista funcionando";
            
            return view('marcas.diagnostico', compact('testPhp', 'testBd', 'testMarcas', 'marcas', 'testVista'));
            
        } catch(Exception $e) {
            return response("Error completo: " . $e->getMessage(), 500);
        }
    })->name('marcas.diagnostico');
    
    // Test ultra básico para marcas
    Route::get('/marcas/test', function() {
        return view('marcas.test');
    })->middleware('auth')->name('marcas.test');

    // Ruta simple de marcas
    Route::get('/marcas/simple', function() {
        try {
            $marcas = \App\Models\Marca::all();
            return view('marcas.simple', compact('marcas'));
        } catch(\Exception $e) {
            return response("Error: " . $e->getMessage(), 500);
        }
    })->name('marcas.simple');
    
    // Proveedores - Todos pueden ver
    Route::resource('proveedores', ProveedorController::class);
    Route::get('/proveedores-exportar', [ProveedorController::class, 'exportar'])->name('proveedores.exportar');
    
    // Categorías - Todos pueden ver
    Route::resource('categorias', CategoriaController::class);
    Route::get('/categorias-exportar', [CategoriaController::class, 'exportar'])->name('categorias.exportar');
    
    // Usuarios - Solo admin puede ver
    Route::resource('usuarios', UsuarioController::class);
    Route::get('/usuarios-exportar', [UsuarioController::class, 'exportar'])->name('usuarios.exportar');
    Route::patch('/usuarios/{usuario}/cambiar-estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.cambiar-estado');
    
    // Ruta de diagnóstico completo del sistema
    Route::get('/test-system', function() {
        return view('test-system');
    })->middleware('auth')->name('test.system');
    
});
