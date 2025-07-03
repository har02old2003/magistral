<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contabilidad;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class ContabilidadController extends Controller
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
            $query = Contabilidad::with(['venta', 'usuario'])
                                ->orderBy('fecha_asiento', 'desc');

            // Filtros
            if ($request->filled('tipo')) {
                $query->where('tipo_asiento', $request->tipo);
            }

            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha_asiento', '>=', $request->fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha_asiento', '<=', $request->fecha_hasta);
            }

            if ($request->filled('cuenta')) {
                $query->where('cuenta_contable', 'like', "%{$request->cuenta}%");
            }

            $asientos = $query->paginate(20);

            // Estadísticas
            $estadisticas = [
                'total_debe' => Contabilidad::sum('debe'),
                'total_haber' => Contabilidad::sum('haber'),
                'asientos_mes' => Contabilidad::whereMonth('fecha_asiento', date('m'))->count(),
                'pendientes' => Contabilidad::where('estado', 'borrador')->count()
            ];

            return view('contabilidad.index', compact('asientos', 'estadisticas'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar contabilidad: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $tipoAsiento = $request->get('tipo', 'gasto');

            return view('contabilidad.create', compact('tipoAsiento'));

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
                'fecha_asiento' => 'required|date',
                'tipo_asiento' => 'required|in:venta,compra,gasto,ingreso,ajuste,apertura,cierre',
                'concepto' => 'required|string|max:500',
                'debe' => 'required|numeric|min:0',
                'haber' => 'required|numeric|min:0',
                'cuenta_contable' => 'required|string|max:20',
                'subcuenta' => 'nullable|string|max:20',
                'centro_costo' => 'nullable|string|max:50',
                'documento_referencia' => 'nullable|string|max:100',
                'venta_id' => 'nullable|exists:ventas,id',
                'observaciones' => 'nullable|string|max:1000'
            ], [
                'fecha_asiento.required' => 'La fecha del asiento es obligatoria.',
                'tipo_asiento.required' => 'El tipo de asiento es obligatorio.',
                'concepto.required' => 'El concepto es obligatorio.',
                'debe.required' => 'El monto del debe es obligatorio.',
                'haber.required' => 'El monto del haber es obligatorio.',
                'cuenta_contable.required' => 'La cuenta contable es obligatoria.'
            ]);

            // Validar que debe y haber no sean ambos cero
            if ($validated['debe'] == 0 && $validated['haber'] == 0) {
                return back()->withInput()->with('error', 'El debe o el haber deben ser mayor a cero.');
            }

            // Validar que no sean ambos diferentes de cero (principio de partida doble)
            if ($validated['debe'] > 0 && $validated['haber'] > 0) {
                return back()->withInput()->with('error', 'Solo uno de los campos (debe o haber) puede tener valor.');
            }

            $asiento = Contabilidad::create([
                'fecha_asiento' => $validated['fecha_asiento'],
                'numero_asiento' => Contabilidad::generarNumero(),
                'tipo_asiento' => $validated['tipo_asiento'],
                'concepto' => $validated['concepto'],
                'debe' => $validated['debe'],
                'haber' => $validated['haber'],
                'cuenta_contable' => $validated['cuenta_contable'],
                'subcuenta' => $validated['subcuenta'],
                'centro_costo' => $validated['centro_costo'],
                'documento_referencia' => $validated['documento_referencia'],
                'venta_id' => $validated['venta_id'],
                'estado' => Contabilidad::ESTADO_BORRADOR,
                'observaciones' => $validated['observaciones'],
                'usuario_id' => Auth::id()
            ]);

            return redirect()->route('contabilidad.show', $asiento)
                           ->with('success', 'Asiento contable creado exitosamente.');

        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Error al crear asiento: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contabilidad $contabilidad)
    {
        try {
            $contabilidad->load(['venta', 'usuario']);

            return view('contabilidad.show', compact('contabilidad'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al cargar asiento: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contabilidad $contabilidad)
    {
        try {
            if ($contabilidad->estado == 'contabilizado') {
                return back()->with('error', 'No se puede modificar un asiento ya contabilizado.');
            }

            $validated = $request->validate([
                'fecha_asiento' => 'required|date',
                'concepto' => 'required|string|max:500',
                'debe' => 'required|numeric|min:0',
                'haber' => 'required|numeric|min:0',
                'cuenta_contable' => 'required|string|max:20',
                'subcuenta' => 'nullable|string|max:20',
                'centro_costo' => 'nullable|string|max:50',
                'observaciones' => 'nullable|string|max:1000'
            ]);

            // Validar que debe y haber no sean ambos cero
            if ($validated['debe'] == 0 && $validated['haber'] == 0) {
                return back()->withInput()->with('error', 'El debe o el haber deben ser mayor a cero.');
            }

            // Validar que no sean ambos diferentes de cero
            if ($validated['debe'] > 0 && $validated['haber'] > 0) {
                return back()->withInput()->with('error', 'Solo uno de los campos (debe o haber) puede tener valor.');
            }

            $contabilidad->update($validated);

            return back()->with('success', 'Asiento contable actualizado correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al actualizar asiento: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contabilidad $contabilidad)
    {
        try {
            if ($contabilidad->estado == 'contabilizado') {
                return back()->with('error', 'No se puede eliminar un asiento ya contabilizado.');
            }

            $contabilidad->update(['estado' => 'anulado']);

            return redirect()->route('contabilidad.index')
                           ->with('success', 'Asiento contable anulado correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al anular asiento: ' . $e->getMessage());
        }
    }

    /**
     * Contabilizar asiento
     */
    public function contabilizar(Contabilidad $contabilidad)
    {
        try {
            if ($contabilidad->estado != 'borrador') {
                return back()->with('error', 'Solo se pueden contabilizar asientos en borrador.');
            }

            $contabilidad->update(['estado' => 'contabilizado']);

            return back()->with('success', 'Asiento contabilizado correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al contabilizar asiento: ' . $e->getMessage());
        }
    }

    /**
     * Libro diario
     */
    public function libroDiario(Request $request)
    {
        try {
            $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->toDateString());
            $fechaHasta = $request->get('fecha_hasta', now()->toDateString());

            $asientos = Contabilidad::with(['venta', 'usuario'])
                                  ->whereBetween('fecha_asiento', [$fechaDesde, $fechaHasta])
                                  ->where('estado', 'contabilizado')
                                  ->orderBy('fecha_asiento')
                                  ->orderBy('numero_asiento')
                                  ->get();

            $totales = [
                'debe' => $asientos->sum('debe'),
                'haber' => $asientos->sum('haber')
            ];

            return view('contabilidad.libro-diario', compact('asientos', 'totales', 'fechaDesde', 'fechaHasta'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al generar libro diario: ' . $e->getMessage());
        }
    }

    /**
     * Balance de comprobación
     */
    public function balanceComprobacion(Request $request)
    {
        try {
            $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->toDateString());
            $fechaHasta = $request->get('fecha_hasta', now()->toDateString());

            $asientos = Contabilidad::whereBetween('fecha_asiento', [$fechaDesde, $fechaHasta])
                                  ->where('estado', 'contabilizado')
                                  ->get();

            $balance = $asientos->groupBy('cuenta_contable')
                              ->map(function($cuentaAsientos) {
                                  return [
                                      'debe' => $cuentaAsientos->sum('debe'),
                                      'haber' => $cuentaAsientos->sum('haber'),
                                      'saldo' => $cuentaAsientos->sum('debe') - $cuentaAsientos->sum('haber')
                                  ];
                              });

            $totales = [
                'debe' => $balance->sum('debe'),
                'haber' => $balance->sum('haber'),
                'saldo' => $balance->sum('saldo')
            ];

            return view('contabilidad.balance-comprobacion', compact('balance', 'totales', 'fechaDesde', 'fechaHasta'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al generar balance: ' . $e->getMessage());
        }
    }

    /**
     * Estado de resultados
     */
    public function estadoResultados(Request $request)
    {
        try {
            $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->toDateString());
            $fechaHasta = $request->get('fecha_hasta', now()->toDateString());

            $ingresos = Contabilidad::tipo('ingreso')
                                  ->whereBetween('fecha_asiento', [$fechaDesde, $fechaHasta])
                                  ->where('estado', 'contabilizado')
                                  ->sum('haber');

            $ventas = Contabilidad::tipo('venta')
                                ->whereBetween('fecha_asiento', [$fechaDesde, $fechaHasta])
                                ->where('estado', 'contabilizado')
                                ->sum('haber');

            $gastos = Contabilidad::tipo('gasto')
                                ->whereBetween('fecha_asiento', [$fechaDesde, $fechaHasta])
                                ->where('estado', 'contabilizado')
                                ->sum('debe');

            $compras = Contabilidad::tipo('compra')
                                 ->whereBetween('fecha_asiento', [$fechaDesde, $fechaHasta])
                                 ->where('estado', 'contabilizado')
                                 ->sum('debe');

            $totalIngresos = $ingresos + $ventas;
            $totalGastos = $gastos + $compras;
            $utilidad = $totalIngresos - $totalGastos;

            $resultado = [
                'ingresos' => $ingresos,
                'ventas' => $ventas,
                'total_ingresos' => $totalIngresos,
                'gastos' => $gastos,
                'compras' => $compras,
                'total_gastos' => $totalGastos,
                'utilidad' => $utilidad
            ];

            return view('contabilidad.estado-resultados', compact('resultado', 'fechaDesde', 'fechaHasta'));

        } catch (Exception $e) {
            return back()->with('error', 'Error al generar estado de resultados: ' . $e->getMessage());
        }
    }

    /**
     * Registrar asiento automático de venta
     */
    public function registrarVenta(Venta $venta)
    {
        try {
            DB::beginTransaction();

            // Asiento de débito - Caja/Bancos
            Contabilidad::create([
                'fecha_asiento' => $venta->created_at->toDateString(),
                'numero_asiento' => Contabilidad::generarNumero(),
                'tipo_asiento' => 'venta',
                'concepto' => "Venta según documento {$venta->numero_venta}",
                'debe' => $venta->total,
                'haber' => 0,
                'cuenta_contable' => '101', // Caja
                'documento_referencia' => $venta->numero_venta,
                'venta_id' => $venta->id,
                'estado' => 'contabilizado',
                'usuario_id' => Auth::id()
            ]);

            // Asiento de crédito - Ventas
            Contabilidad::create([
                'fecha_asiento' => $venta->created_at->toDateString(),
                'numero_asiento' => Contabilidad::generarNumero(),
                'tipo_asiento' => 'venta',
                'concepto' => "Venta según documento {$venta->numero_venta}",
                'debe' => 0,
                'haber' => $venta->subtotal,
                'cuenta_contable' => '701', // Ventas
                'documento_referencia' => $venta->numero_venta,
                'venta_id' => $venta->id,
                'estado' => 'contabilizado',
                'usuario_id' => Auth::id()
            ]);

            // Asiento de crédito - IGV
            if ($venta->igv > 0) {
                Contabilidad::create([
                    'fecha_asiento' => $venta->created_at->toDateString(),
                    'numero_asiento' => Contabilidad::generarNumero(),
                    'tipo_asiento' => 'venta',
                    'concepto' => "IGV de venta según documento {$venta->numero_venta}",
                    'debe' => 0,
                    'haber' => $venta->igv,
                    'cuenta_contable' => '401', // IGV por pagar
                    'documento_referencia' => $venta->numero_venta,
                    'venta_id' => $venta->id,
                    'estado' => 'contabilizado',
                    'usuario_id' => Auth::id()
                ]);
            }

            DB::commit();

            return back()->with('success', 'Asientos contables de venta registrados automáticamente.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar asientos de venta: ' . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas contables
     */
    public function estadisticas(Request $request)
    {
        try {
            $periodo = $request->get('periodo', 'mes');
            $fechaDesde = match($periodo) {
                'dia' => now()->startOfDay(),
                'semana' => now()->startOfWeek(),
                'mes' => now()->startOfMonth(),
                'año' => now()->startOfYear(),
                default => now()->startOfMonth()
            };

            $estadisticas = [
                'asientos' => Contabilidad::where('fecha_asiento', '>=', $fechaDesde)->count(),
                'debe_total' => Contabilidad::where('fecha_asiento', '>=', $fechaDesde)->sum('debe'),
                'haber_total' => Contabilidad::where('fecha_asiento', '>=', $fechaDesde)->sum('haber'),
                'por_tipo' => Contabilidad::where('fecha_asiento', '>=', $fechaDesde)
                                        ->groupBy('tipo_asiento')
                                        ->selectRaw('tipo_asiento, count(*) as cantidad, sum(debe + haber) as monto')
                                        ->get()
                                        ->keyBy('tipo_asiento')
            ];

            return response()->json($estadisticas);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 