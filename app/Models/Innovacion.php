<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Innovacion extends Model
{
    use HasFactory;

    // 👇 ¡ESTA LÍNEA ES OBLIGATORIA!
    protected $table = 'innovaciones';

    protected $fillable = [
        'usuario_id', 'categoria_id', 'titulo', 'descripcion', 
        'imagen_portada', 'meta_financiera', 'monto_recaudado', 
        'estado', 'fecha_inicio', 'fecha_fin'
    ];

    public function usuario() { return $this->belongsTo(Usuario::class); }
    public function categoria() { return $this->belongsTo(Categoria::class); }
    public function etiquetas() { return $this->belongsToMany(Etiqueta::class, 'etiqueta_innovacion'); }
    public function hitos() { return $this->hasMany(HitoFinanciero::class); }
    public function comentarios() { return $this->hasMany(Comentario::class); }
}