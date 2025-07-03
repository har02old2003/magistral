<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PasoLaboratorio extends Model
{
    use HasFactory;

    protected $table = 'paso_laboratorios';

    protected $fillable = [
        'laboratorio_id',
        'orden_paso',
        'titulo_paso',
        'descripcion_paso',
        'instrucciones_detalladas',
        'tiempo_estimado_minutos',
        'equipos_necesarios',
        'materiales_requeridos',
        'observaciones',
        'completado',
        'fecha_completado',
        'usuario_completo',
        'notas_completado'
    ];

    protected $casts = [
        'completado' => 'boolean',
        'fecha_completado' => 'datetime',
        'tiempo_estimado_minutos' => 'integer'
    ];

    /**
     * Relación con el laboratorio
     */
    public function laboratorio(): BelongsTo
    {
        return $this->belongsTo(Laboratorio::class, 'laboratorio_id');
    }

    /**
     * Relación con el usuario que completó el paso
     */
    public function usuarioCompleto(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_completo');
    }

    /**
     * Marcar paso como completado
     */
    public function marcarCompletado(int $usuarioId, string $notas = null): bool
    {
        return $this->update([
            'completado' => true,
            'fecha_completado' => now(),
            'usuario_completo' => $usuarioId,
            'notas_completado' => $notas
        ]);
    }

    /**
     * Marcar paso como pendiente
     */
    public function marcarPendiente(): bool
    {
        return $this->update([
            'completado' => false,
            'fecha_completado' => null,
            'usuario_completo' => null,
            'notas_completado' => null
        ]);
    }

    /**
     * Verificar si el paso está completado
     */
    public function getEstaCompletadoAttribute(): bool
    {
        return $this->completado;
    }

    /**
     * Obtener tiempo transcurrido en el paso
     */
    public function getTiempoTranscurridoAttribute(): string
    {
        if (!$this->fecha_completado) return 'Pendiente';

        $diferencia = $this->created_at->diffInMinutes($this->fecha_completado);
        
        if ($diferencia < 60) {
            return "{$diferencia} minutos";
        } else {
            $horas = floor($diferencia / 60);
            $minutos = $diferencia % 60;
            return "{$horas}h {$minutos}m";
        }
    }

    /**
     * Scope para pasos completados
     */
    public function scopeCompletado($query)
    {
        return $query->where('completado', true);
    }

    /**
     * Scope para pasos pendientes
     */
    public function scopePendiente($query)
    {
        return $query->where('completado', false);
    }
}
