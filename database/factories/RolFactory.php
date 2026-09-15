<?php

namespace Database\Factories;

use App\Models\Rol;
use Illuminate\Database\Eloquent\Factories\Factory;

class RolFactory extends Factory
{
    protected $model = Rol::class;

    private static array $nombres = [
        'Administrador', 'Moderador', 'Creador', 'Cliente', 'Editor',
        'Analista', 'Soporte', 'Finanzas', 'Marketing', 'Invitado',
        'VIP', 'Básico', 'Premium', 'Gold', 'Silver', 'Bronze',
        'Consultor', 'Auditor', 'Supervisor', 'Operador',
    ];

    private static int $contador = 0;

    public function definition(): array
    {
        $indice = self::$contador % count(self::$nombres);
        $nombre = self::$nombres[$indice];
        
        // Si el contador supera el tamaño del array, agregar sufijo numérico
        if (self::$contador >= count(self::$nombres)) {
            $nombre .= ' ' . (intdiv(self::$contador, count(self::$nombres)) + 1);
        }
        
        self::$contador++;

        return [
            'nombre' => $nombre,
            'descripcion' => fake()->sentence(),
        ];
    }
}