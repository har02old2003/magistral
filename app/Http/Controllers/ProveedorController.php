<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exception;

class ProveedorController extends Controller
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
            $proveedores = Proveedor::withCount('productos')->orderBy('nombre')->get();
            return view('proveedores.index', compact('proveedores'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar los proveedores: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
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
                    'max:255',
                    'unique:proveedores,nombre'
                ],
                'ruc' => [
                    'required',
                    'string',
                    'regex:/^[0-9]{11}$/',
                    'unique:proveedores,ruc'
                ],
                'telefono' => [
                    'nullable',
                    'string',
                    'regex:/^[0-9]{7,15}$/'
                ],
                'email' => [
                    'nullable',
                    'email',
                    'unique:proveedores,email'
                ],
                'direccion' => 'nullable|string|max:500',
                'contacto' => [
                    'nullable',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
                ],
                'activo' => 'boolean'
            ], [
                'nombre.required' => 'El nombre de la empresa es obligatorio.',
                'nombre.unique' => 'Ya existe un proveedor con este nombre.',
                'ruc.required' => 'El RUC es obligatorio.',
                'ruc.regex' => 'El RUC debe tener exactamente 11 dígitos.',
                'ruc.unique' => 'Ya existe un proveedor con este RUC.',
                'telefono.regex' => 'El teléfono debe tener entre 7 y 15 dígitos.',
                'email.email' => 'El email debe tener un formato válido.',
                'email.unique' => 'Ya existe un proveedor con este email.',
                'contacto.regex' => 'El contacto solo puede contener letras y espacios.'
            ]);

            // Limpiar datos
            $validated['nombre'] = trim(ucwords(strtolower($validated['nombre'])));
            $validated['email'] = $validated['email'] ? strtolower(trim($validated['email'])) : null;
            $validated['direccion'] = $validated['direccion'] ? trim($validated['direccion']) : null;
            $validated['contacto'] = $validated['contacto'] ? trim(ucwords(strtolower($validated['contacto']))) : null;
            $validated['activo'] = $request->has('activo') ? true : false;

            $proveedor = Proveedor::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proveedor "' . $proveedor->nombre . '" creado exitosamente.',
                    'proveedor' => $proveedor
                ]);
            }

            return redirect()->route('proveedores.index')
                ->with('success', 'Proveedor "' . $proveedor->nombre . '" creado exitosamente.');

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
                    'message' => 'Error al crear el proveedor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al crear el proveedor: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        try {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'proveedor' => $proveedor
                ]);
            }
            
            return view('proveedores.show', compact('proveedor'));
        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar el proveedor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al cargar el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        try {
            $validated = $request->validate([
                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255',
                    Rule::unique('proveedores', 'nombre')->ignore($proveedor->id)
                ],
                'ruc' => [
                    'required',
                    'string',
                    'regex:/^[0-9]{11}$/',
                    Rule::unique('proveedores', 'ruc')->ignore($proveedor->id)
                ],
                'telefono' => [
                    'nullable',
                    'string',
                    'regex:/^[0-9]{7,15}$/'
                ],
                'email' => [
                    'nullable',
                    'email',
                    Rule::unique('proveedores', 'email')->ignore($proveedor->id)
                ],
                'direccion' => 'nullable|string|max:500',
                'contacto' => [
                    'nullable',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
                ],
                'activo' => 'boolean'
            ], [
                'nombre.required' => 'El nombre de la empresa es obligatorio.',
                'nombre.unique' => 'Ya existe un proveedor con este nombre.',
                'ruc.required' => 'El RUC es obligatorio.',
                'ruc.regex' => 'El RUC debe tener exactamente 11 dígitos.',
                'ruc.unique' => 'Ya existe un proveedor con este RUC.',
                'telefono.regex' => 'El teléfono debe tener entre 7 y 15 dígitos.',
                'email.email' => 'El email debe tener un formato válido.',
                'email.unique' => 'Ya existe un proveedor con este email.',
                'contacto.regex' => 'El contacto solo puede contener letras y espacios.'
            ]);

            // Limpiar datos
            $validated['nombre'] = trim(ucwords(strtolower($validated['nombre'])));
            $validated['email'] = $validated['email'] ? strtolower(trim($validated['email'])) : null;
            $validated['direccion'] = $validated['direccion'] ? trim($validated['direccion']) : null;
            $validated['contacto'] = $validated['contacto'] ? trim(ucwords(strtolower($validated['contacto']))) : null;
            $validated['activo'] = $request->has('activo') ? true : false;

            $proveedor->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proveedor "' . $proveedor->nombre . '" actualizado exitosamente.',
                    'proveedor' => $proveedor
                ]);
            }

            return redirect()->route('proveedores.index')
                ->with('success', 'Proveedor "' . $proveedor->nombre . '" actualizado exitosamente.');

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
                    'message' => 'Error al actualizar el proveedor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al actualizar el proveedor: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        try {
            // Verificar si tiene productos asociados
            $productosCount = $proveedor->productos()->count();
            
            if ($productosCount > 0) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "No se puede eliminar el proveedor '{$proveedor->nombre}' porque tiene {$productosCount} producto(s) asociado(s)."
                    ], 400);
                }
                return redirect()->back()
                    ->with('error', "No se puede eliminar el proveedor '{$proveedor->nombre}' porque tiene {$productosCount} producto(s) asociado(s).");
            }

            $nombreProveedor = $proveedor->nombre;
            $proveedor->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Proveedor '{$nombreProveedor}' eliminado exitosamente."
                ]);
            }

            return redirect()->route('proveedores.index')
                ->with('success', "Proveedor '{$nombreProveedor}' eliminado exitosamente.");

        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el proveedor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al eliminar el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Export proveedores to PDF
     */
    public function exportar()
    {
        try {
            $proveedores = Proveedor::withCount('productos')->orderBy('nombre')->get();
            
            $data = [
                'proveedores' => $proveedores,
                'fecha' => now()->format('d/m/Y'),
                'total' => $proveedores->count(),
                'activos' => $proveedores->where('activo', true)->count(),
                'inactivos' => $proveedores->where('activo', false)->count(),
                'con_productos' => $proveedores->where('productos_count', '>', 0)->count()
            ];

            // Crear vista PDF simple
            $html = '<!DOCTYPE html>
            <html><head><meta charset="UTF-8"><title>Proveedores</title>
            <style>body{font-family:Arial;font-size:11px;}table{width:100%;border-collapse:collapse;}th{background:#667eea;color:white;padding:8px;}td{padding:6px;border-bottom:1px solid #ddd;}</style>
            </head><body>
            <h1 style="text-align:center;color:#667eea;">🚚 FARMACIA MAGISTRAL - PROVEEDORES</h1>
            <p style="text-align:center;">Generado el ' . $data['fecha'] . '</p>
            <table>
            <thead><tr><th>Empresa</th><th>RUC</th><th>Contacto</th><th>Teléfono</th><th>Email</th><th>Estado</th></tr></thead>
            <tbody>';
            
            foreach($proveedores as $proveedor) {
                $estado = $proveedor->activo ? '<span style="background:#28a745;color:white;padding:2px 4px;">Activo</span>' : '<span style="background:#dc3545;color:white;padding:2px 4px;">Inactivo</span>';
                $html .= '<tr>
                    <td><strong>' . $proveedor->nombre . '</strong></td>
                    <td>' . $proveedor->ruc . '</td>
                    <td>' . ($proveedor->contacto ?? 'N/A') . '</td>
                    <td>' . ($proveedor->telefono ?? 'N/A') . '</td>
                    <td>' . ($proveedor->email ?? 'N/A') . '</td>
                    <td>' . $estado . '</td>
                </tr>';
            }
            
            $html .= '</tbody></table>
            <div style="margin-top:20px;text-align:center;font-size:9px;">
            <p>Farmacia Magistral - ' . now()->format('d/m/Y H:i:s') . '</p>
            </div></body></html>';

            $pdf = app('dompdf.wrapper');
            $pdf->loadHTML($html);
            $pdf->setPaper('A4', 'landscape');
            
            return $pdf->download('proveedores_' . date('Y-m-d_H-i-s') . '.pdf');
        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al exportar proveedores: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al exportar proveedores: ' . $e->getMessage());
        }
    }

    /**
     * Get proveedores for select options
     */
    public function selectOptions()
    {
        try {
            $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get(['id', 'nombre', 'ruc']);
            
            return response()->json([
                'success' => true,
                'proveedores' => $proveedores
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los proveedores: ' . $e->getMessage()
            ], 500);
        }
    }
}
