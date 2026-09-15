<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarritoDetalle extends Model {
    use HasFactory;
    protected $table = 'carrito_detalles';
    protected $fillable = ['carrito_id', 'innovacion_id', 'monto_comprometido', 'mensaje_apoyo', 'recompensa_seleccionada'];
    public function carrito() { return $this->belongsTo(Carrito::class); }
    public function innovacion() { return $this->belongsTo(Innovacion::class); }
}