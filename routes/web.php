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
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\HistoriaClinicaController;
use App\Http\Controllers\ProformaController;
use App\Http\Controllers\GuiaRemisionController;
use App\Http\Controllers\MovimientoStockController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ContabilidadController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\LaboratorioController;

// Ruta principal redirige al dashboard moderno
Route::get('/', function () {
    return redirect('/dashboard');
});

// Rutas de autenticación
require __DIR__.'/auth.php';

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - Ahora usa el moderno por defecto
    Route::get('/dashboard', function() {
        return view('dashboard.modern');
    })->name('dashboard');
    
    Route::get('/dashboard/notificaciones', [DashboardController::class, 'notificaciones'])->name('dashboard.notificaciones');
    Route::get('/dashboard/stock-bajo', [DashboardController::class, 'stockBajo'])->name('dashboard.stock-bajo');
    Route::get('/dashboard/proximos-vencer', [DashboardController::class, 'proximosVencer'])->name('dashboard.proximos-vencer');
    
    // Dashboard moderno - nueva versión avanzada
    Route::get('/dashboard-moderno', function() {
        return view('dashboard.modern');
    })->name('dashboard.modern');
    
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
    // Rutas específicas ANTES del resource para evitar conflictos
    Route::get('/ventas-directa', [VentaController::class, 'directa'])->name('ventas.directa');
    Route::get('/ventas/{venta}/ticket', [VentaController::class, 'ticket'])->name('ventas.ticket');
    Route::get('/ventas-buscar-producto', [VentaController::class, 'buscarProducto'])->name('ventas.buscar-producto');
    Route::get('/ventas-reportes', [VentaController::class, 'reportes'])->name('ventas.reportes');
    Route::get('/ventas-datos-reportes', [VentaController::class, 'datosReportes'])->name('ventas.datos-reportes');
    Route::get('/ventas-exportar', [VentaController::class, 'exportar'])->name('ventas.exportar');
    
    Route::resource('ventas', VentaController::class);
    
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
    
    // Reportes Avanzados
    Route::prefix('reportes')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/stock', [ReporteController::class, 'reporteStock'])->name('reportes.stock');
        Route::get('/kardex', [ReporteController::class, 'reporteKardex'])->name('reportes.kardex');
        Route::get('/guias-transferencias', [ReporteController::class, 'guiasTransferencias'])->name('reportes.guias-transferencias');
        Route::get('/reporte-guias', [ReporteController::class, 'reporteGuias'])->name('reportes.reporte-guias');
        Route::get('/stock-valorizado', [ReporteController::class, 'stockValorizado'])->name('reportes.stock-valorizado');
        Route::get('/costo-inventario', [ReporteController::class, 'costoInventario'])->name('reportes.costo-inventario');
        Route::get('/ventas', [ReporteController::class, 'reporteVentas'])->name('reportes.ventas');
        Route::get('/datos-ejecutivos', [ReporteController::class, 'datosEjecutivos'])->name('reportes.datos-ejecutivos');
        Route::get('/exportar/{tipo}', [ReporteController::class, 'exportarPDF'])->name('reportes.exportar-pdf');
    });

    // Pedidos - Sistema completo de gestión de pedidos
    Route::resource('pedidos', PedidoController::class);
    Route::post('/pedidos/{pedido}/confirmar', [PedidoController::class, 'confirmar'])->name('pedidos.confirmar');
    Route::post('/pedidos/{pedido}/convertir-venta', [PedidoController::class, 'convertirAVenta'])->name('pedidos.convertir-venta');
    Route::get('/pedidos-estadisticas', [PedidoController::class, 'estadisticas'])->name('pedidos.estadisticas');

    // Historia Clínica - Gestión médica de clientes
    Route::resource('historia-clinica', HistoriaClinicaController::class);
    Route::post('/historia-clinica/{historiaClinica}/consulta', [HistoriaClinicaController::class, 'agregarConsulta'])->name('historia-clinica.agregar-consulta');
    Route::get('/historia-clinica-buscar', [HistoriaClinicaController::class, 'buscarPorCliente'])->name('historia-clinica.buscar');

    // Proformas - Sistema de cotizaciones
    Route::resource('proformas', ProformaController::class);
    Route::post('/proformas/{proforma}/convertir-venta', [ProformaController::class, 'convertirAVenta'])->name('proformas.convertir-venta');
    Route::post('/proformas/{proforma}/enviar-email', [ProformaController::class, 'enviarEmail'])->name('proformas.enviar-email');
    Route::get('/proformas/{proforma}/pdf', [ProformaController::class, 'generarPDF'])->name('proformas.pdf');
    Route::get('/proformas-estadisticas', [ProformaController::class, 'estadisticas'])->name('proformas.estadisticas');

    // Guías de Remisión
    Route::resource('guias', GuiaRemisionController::class);
    Route::post('/guias/{guia}/en-transito', [GuiaRemisionController::class, 'enTransito'])->name('guias.en-transito');
    Route::post('/guias/{guia}/entregar', [GuiaRemisionController::class, 'entregar'])->name('guias.entregar');
    Route::get('/guias/{guia}/pdf', [GuiaRemisionController::class, 'generarPDF'])->name('guias.pdf');
    Route::get('/guias-estadisticas', [GuiaRemisionController::class, 'estadisticas'])->name('guias.estadisticas');

    // Movimientos de Stock - Ingresos y transferencias
    Route::get('/movimientos', [MovimientoStockController::class, 'index'])->name('movimientos.index');
    Route::post('/movimientos', [MovimientoStockController::class, 'store'])->name('movimientos.store');
    Route::get('/movimientos/kardex/{producto}', [MovimientoStockController::class, 'kardex'])->name('movimientos.kardex');
    Route::get('/movimientos/reporte-ingresos', [MovimientoStockController::class, 'reporteIngresos'])->name('movimientos.reporte-ingresos');
    Route::get('/movimientos-estadisticas', [MovimientoStockController::class, 'estadisticas'])->name('movimientos.estadisticas');

    // Delivery - Gestión de entregas
    Route::resource('delivery', DeliveryController::class);
    Route::post('/delivery/{delivery}/asignar-repartidor', [DeliveryController::class, 'asignarRepartidor'])->name('delivery.asignar-repartidor');
    Route::post('/delivery/{delivery}/en-ruta', [DeliveryController::class, 'enRuta'])->name('delivery.en-ruta');
    Route::post('/delivery/{delivery}/entregar', [DeliveryController::class, 'entregar'])->name('delivery.entregar');
    Route::get('/mis-deliveries', [DeliveryController::class, 'misDeliveries'])->name('delivery.mis-deliveries');
    Route::get('/delivery-estadisticas', [DeliveryController::class, 'estadisticas'])->name('delivery.estadisticas');

    // Contabilidad - Módulo contable completo
    Route::resource('contabilidad', ContabilidadController::class);
    Route::post('/contabilidad/{contabilidad}/contabilizar', [ContabilidadController::class, 'contabilizar'])->name('contabilidad.contabilizar');
    Route::get('/contabilidad-libro-diario', [ContabilidadController::class, 'libroDiario'])->name('contabilidad.libro-diario');
    Route::get('/contabilidad-balance-comprobacion', [ContabilidadController::class, 'balanceComprobacion'])->name('contabilidad.balance-comprobacion');
    Route::get('/contabilidad-estado-resultados', [ContabilidadController::class, 'estadoResultados'])->name('contabilidad.estado-resultados');
    Route::post('/contabilidad-registrar-venta/{venta}', [ContabilidadController::class, 'registrarVenta'])->name('contabilidad.registrar-venta');
    Route::get('/contabilidad-estadisticas', [ContabilidadController::class, 'estadisticas'])->name('contabilidad.estadisticas');

    // Caja - Control de caja y movimientos
    Route::get('/caja', function() {
        return view('caja.index');
    })->name('caja.index');
    
    // ========== RUTAS AJAX PARA MODALES ==========
    
    // Rutas AJAX para Caja (ya implementadas en CajaController)
    Route::post('/caja/ajax', [CajaController::class, 'storeAjax'])->name('caja.store.ajax');
    Route::get('/caja/{id}/ajax', [CajaController::class, 'show'])->name('caja.show.ajax');
    Route::delete('/caja/{id}/ajax', [CajaController::class, 'destroy'])->name('caja.destroy.ajax');
    
    // Rutas AJAX para Ventas
    Route::post('/ventas/ajax', [VentaController::class, 'storeAjax'])->name('ventas.store.ajax');
    Route::get('/ventas/{id}/ajax', [VentaController::class, 'show'])->name('ventas.show.ajax');
    Route::get('/ventas/buscar-cliente-ajax', [VentaController::class, 'buscarClienteAjax'])->name('ventas.buscar-cliente.ajax');
    Route::get('/ventas/calcular-totales-ajax', [VentaController::class, 'calcularTotalesAjax'])->name('ventas.calcular-totales.ajax');
    
    // Rutas AJAX para Proformas  
    Route::post('/proformas/ajax', [ProformaController::class, 'storeAjax'])->name('proformas.store.ajax');
    Route::get('/proformas/{id}/ajax', [ProformaController::class, 'show'])->name('proformas.show.ajax');
    Route::put('/proformas/{id}/ajax', [ProformaController::class, 'updateAjax'])->name('proformas.update.ajax');
    
    // Rutas AJAX para Clientes
    Route::post('/clientes/ajax', [ClienteController::class, 'storeAjax'])->name('clientes.store.ajax');
    Route::get('/clientes/{id}/ajax', [ClienteController::class, 'show'])->name('clientes.show.ajax');
    Route::put('/clientes/{id}/ajax', [ClienteController::class, 'updateAjax'])->name('clientes.update.ajax');
    Route::get('/clientes/buscar-ajax', [ClienteController::class, 'buscarAjax'])->name('clientes.buscar.ajax');
    
    // Rutas AJAX para Pedidos
    Route::post('/pedidos/ajax', [PedidoController::class, 'storeAjax'])->name('pedidos.store.ajax');
    Route::get('/pedidos/{id}/ajax', [PedidoController::class, 'show'])->name('pedidos.show.ajax');
    Route::delete('/pedidos/{id}/ajax', [PedidoController::class, 'destroy'])->name('pedidos.destroy.ajax');
    
    // Rutas AJAX para Marcas
    Route::post('/marcas/ajax', [MarcaController::class, 'storeAjax'])->name('marcas.store.ajax');
    Route::put('/marcas/{marca}/ajax', [MarcaController::class, 'updateAjax'])->name('marcas.update.ajax');
    Route::get('/marcas-exportar', [MarcaController::class, 'exportar'])->name('marcas.exportar');
    
    // Rutas AJAX para Categorías
    Route::post('/categorias/ajax', [CategoriaController::class, 'storeAjax'])->name('categorias.store.ajax');
    Route::put('/categorias/{categoria}/ajax', [CategoriaController::class, 'updateAjax'])->name('categorias.update.ajax');
    Route::get('/categorias-exportar', [CategoriaController::class, 'exportar'])->name('categorias.exportar');
    
    // Rutas AJAX para Proveedores
    Route::post('/proveedores/ajax', [ProveedorController::class, 'storeAjax'])->name('proveedores.store.ajax');
    Route::put('/proveedores/{proveedor}/ajax', [ProveedorController::class, 'updateAjax'])->name('proveedores.update.ajax');
    Route::get('/proveedores-exportar', [ProveedorController::class, 'exportar'])->name('proveedores.exportar');
    
    // Rutas AJAX para Usuarios
    Route::post('/usuarios/ajax', [UsuarioController::class, 'storeAjax'])->name('usuarios.store.ajax');
    Route::put('/usuarios/{usuario}/ajax', [UsuarioController::class, 'updateAjax'])->name('usuarios.update.ajax');
    Route::get('/usuarios-exportar', [UsuarioController::class, 'exportar'])->name('usuarios.exportar');
    
    // ========== FIN RUTAS AJAX PARA MODALES ==========
    
    // Ruta de diagnóstico completo del sistema
    Route::get('/test-system', function() {
        return view('test-system');
    })->middleware('auth')->name('test.system');
    
    // RUTAS AJAX PARA PRODUCTOS
    Route::post('/productos/ajax', [ProductoController::class, 'store'])->name('productos.ajax.store');
    Route::put('/productos/ajax/{producto}', [ProductoController::class, 'updateAjax'])->name('productos.ajax.update');
    Route::delete('/productos/ajax/{producto}', [ProductoController::class, 'destroy'])->name('productos.ajax.destroy');
    Route::get('/productos/{producto}', [ProductoController::class, 'show'])->name('productos.show');
    Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    
    // Laboratorio - Gestión de fabricación de medicamentos
    Route::resource('laboratorio', LaboratorioController::class);
    Route::post('/laboratorio/{laboratorio}/iniciar', [LaboratorioController::class, 'iniciarProceso'])->name('laboratorio.iniciar');
    Route::post('/laboratorio/{laboratorio}/paso/{paso}/completar', [LaboratorioController::class, 'completarPaso'])->name('laboratorio.completar-paso');
    Route::get('/laboratorio/{laboratorio}/reporte', [LaboratorioController::class, 'generarReporte'])->name('laboratorio.reporte');
    
});
