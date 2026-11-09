<?php

namespace App\Policies;

use App\Models\Innovacion;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class ClienteInnovacionPolicy
{
    public function viewAny(User $user): Response
    {
        return Response::allow();
    }

    public function view(User $user, Innovacion $innovacion): Response
    {
        return $this->isOwner($user, $innovacion)
            ? Response::allow()
            : Response::deny('No tiene autorizacion para consultar este recurso ajeno.');
    }

    public function create(User $user): Response
    {
        return Response::allow();
    }

    public function update(User $user, Innovacion $innovacion): Response
    {
        return $this->isOwner($user, $innovacion)
            ? Response::allow()
            : Response::deny('No tiene autorizacion para modificar este recurso.');
    }

    public function delete(User $user, Innovacion $innovacion): Response
    {
        if (!$this->isOwner($user, $innovacion)) {
            return Response::deny('No tiene autorizacion para cancelar este recurso.');
        }

        if (!in_array($innovacion->estado, ['borrador', 'activo', 'pendiente'])) {
            return Response::deny('El recurso no se encuentra en un estado cancelable.');
        }

        return Response::allow();
    }

    protected function isOwner(User $user, Innovacion $innovacion): bool
    {
        if ($innovacion->user_id !== null && (int) $innovacion->user_id === (int) $user->id) {
            return true;
        }

        $usuario = Usuario::where('email', $user->email)->first();
        if ($usuario && (int) $innovacion->usuario_id === (int) $usuario->id) {
            return true;
        }

        return false;
    }
}
