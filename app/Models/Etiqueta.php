<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{
    use HasFactory;

    protected $table = 'etiquetas';

    protected $fillable = ['nombre', 'color'];

    public function innovaciones()
    {
        return $this->belongsToMany(Innovacion::class, 'etiqueta_innovacion');
    }
}