<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;
use App\Models\CandidatModel;


class Auth extends BaseController
{
    public function login()
    {
        return view('login');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }


    public function connexion()
    {
        $email = $this->request->getPost('email');
        $mdp = $this->request->getPost('mdp');

        $model = new UtilisateurModel();

        $user = $model->where('email', $email)
                      ->where('mdp', $mdp)
                      ->first();

        if ($user) {
            session()->set('utilisateur', $user);

            return redirect()->to('/accueil');
        }

        return redirect()->to('/login')
                         ->with('erreur', 'Email ou mot de passe incorrect');
    }

    public function accueil()
    {
        if (!session()->has('utilisateur')) {
            return redirect()->to('/login');
        }

        return view('accueil', [
            'user' => session()->get('utilisateur')
        ]);
    }

    public function loginCandidat()
    {
        return view('loginCandidat');
    }



   




    
}