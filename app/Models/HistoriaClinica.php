<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriaClinica extends Model
{
    use HasFactory;

    protected $table = 'historia_clinicas';

    protected $fillable = [
        'numero_historia',
        'cliente_id',
        'fecha_apertura',
        'peso',
        'altura',
        'presion_arterial',
        'temperatura',
        'frecuencia_cardiaca',
        'tipo_sangre',
        'alergias',
        'enfermedades_cronicas',
        'medicamentos_actuales',
        'observaciones_generales',
        'contacto_emergencia',
        'telefono_emergencia',
        'usuario_id',
        'activo'
    ];

    protected $casts = [
        'fecha_apertura' => 'date',
        'peso' => 'decimal:2',
        'altura' => 'decimal:2',
        'temperatura' => 'decimal:1',
        'activo' => 'boolean'
    ];

    /**
     * Relación con cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación con usuario que creó la historia
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con consultas médicas
     */
    public function consultas()
    {
        return $this->hasMany(ConsultaMedica::class);
    }

    /**
     * Scope para historias activas
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Generar número de historia automáticamente
     */
    public static function generarNumero()
    {
        $ultimaHistoria = static::orderBy('created_at', 'desc')->first();

        if ($ultimaHistoria) {
            $ultimoNumero = intval(substr($ultimaHistoria->numero_historia, -6));
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        return 'HC-' . date('Y') . '-' . str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Obtener IMC calculado
     */
    public function getImcAttribute()
    {
        if ($this->peso && $this->altura) {
            $alturaMetros = $this->altura / 100;
            return round($this->peso / ($alturaMetros * $alturaMetros), 2);
        }
        return null;
    }

    /**
     * Obtener clasificación del IMC
     */
    public function getClasificacionImcAttribute()
    {
        $imc = $this->imc;
        
        if (!$imc) return 'No calculable';
        
        if ($imc < 18.5) return 'Bajo peso';
        if ($imc < 25) return 'Peso normal';
        if ($imc < 30) return 'Sobrepeso';
        return 'Obesidad';
    }
} 