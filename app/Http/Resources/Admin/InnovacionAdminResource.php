<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InnovacionAdminResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $meta = (float) $this->meta_financiera;
        $recaudado = (float) $this->monto_recaudado;
        $porcentaje = $meta > 0 ? round(($recaudado / $meta) * 100, 2) : 0.0;

        return [
            'id'                => $this->id,
            'titulo'            => $this->titulo,
            'descripcion'       => $this->descripcion,
            'meta_financiera'   => number_format($meta, 2, '.', ''),
            'monto_recaudado'   => number_format($recaudado, 2, '.', ''),
            'porcentaje_avance' => $porcentaje,
            'estado'            => $this->estado,
            'imagen_url'        => $this->imagen_url,
            'eliminado'         => $this->trashed(),
            'categoria'         => $this->whenLoaded('categoria', function () {
                return [
                    'id'     => $this->categoria->id,
                    'nombre' => $this->categoria->nombre,
                    'icono'  => $this->categoria->icono,
                ];
            }),
            'usuario_creador'   => $this->whenLoaded('usuario', function () {
                return [
                    'id'     => $this->usuario->id,
                    'nombre' => $this->usuario->nombre,
                    'email'  => $this->usuario->email,
                ];
            }),
            'fecha_inicio'      => $this->fecha_inicio ? $this->fecha_inicio->toIso8601String() : null,
            'fecha_fin'         => $this->fecha_fin ? $this->fecha_fin->toIso8601String() : null,
            'created_at'        => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at'        => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
