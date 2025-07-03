<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el estado actual de la caja
     */
    public function index()
    {
        try {
            // Obtener movimientos de caja del día actual
            $movimientosHoy = \App\Models\MovimientoStock::whereDate('fecha_movimiento', today())
                ->where('tipo_movimiento', 'like', '%caja%')
                ->orderBy('fecha_movimiento', 'desc')
                ->get();
            
            // Estadísticas de caja
            $cajaAbierta = session('caja_abierta', false);
            $montoInicial = session('monto_inicial', 0);
            $totalIngresos = $movimientosHoy->where('tipo_movimiento', 'ingreso')->sum('precio_costo');
            $totalEgresos = $movimientosHoy->where('tipo_movimiento', 'egreso')->sum('precio_costo');
            $saldoActual = $montoInicial + $totalIngresos - $totalEgresos;
            
            return view('caja.index', compact(
                'movimientosHoy',
                'cajaAbierta',
                'montoInicial',
                'totalIngresos',
                'totalEgresos',
                'saldoActual'
            ));
        } catch (\Exception $e) {
            return view('caja.index', [
                'movimientosHoy' => collect(),
                'cajaAbierta' => false,
                'montoInicial' => 0,
                'totalIngresos' => 0,
                'totalEgresos' => 0,
                'saldoActual' => 0,
                'error' => 'Error al cargar los datos de caja'
            ]);
        }
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->expectsJson()) {
            return $this->storeAjax($request);
        }

        $request->validate([
            'tipo_movimiento' => 'required|in:ingreso,egreso,apertura,cierre',
            'monto' => 'required|numeric|min:0',
            'concepto' => 'required|string|max:255',
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            $movimiento = new \App\Models\MovimientoStock();
            $movimiento->tipo_movimiento = $request->tipo_movimiento;
            $movimiento->precio_costo = $request->monto;
            $movimiento->observaciones = $request->concepto . ($request->observaciones ? ' - ' . $request->observaciones : '');
            $movimiento->fecha_movimiento = now();
            $movimiento->usuario_id = auth()->id();
            
            if ($request->tipo_movimiento === 'apertura') {
                session(['caja_abierta' => true, 'monto_inicial' => $request->monto]);
            } elseif ($request->tipo_movimiento === 'cierre') {
                session(['caja_abierta' => false, 'monto_inicial' => 0]);
            }
            
            $movimiento->save();

            return redirect()->route('caja.index')->with('success', 'Movimiento de caja registrado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar movimiento: ' . $e->getMessage());
        }
    }

    public function storeAjax(Request $request)
    {
        try {
            $request->validate([
                'tipo_movimiento' => 'required|in:ingreso,egreso,apertura,cierre',
                'monto' => 'required|numeric|min:0',
                'concepto' => 'required|string|max:255',
                'observaciones' => 'nullable|string|max:500'
            ]);

            $movimiento = new \App\Models\MovimientoStock();
            $movimiento->tipo_movimiento = $request->tipo_movimiento;
            $movimiento->precio_costo = $request->monto;
            $movimiento->observaciones = $request->concepto . ($request->observaciones ? ' - ' . $request->observaciones : '');
            $movimiento->fecha_movimiento = now();
            $movimiento->usuario_id = auth()->id();
            
            if ($request->tipo_movimiento === 'apertura') {
                session(['caja_abierta' => true, 'monto_inicial' => $request->monto]);
            } elseif ($request->tipo_movimiento === 'cierre') {
                session(['caja_abierta' => false, 'monto_inicial' => 0]);
            }
            
            $movimiento->save();

            return response()->json([
                'success' => true,
                'message' => 'Movimiento de caja registrado exitosamente',
                'movimiento' => [
                    'id' => $movimiento->id,
                    'tipo' => $movimiento->tipo_movimiento,
                    'monto' => $movimiento->precio_costo,
                    'concepto' => $request->concepto,
                    'fecha' => $movimiento->fecha_movimiento->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar movimiento: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $movimiento = \App\Models\MovimientoStock::findOrFail($id);
            
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $movimiento
                ]);
            }
            
            return view('caja.show', compact('movimiento'));
        } catch (\Exception $e) {
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movimiento no encontrado'
                ], 404);
            }
            
            return back()->with('error', 'Movimiento no encontrado');
        }
    }

    public function destroy($id)
    {
        try {
            $movimiento = \App\Models\MovimientoStock::findOrFail($id);
            
            // Solo permitir eliminar movimientos del día actual
            if (!$movimiento->fecha_movimiento->isToday()) {
                if (request()->ajax() || request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Solo se pueden eliminar movimientos del día actual'
                    ], 400);
                }
                return back()->with('error', 'Solo se pueden eliminar movimientos del día actual');
            }
            
            $movimiento->delete();
            
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Movimiento eliminado exitosamente'
                ]);
            }
            
            return redirect()->route('caja.index')->with('success', 'Movimiento eliminado exitosamente');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar movimiento: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error al eliminar movimiento: ' . $e->getMessage());
        }
    }

    /**
     * Reporte de caja
     */
    public function reporte(Request $request)
    {
        try {
            $fechaInicio = $request->fecha_inicio ?? now()->subDays(7)->format('Y-m-d');
            $fechaFin = $request->fecha_fin ?? now()->format('Y-m-d');
            $usuarioId = $request->user_id ?? Auth::id();
            
            // Obtener datos del reporte
            $reporte = [
                'resumen' => $this->obtenerResumenCaja($usuarioId, $fechaInicio, $fechaFin),
                'movimientos' => $this->obtenerMovimientosPeriodo($usuarioId, $fechaInicio, $fechaFin),
                'cajas' => $this->obtenerCajasPeriodo($usuarioId, $fechaInicio, $fechaFin)
            ];
            
            if ($request->ajax()) {
                return response()->json($reporte);
            }
            
            $usuarios = User::orderBy('name')->get();
            
            return view('caja.reporte', compact('reporte', 'usuarios', 'fechaInicio', 'fechaFin', 'usuarioId'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar reporte: ' . $e->getMessage());
        }
    }

    /**
     * Métodos privados de apoyo
     */
    private function verificarCajaAbierta($usuarioId, $fecha)
    {
        return DB::table('cajas')
            ->where('user_id', $usuarioId)
            ->whereDate('fecha_apertura', $fecha)
            ->where('estado', 'abierta')
            ->exists();
    }

    private function obtenerDatosCaja($usuarioId, $fecha)
    {
        // Obtener caja del día
        $caja = DB::table('cajas')
            ->where('user_id', $usuarioId)
            ->whereDate('fecha_apertura', $fecha)
            ->first();
        
        if (!$caja) {
            return [
                'abierta' => false,
                'monto_inicial' => 0,
                'total_ingresos' => 0,
                'total_egresos' => 0,
                'total_ventas' => 0,
                'monto_teorico' => 0
            ];
        }
        
        // Calcular totales del día
        $totalVentas = Venta::where('user_id', $usuarioId)
                           ->whereDate('fecha', $fecha)
                           ->sum('total');
        
        $movimientos = DB::table('movimientos_caja')
            ->where('user_id', $usuarioId)
            ->whereDate('created_at', $fecha)
            ->selectRaw('
                SUM(CASE WHEN tipo_movimiento = "ingreso" THEN monto ELSE 0 END) as total_ingresos,
                SUM(CASE WHEN tipo_movimiento = "egreso" THEN monto ELSE 0 END) as total_egresos
            ')
            ->first();
        
        $montoTeorico = $caja->monto_inicial + $totalVentas + ($movimientos->total_ingresos ?? 0) - ($movimientos->total_egresos ?? 0);
        
        return [
            'abierta' => $caja->estado === 'abierta',
            'monto_inicial' => $caja->monto_inicial,
            'total_ingresos' => $movimientos->total_ingresos ?? 0,
            'total_egresos' => $movimientos->total_egresos ?? 0,
            'total_ventas' => $totalVentas,
            'monto_teorico' => $montoTeorico,
            'fecha_apertura' => $caja->fecha_apertura,
            'fecha_cierre' => $caja->fecha_cierre,
            'observaciones' => $caja->observaciones
        ];
    }

    private function registrarMovimiento($usuarioId, $tipo, $monto, $concepto, $metodoPago = 'efectivo')
    {
        DB::table('movimientos_caja')->insert([
            'user_id' => $usuarioId,
            'tipo_movimiento' => $tipo,
            'monto' => $monto,
            'concepto' => $concepto,
            'metodo_pago' => $metodoPago,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    private function obtenerHistorialCajas($usuarioId, $limite = 10)
    {
        return DB::table('cajas')
            ->where('user_id', $usuarioId)
            ->orderBy('fecha_apertura', 'desc')
            ->limit($limite)
            ->get();
    }

    private function obtenerResumenCaja($usuarioId, $fechaInicio, $fechaFin)
    {
        $cajas = DB::table('cajas')
            ->where('user_id', $usuarioId)
            ->whereBetween('fecha_apertura', [$fechaInicio, $fechaFin])
            ->get();
        
        return [
            'total_cajas' => $cajas->count(),
            'cajas_cerradas' => $cajas->where('estado', 'cerrada')->count(),
            'monto_inicial_total' => $cajas->sum('monto_inicial'),
            'monto_final_total' => $cajas->sum('monto_final'),
            'diferencia_total' => $cajas->sum('diferencia')
        ];
    }

    private function obtenerMovimientosPeriodo($usuarioId, $fechaInicio, $fechaFin)
    {
        return DB::table('movimientos_caja')
            ->where('user_id', $usuarioId)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function obtenerCajasPeriodo($usuarioId, $fechaInicio, $fechaFin)
    {
        return DB::table('cajas')
            ->where('user_id', $usuarioId)
            ->whereBetween('fecha_apertura', [$fechaInicio, $fechaFin])
            ->orderBy('fecha_apertura', 'desc')
            ->get();
    }
}
