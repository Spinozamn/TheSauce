<?php

namespace App\Models;

use Database\Factories\InnovacionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Innovacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'innovaciones';

    protected $fillable = [
        'usuario_id',
        'categoria_id',
        'titulo',
        'descripcion',
        'imagen_portada',
        'meta_financiera',
        'monto_recaudado',
        'estado',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'meta_financiera' => 'decimal:2',
        'monto_recaudado' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function etiquetas(): BelongsToMany
    {
        return $this->belongsToMany(Etiqueta::class, 'etiqueta_innovacion', 'innovacion_id', 'etiqueta_id');
    }

    public function hitosFinancieros(): HasMany
    {
        return $this->hasMany(HitoFinanciero::class, 'innovacion_id');
    }

    public function listaDeseos(): HasMany
    {
        return $this->hasMany(ListaDeseo::class, 'innovacion_id');
    }

    public function carritoDetalles(): HasMany
    {
        return $this->hasMany(CarritoDetalle::class, 'innovacion_id');
    }

    public function contribuciones(): HasMany
    {
        return $this->hasMany(Contribucion::class, 'innovacion_id');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'innovacion_id');
    }
}