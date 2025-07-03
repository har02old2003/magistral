<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleProforma extends Model
{
    use HasFactory;

    protected $table = 'detalle_proformas';

    protected $fillable = [
        'proforma_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'descuento_unitario',
        'subtotal'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'descuento_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    /**
     * Relación con proforma
     */
    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }

    /**
     * Relación con producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Calcular subtotal automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($detalle) {
            $precioConDescuento = $detalle->precio_unitario - $detalle->descuento_unitario;
            $detalle->subtotal = $detalle->cantidad * $precioConDescuento;
        });
    }
} 