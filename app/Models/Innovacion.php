<?php

namespace App\Models;

use Database\Factories\InnovacionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Innovacion extends Model
{
    /** @use HasFactory<InnovacionFactory> */
    use HasFactory;

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

    /**
     * @return BelongsTo<Usuario, $this>
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * @return BelongsTo<Categoria, $this>
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * @return BelongsToMany<Etiqueta, $this>
     */
    public function etiquetas(): BelongsToMany
    {
        return $this->belongsToMany(Etiqueta::class, 'etiqueta_innovacion', 'innovacion_id', 'etiqueta_id');
    }

    /**
     * @return HasMany<HitoFinanciero, $this>
     */
    public function hitosFinancieros(): HasMany
    {
        return $this->hasMany(HitoFinanciero::class, 'innovacion_id');
    }

    /**
     * @return HasMany<ListaDeseo, $this>
     */
    public function listaDeseos(): HasMany
    {
        return $this->hasMany(ListaDeseo::class, 'innovacion_id');
    }

    /**
     * @return HasMany<CarritoDetalle, $this>
     */
    public function carritoDetalles(): HasMany
    {
        return $this->hasMany(CarritoDetalle::class, 'innovacion_id');
    }

    /**
     * @return HasMany<Contribucion, $this>
     */
    public function contribuciones(): HasMany
    {
        return $this->hasMany(Contribucion::class, 'innovacion_id');
    }

    /**
     * @return HasMany<Comentario, $this>
     */
    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'innovacion_id');
    }
}
