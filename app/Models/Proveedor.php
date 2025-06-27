<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'ruc',
        'telefono',
        'email',
        'direccion',
        'contacto',
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
     * Scope para proveedores activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('ruc', 'like', "%{$termino}%")
              ->orWhere('email', 'like', "%{$termino}%")
              ->orWhere('contacto', 'like', "%{$termino}%");
        });
    }

    /**
     * Accessor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ($this->ruc ? " - RUC: {$this->ruc}" : '');
    }

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
