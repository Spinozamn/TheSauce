<?php

namespace App\Policies;

use App\Models\Administrador;
use App\Models\Innovacion;
use Illuminate\Auth\Access\Response;

class InnovacionAdminPolicy
{
    public function viewAny($user): Response
    {
        return $user instanceof Administrador && $user->activo
            ? Response::allow()
            : Response::deny('Se requieren permisos de administrador.');
    }

    public function view($user, Innovacion $innovacion): Response
    {
        return $user instanceof Administrador && $user->activo
            ? Response::allow()
            : Response::deny('Se requieren permisos de administrador.');
    }

    public function create($user): Response
    {
        if (!($user instanceof Administrador) || !$user->activo) {
            return Response::deny('Se requieren permisos de administrador.');
        }

        return $user->nivel >= 2
            ? Response::allow()
            : Response::deny('Se requiere nivel 2 de administrador o superior para registrar recursos.');
    }

    public function update($user, Innovacion $innovacion): Response
    {
        if (!($user instanceof Administrador) || !$user->activo) {
            return Response::deny('Se requieren permisos de administrador.');
        }

        return $user->nivel >= 2
            ? Response::allow()
            : Response::deny('Se requiere nivel 2 de administrador o superior para editar recursos.');
    }

    public function delete($user, Innovacion $innovacion): Response
    {
        if (!($user instanceof Administrador) || !$user->activo) {
            return Response::deny('Se requieren permisos de administrador.');
        }

        return $user->nivel >= 2
            ? Response::allow()
            : Response::deny('Se requiere nivel 2 de administrador o superior para eliminar recursos.');
    }

    public function restore($user, Innovacion $innovacion): Response
    {
        if (!($user instanceof Administrador) || !$user->activo) {
            return Response::deny('Se requieren permisos de administrador.');
        }

        return $user->nivel >= 2
            ? Response::allow()
            : Response::deny('Se requiere nivel 2 de administrador o superior para restaurar recursos.');
    }

    public function forceDelete($user, Innovacion $innovacion): Response
    {
        if (!($user instanceof Administrador) || !$user->activo) {
            return Response::deny('Se requieren permisos de administrador.');
        }

        return $user->nivel === 3
            ? Response::allow()
            : Response::deny('Se requiere nivel 3 (administrador general) para eliminacion definitiva.');
    }
}
