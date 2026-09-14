<?php

namespace App\Models;

use Database\Factories\LogSistemaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogSistema extends Model
{
    /** @use HasFactory<LogSistemaFactory> */
    use HasFactory;

    protected $table = 'logs_sistema';

    protected $fillable = [
        'usuario_id',
        'accion',
        'entidad_afectada',
        'entidad_id',
        'detalles',
        'ip_origen',
        'user_agent',
        'fecha_registro',
    ];

    protected $casts = ['detalles' => 'array'];

    /**
     * @return BelongsTo<Usuario, $this>
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
