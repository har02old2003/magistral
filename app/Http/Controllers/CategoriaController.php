<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Exception;

class CategoriaController extends Controller
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
            $categorias = Categoria::withCount('productos')->orderBy('nombre')->get();
            return view('categorias.index', compact('categorias'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar las categorías: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo administrador puede crear categorías
        if (Auth::user()->role !== 'administrador') {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        return view('categorias.create');
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
                    'unique:categorias,nombre',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-]+$/'
                ],
                'descripcion' => 'nullable|string|max:255',
                'activo' => 'boolean'
            ], [
                'nombre.required' => 'El nombre de la categoría es obligatorio.',
                'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
                'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
                'nombre.unique' => 'Ya existe una categoría con este nombre.',
                'nombre.regex' => 'El nombre solo puede contener letras, espacios, puntos y guiones.',
                'descripcion.max' => 'La descripción no puede superar los 255 caracteres.'
            ]);

            // Limpiar datos
            $validated['nombre'] = trim(ucwords(strtolower($validated['nombre'])));
            $validated['descripcion'] = $validated['descripcion'] ? trim($validated['descripcion']) : null;
            $validated['activo'] = $request->has('activo') ? true : false;

            $categoria = Categoria::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoría "' . $categoria->nombre . '" creada exitosamente.',
                    'categoria' => $categoria
                ]);
            }

            return redirect()->route('categorias.index')
                ->with('success', 'Categoría "' . $categoria->nombre . '" creada exitosamente.');

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
                    'message' => 'Error al crear la categoría: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al crear la categoría: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        try {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'categoria' => $categoria
                ]);
            }
            
            return view('categorias.show', compact('categoria'));
        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar la categoría: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al cargar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        // Solo administrador puede editar categorías
        if (Auth::user()->role !== 'administrador') {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        try {
            $validated = $request->validate([
                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    Rule::unique('categorias', 'nombre')->ignore($categoria->id),
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-]+$/'
                ],
                'descripcion' => 'nullable|string|max:255',
                'activo' => 'boolean'
            ], [
                'nombre.required' => 'El nombre de la categoría es obligatorio.',
                'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
                'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
                'nombre.unique' => 'Ya existe una categoría con este nombre.',
                'nombre.regex' => 'El nombre solo puede contener letras, espacios, puntos y guiones.',
                'descripcion.max' => 'La descripción no puede superar los 255 caracteres.'
            ]);

            // Limpiar datos
            $validated['nombre'] = trim(ucwords(strtolower($validated['nombre'])));
            $validated['descripcion'] = $validated['descripcion'] ? trim($validated['descripcion']) : null;
            $validated['activo'] = $request->has('activo') ? true : false;

            $categoria->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoría "' . $categoria->nombre . '" actualizada exitosamente.',
                    'categoria' => $categoria
                ]);
            }

            return redirect()->route('categorias.index')
                ->with('success', 'Categoría "' . $categoria->nombre . '" actualizada exitosamente.');

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
                    'message' => 'Error al actualizar la categoría: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al actualizar la categoría: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        try {
            // Verificar si tiene productos asociados
            $productosCount = $categoria->productos()->count();
            
            if ($productosCount > 0) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "No se puede eliminar la categoría '{$categoria->nombre}' porque tiene {$productosCount} producto(s) asociado(s)."
                    ], 400);
                }
                return redirect()->back()
                    ->with('error', "No se puede eliminar la categoría '{$categoria->nombre}' porque tiene {$productosCount} producto(s) asociado(s).");
            }

            $nombreCategoria = $categoria->nombre;
            $categoria->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Categoría '{$nombreCategoria}' eliminada exitosamente."
                ]);
            }

            return redirect()->route('categorias.index')
                ->with('success', "Categoría '{$nombreCategoria}' eliminada exitosamente.");

        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la categoría: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Export categorías to PDF
     */
    public function exportar()
    {
        try {
            $categorias = Categoria::withCount('productos')->orderBy('nombre')->get();
            
            $data = [
                'categorias' => $categorias,
                'fecha' => now()->format('d/m/Y'),
                'total' => $categorias->count(),
                'activas' => $categorias->where('activo', true)->count(),
                'inactivas' => $categorias->where('activo', false)->count(),
                'con_productos' => $categorias->where('productos_count', '>', 0)->count()
            ];

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('pdf.categorias', $data);
            $pdf->setPaper('A4', 'portrait');
            
            return $pdf->download('categorias_' . date('Y-m-d_H-i-s') . '.pdf');
        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al exportar categorías: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al exportar categorías: ' . $e->getMessage());
        }
    }

    /**
     * Get categorías for select options
     */
    public function selectOptions()
    {
        try {
            $categorias = Categoria::where('activo', true)->orderBy('nombre')->get(['id', 'nombre']);
            
            return response()->json([
                'success' => true,
                'categorias' => $categorias
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las categorías: ' . $e->getMessage()
            ], 500);
        }
    }
}
