<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Administrador extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'administradores';

    protected $fillable = [
        'nombre',
        'usuario',
        'email',
        'password',
        'activo',
        'nivel',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'activo'   => 'boolean',
            'nivel'    => 'integer',
        ];
    }
}