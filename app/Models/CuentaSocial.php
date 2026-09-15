<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaSocial extends Model {
    use HasFactory;
    protected $table = 'cuentas_sociales';
    protected $fillable = ['usuario_id', 'proveedor', 'id_proveedor', 'token', 'refresh_token', 'expira_en'];
    public function usuario() { return $this->belongsTo(Usuario::class); }
}