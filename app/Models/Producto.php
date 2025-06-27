<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria_id',
        'marca_id',
        'proveedor_id',
        'precio_compra',
        'precio_venta',
        'stock_actual',
        'stock_minimo',
        'fecha_vencimiento',
        'lote',
        'meses_vencimiento',
        'presentacion',
        'principio_activo',
        'concentracion',
        'laboratorio',
        'registro_sanitario',
        'requiere_receta',
        'activo'
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'fecha_vencimiento' => 'date',
        'requiere_receta' => 'boolean',
        'activo' => 'boolean'
    ];

    /**
     * Relación con categoria
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Relación con marca
     */
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    /**
     * Relación con proveedor
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Relación con detalles de venta
     */
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    /**
     * Relación con ventas
     */
    public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'detalle_ventas')
                    ->withPivot('cantidad', 'precio_unitario', 'subtotal');
    }

    /**
     * Scope para productos activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para productos con stock bajo (menos de 10 unidades)
     */
    public function scopeStockBajo($query)
    {
        return $query->whereRaw('stock_actual <= COALESCE(stock_minimo, 10)')
                    ->where('stock_actual', '>', 0);
    }

    /**
     * Scope para productos agotados
     */
    public function scopeAgotado($query)
    {
        return $query->where('stock_actual', 0);
    }

    /**
     * Scope para productos próximos a vencer (30 días)
     */
    public function scopeProximosVencer($query, $dias = 30)
    {
        return $query->whereNotNull('fecha_vencimiento')
                    ->whereDate('fecha_vencimiento', '<=', now()->addDays($dias))
                    ->whereDate('fecha_vencimiento', '>=', now())
                    ->where('stock_actual', '>', 0);
    }

    /**
     * Scope para productos vencidos
     */
    public function scopeVencidos($query)
    {
        return $query->whereNotNull('fecha_vencimiento')
                    ->whereDate('fecha_vencimiento', '<', now());
    }

    /**
     * Scope para buscar productos
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('codigo', 'like', "%{$termino}%")
              ->orWhereHas('marca', function($mq) use ($termino) {
                  $mq->where('nombre', 'like', "%{$termino}%");
              })
              ->orWhereHas('categoria', function($cq) use ($termino) {
                  $cq->where('nombre', 'like', "%{$termino}%");
              });
        });
    }

    /**
     * Verificar si tiene stock suficiente
     */
    public function tieneStock($cantidad = 1)
    {
        return $this->stock_actual >= $cantidad;
    }

    /**
     * Obtener el estado del producto
     */
    public function getEstadoAttribute()
    {
        if ($this->stock_actual == 0) {
            return 'agotado';
        } elseif ($this->stock_actual <= ($this->stock_minimo ?? 10)) {
            return 'stock_bajo';
        } elseif ($this->fecha_vencimiento && Carbon::parse($this->fecha_vencimiento)->diffInDays(now()) <= 30) {
            return 'por_vencer';
        }
        return 'disponible';
    }

    /**
     * Obtener el nombre completo del producto
     */
    public function getNombreCompletoAttribute()
    {
        $nombre = $this->nombre;
        if ($this->marca) {
            $nombre .= ' - ' . $this->marca->nombre;
        }
        if ($this->lote) {
            $nombre .= ' (Lote: ' . $this->lote . ')';
        }
        return $nombre;
    }

    /**
     * Para compatibilidad con vistas que usan 'stock'
     */
    public function getStockAttribute()
    {
        return $this->stock_actual;
    }

    /**
     * Obtener días restantes para vencimiento
     */
    public function getDiasParaVencimientoAttribute()
    {
        if (!$this->fecha_vencimiento) {
            return null;
        }
        return Carbon::parse($this->fecha_vencimiento)->diffInDays(now());
    }

    /**
     * Verificar si está próximo a vencer
     */
    public function getEstaProximoAVencerAttribute()
    {
        return $this->fecha_vencimiento && Carbon::parse($this->fecha_vencimiento)->diffInDays() <= 30;
    }

    /**
     * Verificar si tiene stock bajo
     */
    public function getTieneStockBajoAttribute()
    {
        return $this->stock_actual <= ($this->stock_minimo ?? 10);
    }

    /**
     * Reducir stock del producto
     */
    public function reducirStock($cantidad)
    {
        if ($this->tieneStock($cantidad)) {
            $this->stock_actual -= $cantidad;
            return $this->save();
        }
        return false;
    }

    /**
     * Aumentar stock del producto
     */
    public function aumentarStock($cantidad)
    {
        $this->stock_actual += $cantidad;
        return $this->save();
    }

    /**
     * Verificar si el producto está vencido
     */
    public function estaVencido()
    {
        if (!$this->fecha_vencimiento) {
            return false;
        }
        return Carbon::parse($this->fecha_vencimiento)->isPast();
    }

    /**
     * Verificar si está próximo a vencer
     */
    public function estaProximoAVencer($dias = 30)
    {
        if (!$this->fecha_vencimiento) {
            return false;
        }
        return $this->getDiasParaVencimientoAttribute() <= $dias;
    }
}
