<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoStock extends Model
{
    use HasFactory;

    protected $table = 'movimiento_stocks';

    protected $fillable = [
        'producto_id',
        'tipo_movimiento',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'precio_unitario',
        'costo_total',
        'motivo',
        'documento_referencia',
        'fecha_movimiento',
        'fecha_vencimiento',
        'lote',
        'proveedor_id',
        'venta_id',
        'pedido_id',
        'observaciones',
        'usuario_id'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'stock_anterior' => 'integer',
        'stock_nuevo' => 'integer',
        'precio_unitario' => 'decimal:2',
        'costo_total' => 'decimal:2',
        'fecha_movimiento' => 'datetime',
        'fecha_vencimiento' => 'date'
    ];

    // Tipos de movimiento
    const TIPO_INGRESO = 'ingreso';
    const TIPO_EGRESO = 'egreso';
    const TIPO_AJUSTE = 'ajuste';
    const TIPO_TRANSFERENCIA = 'transferencia';
    const TIPO_DEVOLUCION = 'devolucion';

    // Motivos de movimiento
    const MOTIVO_COMPRA = 'compra';
    const MOTIVO_VENTA = 'venta';
    const MOTIVO_AJUSTE_INVENTARIO = 'ajuste_inventario';
    const MOTIVO_TRANSFERENCIA_SUCURSAL = 'transferencia_sucursal';
    const MOTIVO_DEVOLUCION_CLIENTE = 'devolucion_cliente';
    const MOTIVO_DEVOLUCION_PROVEEDOR = 'devolucion_proveedor';
    const MOTIVO_PRODUCTO_VENCIDO = 'producto_vencido';
    const MOTIVO_PRODUCTO_DAÑADO = 'producto_dañado';

    /**
     * Relación con producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Relación con proveedor
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

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
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para movimientos por tipo
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_movimiento', $tipo);
    }

    /**
     * Scope para movimientos por fecha
     */
    public function scopeFecha($query, $fechaInicio, $fechaFin = null)
    {
        if ($fechaFin) {
            return $query->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
        }
        return $query->whereDate('fecha_movimiento', $fechaInicio);
    }

    /**
     * Obtener el tipo formateado
     */
    public function getTipoFormateadoAttribute()
    {
        $tipos = [
            'ingreso' => 'Ingreso',
            'egreso' => 'Egreso',
            'ajuste' => 'Ajuste',
            'transferencia' => 'Transferencia',
            'devolucion' => 'Devolución'
        ];

        return $tipos[$this->tipo_movimiento] ?? $this->tipo_movimiento;
    }

    /**
     * Obtener el motivo formateado
     */
    public function getMotivoFormateadoAttribute()
    {
        $motivos = [
            'compra' => 'Compra',
            'venta' => 'Venta',
            'ajuste_inventario' => 'Ajuste de Inventario',
            'transferencia_sucursal' => 'Transferencia entre Sucursales',
            'devolucion_cliente' => 'Devolución de Cliente',
            'devolucion_proveedor' => 'Devolución a Proveedor',
            'producto_vencido' => 'Producto Vencido',
            'producto_dañado' => 'Producto Dañado'
        ];

        return $motivos[$this->motivo] ?? $this->motivo;
    }
} 