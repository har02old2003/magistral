<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    /**
     * Relación con productos
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    /**
     * Scope para categorías activas
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('descripcion', 'like', "%{$termino}%");
        });
    }

    /**
     * Métodos
     */
    public function tieneProductos()
    {
        return $this->productos()->exists();
    }

    public function getCantidadProductosAttribute()
    {
        return $this->productos()->count();
    }

    public function getEstadoAttribute()
    {
        return $this->activo ? 'Activo' : 'Inactivo';
    }
}
