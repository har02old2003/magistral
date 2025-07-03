<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_pedido',
        'cliente_id',
        'proveedor_id',
        'fecha_pedido',
        'fecha_entrega_estimada',
        'fecha_entrega_real',
        'estado',
        'tipo_pedido',
        'subtotal',
        'igv',
        'total',
        'observaciones',
        'direccion_entrega',
        'telefono_contacto',
        'usuario_id',
        'activo'
    ];

    protected $casts = [
        'fecha_pedido' => 'date',
        'fecha_entrega_estimada' => 'date',
        'fecha_entrega_real' => 'date',
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2',
        'activo' => 'boolean'
    ];

    // Estados del pedido
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_CONFIRMADO = 'confirmado';
    const ESTADO_PREPARANDO = 'preparando';
    const ESTADO_EN_CAMINO = 'en_camino';
    const ESTADO_ENTREGADO = 'entregado';
    const ESTADO_CANCELADO = 'cancelado';

    // Tipos de pedido
    const TIPO_COMPRA = 'compra';
    const TIPO_VENTA = 'venta';
    const TIPO_DELIVERY = 'delivery';

    /**
     * Relación con cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación con proveedor
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Relación con usuario que creó el pedido
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con detalles del pedido
     */
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    /**
     * Scope para pedidos activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para pedidos por estado
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para pedidos por tipo
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_pedido', $tipo);
    }

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFormateadoAttribute()
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'confirmado' => 'Confirmado',
            'preparando' => 'Preparando',
            'en_camino' => 'En Camino',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado'
        ];

        return $estados[$this->estado] ?? $this->estado;
    }

    /**
     * Obtener el tipo formateado
     */
    public function getTipoFormateadoAttribute()
    {
        $tipos = [
            'compra' => 'Compra',
            'venta' => 'Venta',
            'delivery' => 'Delivery'
        ];

        return $tipos[$this->tipo_pedido] ?? $this->tipo_pedido;
    }

    /**
     * Generar número de pedido automáticamente
     */
    public static function generarNumero($tipo = 'venta')
    {
        $prefijo = [
            'compra' => 'COM',
            'venta' => 'VEN',
            'delivery' => 'DEL'
        ];

        $ultimoPedido = static::where('tipo_pedido', $tipo)
                            ->orderBy('created_at', 'desc')
                            ->first();

        if ($ultimoPedido) {
            $ultimoNumero = intval(substr($ultimoPedido->numero_pedido, -6));
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        return ($prefijo[$tipo] ?? 'PED') . '-' . date('Y') . '-' . str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);
    }
} 