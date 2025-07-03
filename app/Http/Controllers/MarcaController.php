<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Exception;

class MarcaController extends Controller
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
            $marcas = Marca::withCount('productos')->orderBy('nombre')->get();
            return view('marcas.index', compact('marcas'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar las marcas: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marcas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    'unique:marcas,nombre',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-]+$/'
                ],
                'descripcion' => 'nullable|string|max:255',
                'activo' => 'boolean'
            ], [
                'nombre.required' => 'El nombre de la marca es obligatorio.',
                'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
                'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
                'nombre.unique' => 'Ya existe una marca con este nombre.',
                'nombre.regex' => 'El nombre solo puede contener letras, espacios, puntos y guiones.',
                'descripcion.max' => 'La descripción no puede superar los 255 caracteres.'
            ]);

            // Limpiar datos
            $validated['nombre'] = trim(ucwords(strtolower($validated['nombre'])));
            $validated['descripcion'] = $validated['descripcion'] ? trim($validated['descripcion']) : null;
            $validated['activo'] = $request->has('activo') ? true : false;

            $marca = Marca::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Marca "' . $marca->nombre . '" creada exitosamente.',
                    'marca' => $marca
                ]);
            }

            return redirect()->route('marcas.index')
                ->with('success', 'Marca "' . $marca->nombre . '" creada exitosamente.');

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
                    'message' => 'Error al crear la marca: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al crear la marca: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Marca $marca)
    {
        try {
            $marca->load('productos');
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'marca' => $marca
                ]);
            }
            
            return view('marcas.show', compact('marca'));
        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar la marca: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al cargar la marca: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        return view('marcas.edit', compact('marca'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca)
    {
        try {
            $validated = $request->validate([
                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    Rule::unique('marcas', 'nombre')->ignore($marca->id),
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-]+$/'
                ],
                'descripcion' => 'nullable|string|max:255',
                'activo' => 'boolean'
            ], [
                'nombre.required' => 'El nombre de la marca es obligatorio.',
                'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
                'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
                'nombre.unique' => 'Ya existe una marca con este nombre.',
                'nombre.regex' => 'El nombre solo puede contener letras, espacios, puntos y guiones.',
                'descripcion.max' => 'La descripción no puede superar los 255 caracteres.'
            ]);

            // Limpiar datos
            $validated['nombre'] = trim(ucwords(strtolower($validated['nombre'])));
            $validated['descripcion'] = $validated['descripcion'] ? trim($validated['descripcion']) : null;
            $validated['activo'] = $request->has('activo') ? true : false;

            $marca->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Marca "' . $marca->nombre . '" actualizada exitosamente.',
                    'marca' => $marca
                ]);
            }

            return redirect()->route('marcas.index')
                ->with('success', 'Marca "' . $marca->nombre . '" actualizada exitosamente.');

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
                    'message' => 'Error al actualizar la marca: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al actualizar la marca: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        try {
            // Verificar si tiene productos asociados
            $productosCount = $marca->productos()->count();
            
            if ($productosCount > 0) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "No se puede eliminar la marca '{$marca->nombre}' porque tiene {$productosCount} producto(s) asociado(s)."
                    ], 400);
                }
                return redirect()->back()
                    ->with('error', "No se puede eliminar la marca '{$marca->nombre}' porque tiene {$productosCount} producto(s) asociado(s).");
            }

            $nombreMarca = $marca->nombre;
            $marca->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Marca '{$nombreMarca}' eliminada exitosamente."
                ]);
            }

            return redirect()->route('marcas.index')
                ->with('success', "Marca '{$nombreMarca}' eliminada exitosamente.");

        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la marca: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al eliminar la marca: ' . $e->getMessage());
        }
    }

    /**
     * Export marcas to PDF
     */
    public function exportar()
    {
        try {
            $marcas = Marca::withCount('productos')->orderBy('nombre')->get();
            
            $data = [
                'marcas' => $marcas,
                'fecha' => now()->format('d/m/Y'),
                'total' => $marcas->count(),
                'activas' => $marcas->where('activo', true)->count(),
                'inactivas' => $marcas->where('activo', false)->count(),
                'con_productos' => $marcas->where('productos_count', '>', 0)->count()
            ];

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('pdf.marcas', $data);
            $pdf->setPaper('A4', 'portrait');
            
            return $pdf->download('marcas_' . date('Y-m-d_H-i-s') . '.pdf');
        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al exportar marcas: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al exportar marcas: ' . $e->getMessage());
        }
    }

    /**
     * Get marcas for select options
     */
    public function selectOptions()
    {
        try {
            $marcas = Marca::where('activo', true)->orderBy('nombre')->get(['id', 'nombre']);
            
            return response()->json([
                'success' => true,
                'marcas' => $marcas
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las marcas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate automatic brand code
     */
    public function generarCodigo()
    {
        try {
            // Obtener el último código de marca
            $ultimoCodigo = Marca::where('codigo', 'LIKE', 'MAR%')
                ->orderBy('codigo', 'desc')
                ->first();

            if ($ultimoCodigo) {
                // Extraer el número del código (ej: MAR0001 -> 0001)
                $numero = intval(substr($ultimoCodigo->codigo, 3));
                $nuevoNumero = $numero + 1;
            } else {
                $nuevoNumero = 1;
            }

            // Formatear con ceros a la izquierda (4 dígitos)
            $nuevoCodigo = 'MAR' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);

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
     * 💾 CREAR MARCA VÍA AJAX
     */
    public function storeAjax(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    'unique:marcas,nombre',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-]+$/'
                ],
                'descripcion' => 'nullable|string|max:255',
                'activo' => 'boolean'
            ], [
                'nombre.required' => 'El nombre de la marca es obligatorio.',
                'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
                'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
                'nombre.unique' => 'Ya existe una marca con este nombre.',
                'nombre.regex' => 'El nombre solo puede contener letras, espacios, puntos y guiones.',
                'descripcion.max' => 'La descripción no puede superar los 255 caracteres.'
            ]);

            // Limpiar datos
            $validated['nombre'] = trim(ucwords(strtolower($validated['nombre'])));
            $validated['descripcion'] = $validated['descripcion'] ? trim($validated['descripcion']) : null;
            $validated['activo'] = $request->has('activo') ? true : false;

            $marca = Marca::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Marca "' . $marca->nombre . '" creada exitosamente.',
                'marca' => [
                    'id' => $marca->id,
                    'nombre' => $marca->nombre,
                    'descripcion' => $marca->descripcion,
                    'activo' => $marca->activo,
                    'productos_count' => 0
                ]
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
                'message' => 'Error al crear la marca: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 📝 ACTUALIZAR MARCA VÍA AJAX
     */
    public function updateAjax(Request $request, Marca $marca)
    {
        try {
            $validated = $request->validate([
                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    Rule::unique('marcas', 'nombre')->ignore($marca->id),
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-]+$/'
                ],
                'descripcion' => 'nullable|string|max:255',
                'activo' => 'boolean'
            ], [
                'nombre.required' => 'El nombre de la marca es obligatorio.',
                'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
                'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
                'nombre.unique' => 'Ya existe una marca con este nombre.',
                'nombre.regex' => 'El nombre solo puede contener letras, espacios, puntos y guiones.',
                'descripcion.max' => 'La descripción no puede superar los 255 caracteres.'
            ]);

            // Limpiar datos
            $validated['nombre'] = trim(ucwords(strtolower($validated['nombre'])));
            $validated['descripcion'] = $validated['descripcion'] ? trim($validated['descripcion']) : null;
            $validated['activo'] = $request->has('activo') ? true : false;

            $marca->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Marca "' . $marca->nombre . '" actualizada exitosamente.',
                'marca' => $marca->fresh()
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
                'message' => 'Error al actualizar la marca: ' . $e->getMessage()
            ], 500);
        }
    }
}
