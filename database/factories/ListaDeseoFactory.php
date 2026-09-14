<?php

namespace Database\Factories;

use App\Models\Innovacion;
use App\Models\ListaDeseo;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ListaDeseo>
 */
class ListaDeseoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'innovacion_id' => Innovacion::factory(),
        ];
    }
}
