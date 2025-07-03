<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultaMedica extends Model
{
    use HasFactory;

    protected $table = 'consulta_medicas';

    protected $fillable = [
        'historia_clinica_id',
        'fecha_consulta',
        'motivo_consulta',
        'sintomas',
        'diagnostico',
        'tratamiento',
        'medicamentos_recetados',
        'dosis_medicamentos',
        'duracion_tratamiento',
        'proxima_cita',
        'observaciones',
        'precio_consulta',
        'usuario_id'
    ];

    protected $casts = [
        'fecha_consulta' => 'datetime',
        'proxima_cita' => 'datetime',
        'precio_consulta' => 'decimal:2'
    ];

    /**
     * Relación con historia clínica
     */
    public function historiaClinica()
    {
        return $this->belongsTo(HistoriaClinica::class);
    }

    /**
     * Relación con usuario (médico/farmacéutico)
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para consultas recientes
     */
    public function scopeRecientes($query, $dias = 30)
    {
        return $query->where('fecha_consulta', '>=', now()->subDays($dias));
    }

    /**
     * Scope para consultas por fecha
     */
    public function scopeFecha($query, $fecha)
    {
        return $query->whereDate('fecha_consulta', $fecha);
    }
} 