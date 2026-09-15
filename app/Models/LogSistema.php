<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSistema extends Model {
    use HasFactory;
    protected $table = 'logs_sistema';
    protected $fillable = ['usuario_id', 'accion', 'entidad_afectada', 'entidad_id', 'detalles', 'ip_origen', 'user_agent'];
    protected $casts = ['detalles' => 'array'];
    public function usuario() { return $this->belongsTo(Usuario::class); }
}