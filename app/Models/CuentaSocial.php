<?php

namespace App\Models;

use Database\Factories\CuentaSocialFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuentaSocial extends Model
{
    /** @use HasFactory<CuentaSocialFactory> */
    use HasFactory;

    protected $table = 'cuentas_sociales';

    protected $fillable = ['usuario_id', 'proveedor', 'id_proveedor', 'token', 'refresh_token', 'expira_en'];

    /**
     * @return BelongsTo<Usuario, $this>
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
