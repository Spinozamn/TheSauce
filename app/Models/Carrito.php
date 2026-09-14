<?php

namespace App\Models;

use Database\Factories\CarritoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrito extends Model
{
    /** @use HasFactory<CarritoFactory> */
    use HasFactory;

    protected $fillable = ['usuario_id', 'estado'];

    /**
     * @return BelongsTo<Usuario, $this>
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * @return HasMany<CarritoDetalle, $this>
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(CarritoDetalle::class, 'carrito_id');
    }
}
