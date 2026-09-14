<?php

namespace App\Models;

use Database\Factories\ContribucionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contribucion extends Model
{
    /** @use HasFactory<ContribucionFactory> */
    use HasFactory;

    protected $table = 'contribuciones';

    protected $fillable = [
        'usuario_id',
        'innovacion_id',
        'monto',
        'metodo_pago',
        'estado',
        'referencia_transaccion',
        'fecha_contribucion',
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
}
