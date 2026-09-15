<?php
namespace Database\Factories;
use App\Models\ListaDeseo;
use App\Models\Usuario;
use App\Models\Innovacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListaDeseoFactory extends Factory
{
    protected $model = ListaDeseo::class;
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'innovacion_id' => Innovacion::factory(),
        ];
    }
}