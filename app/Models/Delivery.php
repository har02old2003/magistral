<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $table = 'deliveries';

    protected $fillable = [
        'codigo_delivery',
        'venta_id',
        'pedido_id',
        'cliente_id',
        'repartidor_id',
        'fecha_programada',
        'fecha_entrega',
        'hora_salida',
        'hora_entrega',
        'direccion_entrega',
        'referencia_direccion',
        'telefono_contacto',
        'costo_delivery',
        'metodo_pago_delivery',
        'estado',
        'observaciones',
        'latitud',
        'longitud',
        'foto_entrega',
        'firma_cliente',
        'usuario_id'
    ];

    protected $casts = [
        'fecha_programada' => 'date',
        'fecha_entrega' => 'datetime',
        'hora_salida' => 'time',
        'hora_entrega' => 'time',
        'costo_delivery' => 'decimal:2',
        'latitud' => 'decimal:8,6',
        'longitud' => 'decimal:9,6'
    ];

    // Estados del delivery
    const ESTADO_PROGRAMADO = 'programado';
    const ESTADO_ASIGNADO = 'asignado';
    const ESTADO_EN_RUTA = 'en_ruta';
    const ESTADO_ENTREGADO = 'entregado';
    const ESTADO_NO_ENTREGADO = 'no_entregado';
    const ESTADO_CANCELADO = 'cancelado';

    /**
     * Relación con venta
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    /**
     * Relación con pedido
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Relación con cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación con repartidor (usuario)
     */
    public function repartidor()
    {
        return $this->belongsTo(User::class, 'repartidor_id');
    }

    /**
     * Relación con usuario que creó el delivery
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para deliveries por estado
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para deliveries por fecha
     */
    public function scopeFecha($query, $fecha)
    {
        return $query->whereDate('fecha_programada', $fecha);
    }

    /**
     * Scope para deliveries por repartidor
     */
    public function scopeRepartidor($query, $repartidorId)
    {
        return $query->where('repartidor_id', $repartidorId);
    }

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFormateadoAttribute()
    {
        $estados = [
            'programado' => 'Programado',
            'asignado' => 'Asignado',
            'en_ruta' => 'En Ruta',
            'entregado' => 'Entregado',
            'no_entregado' => 'No Entregado',
            'cancelado' => 'Cancelado'
        ];

        return $estados[$this->estado] ?? $this->estado;
    }

    /**
     * Generar código de delivery automáticamente
     */
    public static function generarCodigo()
    {
        $ultimoDelivery = static::whereDate('created_at', date('Y-m-d'))
                               ->orderBy('created_at', 'desc')
                               ->first();

        if ($ultimoDelivery) {
            $ultimoNumero = intval(substr($ultimoDelivery->codigo_delivery, -4));
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        return 'DEL-' . date('Ymd') . '-' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calcular duración de entrega
     */
    public function getDuracionEntregaAttribute()
    {
        if ($this->hora_salida && $this->hora_entrega) {
            $salida = \Carbon\Carbon::parse($this->hora_salida);
            $entrega = \Carbon\Carbon::parse($this->hora_entrega);
            return $salida->diffInMinutes($entrega);
        }
        return null;
    }
} 