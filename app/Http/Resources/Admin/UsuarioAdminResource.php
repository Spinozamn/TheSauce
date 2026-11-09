<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioAdminResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $nombreCompleto = trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");

        return [
            'id'              => $this->id,
            'nombre'          => $this->nombre,
            'nombre_completo' => $nombreCompleto ?: $this->nombre,
            'email'           => $this->email,
            'telefono'        => $this->telefono,
            'foto_perfil'     => $this->foto_perfil,
            'estado'          => $this->estado,
            'rol'             => $this->whenLoaded('rol', function () {
                return [
                    'id'     => $this->rol->id,
                    'nombre' => $this->rol->nombre,
                ];
            }),
            'created_at'      => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at'      => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
