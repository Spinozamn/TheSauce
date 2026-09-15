<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model {
    use HasFactory;
    protected $table = 'carritos';
    protected $fillable = ['usuario_id', 'estado'];
    public function usuario() { return $this->belongsTo(Usuario::class); }
    public function detalles() { return $this->hasMany(CarritoDetalle::class); }
}