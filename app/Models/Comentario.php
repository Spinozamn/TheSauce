<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $table = 'comentarios';

    protected $fillable = [
        'usuario_id',
        'innovacion_id',
        'comentario_padre_id',
        'contenido',
        'es_respuesta',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function innovacion()
    {
        return $this->belongsTo(Innovacion::class);
    }

    public function padre()
    {
        return $this->belongsTo(Comentario::class, 'comentario_padre_id');
    }

    public function comentarioPadre()
    {
        return $this->padre();
    }

    public function respuestas()
    {
        return $this->hasMany(Comentario::class, 'comentario_padre_id');
    }
}