<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laboratorio;
use App\Models\PasoLaboratorio;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class LaboratorioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Laboratorio::with(['usuario', 'producto', 'pasos'])
                               ->orderBy('created_at', 'desc');

            // Filtros
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->filled('buscar')) {
                $buscar = $request->buscar;
                $query->where(function($q) use ($buscar) {
                    $q->where('nombre_medicamento', 'like', "%{$buscar}%")
                      ->orWhere('numero_lote', 'like', "%{$buscar}%")
                      ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            }

            if ($request->filled('fecha_desde')) {
                $query->whereDate('created_at', '>=', $request->fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereDate('created_at', '<=', $request->fecha_hasta);
            }

            $laboratorios = $query->paginate(15);

            // Estadísticas
            $estadisticas = [
                'total' => Laboratorio::count(),
                'en_proceso' => Laboratorio::where('estado', Laboratorio::ESTADO_EN_PROCESO)->count(),
                'completados' => Laboratorio::where('estado', Laboratorio::ESTADO_COMPLETADO)->count(),
                'borradores' => Laboratorio::where('estado', Laboratorio::ESTADO_BORRADOR)->count(),
                'hoy' => Laboratorio::whereDate('created_at', today())->count(),
                'tiempo_promedio' => $this->calcularTiempoPromedio()
            ];

            return view('laboratorio.index', compact('laboratorios', 'estadisticas'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar laboratorios: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $productos = Producto::where('activo', true)->orderBy('nombre')->get();
            
            return view('laboratorio.create', compact('productos'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre_medicamento' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:1000',
                'formula_quimica' => 'nullable|string|max:255',
                'instrucciones_generales' => 'nullable|string|max:1000',
                'cantidad_producir' => 'required|integer|min:1',
                'unidad_medida' => 'required|string|max:50',
                'temperatura_optima' => 'nullable|numeric|min:-50|max:200',
                'tiempo_fabricacion_minutos' => 'nullable|integer|min:1',
                'equipos_requeridos' => 'nullable|string|max:255',
                'precauciones_seguridad' => 'nullable|string|max:1000',
                'producto_id' => 'nullable|exists:productos,id',
                'pasos' => 'required|array|min:1',
                'pasos.*.titulo_paso' => 'required|string|max:255',
                'pasos.*.descripcion_paso' => 'required|string|max:1000',
                'pasos.*.instrucciones_detalladas' => 'nullable|string|max:1000',
                'pasos.*.tiempo_estimado_minutos' => 'nullable|integer|min:1',
                'pasos.*.equipos_necesarios' => 'nullable|string|max:255',
                'pasos.*.materiales_requeridos' => 'nullable|string|max:1000',
                'pasos.*.observaciones' => 'nullable|string|max:1000'
            ], [
                'nombre_medicamento.required' => 'El nombre del medicamento es obligatorio.',
                'cantidad_producir.required' => 'La cantidad a producir es obligatoria.',
                'cantidad_producir.min' => 'La cantidad debe ser mayor a 0.',
                'pasos.required' => 'Debe agregar al menos un paso al proceso.',
                'pasos.min' => 'Debe agregar al menos un paso al proceso.'
            ]);

            DB::beginTransaction();

            // Crear laboratorio
            $laboratorio = Laboratorio::create([
                'numero_lote' => Laboratorio::generarNumeroLote(),
                'nombre_medicamento' => $validated['nombre_medicamento'],
                'descripcion' => $validated['descripcion'],
                'formula_quimica' => $validated['formula_quimica'],
                'instrucciones_generales' => $validated['instrucciones_generales'],
                'cantidad_producir' => $validated['cantidad_producir'],
                'unidad_medida' => $validated['unidad_medida'],
                'temperatura_optima' => $validated['temperatura_optima'],
                'tiempo_fabricacion_minutos' => $validated['tiempo_fabricacion_minutos'],
                'equipos_requeridos' => $validated['equipos_requeridos'],
                'precauciones_seguridad' => $validated['precauciones_seguridad'],
                'estado' => Laboratorio::ESTADO_BORRADOR,
                'usuario_id' => Auth::id(),
                'producto_id' => $validated['producto_id']
            ]);

            // Crear pasos del laboratorio
            foreach ($request->pasos as $index => $paso) {
                PasoLaboratorio::create([
                    'laboratorio_id' => $laboratorio->id,
                    'orden_paso' => $index + 1,
                    'titulo_paso' => $paso['titulo_paso'],
                    'descripcion_paso' => $paso['descripcion_paso'],
                    'instrucciones_detalladas' => $paso['instrucciones_detalladas'] ?? null,
                    'tiempo_estimado_minutos' => $paso['tiempo_estimado_minutos'] ?? null,
                    'equipos_necesarios' => $paso['equipos_necesarios'] ?? null,
                    'materiales_requeridos' => $paso['materiales_requeridos'] ?? null,
                    'observaciones' => $paso['observaciones'] ?? null
                ]);
            }

            DB::commit();

            return redirect()->route('laboratorio.show', $laboratorio)
                           ->with('success', 'Laboratorio creado exitosamente.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear laboratorio: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Laboratorio $laboratorio)
    {
        try {
            $laboratorio->load(['usuario', 'producto', 'pasos.usuarioCompleto']);

            return view('laboratorio.show', compact('laboratorio'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar laboratorio: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Laboratorio $laboratorio)
    {
        try {
            if ($laboratorio->estado !== Laboratorio::ESTADO_BORRADOR) {
                return back()->with('error', 'Solo se pueden editar laboratorios en estado borrador.');
            }

            $productos = Producto::where('activo', true)->orderBy('nombre')->get();
            $laboratorio->load('pasos');

            return view('laboratorio.edit', compact('laboratorio', 'productos'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Laboratorio $laboratorio)
    {
        try {
            if ($laboratorio->estado !== Laboratorio::ESTADO_BORRADOR) {
                return back()->with('error', 'Solo se pueden editar laboratorios en estado borrador.');
            }

            $validated = $request->validate([
                'nombre_medicamento' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:1000',
                'formula_quimica' => 'nullable|string|max:255',
                'instrucciones_generales' => 'nullable|string|max:1000',
                'cantidad_producir' => 'required|integer|min:1',
                'unidad_medida' => 'required|string|max:50',
                'temperatura_optima' => 'nullable|numeric|min:-50|max:200',
                'tiempo_fabricacion_minutos' => 'nullable|integer|min:1',
                'equipos_requeridos' => 'nullable|string|max:255',
                'precauciones_seguridad' => 'nullable|string|max:1000',
                'producto_id' => 'nullable|exists:productos,id'
            ]);

            $laboratorio->update($validated);

            return redirect()->route('laboratorio.show', $laboratorio)
                           ->with('success', 'Laboratorio actualizado exitosamente.');

        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Error al actualizar laboratorio: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laboratorio $laboratorio)
    {
        try {
            if ($laboratorio->estado !== Laboratorio::ESTADO_BORRADOR) {
                return back()->with('error', 'Solo se pueden eliminar laboratorios en estado borrador.');
            }

            $laboratorio->delete();

            return redirect()->route('laboratorio.index')
                           ->with('success', 'Laboratorio eliminado exitosamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al eliminar laboratorio: ' . $e->getMessage());
        }
    }

    /**
     * Iniciar proceso de laboratorio
     */
    public function iniciarProceso(Laboratorio $laboratorio)
    {
        try {
            if ($laboratorio->estado !== Laboratorio::ESTADO_BORRADOR) {
                return back()->with('error', 'Solo se pueden iniciar laboratorios en estado borrador.');
            }

            $laboratorio->update([
                'estado' => Laboratorio::ESTADO_EN_PROCESO,
                'fecha_inicio' => now()
            ]);

            return back()->with('success', 'Proceso de laboratorio iniciado exitosamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al iniciar proceso: ' . $e->getMessage());
        }
    }

    /**
     * Completar paso del laboratorio
     */
    public function completarPaso(Request $request, Laboratorio $laboratorio, PasoLaboratorio $paso)
    {
        try {
            if ($laboratorio->estado !== Laboratorio::ESTADO_EN_PROCESO) {
                return response()->json([
                    'success' => false,
                    'message' => 'El laboratorio debe estar en proceso para completar pasos.'
                ], 400);
            }

            $validated = $request->validate([
                'notas' => 'nullable|string|max:1000'
            ]);

            $paso->marcarCompletado(Auth::id(), $validated['notas'] ?? null);

            // Verificar si todos los pasos están completados
            $pasosCompletados = $laboratorio->pasos()->where('completado', true)->count();
            $totalPasos = $laboratorio->pasos()->count();

            if ($pasosCompletados === $totalPasos) {
                $laboratorio->update([
                    'estado' => Laboratorio::ESTADO_COMPLETADO,
                    'fecha_fin' => now()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Paso completado exitosamente.',
                'progreso' => $laboratorio->progreso,
                'completado' => $pasosCompletados === $totalPasos
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al completar paso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar reporte de laboratorio
     */
    public function generarReporte(Laboratorio $laboratorio)
    {
        try {
            $laboratorio->load(['usuario', 'producto', 'pasos.usuarioCompleto']);

            // Aquí puedes generar un PDF o reporte detallado
            return view('laboratorio.reporte', compact('laboratorio'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al generar reporte: ' . $e->getMessage());
        }
    }

    /**
     * Calcular tiempo promedio de fabricación
     */
    private function calcularTiempoPromedio(): string
    {
        $laboratoriosCompletados = Laboratorio::where('estado', Laboratorio::ESTADO_COMPLETADO)
                                             ->whereNotNull('fecha_inicio')
                                             ->whereNotNull('fecha_fin')
                                             ->get();

        if ($laboratoriosCompletados->isEmpty()) {
            return '0 minutos';
        }

        $tiempoTotal = 0;
        foreach ($laboratoriosCompletados as $lab) {
            $tiempoTotal += $lab->fecha_inicio->diffInMinutes($lab->fecha_fin);
        }

        $promedio = round($tiempoTotal / $laboratoriosCompletados->count());

        if ($promedio < 60) {
            return "{$promedio} minutos";
        } else {
            $horas = floor($promedio / 60);
            $minutos = $promedio % 60;
            return "{$horas}h {$minutos}m";
        }
    }
}
