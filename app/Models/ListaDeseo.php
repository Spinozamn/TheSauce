<?php

namespace App\Models;

use Database\Factories\ListaDeseoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListaDeseo extends Model
{
    /** @use HasFactory<ListaDeseoFactory> */
    use HasFactory;

    protected $fillable = ['usuario_id', 'innovacion_id'];

    /**
     * @return BelongsTo<Usuario, $this>
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * @return BelongsTo<Innovacion, $this>
     */
    public function innovacion(): BelongsTo
    {
        return $this->belongsTo(Innovacion::class, 'innovacion_id');
    }
}
