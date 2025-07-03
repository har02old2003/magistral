<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoriaClinica;
use App\Models\ConsultaMedica;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class HistoriaClinicaController extends Controller
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
            $query = HistoriaClinica::with(['cliente', 'usuario'])
                                  ->activo()
                                  ->orderBy('created_at', 'desc');

            // Filtros
            if ($request->filled('buscar')) {
                $buscar = $request->buscar;
                $query->whereHas('cliente', function($q) use ($buscar) {
                    $q->where('nombres', 'like', "%{$buscar}%")
                      ->orWhere('apellidos', 'like', "%{$buscar}%")
                      ->orWhere('dni', 'like', "%{$buscar}%");
                });
            }

            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha_apertura', '>=', $request->fecha_desde);
            }

            $historias = $query->paginate(15);

            // Estadísticas
            $estadisticas = [
                'total' => HistoriaClinica::activo()->count(),
                'nuevas_mes' => HistoriaClinica::activo()->whereMonth('created_at', date('m'))->count(),
                'consultas_hoy' => ConsultaMedica::whereDate('fecha_consulta', today())->count(),
                'proximas_citas' => ConsultaMedica::where('proxima_cita', '>=', now())->count()
            ];

            return view('historia-clinica.index', compact('historias', 'estadisticas'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar historias clínicas: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $clientes = Cliente::where('activo', true)
                             ->whereNotIn('id', HistoriaClinica::pluck('cliente_id'))
                             ->orderBy('nombres')
                             ->get();

            return view('historia-clinica.create', compact('clientes'));

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
                'cliente_id' => 'required|exists:clientes,id|unique:historia_clinicas,cliente_id',
                'fecha_apertura' => 'required|date',
                'peso' => 'nullable|numeric|min:1|max:300',
                'altura' => 'nullable|numeric|min:50|max:250',
                'presion_arterial' => 'nullable|string|max:20',
                'temperatura' => 'nullable|numeric|min:30|max:45',
                'frecuencia_cardiaca' => 'nullable|string|max:20',
                'tipo_sangre' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'alergias' => 'nullable|string|max:1000',
                'enfermedades_cronicas' => 'nullable|string|max:1000',
                'medicamentos_actuales' => 'nullable|string|max:1000',
                'observaciones_generales' => 'nullable|string|max:1000',
                'contacto_emergencia' => 'nullable|string|max:255',
                'telefono_emergencia' => 'nullable|string|max:20'
            ], [
                'cliente_id.required' => 'Debe seleccionar un cliente.',
                'cliente_id.unique' => 'Este cliente ya tiene una historia clínica.',
                'fecha_apertura.required' => 'La fecha de apertura es obligatoria.',
                'peso.min' => 'El peso debe ser mayor a 1 kg.',
                'peso.max' => 'El peso debe ser menor a 300 kg.',
                'altura.min' => 'La altura debe ser mayor a 50 cm.',
                'altura.max' => 'La altura debe ser menor a 250 cm.',
                'temperatura.min' => 'La temperatura debe ser mayor a 30°C.',
                'temperatura.max' => 'La temperatura debe ser menor a 45°C.'
            ]);

            $historia = HistoriaClinica::create([
                'numero_historia' => HistoriaClinica::generarNumero(),
                'cliente_id' => $validated['cliente_id'],
                'fecha_apertura' => $validated['fecha_apertura'],
                'peso' => $validated['peso'],
                'altura' => $validated['altura'],
                'presion_arterial' => $validated['presion_arterial'],
                'temperatura' => $validated['temperatura'],
                'frecuencia_cardiaca' => $validated['frecuencia_cardiaca'],
                'tipo_sangre' => $validated['tipo_sangre'],
                'alergias' => $validated['alergias'],
                'enfermedades_cronicas' => $validated['enfermedades_cronicas'],
                'medicamentos_actuales' => $validated['medicamentos_actuales'],
                'observaciones_generales' => $validated['observaciones_generales'],
                'contacto_emergencia' => $validated['contacto_emergencia'],
                'telefono_emergencia' => $validated['telefono_emergencia'],
                'usuario_id' => Auth::id(),
                'activo' => true
            ]);

            return redirect()->route('historia-clinica.show', $historia)
                           ->with('success', 'Historia clínica creada exitosamente.');

        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Error al crear historia clínica: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(HistoriaClinica $historiaClinica)
    {
        try {
            $historiaClinica->load(['cliente', 'usuario', 'consultas.usuario']);

            return view('historia-clinica.show', compact('historiaClinica'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar historia clínica: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HistoriaClinica $historiaClinica)
    {
        try {
            $validated = $request->validate([
                'peso' => 'nullable|numeric|min:1|max:300',
                'altura' => 'nullable|numeric|min:50|max:250',
                'presion_arterial' => 'nullable|string|max:20',
                'temperatura' => 'nullable|numeric|min:30|max:45',
                'frecuencia_cardiaca' => 'nullable|string|max:20',
                'tipo_sangre' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'alergias' => 'nullable|string|max:1000',
                'enfermedades_cronicas' => 'nullable|string|max:1000',
                'medicamentos_actuales' => 'nullable|string|max:1000',
                'observaciones_generales' => 'nullable|string|max:1000',
                'contacto_emergencia' => 'nullable|string|max:255',
                'telefono_emergencia' => 'nullable|string|max:20'
            ]);

            $historiaClinica->update($validated);

            return back()->with('success', 'Historia clínica actualizada correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al actualizar historia clínica: ' . $e->getMessage());
        }
    }

    /**
     * Agregar consulta médica
     */
    public function agregarConsulta(Request $request, HistoriaClinica $historiaClinica)
    {
        try {
            $validated = $request->validate([
                'fecha_consulta' => 'required|date',
                'motivo_consulta' => 'required|string|max:500',
                'sintomas' => 'nullable|string|max:1000',
                'diagnostico' => 'required|string|max:1000',
                'tratamiento' => 'nullable|string|max:1000',
                'medicamentos_recetados' => 'nullable|string|max:1000',
                'dosis_medicamentos' => 'nullable|string|max:500',
                'duracion_tratamiento' => 'nullable|string|max:100',
                'proxima_cita' => 'nullable|date|after:fecha_consulta',
                'observaciones' => 'nullable|string|max:1000',
                'precio_consulta' => 'nullable|numeric|min:0'
            ], [
                'fecha_consulta.required' => 'La fecha de consulta es obligatoria.',
                'motivo_consulta.required' => 'El motivo de consulta es obligatorio.',
                'diagnostico.required' => 'El diagnóstico es obligatorio.',
                'proxima_cita.after' => 'La próxima cita debe ser posterior a la fecha de consulta.'
            ]);

            ConsultaMedica::create([
                'historia_clinica_id' => $historiaClinica->id,
                'fecha_consulta' => $validated['fecha_consulta'],
                'motivo_consulta' => $validated['motivo_consulta'],
                'sintomas' => $validated['sintomas'],
                'diagnostico' => $validated['diagnostico'],
                'tratamiento' => $validated['tratamiento'],
                'medicamentos_recetados' => $validated['medicamentos_recetados'],
                'dosis_medicamentos' => $validated['dosis_medicamentos'],
                'duracion_tratamiento' => $validated['duracion_tratamiento'],
                'proxima_cita' => $validated['proxima_cita'],
                'observaciones' => $validated['observaciones'],
                'precio_consulta' => $validated['precio_consulta'],
                'usuario_id' => Auth::id()
            ]);

            return back()->with('success', 'Consulta médica agregada correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al agregar consulta: ' . $e->getMessage());
        }
    }

    /**
     * Buscar historia clínica por cliente
     */
    public function buscarPorCliente(Request $request)
    {
        try {
            $buscar = $request->get('q');
            
            $historias = HistoriaClinica::with('cliente')
                                     ->whereHas('cliente', function($query) use ($buscar) {
                                         $query->where('nombres', 'like', "%{$buscar}%")
                                               ->orWhere('apellidos', 'like', "%{$buscar}%")
                                               ->orWhere('dni', 'like', "%{$buscar}%");
                                     })
                                     ->activo()
                                     ->limit(10)
                                     ->get();

            return response()->json($historias);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 