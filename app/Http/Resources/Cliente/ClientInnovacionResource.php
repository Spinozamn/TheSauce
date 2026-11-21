<?php

namespace App\Http\Resources\Cliente;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientInnovacionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $meta = (float) $this->meta_financiera;
        $recaudado = (float) $this->monto_recaudado;
        $porcentaje = $meta > 0 ? round(($recaudado / $meta) * 100, 2) : 0.0;

        return [
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'usuario_id'        => $this->usuario_id,
            'titulo'            => $this->titulo,
            'descripcion'       => $this->descripcion,
            'meta_financiera'   => number_format($meta, 2, '.', ''),
            'monto_recaudado'   => number_format($recaudado, 2, '.', ''),
            'porcentaje_avance' => $porcentaje,
            'estado'            => $this->estado,
            'imagen_url'        => $this->imagen_url,
            'categoria'         => $this->whenLoaded('categoria', function () {
                return [
                    'id'     => $this->categoria->id,
                    'nombre' => $this->categoria->nombre,
                    'icono'  => $this->categoria->icono,
                ];
            }),
            'comentarios'       => $this->whenLoaded('comentarios', function () {
                return $this->comentarios->map(function ($c) {
                    return [
                        'id'               => $c->id,
                        'contenido'        => $c->contenido,
                        'fecha_comentario' => $c->fecha_comentario ?? ($c->created_at ? $c->created_at->format('Y-m-d') : null),
                        'usuario'          => [
                            'id'     => $c->usuario?->id,
                            'nombre' => $c->usuario ? trim($c->usuario->nombre . ' ' . $c->usuario->apellido_paterno) : 'Anonimo',
                        ],
                    ];
                });
            }),
            'fecha_inicio'      => $this->fecha_inicio ? $this->fecha_inicio->toIso8601String() : null,
            'fecha_fin'         => $this->fecha_fin ? $this->fecha_fin->toIso8601String() : null,
            'created_at'        => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at'        => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
