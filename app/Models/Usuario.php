<?php

namespace App\Models;

use Database\Factories\UsuarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Usuario extends Model
{
    /** @use HasFactory<UsuarioFactory> */
    use HasFactory;

    protected $fillable = [
        'rol_id',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'email',
        'password',
        'telefono',
        'foto_perfil',
        'estado',
    ];

    protected $hidden = ['password', 'remember_token'];

    /**
     * @return BelongsTo<Rol, $this>
     */
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     * @return HasMany<CuentaSocial, $this>
     */
    public function cuentasSociales(): HasMany
    {
        return $this->hasMany(CuentaSocial::class, 'usuario_id');
    }

    /**
     * @return HasMany<Innovacion, $this>
     */
    public function innovaciones(): HasMany
    {
        return $this->hasMany(Innovacion::class, 'usuario_id');
    }

    /**
     * @return HasMany<ListaDeseo, $this>
     */
    public function listaDeseos(): HasMany
    {
        return $this->hasMany(ListaDeseo::class, 'usuario_id');
    }

    /**
     * @return HasOne<Carrito, $this>
     */
    public function carrito(): HasOne
    {
        return $this->hasOne(Carrito::class, 'usuario_id');
    }

    /**
     * @return HasMany<Contribucion, $this>
     */
    public function contribuciones(): HasMany
    {
        return $this->hasMany(Contribucion::class, 'usuario_id');
    }

    /**
     * @return HasMany<Comentario, $this>
     */
    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'usuario_id');
    }

    /**
     * @return HasMany<LogSistema, $this>
     */
    public function logsSistema(): HasMany
    {
        return $this->hasMany(LogSistema::class, 'usuario_id');
    }

    /**
     * @return HasMany<Notificacion, $this>
     */
    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'usuario_id');
    }
}
