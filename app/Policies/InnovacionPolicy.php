<?php

namespace App\Policies;

use App\Models\Administrador;
use App\Models\Innovacion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InnovacionPolicy
{
    protected ClienteInnovacionPolicy $clientePolicy;
    protected InnovacionAdminPolicy $adminPolicy;

    public function __construct()
    {
        $this->clientePolicy = new ClienteInnovacionPolicy();
        $this->adminPolicy = new InnovacionAdminPolicy();
    }

    public function viewAny($user): Response
    {
        if ($user instanceof Administrador) {
            return $this->adminPolicy->viewAny($user);
        }
        if ($user instanceof User) {
            return $this->clientePolicy->viewAny($user);
        }
        return Response::deny('Usuario no autenticado para esta operacion.');
    }

    public function view($user, Innovacion $innovacion): Response
    {
        if ($user instanceof Administrador) {
            return $this->adminPolicy->view($user, $innovacion);
        }
        if ($user instanceof User) {
            return $this->clientePolicy->view($user, $innovacion);
        }
        return Response::deny('Usuario no autenticado para esta operacion.');
    }

    public function create($user): Response
    {
        if ($user instanceof Administrador) {
            return $this->adminPolicy->create($user);
        }
        if ($user instanceof User) {
            return $this->clientePolicy->create($user);
        }
        return Response::deny('Usuario no autenticado para esta operacion.');
    }

    public function update($user, Innovacion $innovacion): Response
    {
        if ($user instanceof Administrador) {
            return $this->adminPolicy->update($user, $innovacion);
        }
        if ($user instanceof User) {
            return $this->clientePolicy->update($user, $innovacion);
        }
        return Response::deny('Usuario no autenticado para esta operacion.');
    }

    public function delete($user, Innovacion $innovacion): Response
    {
        if ($user instanceof Administrador) {
            return $this->adminPolicy->delete($user, $innovacion);
        }
        if ($user instanceof User) {
            return $this->clientePolicy->delete($user, $innovacion);
        }
        return Response::deny('Usuario no autenticado para esta operacion.');
    }

    public function restore($user, Innovacion $innovacion): Response
    {
        if ($user instanceof Administrador) {
            return $this->adminPolicy->restore($user, $innovacion);
        }
        return Response::deny('Accion reservada para administradores.');
    }

    public function forceDelete($user, Innovacion $innovacion): Response
    {
        if ($user instanceof Administrador) {
            return $this->adminPolicy->forceDelete($user, $innovacion);
        }
        return Response::deny('Accion reservada para administradores.');
    }
}
