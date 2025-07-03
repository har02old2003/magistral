<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proforma extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_proforma',
        'cliente_id',
        'fecha_proforma',
        'fecha_vencimiento',
        'estado',
        'subtotal',
        'descuento',
        'igv',
        'total',
        'observaciones',
        'condiciones_pago',
        'tiempo_entrega',
        'usuario_id',
        'venta_id'
    ];

    protected $casts = [
        'fecha_proforma' => 'date',
        'fecha_vencimiento' => 'date',
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    // Estados de la proforma
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_ENVIADO = 'enviado';
    const ESTADO_ACEPTADO = 'aceptado';
    const ESTADO_RECHAZADO = 'rechazado';
    const ESTADO_VENCIDO = 'vencido';
    const ESTADO_CONVERTIDO = 'convertido';

    /**
     * Relación con cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con venta (si se convirtió)
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    /**
     * Relación con detalles de la proforma
     */
    public function detalles()
    {
        return $this->hasMany(DetalleProforma::class);
    }

    /**
     * Scope para proformas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    /**
     * Scope para proformas vigentes
     */
    public function scopeVigentes($query)
    {
        return $query->where('fecha_vencimiento', '>=', now()->toDateString());
    }

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFormateadoAttribute()
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'enviado' => 'Enviado',
            'aceptado' => 'Aceptado',
            'rechazado' => 'Rechazado',
            'vencido' => 'Vencido',
            'convertido' => 'Convertido a Venta'
        ];

        return $estados[$this->estado] ?? $this->estado;
    }

    /**
     * Verificar si está vencida
     */
    public function getEstaVencidaAttribute()
    {
        return $this->fecha_vencimiento < now()->toDateString();
    }

    /**
     * Generar número de proforma automáticamente
     */
    public static function generarNumero()
    {
        $ultimaProforma = static::orderBy('created_at', 'desc')->first();

        if ($ultimaProforma) {
            $ultimoNumero = intval(substr($ultimaProforma->numero_proforma, -6));
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        return 'PRO-' . date('Y') . '-' . str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);
    }
} 