<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombres',
        'apellidos',
        'documento',
        'tipo_documento',
        'telefono',
        'email',
        'direccion',
        'fecha_nacimiento',
        'genero',
        'activo'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean'
    ];

    /**
     * Relación con ventas
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    /**
     * Scope para clientes activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Accessor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }

    /**
     * Accessor para documento con tipo
     */
    public function getDocumentoCompletoAttribute()
    {
        return $this->tipo_documento . ': ' . $this->documento;
    }

    /**
     * Obtener edad
     */
    public function getEdadAttribute()
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }
        return $this->fecha_nacimiento->age;
    }

    public function scopePorTipoDocumento($query, $tipo)
    {
        return $query->where('tipo_documento', $tipo);
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('nombres', 'like', "%{$termino}%")
              ->orWhere('apellidos', 'like', "%{$termino}%")
              ->orWhere('documento', 'like', "%{$termino}%")
              ->orWhere('email', 'like', "%{$termino}%")
              ->orWhere('telefono', 'like', "%{$termino}%");
        });
    }

    public function getTotalCompras()
    {
        return $this->ventas()->sum('total');
    }

    public function getCantidadCompras()
    {
        return $this->ventas()->count();
    }

    public function getUltimaCompra()
    {
        return $this->ventas()->latest('fecha')->first();
    }

    public function esClienteVip()
    {
        return $this->getTotalCompras() >= 1000; // S/ 1000 o más
    }
}
