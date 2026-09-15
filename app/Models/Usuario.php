<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable {
    use HasFactory;
    protected $fillable = ['rol_id', 'nombre', 'apellido_paterno', 'apellido_materno', 'email', 'password', 'telefono', 'estado'];
    protected $hidden = ['password', 'remember_token'];

    public function rol() { return $this->belongsTo(Rol::class); }
    public function innovaciones() { return $this->hasMany(Innovacion::class); }
    public function comentarios() { return $this->hasMany(Comentario::class); }
    public function contribuciones() { return $this->hasMany(Contribucion::class); }
    public function listaDeseos() { return $this->belongsToMany(Innovacion::class, 'lista_deseos'); }
}