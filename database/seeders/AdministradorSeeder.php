<?php

namespace Database\Seeders;

use App\Models\Administrador;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministradorSeeder extends Seeder
{
    public function run(): void
    {

        Administrador::updateOrCreate(
            ['usuario' => 'admin_consulta'],
            [
                'nombre'   => 'Administrador de consulta',
                'email'    => 'consulta@thesauce.test',
                'password' => Hash::make('Admin1234#'),
                'activo'   => true,
                'nivel'    => 1,
            ]
        );

        Administrador::updateOrCreate(
            ['usuario' => 'admin_editor'],
            [
                'nombre'   => 'Administrador de edicion',
                'email'    => 'editor@thesauce.test',
                'password' => Hash::make('Admin1234#'),
                'activo'   => true,
                'nivel'    => 2,
            ]
        );

        Administrador::updateOrCreate(
            ['usuario' => 'admin_general'],
            [
                'nombre'   => 'Administrador general',
                'email'    => 'admin@thesauce.test',
                'password' => Hash::make('Admin1234#'),
                'activo'   => true,
                'nivel'    => 3,
            ]
        );

        Administrador::updateOrCreate(
            ['usuario' => 'admin_bloqueado'],
            [
                'nombre'   => 'Administrador suspendido',
                'email'    => 'bloqueado@thesauce.test',
                'password' => Hash::make('Admin1234#'),
                'activo'   => false,
                'nivel'    => 1,
            ]
        );
    }
}