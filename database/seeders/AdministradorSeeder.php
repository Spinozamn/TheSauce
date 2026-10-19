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
            ['usuario' => 'admin_general'],
            [
                'nombre'   => 'Manuel Espinoza (Activo)',
                'email'    => 'admin@thesauce.test',
                'password' => Hash::make('Admin1234#'),
                'activo'   => true,
            ]
        );

        Administrador::updateOrCreate(
            ['usuario' => 'admin_bloqueado'],
            [
                'nombre'   => 'Usuario Suspendido (Inactivo)',
                'email'    => 'bloqueado@thesauce.test',
                'password' => Hash::make('Admin1234#'),
                'activo'   => false,
            ]
        );
    }
}