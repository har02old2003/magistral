<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contabilidad extends Model
{
    use HasFactory;

    protected $table = 'contabilidad';

    protected $fillable = [
        'fecha_asiento',
        'numero_asiento',
        'tipo_asiento',
        'concepto',
        'debe',
        'haber',
        'cuenta_contable',
        'subcuenta',
        'centro_costo',
        'documento_referencia',
        'venta_id',
        'compra_id',
        'estado',
        'observaciones',
        'usuario_id'
    ];

    protected $casts = [
        'fecha_asiento' => 'date',
        'debe' => 'decimal:2',
        'haber' => 'decimal:2'
    ];

    // Tipos de asiento
    const TIPO_VENTA = 'venta';
    const TIPO_COMPRA = 'compra';
    const TIPO_GASTO = 'gasto';
    const TIPO_INGRESO = 'ingreso';
    const TIPO_AJUSTE = 'ajuste';
    const TIPO_APERTURA = 'apertura';
    const TIPO_CIERRE = 'cierre';

    // Estados del asiento
    const ESTADO_BORRADOR = 'borrador';
    const ESTADO_CONTABILIZADO = 'contabilizado';
    const ESTADO_ANULADO = 'anulado';

    /**
     * Relación con venta
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para asientos por tipo
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_asiento', $tipo);
    }

    /**
     * Scope para asientos por estado
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para asientos por período
     */
    public function scopePeriodo($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_asiento', [$fechaInicio, $fechaFin]);
    }

    /**
     * Obtener el tipo formateado
     */
    public function getTipoFormateadoAttribute()
    {
        $tipos = [
            'venta' => 'Venta',
            'compra' => 'Compra',
            'gasto' => 'Gasto',
            'ingreso' => 'Ingreso',
            'ajuste' => 'Ajuste',
            'apertura' => 'Apertura',
            'cierre' => 'Cierre'
        ];

        return $tipos[$this->tipo_asiento] ?? $this->tipo_asiento;
    }

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFormateadoAttribute()
    {
        $estados = [
            'borrador' => 'Borrador',
            'contabilizado' => 'Contabilizado',
            'anulado' => 'Anulado'
        ];

        return $estados[$this->estado] ?? $this->estado;
    }

    /**
     * Generar número de asiento automáticamente
     */
    public static function generarNumero()
    {
        $ultimoAsiento = static::whereYear('fecha_asiento', date('Y'))
                              ->orderBy('numero_asiento', 'desc')
                              ->first();

        if ($ultimoAsiento) {
            $ultimoNumero = intval(substr($ultimoAsiento->numero_asiento, -6));
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        return 'ASI-' . date('Y') . '-' . str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);
    }
} 