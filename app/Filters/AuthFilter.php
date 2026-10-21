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
        if (!in_array($roleUtilisateur, $arguments)) {
            return redirect()->to('/dashboard')
                             ->with('error', 'Accès refusé.');
        }
    }
}

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}