<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleGuiaRemision extends Model
{
    use HasFactory;

    protected $table = 'detalle_guia_remisions';

    protected $fillable = [
        'guia_remision_id',
        'producto_id',
        'cantidad',
        'unidad_medida',
        'peso',
        'observaciones'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'peso' => 'decimal:2'
    ];

    /**
     * Relación con guía de remisión
     */
    public function guiaRemision()
    {
        return $this->belongsTo(GuiaRemision::class);
    }

    /**
     * Relación con producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
} 