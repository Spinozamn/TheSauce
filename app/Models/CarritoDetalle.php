<?php

namespace App\Models;

use Database\Factories\CarritoDetalleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarritoDetalle extends Model
{
    /** @use HasFactory<CarritoDetalleFactory> */
    use HasFactory;

    protected $fillable = ['carrito_id', 'innovacion_id', 'monto_comprometido', 'mensaje_apoyo', 'recompensa_seleccionada'];

    /**
     * @return BelongsTo<Carrito, $this>
     */
    public function carrito(): BelongsTo
    {
        return $this->belongsTo(Carrito::class, 'carrito_id');
    }

    /**
     * @return BelongsTo<Innovacion, $this>
     */
    public function innovacion(): BelongsTo
    {
        return $this->belongsTo(Innovacion::class, 'innovacion_id');
    }
}
