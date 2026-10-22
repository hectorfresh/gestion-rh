<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
public function before(RequestInterface $request, $arguments = null)
{
    // 1) Non connecté → login
    if (!session()->get('user_id')) {
        return redirect()->to('/login');
    }

    // 2) Le DG a tous les rôles : il passe partout
    if (session()->get('role') === 'dg') {
        return;
    }

    // 3) Vérification du rôle demandé
    if (!empty($arguments)) {
        $roleUtilisateur = session()->get('role');
        $rolesAutorises = [];
        foreach ($arguments as $argument) {
            $rolesAutorises = array_merge($rolesAutorises, explode(',', $argument));
        }
        $rolesAutorises = array_map('trim', $rolesAutorises);

        if (!in_array($roleUtilisateur, $rolesAutorises, true)) {
            return redirect()->to('/dashboard')
                             ->with('error', 'Accès refusé.');
        }
    }
}

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}