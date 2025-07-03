<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuiaRemision extends Model
{
    use HasFactory;

    protected $table = 'guia_remisions';

    protected $fillable = [
        'numero_guia',
        'cliente_id',
        'proveedor_id',
        'destinatario',
        'direccion_destino',
        'tipo_traslado',
        'estado',
        'fecha_emision',
        'fecha_traslado',
        'transportista',
        'ruc_transportista',
        'placa_vehiculo',
        'observaciones',
        'peso_total',
        'cantidad_bultos',
        'activo'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_traslado' => 'date',
        'peso_total' => 'decimal:2',
        'activo' => 'boolean'
    ];

    // Estados de la guía
    const ESTADO_EMITIDA = 'emitida';
    const ESTADO_EN_TRANSITO = 'en_transito';
    const ESTADO_ENTREGADA = 'entregada';
    const ESTADO_ANULADA = 'anulada';

    // Tipos de traslado
    const TIPO_VENTA = 'venta';
    const TIPO_COMPRA = 'compra';
    const TIPO_TRASLADO = 'traslado';

    /**
     * Relación con proveedor
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

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
     * Relación con detalles de la guía
     */
    public function detalles()
    {
        return $this->hasMany(DetalleGuiaRemision::class);
    }

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFormateadoAttribute()
    {
        $estados = [
            'emitida' => 'Emitida',
            'en_transito' => 'En Tránsito',
            'entregada' => 'Entregada',
            'anulada' => 'Anulada'
        ];

        return $estados[$this->estado] ?? $this->estado;
    }

    /**
     * Obtener el motivo formateado
     */
    public function getMotivoFormateadoAttribute()
    {
        $motivos = [
            'venta' => 'Venta',
            'traslado' => 'Traslado entre almacenes',
            'devolucion' => 'Devolución',
            'compra' => 'Compra'
        ];

        return $motivos[$this->motivo_traslado] ?? $this->motivo_traslado;
    }

    /**
     * Generar número de guía automáticamente
     */
    public static function generarNumero()
    {
        $ultimaGuia = static::orderBy('created_at', 'desc')->first();

        if ($ultimaGuia) {
            $ultimoNumero = intval(substr($ultimaGuia->numero_guia, -8));
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        return 'T001-' . str_pad($nuevoNumero, 8, '0', STR_PAD_LEFT);
    }
} 