<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_ticket',
        'fecha',
        'cliente_id',
        'user_id',
        'subtotal',
        'igv',
        'total',
        'tipo_pago',
        'estado',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    /**
     * Relación con cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación con usuario (vendedor)
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Relación con detalles de venta
     */
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    /**
     * Relación con productos de la venta
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'detalle_ventas')
                    ->withPivot('cantidad', 'precio_unitario', 'subtotal');
    }

    /**
     * Generar número de ticket único
     */
    public static function generarNumeroTicket()
    {
        $fecha = Carbon::now()->format('Ymd');
        $ultimo = self::where('numero_ticket', 'like', "T{$fecha}%")
                     ->orderBy('numero_ticket', 'desc')
                     ->first();
        
        if ($ultimo) {
            $numero = intval(substr($ultimo->numero_ticket, -4)) + 1;
        } else {
            $numero = 1;
        }
        
        return "T{$fecha}" . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calcular totales de la venta
     */
    public function calcularTotales()
    {
        $this->subtotal = $this->detalles->sum('subtotal');
        $this->igv = $this->subtotal * 0.18;
        $this->total = $this->subtotal + $this->igv;
        $this->save();
    }

    /**
     * Scope para ventas del día
     */
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', Carbon::today());
    }

    /**
     * Scope para ventas del mes
     */
    public function scopeDelMes($query)
    {
        return $query->whereMonth('fecha', Carbon::now()->month)
                     ->whereYear('fecha', Carbon::now()->year);
    }

    /**
     * Scope para ventas completadas
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    /**
     * Scope para ventas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para ventas canceladas
     */
    public function scopeCanceladas($query)
    {
        return $query->where('estado', 'cancelada');
    }

    /**
     * Scope para ventas entre fechas
     */
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    /**
     * Scope para ventas de un cliente
     */
    public function scopeDelCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    /**
     * Scope para ventas de un vendedor
     */
    public function scopeDelVendedor($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para ventas por tipo de pago
     */
    public function scopePorTipoPago($query, $tipoPago)
    {
        return $query->where('tipo_pago', $tipoPago);
    }

    /**
     * Accessors
     */
    public function getNumeroFormateadoAttribute()
    {
        return str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getTotalConIgvAttribute()
    {
        return $this->subtotal + $this->igv;
    }

    public function getClienteNombreAttribute()
    {
        return $this->cliente ? $this->cliente->nombre_completo : 'Cliente General';
    }

    public function getVendedorNombreAttribute()
    {
        return $this->user->name;
    }

    public function getFechaFormateadaAttribute()
    {
        return $this->fecha->format('d/m/Y H:i');
    }

    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'warning',
            'completada' => 'success',
            'cancelada' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Métodos personalizados
     */
    public function marcarComoPendiente()
    {
        $this->estado = 'pendiente';
        $this->save();
    }

    public function marcarComoCompletada()
    {
        $this->estado = 'completada';
        $this->save();
    }

    public function marcarComoCancelada()
    {
        $this->estado = 'cancelada';
        $this->save();
    }

    public function esDelDia()
    {
        return $this->fecha->isToday();
    }

    public function puedeSerEditada()
    {
        return $this->esDelDia() && $this->estado !== 'cancelada';
    }

    public function puedeSerCancelada()
    {
        return $this->esDelDia() && $this->estado !== 'cancelada';
    }
}
