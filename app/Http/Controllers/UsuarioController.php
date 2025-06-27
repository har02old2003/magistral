<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
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
        // Solo administrador puede ver usuarios
        if (Auth::user()->role !== 'administrador') {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        $usuarios = User::withCount('ventas')->orderBy('name')->paginate(15);
        
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo administrador puede crear usuarios
        if (Auth::user()->role !== 'administrador') {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Solo administrador puede crear usuarios
        if (Auth::user()->role !== 'administrador') {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para realizar esta acción.'
                ], 403);
            }
            return back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:6', // Removido 'confirmed'
                'role' => 'required|in:administrador,empleado',
            ]);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'activo' => true,
                'email_verified_at' => now(),
            ];

            $usuario = User::create($data);

            // Si es una petición AJAX, devolver JSON
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario creado exitosamente',
                    'usuario' => $usuario
                ], 201);
            }

            return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            \Log::error('Error al crear usuario: ' . $e->getMessage());
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno del servidor: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        // Solo administrador puede ver detalles de usuarios
        if (Auth::user()->role !== 'administrador') {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        $usuario->load('ventas');
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        // Solo administrador puede editar usuarios
        if (Auth::user()->role !== 'administrador') {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        // Solo administrador puede actualizar usuarios
        if (Auth::user()->role !== 'administrador') {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para realizar esta acción.'
                ], 403);
            }
            return back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $usuario->id,
                'password' => 'nullable|string|min:6', // Removido 'confirmed'
                'role' => 'required|in:administrador,empleado',
                'activo' => 'boolean',
            ]);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];
            
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            if ($request->has('activo')) {
                $data['activo'] = $request->activo;
            }

            $usuario->update($data);

            // Si es una petición AJAX, devolver JSON
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario actualizado exitosamente',
                    'usuario' => $usuario->fresh()
                ]);
            }

            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            \Log::error('Error al actualizar usuario: ' . $e->getMessage());
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno del servidor: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        // Solo administrador puede eliminar usuarios
        if (Auth::user()->role !== 'administrador') {
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para realizar esta acción.'
                ], 403);
            }
            return back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // No permitir eliminar el propio usuario
        if ($usuario->id === Auth::id()) {
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes eliminar tu propio usuario.'
                ], 400);
            }
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Verificar si el usuario tiene ventas asociadas
        if ($usuario->ventas()->count() > 0) {
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el usuario porque tiene ventas registradas.'
                ], 400);
            }
            return back()->with('error', 'No se puede eliminar el usuario porque tiene ventas registradas.');
        }

        try {
            $usuario->delete();

            // Si es una petición AJAX, devolver JSON
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario eliminado exitosamente'
                ]);
            }

            return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente');

        } catch (\Exception $e) {
            \Log::error('Error al eliminar usuario: ' . $e->getMessage());
            
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado de usuario
     */
    public function cambiarEstado(User $usuario)
    {
        // Solo administrador puede cambiar estado
        if (Auth::user()->role !== 'administrador') {
            return back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // No permitir desactivar el propio usuario
        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes desactivar tu propio usuario.');
        }

        $usuario->update(['activo' => !$usuario->activo]);

        $estado = $usuario->activo ? 'activado' : 'desactivado';
        
        return back()->with('success', "Usuario {$estado} exitosamente");
    }

    /**
     * Exportar usuarios a CSV
     */
    public function exportar()
    {
        // Solo administrador puede exportar
        if (Auth::user()->role !== 'administrador') {
            return back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        $usuarios = User::withCount('ventas')->orderBy('name')->get();
        
        $filename = 'usuarios_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($usuarios) {
            $file = fopen('php://output', 'w');
            
            // Encabezados del CSV
            fputcsv($file, [
                'ID',
                'Nombre',
                'Email',
                'Rol',
                'Ventas',
                'Estado',
                'Fecha Registro'
            ]);

            // Datos
            foreach ($usuarios as $usuario) {
                fputcsv($file, [
                    $usuario->id,
                    $usuario->name,
                    $usuario->email,
                    $usuario->role,
                    $usuario->ventas_count,
                    $usuario->activo ? 'Activo' : 'Inactivo',
                    $usuario->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
