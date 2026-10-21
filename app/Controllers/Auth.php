<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;

class Auth extends BaseController
{
    // Page d'accueil : redirige selon état de connexion
public function index()
{
    if (session()->get('user_id'))     return redirect()->to('/dashboard');
    if (session()->get('candidat_id')) return redirect()->to('/candidat/dashboard');
    return redirect()->to('/annonces');
}

    // Affiche le formulaire de login
    public function loginForm()
    {
        return view('auth/login');
    }

    // Traite la soumission du login
    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model = new UtilisateurModel();
        $user  = $model->trouverParEmail($email);

        // Vérification email + mot de passe
        if (!$user || !password_verify($password, $user['mot_de_passe'])) {
            return redirect()->to('/login')
                             ->with('error', 'Email ou mot de passe incorrect.');
        }

        // On récupère le rôle et on stocke en session
        $userComplet = $model->trouverAvecRole($user['id']);

        session()->set([
            'user_id' => $user['id'],
            'nom'     => $user['nom'],
            'email'   => $user['email'],
            'role'    => strtolower($userComplet['role_libelle']), // manager, rh, dg
        ]);

        return redirect()->to('/dashboard');
    }

    // Déconnexion
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}