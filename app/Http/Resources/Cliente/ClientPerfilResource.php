<?php

namespace App\Http\Resources\Cliente;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientPerfilResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $perfil = $this->usuario;

        return [
            'id'         => $this->id,
            'nombre'     => $this->name,
            'email'      => $this->email,
            'telefono'   => $perfil?->telefono ?? null,
            'rol'        => 'Cliente',
            'estado'     => $perfil?->estado ?? 'activo',
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
