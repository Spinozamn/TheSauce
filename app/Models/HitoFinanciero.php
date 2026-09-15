<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HitoFinanciero extends Model
{
    use HasFactory;

    protected $table = 'hito_financieros';

    protected $fillable = ['innovacion_id', 'titulo', 'descripcion', 'monto_objetivo', 'fecha_limite', 'estado'];

    public function innovacion()
    {
        return $this->belongsTo(Innovacion::class);
    }
}