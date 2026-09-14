<?php

namespace App\Models;

use Database\Factories\HitoFinancieroFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HitoFinanciero extends Model
{
    /** @use HasFactory<HitoFinancieroFactory> */
    use HasFactory;

    protected $table = 'hitos_financieros';

    protected $fillable = ['innovacion_id', 'titulo', 'descripcion', 'monto_objetivo', 'fecha_limite', 'estado'];

    protected $casts = ['monto_objetivo' => 'decimal:2', 'fecha_limite' => 'date'];

    /**
     * @return BelongsTo<Innovacion, $this>
     */
    public function innovacion(): BelongsTo
    {
        return $this->belongsTo(Innovacion::class, 'innovacion_id');
    }
}
