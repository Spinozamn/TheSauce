<?php

namespace App\Models;

use Database\Factories\CategoriaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    /** @use HasFactory<CategoriaFactory> */
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'icono', 'estado'];

    /**
     * @return HasMany<Innovacion, $this>
     */
    public function innovaciones(): HasMany
    {
        return $this->hasMany(Innovacion::class, 'categoria_id');
    }
}
