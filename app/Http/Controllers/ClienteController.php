<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exception;

class ClienteController extends Controller
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
            $clientes = Cliente::orderBy('nombres')->get();

            // Estadísticas básicas (sin consultas complejas)
            $totalClientes = Cliente::count();
            $clientesActivos = Cliente::where('activo', true)->count();
            $clientesNuevos = Cliente::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();
            
            // Clientes VIP - cálculo simple sin GROUP BY problemático
            $clientesVip = 0; // Por ahora lo dejamos en 0 para evitar errores SQL

            return view('clientes.index', compact(
                'clientes',
                'totalClientes',
                'clientesActivos', 
                'clientesNuevos',
                'clientesVip'
            ));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar los clientes: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombres' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
                ],
                'apellidos' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
                ],
                'documento' => [
                    'required',
                    'string',
                    'unique:clientes,documento',
                    'regex:/^[0-9]{8,11}$/'
                ],
                'tipo_documento' => 'required|in:DNI,CE,RUC',
                'telefono' => [
                    'nullable',
                    'string',
                    'regex:/^[0-9]{7,15}$/'
                ],
                'email' => [
                    'nullable',
                    'email',
                    'unique:clientes,email'
                ],
                'direccion' => 'nullable|string|max:255',
                'fecha_nacimiento' => 'nullable|date|before:today',
                'activo' => 'boolean'
            ], [
                'nombres.required' => 'Los nombres son obligatorios.',
                'nombres.regex' => 'Los nombres solo pueden contener letras y espacios.',
                'apellidos.required' => 'Los apellidos son obligatorios.',
                'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
                'documento.required' => 'El documento es obligatorio.',
                'documento.unique' => 'Ya existe un cliente con este documento.',
                'documento.regex' => 'El documento debe tener entre 8 y 11 dígitos.',
                'tipo_documento.required' => 'El tipo de documento es obligatorio.',
                'telefono.regex' => 'El teléfono debe tener entre 7 y 15 dígitos.',
                'email.email' => 'El email debe tener un formato válido.',
                'email.unique' => 'Ya existe un cliente con este email.',
                'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.'
            ]);

            // Validaciones específicas por tipo de documento
            if ($validated['tipo_documento'] === 'DNI' && strlen($validated['documento']) !== 8) {
                throw new \Illuminate\Validation\ValidationException(validator([], []), [
                    'documento' => ['El DNI debe tener exactamente 8 dígitos.']
                ]);
            }
            
            if ($validated['tipo_documento'] === 'CE' && strlen($validated['documento']) !== 9) {
                throw new \Illuminate\Validation\ValidationException(validator([], []), [
                    'documento' => ['El CE debe tener exactamente 9 dígitos.']
                ]);
            }
            
            if ($validated['tipo_documento'] === 'RUC' && strlen($validated['documento']) !== 11) {
                throw new \Illuminate\Validation\ValidationException(validator([], []), [
                    'documento' => ['El RUC debe tener exactamente 11 dígitos.']
                ]);
            }

            // Limpiar datos
            $validated['nombres'] = trim(ucwords(strtolower($validated['nombres'])));
            $validated['apellidos'] = trim(ucwords(strtolower($validated['apellidos'])));
            $validated['email'] = $validated['email'] ? strtolower(trim($validated['email'])) : null;
            $validated['direccion'] = $validated['direccion'] ? trim($validated['direccion']) : null;
            $validated['activo'] = $request->has('activo') ? true : false;

            $cliente = Cliente::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cliente "' . $cliente->nombres . ' ' . $cliente->apellidos . '" creado exitosamente.',
                    'cliente' => $cliente
                ]);
            }

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente "' . $cliente->nombres . ' ' . $cliente->apellidos . '" creado exitosamente.');

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
                    'message' => 'Error al crear el cliente: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al crear el cliente: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        try {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'cliente' => $cliente
                ]);
            }
            
            return view('clientes.show', compact('cliente'));
        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar el cliente: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al cargar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        try {
            $validated = $request->validate([
                'nombres' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
                ],
                'apellidos' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
                ],
                'documento' => [
                    'required',
                    'string',
                    Rule::unique('clientes', 'documento')->ignore($cliente->id),
                    'regex:/^[0-9]{8,11}$/'
                ],
                'tipo_documento' => 'required|in:DNI,CE,RUC',
                'telefono' => [
                    'nullable',
                    'string',
                    'regex:/^[0-9]{7,15}$/'
                ],
                'email' => [
                    'nullable',
                    'email',
                    Rule::unique('clientes', 'email')->ignore($cliente->id)
                ],
                'direccion' => 'nullable|string|max:255',
                'fecha_nacimiento' => 'nullable|date|before:today',
                'activo' => 'boolean'
            ], [
                'nombres.required' => 'Los nombres son obligatorios.',
                'nombres.regex' => 'Los nombres solo pueden contener letras y espacios.',
                'apellidos.required' => 'Los apellidos son obligatorios.',
                'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
                'documento.required' => 'El documento es obligatorio.',
                'documento.unique' => 'Ya existe un cliente con este documento.',
                'documento.regex' => 'El documento debe tener entre 8 y 11 dígitos.',
                'tipo_documento.required' => 'El tipo de documento es obligatorio.',
                'telefono.regex' => 'El teléfono debe tener entre 7 y 15 dígitos.',
                'email.email' => 'El email debe tener un formato válido.',
                'email.unique' => 'Ya existe un cliente con este email.',
                'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.'
            ]);

            // Validaciones específicas por tipo de documento
            if ($validated['tipo_documento'] === 'DNI' && strlen($validated['documento']) !== 8) {
                throw new \Illuminate\Validation\ValidationException(validator([], []), [
                    'documento' => ['El DNI debe tener exactamente 8 dígitos.']
                ]);
            }
            
            if ($validated['tipo_documento'] === 'CE' && strlen($validated['documento']) !== 9) {
                throw new \Illuminate\Validation\ValidationException(validator([], []), [
                    'documento' => ['El CE debe tener exactamente 9 dígitos.']
                ]);
            }
            
            if ($validated['tipo_documento'] === 'RUC' && strlen($validated['documento']) !== 11) {
                throw new \Illuminate\Validation\ValidationException(validator([], []), [
                    'documento' => ['El RUC debe tener exactamente 11 dígitos.']
                ]);
            }

            // Limpiar datos
            $validated['nombres'] = trim(ucwords(strtolower($validated['nombres'])));
            $validated['apellidos'] = trim(ucwords(strtolower($validated['apellidos'])));
            $validated['email'] = $validated['email'] ? strtolower(trim($validated['email'])) : null;
            $validated['direccion'] = $validated['direccion'] ? trim($validated['direccion']) : null;
            $validated['activo'] = $request->has('activo') ? true : false;

            $cliente->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cliente "' . $cliente->nombres . ' ' . $cliente->apellidos . '" actualizado exitosamente.',
                    'cliente' => $cliente
                ]);
            }

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente "' . $cliente->nombres . ' ' . $cliente->apellidos . '" actualizado exitosamente.');

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
                    'message' => 'Error al actualizar el cliente: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al actualizar el cliente: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        try {
            // Verificar si tiene ventas asociadas
            $ventasCount = $cliente->ventas()->count();
            
            if ($ventasCount > 0) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "No se puede eliminar el cliente '{$cliente->nombres} {$cliente->apellidos}' porque tiene {$ventasCount} venta(s) asociada(s)."
                    ], 400);
                }
                return redirect()->back()
                    ->with('error', "No se puede eliminar el cliente '{$cliente->nombres} {$cliente->apellidos}' porque tiene {$ventasCount} venta(s) asociada(s).");
            }

            $nombreCliente = $cliente->nombres . ' ' . $cliente->apellidos;
            $cliente->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Cliente '{$nombreCliente}' eliminado exitosamente."
                ]);
            }

            return redirect()->route('clientes.index')
                ->with('success', "Cliente '{$nombreCliente}' eliminado exitosamente.");

        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el cliente: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al eliminar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Export clientes to PDF
     */
    public function exportar()
    {
        try {
            $clientes = Cliente::orderBy('nombres')->get();
            
            $data = [
                'clientes' => $clientes,
                'fecha' => now()->format('d/m/Y'),
                'total' => $clientes->count(),
                'activos' => $clientes->where('activo', true)->count(),
                'inactivos' => $clientes->where('activo', false)->count(),
                'con_email' => $clientes->whereNotNull('email')->count()
            ];

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('pdf.clientes', $data);
            $pdf->setPaper('A4', 'landscape');
            
            return $pdf->download('clientes_' . date('Y-m-d_H-i-s') . '.pdf');
        } catch (Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al exportar clientes: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al exportar clientes: ' . $e->getMessage());
        }
    }

    /**
     * Get clientes for select options
     */
    public function selectOptions()
    {
        try {
            $clientes = Cliente::where('activo', true)
                ->orderBy('nombres')
                ->get()
                ->map(function ($cliente) {
                    return [
                        'id' => $cliente->id,
                        'nombre_completo' => $cliente->nombres . ' ' . $cliente->apellidos,
                        'documento' => $cliente->documento
                    ];
                });
            
            return response()->json([
                'success' => true,
                'clientes' => $clientes
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los clientes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 💾 CREAR CLIENTE VÍA AJAX
     */
    public function storeAjax(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombres' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
                ],
                'apellidos' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
                ],
                'documento' => [
                    'required',
                    'string',
                    'unique:clientes,documento',
                    'regex:/^[0-9]{8,11}$/'
                ],
                'tipo_documento' => 'required|in:DNI,CE,RUC',
                'telefono' => [
                    'nullable',
                    'string',
                    'regex:/^[0-9]{7,15}$/'
                ],
                'email' => [
                    'nullable',
                    'email',
                    'unique:clientes,email'
                ],
                'direccion' => 'nullable|string|max:255',
                'fecha_nacimiento' => 'nullable|date|before:today'
            ], [
                'nombres.required' => 'Los nombres son obligatorios.',
                'nombres.regex' => 'Los nombres solo pueden contener letras y espacios.',
                'apellidos.required' => 'Los apellidos son obligatorios.',
                'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
                'documento.required' => 'El documento es obligatorio.',
                'documento.unique' => 'Ya existe un cliente con este documento.',
                'documento.regex' => 'El documento debe tener entre 8 y 11 dígitos.',
                'tipo_documento.required' => 'El tipo de documento es obligatorio.',
                'telefono.regex' => 'El teléfono debe tener entre 7 y 15 dígitos.',
                'email.email' => 'El email debe tener un formato válido.',
                'email.unique' => 'Ya existe un cliente con este email.',
                'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.'
            ]);

            // Validaciones específicas por tipo de documento
            if ($validated['tipo_documento'] === 'DNI' && strlen($validated['documento']) !== 8) {
                return response()->json([
                    'success' => false,
                    'message' => 'El DNI debe tener exactamente 8 dígitos.',
                    'errors' => ['documento' => ['El DNI debe tener exactamente 8 dígitos.']]
                ], 422);
            }
            
            if ($validated['tipo_documento'] === 'CE' && strlen($validated['documento']) !== 9) {
                return response()->json([
                    'success' => false,
                    'message' => 'El CE debe tener exactamente 9 dígitos.',
                    'errors' => ['documento' => ['El CE debe tener exactamente 9 dígitos.']]
                ], 422);
            }
            
            if ($validated['tipo_documento'] === 'RUC' && strlen($validated['documento']) !== 11) {
                return response()->json([
                    'success' => false,
                    'message' => 'El RUC debe tener exactamente 11 dígitos.',
                    'errors' => ['documento' => ['El RUC debe tener exactamente 11 dígitos.']]
                ], 422);
            }

            // Limpiar datos
            $validated['nombres'] = trim(ucwords(strtolower($validated['nombres'])));
            $validated['apellidos'] = trim(ucwords(strtolower($validated['apellidos'])));
            $validated['email'] = $validated['email'] ? strtolower(trim($validated['email'])) : null;
            $validated['direccion'] = $validated['direccion'] ? trim($validated['direccion']) : null;
            $validated['activo'] = true; // Los nuevos clientes se crean activos por defecto

            $cliente = Cliente::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cliente "' . $cliente->nombres . ' ' . $cliente->apellidos . '" creado exitosamente.',
                'cliente' => [
                    'id' => $cliente->id,
                    'nombres' => $cliente->nombres,
                    'apellidos' => $cliente->apellidos,
                    'nombre_completo' => $cliente->nombres . ' ' . $cliente->apellidos,
                    'documento' => $cliente->documento,
                    'tipo_documento' => $cliente->tipo_documento,
                    'telefono' => $cliente->telefono,
                    'email' => $cliente->email,
                    'direccion' => $cliente->direccion,
                    'fecha_nacimiento' => $cliente->fecha_nacimiento,
                    'activo' => $cliente->activo
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
                'message' => 'Error al crear el cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 📝 ACTUALIZAR CLIENTE VÍA AJAX
     */
    public function updateAjax(Request $request, Cliente $cliente)
    {
        try {
            $validated = $request->validate([
                'nombres' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
                ],
                'apellidos' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
                ],
                'documento' => [
                    'required',
                    'string',
                    Rule::unique('clientes', 'documento')->ignore($cliente->id),
                    'regex:/^[0-9]{8,11}$/'
                ],
                'tipo_documento' => 'required|in:DNI,CE,RUC',
                'telefono' => [
                    'nullable',
                    'string',
                    'regex:/^[0-9]{7,15}$/'
                ],
                'email' => [
                    'nullable',
                    'email',
                    Rule::unique('clientes', 'email')->ignore($cliente->id)
                ],
                'direccion' => 'nullable|string|max:255',
                'fecha_nacimiento' => 'nullable|date|before:today',
                'activo' => 'boolean'
            ]);

            // Limpiar datos
            $validated['nombres'] = trim(ucwords(strtolower($validated['nombres'])));
            $validated['apellidos'] = trim(ucwords(strtolower($validated['apellidos'])));
            $validated['email'] = $validated['email'] ? strtolower(trim($validated['email'])) : null;
            $validated['direccion'] = $validated['direccion'] ? trim($validated['direccion']) : null;

            $cliente->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cliente "' . $cliente->nombres . ' ' . $cliente->apellidos . '" actualizado exitosamente.',
                'cliente' => $cliente->fresh()
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
                'message' => 'Error al actualizar el cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔍 BUSCAR CLIENTES VÍA AJAX
     */
    public function buscarAjax(Request $request)
    {
        try {
            $termino = trim($request->get('q', ''));
            
            if (empty($termino)) {
                return response()->json([
                    'success' => true,
                    'clientes' => []
                ]);
            }
            
            $clientes = Cliente::where('activo', true)
                ->where(function($query) use ($termino) {
                    $query->where('nombres', 'like', "%{$termino}%")
                          ->orWhere('apellidos', 'like', "%{$termino}%")
                          ->orWhere('documento', 'like', "%{$termino}%")
                          ->orWhere('telefono', 'like', "%{$termino}%")
                          ->orWhere('email', 'like', "%{$termino}%");
                })
                ->orderBy('nombres')
                ->limit(15)
                ->get();
            
            return response()->json([
                'success' => true,
                'clientes' => $clientes->map(function($cliente) {
                    return [
                        'id' => $cliente->id,
                        'nombres' => $cliente->nombres,
                        'apellidos' => $cliente->apellidos,
                        'nombre_completo' => $cliente->nombres . ' ' . $cliente->apellidos,
                        'documento' => $cliente->documento,
                        'tipo_documento' => $cliente->tipo_documento,
                        'telefono' => $cliente->telefono,
                        'email' => $cliente->email,
                        'direccion' => $cliente->direccion,
                        'fecha_nacimiento' => $cliente->fecha_nacimiento,
                        'activo' => $cliente->activo
                    ];
                })
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar clientes: ' . $e->getMessage()
            ], 500);
        }
    }
}
