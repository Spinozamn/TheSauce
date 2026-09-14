<?php

namespace App\Models;

use Database\Factories\ComentarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comentario extends Model
{
    /** @use HasFactory<ComentarioFactory> */
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'innovacion_id',
        'comentario_padre_id',
        'contenido',
        'es_respuesta',
        'fecha_comentario',
    ];

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

    /**
     * @return BelongsTo<Comentario, $this>
     */
    public function padre(): BelongsTo
    {
        return $this->belongsTo(Comentario::class, 'comentario_padre_id');
    }

    /**
     * @return HasMany<Comentario, $this>
     */
    public function respuestas(): HasMany
    {
        return $this->hasMany(Comentario::class, 'comentario_padre_id');
    }
}
