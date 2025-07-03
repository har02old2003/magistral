<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Exception;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $productos = Producto::with(['categoria', 'marca', 'proveedor'])
                                ->activo()
                                ->orderBy('nombre')
                                ->paginate(15);

            $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();
            $marcas = Marca::where('activo', true)->orderBy('nombre')->get();
            $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();

            // Obtener conteos de productos con problemas
            $productosStockBajo = Producto::where('stock_actual', '<=', 10)
                                        ->where('stock_actual', '>', 0)
                                        ->count();
            
            $productosProximosVencer = Producto::whereNotNull('fecha_vencimiento')
                                             ->whereDate('fecha_vencimiento', '<=', now()->addDays(30))
                                             ->whereDate('fecha_vencimiento', '>=', now())
                                             ->count();

            return view('productos.index', compact(
                'productos',
                'categorias', 
                'marcas', 
                'proveedores',
                'productosStockBajo',
                'productosProximosVencer'
            ));
        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Error en ProductoController@index: ' . $e->getMessage());
            
            // Datos de fallback
            $productos = collect();
            $categorias = collect();
            $marcas = collect(); 
            $proveedores = collect();
            $productosStockBajo = 0;
            $productosProximosVencer = 0;

            return view('productos.index', compact(
                'productos',
                'categorias',
                'marcas',
                'proveedores',
                'productosStockBajo',
                'productosProximosVencer'
            ));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo administrador puede crear productos
        if (Auth::user()->role !== 'administrador') {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'No tienes permisos para realizar esta acción.'], 403);
            }
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();
        $marcas = Marca::where('activo', true)->orderBy('nombre')->get();
        $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();
        
        if (request()->expectsJson()) {
            $html = view('productos._create_form', compact('categorias', 'marcas', 'proveedores'))->render();
            return response()->json(['html' => $html]);
        }
        
        return view('productos.create', compact('categorias', 'marcas', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'codigo' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[A-Z0-9\-]+$/',
                    'unique:productos,codigo'
                ],
                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255'
                ],
                'descripcion' => 'nullable|string|max:1000',
                'precio_compra' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    'max:99999.99'
                ],
                'precio_venta' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    'max:99999.99',
                    'gt:precio_compra'
                ],
                'stock_actual' => [
                    'required',
                    'integer',
                    'min:0',
                    'max:99999'
                ],
                'stock_minimo' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:9999'
                ],
                'lote' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[A-Z0-9\-]+$/'
                ],
                'fecha_vencimiento' => [
                    'required',
                    'date',
                    'after:today'
                ],
                'meses_vencimiento' => 'required|in:12,18,24',
                'presentacion' => 'nullable|string|max:100',
                'principio_activo' => 'nullable|string|max:255',
                'concentracion' => 'nullable|string|max:100',
                'laboratorio' => 'nullable|string|max:255',
                'registro_sanitario' => [
                    'nullable',
                    'string',
                    'max:50',
                    'regex:/^[A-Z0-9\-]+$/'
                ],
                'requiere_receta' => 'boolean',
                'activo' => 'boolean',
                'categoria_id' => 'required|exists:categorias,id',
                'marca_id' => 'required|exists:marcas,id',
                'proveedor_id' => 'required|exists:proveedores,id'
            ], [
                'codigo.required' => 'El código del producto es obligatorio.',
                'codigo.regex' => 'El código solo puede contener letras mayúsculas, números y guiones.',
                'codigo.unique' => 'Ya existe un producto con este código.',
                'nombre.required' => 'El nombre del producto es obligatorio.',
                'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
                'precio_compra.required' => 'El precio de compra es obligatorio.',
                'precio_compra.min' => 'El precio de compra debe ser mayor a 0.',
                'precio_venta.required' => 'El precio de venta es obligatorio.',
                'precio_venta.gt' => 'El precio de venta debe ser mayor al precio de compra.',
                'stock_actual.required' => 'El stock actual es obligatorio.',
                'stock_actual.min' => 'El stock actual no puede ser negativo.',
                'stock_minimo.required' => 'El stock mínimo es obligatorio.',
                'stock_minimo.min' => 'El stock mínimo debe ser al menos 1.',
                'lote.required' => 'El lote es obligatorio.',
                'lote.regex' => 'El lote solo puede contener letras mayúsculas, números y guiones.',
                'fecha_vencimiento.required' => 'La fecha de vencimiento es obligatoria.',
                'fecha_vencimiento.after' => 'La fecha de vencimiento debe ser posterior a hoy.',
                'meses_vencimiento.required' => 'Los meses de vencimiento son obligatorios.',
                'meses_vencimiento.in' => 'Los meses de vencimiento deben ser 12, 18 o 24.',
                'registro_sanitario.regex' => 'El registro sanitario solo puede contener letras mayúsculas, números y guiones.',
                'categoria_id.required' => 'La categoría es obligatoria.',
                'categoria_id.exists' => 'La categoría seleccionada no existe.',
                'marca_id.required' => 'La marca es obligatoria.',
                'marca_id.exists' => 'La marca seleccionada no existe.',
                'proveedor_id.required' => 'El proveedor es obligatorio.',
                'proveedor_id.exists' => 'El proveedor seleccionado no existe.'
            ]);

            // Limpiar y formatear datos
            $validated['codigo'] = strtoupper(trim($validated['codigo']));
            $validated['nombre'] = trim(ucwords(strtolower($validated['nombre'])));
            $validated['lote'] = strtoupper(trim($validated['lote']));
            $validated['registro_sanitario'] = $validated['registro_sanitario'] ? strtoupper(trim($validated['registro_sanitario'])) : null;
            $validated['presentacion'] = $validated['presentacion'] ? trim($validated['presentacion']) : null;
            $validated['principio_activo'] = $validated['principio_activo'] ? trim(ucwords(strtolower($validated['principio_activo']))) : null;
            $validated['concentracion'] = $validated['concentracion'] ? trim($validated['concentracion']) : null;
            $validated['laboratorio'] = $validated['laboratorio'] ? trim(ucwords(strtolower($validated['laboratorio']))) : null;
            $validated['requiere_receta'] = $request->has('requiere_receta') ? true : false;
            $validated['activo'] = $request->has('activo') ? true : false;

            $producto = Producto::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Producto creado exitosamente.',
                    'producto' => $producto->load(['categoria', 'marca', 'proveedor'])
                ]);
            }

            return redirect()->route('productos.index')
                ->with('success', 'Producto "' . $producto->nombre . '" creado exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación.',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el producto: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al crear el producto: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        try {
            // Cargar todas las relaciones necesarias para el modal de edición
            $producto->load(['categoria', 'marca', 'proveedor']);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'producto' => [
                        'id' => $producto->id,
                        'codigo' => $producto->codigo,
                        'nombre' => $producto->nombre,
                        'descripcion' => $producto->descripcion,
                        'precio_compra' => $producto->precio_compra,
                        'precio_venta' => $producto->precio_venta,
                        'stock_actual' => $producto->stock_actual,
                        'stock_minimo' => $producto->stock_minimo,
                        'lote' => $producto->lote,
                        'fecha_vencimiento' => $producto->fecha_vencimiento,
                        'meses_vencimiento' => $producto->meses_vencimiento,
                        'presentacion' => $producto->presentacion,
                        'principio_activo' => $producto->principio_activo,
                        'concentracion' => $producto->concentracion,
                        'laboratorio' => $producto->laboratorio,
                        'registro_sanitario' => $producto->registro_sanitario,
                        'requiere_receta' => $producto->requiere_receta,
                        'activo' => $producto->activo,
                        'categoria_id' => $producto->categoria_id,
                        'marca_id' => $producto->marca_id,
                        'proveedor_id' => $producto->proveedor_id,
                        'categoria' => $producto->categoria,
                        'marca' => $producto->marca,
                        'proveedor' => $producto->proveedor,
                        'created_at' => $producto->created_at,
                        'updated_at' => $producto->updated_at
                    ]
                ]);
            }
            
            return view('productos.show', compact('producto'));
        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar el producto: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al cargar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        // Solo administrador puede editar productos
        if (Auth::user()->role !== 'administrador') {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'No tienes permisos para realizar esta acción.'], 403);
            }
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();
        $marcas = Marca::where('activo', true)->orderBy('nombre')->get();
        $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();
        
        if (request()->expectsJson()) {
            $html = view('productos._edit_form', compact('producto', 'categorias', 'marcas', 'proveedores'))->render();
            return response()->json(['html' => $html]);
        }
        
        return view('productos.edit', compact('producto', 'categorias', 'marcas', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        try {
            $validated = $request->validate([
                'codigo' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[A-Z0-9\-]+$/',
                    Rule::unique('productos', 'codigo')->ignore($producto->id)
                ],
                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255'
                ],
                'descripcion' => 'nullable|string|max:1000',
                'precio_compra' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    'max:99999.99'
                ],
                'precio_venta' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    'max:99999.99',
                    'gt:precio_compra'
                ],
                'stock_actual' => [
                    'required',
                    'integer',
                    'min:0',
                    'max:99999'
                ],
                'stock_minimo' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:9999'
                ],
                'lote' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[A-Z0-9\-]+$/'
                ],
                'fecha_vencimiento' => [
                    'required',
                    'date',
                    'after:today'
                ],
                'meses_vencimiento' => 'required|in:12,18,24',
                'presentacion' => 'nullable|string|max:100',
                'principio_activo' => 'nullable|string|max:255',
                'concentracion' => 'nullable|string|max:100',
                'laboratorio' => 'nullable|string|max:255',
                'registro_sanitario' => [
                    'nullable',
                    'string',
                    'max:50',
                    'regex:/^[A-Z0-9\-]+$/'
                ],
                'requiere_receta' => 'boolean',
                'activo' => 'boolean',
                'categoria_id' => 'required|exists:categorias,id',
                'marca_id' => 'required|exists:marcas,id',
                'proveedor_id' => 'required|exists:proveedores,id'
            ], [
                'codigo.required' => 'El código del producto es obligatorio.',
                'codigo.regex' => 'El código solo puede contener letras mayúsculas, números y guiones.',
                'codigo.unique' => 'Ya existe un producto con este código.',
                'nombre.required' => 'El nombre del producto es obligatorio.',
                'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
                'precio_compra.required' => 'El precio de compra es obligatorio.',
                'precio_compra.min' => 'El precio de compra debe ser mayor a 0.',
                'precio_venta.required' => 'El precio de venta es obligatorio.',
                'precio_venta.gt' => 'El precio de venta debe ser mayor al precio de compra.',
                'stock_actual.required' => 'El stock actual es obligatorio.',
                'stock_actual.min' => 'El stock actual no puede ser negativo.',
                'stock_minimo.required' => 'El stock mínimo es obligatorio.',
                'stock_minimo.min' => 'El stock mínimo debe ser al menos 1.',
                'lote.required' => 'El lote es obligatorio.',
                'lote.regex' => 'El lote solo puede contener letras mayúsculas, números y guiones.',
                'fecha_vencimiento.required' => 'La fecha de vencimiento es obligatoria.',
                'fecha_vencimiento.after' => 'La fecha de vencimiento debe ser posterior a hoy.',
                'meses_vencimiento.required' => 'Los meses de vencimiento son obligatorios.',
                'meses_vencimiento.in' => 'Los meses de vencimiento deben ser 12, 18 o 24.',
                'registro_sanitario.regex' => 'El registro sanitario solo puede contener letras mayúsculas, números y guiones.',
                'categoria_id.required' => 'La categoría es obligatoria.',
                'categoria_id.exists' => 'La categoría seleccionada no existe.',
                'marca_id.required' => 'La marca es obligatoria.',
                'marca_id.exists' => 'La marca seleccionada no existe.',
                'proveedor_id.required' => 'El proveedor es obligatorio.',
                'proveedor_id.exists' => 'El proveedor seleccionado no existe.'
            ]);

            // Limpiar y formatear datos
            $validated['codigo'] = strtoupper(trim($validated['codigo']));
            $validated['nombre'] = trim(ucwords(strtolower($validated['nombre'])));
            $validated['lote'] = strtoupper(trim($validated['lote']));
            $validated['registro_sanitario'] = $validated['registro_sanitario'] ? strtoupper(trim($validated['registro_sanitario'])) : null;
            $validated['presentacion'] = $validated['presentacion'] ? trim($validated['presentacion']) : null;
            $validated['principio_activo'] = $validated['principio_activo'] ? trim(ucwords(strtolower($validated['principio_activo']))) : null;
            $validated['concentracion'] = $validated['concentracion'] ? trim($validated['concentracion']) : null;
            $validated['laboratorio'] = $validated['laboratorio'] ? trim(ucwords(strtolower($validated['laboratorio']))) : null;
            $validated['requiere_receta'] = $request->has('requiere_receta') ? true : false;
            $validated['activo'] = $request->has('activo') ? true : false;

            $producto->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Producto actualizado exitosamente.',
                    'producto' => $producto->load(['categoria', 'marca', 'proveedor'])
                ]);
            }

            return redirect()->route('productos.index')
                ->with('success', 'Producto "' . $producto->nombre . '" actualizado exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación.',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el producto: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al actualizar el producto: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        try {
            // Verificar si el producto tiene ventas asociadas
            $ventasCount = $producto->detalleVentas()->count();
            
            if ($ventasCount > 0) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "No se puede eliminar el producto '{$producto->nombre}' porque tiene {$ventasCount} venta(s) asociada(s)."
                    ], 400);
                }
                return redirect()->back()
                    ->with('error', "No se puede eliminar el producto '{$producto->nombre}' porque tiene {$ventasCount} venta(s) asociada(s).");
            }

            $nombreProducto = $producto->nombre;
            $producto->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Producto '{$nombreProducto}' eliminado exitosamente."
                ]);
            }

            return redirect()->route('productos.index')
                ->with('success', "Producto '{$nombreProducto}' eliminado exitosamente.");

        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el producto: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Obtener productos para ventas (solo activos y con stock)
     */
    public function paraVentas()
    {
        try {
            $productos = Producto::with(['categoria', 'marca'])
                ->where('activo', true)
                ->where('stock_actual', '>', 0)
                ->orderBy('nombre')
                ->get()
                ->map(function ($producto) {
                    return [
                        'id' => $producto->id,
                        'codigo' => $producto->codigo,
                        'nombre' => $producto->nombre,
                        'precio_venta' => $producto->precio_venta,
                        'stock_actual' => $producto->stock_actual,
                        'categoria' => $producto->categoria->nombre ?? 'Sin categoría',
                        'marca' => $producto->marca->nombre ?? 'Sin marca'
                    ];
                });
            
            return response()->json([
                'success' => true,
                'productos' => $productos
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los productos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar stock de un producto
     */
    public function actualizarStock(Request $request, Producto $producto)
    {
        try {
            $validated = $request->validate([
                'stock_actual' => [
                    'required',
                    'integer',
                    'min:0',
                    'max:99999'
                ],
                'operacion' => 'required|in:suma,resta,establece'
            ]);

            $stockAnterior = $producto->stock_actual;
            
            switch ($validated['operacion']) {
                case 'suma':
                    $nuevoStock = $stockAnterior + $validated['stock_actual'];
                    break;
                case 'resta':
                    $nuevoStock = max(0, $stockAnterior - $validated['stock_actual']);
                    break;
                case 'establece':
                    $nuevoStock = $validated['stock_actual'];
                    break;
            }

            $producto->update(['stock_actual' => $nuevoStock]);

            return response()->json([
                'success' => true,
                'message' => "Stock actualizado de {$stockAnterior} a {$nuevoStock} unidades.",
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $nuevoStock
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar productos a PDF
     */
    public function exportar()
    {
        try {
            $productos = Producto::with(['categoria', 'marca', 'proveedor'])->orderBy('nombre')->get();
            
            $data = [
                'productos' => $productos,
                'fecha' => now()->format('d/m/Y'),
                'total' => $productos->count(),
                'disponibles' => $productos->where('stock_actual', '>', 10)->count(),
                'stock_bajo' => $productos->where('stock_actual', '<=', 10)->where('stock_actual', '>', 0)->count(),
                'agotados' => $productos->where('stock_actual', 0)->count()
            ];

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('pdf.productos', $data);
            $pdf->setPaper('A4', 'landscape');
            
            return $pdf->download('productos_' . date('Y-m-d_H-i-s') . '.pdf');
        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al exportar productos: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al exportar productos: ' . $e->getMessage());
        }
    }

    /**
     * Generate automatic product code
     */
    public function generarCodigo()
    {
        try {
            // Obtener el último código de producto
            $ultimoCodigo = Producto::where('codigo', 'LIKE', 'MED%')
                ->orderBy('codigo', 'desc')
                ->first();

            if ($ultimoCodigo) {
                // Extraer el número del código (ej: MED0001 -> 0001)
                $numero = intval(substr($ultimoCodigo->codigo, 3));
                $nuevoNumero = $numero + 1;
            } else {
                $nuevoNumero = 1;
            }

            // Formatear con ceros a la izquierda (4 dígitos)
            $nuevoCodigo = 'MED' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);

            return response()->json([
                'success' => true,
                'codigo' => $nuevoCodigo
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar código: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * 💾 CREAR PRODUCTO VÍA AJAX
     */
    public function storeAjax(Request $request)
    {
        try {
            $validated = $request->validate([
                'codigo' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[A-Z0-9\-]+$/',
                    'unique:productos,codigo'
                ],
                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255'
                ],
                'descripcion' => 'nullable|string|max:1000',
                'precio_compra' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    'max:99999.99'
                ],
                'precio_venta' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    'max:99999.99',
                    'gt:precio_compra'
                ],
                'stock_actual' => [
                    'required',
                    'integer',
                    'min:0',
                    'max:99999'
                ],
                'stock_minimo' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:9999'
                ],
                'lote' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[A-Z0-9\-]+$/'
                ],
                'fecha_vencimiento' => [
                    'required',
                    'date',
                    'after:today'
                ],
                'meses_vencimiento' => 'required|in:12,18,24',
                'presentacion' => 'nullable|string|max:100',
                'principio_activo' => 'nullable|string|max:255',
                'concentracion' => 'nullable|string|max:100',
                'laboratorio' => 'nullable|string|max:255',
                'registro_sanitario' => [
                    'nullable',
                    'string',
                    'max:50',
                    'regex:/^[A-Z0-9\-]+$/'
                ],
                'requiere_receta' => 'boolean',
                'activo' => 'boolean',
                'categoria_id' => 'required|exists:categorias,id',
                'marca_id' => 'required|exists:marcas,id',
                'proveedor_id' => 'required|exists:proveedores,id'
            ]);

            // Limpiar y formatear datos
            $validated['codigo'] = strtoupper(trim($validated['codigo']));
            $validated['nombre'] = trim(ucwords(strtolower($validated['nombre'])));
            $validated['lote'] = strtoupper(trim($validated['lote']));
            $validated['registro_sanitario'] = $validated['registro_sanitario'] ? strtoupper(trim($validated['registro_sanitario'])) : null;
            $validated['presentacion'] = $validated['presentacion'] ? trim($validated['presentacion']) : null;
            $validated['principio_activo'] = $validated['principio_activo'] ? trim(ucwords(strtolower($validated['principio_activo']))) : null;
            $validated['concentracion'] = $validated['concentracion'] ? trim($validated['concentracion']) : null;
            $validated['laboratorio'] = $validated['laboratorio'] ? trim(ucwords(strtolower($validated['laboratorio']))) : null;

            $producto = Producto::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Producto creado exitosamente.',
                'producto' => $producto->load(['categoria', 'marca', 'proveedor'])
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 📝 ACTUALIZAR PRODUCTO VÍA AJAX
     */
    public function updateAjax(Request $request, Producto $producto)
    {
        try {
            $validated = $request->validate([
                'codigo' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[A-Z0-9\-]+$/',
                    Rule::unique('productos', 'codigo')->ignore($producto->id)
                ],
                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255'
                ],
                'descripcion' => 'nullable|string|max:1000',
                'precio_compra' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    'max:99999.99'
                ],
                'precio_venta' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    'max:99999.99',
                    'gt:precio_compra'
                ],
                'stock_actual' => [
                    'required',
                    'integer',
                    'min:0',
                    'max:99999'
                ],
                'stock_minimo' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:9999'
                ],
                'lote' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[A-Z0-9\-]+$/'
                ],
                'fecha_vencimiento' => [
                    'required',
                    'date',
                    'after:today'
                ],
                'meses_vencimiento' => 'required|in:12,18,24',
                'presentacion' => 'nullable|string|max:100',
                'principio_activo' => 'nullable|string|max:255',
                'concentracion' => 'nullable|string|max:100',
                'laboratorio' => 'nullable|string|max:255',
                'registro_sanitario' => [
                    'nullable',
                    'string',
                    'max:50',
                    'regex:/^[A-Z0-9\-]+$/'
                ],
                'requiere_receta' => 'boolean',
                'activo' => 'boolean',
                'categoria_id' => 'required|exists:categorias,id',
                'marca_id' => 'required|exists:marcas,id',
                'proveedor_id' => 'required|exists:proveedores,id'
            ]);

            // Limpiar y formatear datos
            $validated['codigo'] = strtoupper(trim($validated['codigo']));
            $validated['nombre'] = trim(ucwords(strtolower($validated['nombre'])));
            $validated['lote'] = strtoupper(trim($validated['lote']));
            $validated['registro_sanitario'] = $validated['registro_sanitario'] ? strtoupper(trim($validated['registro_sanitario'])) : null;
            $validated['presentacion'] = $validated['presentacion'] ? trim($validated['presentacion']) : null;
            $validated['principio_activo'] = $validated['principio_activo'] ? trim(ucwords(strtolower($validated['principio_activo']))) : null;
            $validated['concentracion'] = $validated['concentracion'] ? trim($validated['concentracion']) : null;
            $validated['laboratorio'] = $validated['laboratorio'] ? trim(ucwords(strtolower($validated['laboratorio']))) : null;

            $producto->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado exitosamente.',
                'producto' => $producto->load(['categoria', 'marca', 'proveedor'])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔍 BUSCAR PRODUCTO POR CÓDIGO VÍA AJAX
     */
    public function buscarPorCodigoAjax(Request $request)
    {
        try {
            $codigo = trim($request->get('codigo', ''));
            
            if (empty($codigo)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código de producto requerido'
                ], 400);
            }
            
            $producto = Producto::with(['categoria', 'marca'])
                ->where('activo', true)
                ->where('codigo', strtoupper($codigo))
                ->first();
            
            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado con el código: ' . $codigo
                ], 404);
            }
            
            if ($producto->stock_actual <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto sin stock disponible'
                ], 400);
            }
            
            return response()->json([
                'success' => true,
                'producto' => [
                    'id' => $producto->id,
                    'codigo' => $producto->codigo,
                    'nombre' => $producto->nombre,
                    'precio_venta' => $producto->precio_venta,
                    'stock_actual' => $producto->stock_actual,
                    'categoria' => $producto->categoria->nombre ?? 'Sin categoría',
                    'marca' => $producto->marca->nombre ?? 'Sin marca',
                    'requiere_receta' => $producto->requiere_receta
                ]
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar producto: ' . $e->getMessage()
            ], 500);
        }
    }
}
