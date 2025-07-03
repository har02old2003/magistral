<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Laboratorio extends Model
{
    use HasFactory;

    protected $table = 'laboratorios';

    protected $fillable = [
        'numero_lote',
        'nombre_medicamento',
        'descripcion',
        'formula_quimica',
        'instrucciones_generales',
        'cantidad_producir',
        'unidad_medida',
        'temperatura_optima',
        'tiempo_fabricacion_minutos',
        'equipos_requeridos',
        'precauciones_seguridad',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'usuario_id',
        'producto_id'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'temperatura_optima' => 'decimal:2',
        'cantidad_producir' => 'integer',
        'tiempo_fabricacion_minutos' => 'integer'
    ];

    // Estados del laboratorio
    const ESTADO_BORRADOR = 'borrador';
    const ESTADO_EN_PROCESO = 'en_proceso';
    const ESTADO_COMPLETADO = 'completado';
    const ESTADO_CANCELADO = 'cancelado';

    /**
     * Relación con el usuario que creó el laboratorio
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación con el producto asociado
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    /**
     * Relación con los pasos del laboratorio
     */
    public function pasos(): HasMany
    {
        return $this->hasMany(PasoLaboratorio::class, 'laboratorio_id')->orderBy('orden_paso');
    }

    /**
     * Generar número de lote único
     */
    public static function generarNumeroLote(): string
    {
        $fecha = now()->format('Ymd');
        $ultimoLote = self::where('numero_lote', 'like', "LOT-{$fecha}-%")
                          ->orderBy('numero_lote', 'desc')
                          ->first();

        if ($ultimoLote) {
            $ultimoNumero = (int) substr($ultimoLote->numero_lote, -3);
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        return "LOT-{$fecha}-" . str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Obtener progreso del laboratorio
     */
    public function getProgresoAttribute(): int
    {
        $totalPasos = $this->pasos()->count();
        if ($totalPasos === 0) return 0;

        $pasosCompletados = $this->pasos()->where('completado', true)->count();
        return round(($pasosCompletados / $totalPasos) * 100);
    }

    /**
     * Verificar si el laboratorio está completado
     */
    public function getEstaCompletadoAttribute(): bool
    {
        return $this->estado === self::ESTADO_COMPLETADO;
    }

    /**
     * Verificar si el laboratorio está en proceso
     */
    public function getEstaEnProcesoAttribute(): bool
    {
        return $this->estado === self::ESTADO_EN_PROCESO;
    }

    /**
     * Obtener tiempo transcurrido
     */
    public function getTiempoTranscurridoAttribute(): string
    {
        if (!$this->fecha_inicio) return '0 minutos';

        $fin = $this->fecha_fin ?? now();
        $diferencia = $this->fecha_inicio->diffInMinutes($fin);
        
        if ($diferencia < 60) {
            return "{$diferencia} minutos";
        } else {
            $horas = floor($diferencia / 60);
            $minutos = $diferencia % 60;
            return "{$horas}h {$minutos}m";
        }
    }

    /**
     * Scope para laboratorios activos
     */
    public function scopeActivo($query)
    {
        return $query->whereIn('estado', [self::ESTADO_BORRADOR, self::ESTADO_EN_PROCESO]);
    }

    /**
     * Scope para laboratorios completados
     */
    public function scopeCompletado($query)
    {
        return $query->where('estado', self::ESTADO_COMPLETADO);
    }

    /**
     * Scope para laboratorios en proceso
     */
    public function scopeEnProceso($query)
    {
        return $query->where('estado', self::ESTADO_EN_PROCESO);
    }
}
