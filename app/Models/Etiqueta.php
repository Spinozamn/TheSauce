<?php

namespace App\Models;

use Database\Factories\EtiquetaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Etiqueta extends Model
{
    /** @use HasFactory<EtiquetaFactory> */
    use HasFactory;

    protected $fillable = ['nombre', 'color'];

    /**
     * @return BelongsToMany<Innovacion, $this>
     */
    public function innovaciones(): BelongsToMany
    {
        return $this->belongsToMany(Innovacion::class, 'etiqueta_innovacion', 'etiqueta_id', 'innovacion_id');
    }
}
