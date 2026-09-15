<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaDeseo extends Model {
    use HasFactory;
    protected $table = 'lista_deseos';
    protected $fillable = ['usuario_id', 'innovacion_id'];
    public function usuario() { return $this->belongsTo(Usuario::class); }
    public function innovacion() { return $this->belongsTo(Innovacion::class); }
}