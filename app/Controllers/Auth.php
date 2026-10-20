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



public function inscriptionCandidat()
{
    $genres = db_connect()
        ->table('gender')
        ->get()
        ->getResultArray();

    return view('inscriptionCandidat', [
        'genres' => $genres
    ]);
}

public function enregistrerCandidat()
{
    $model = new CandidatModel();

    $email = $this->request->getPost('email');

    // Vérifier si l'email existe déjà
    if ($model->where('email', $email)->first()) {
        return redirect()->back()
            ->with('erreur', 'Cet email existe déjà.');
    }

    // Enregistrer le candidat
    $model->insert([
        'nom' => $this->request->getPost('nom'),
        'prenom' => $this->request->getPost('prenom'),
        'gender_id' => $this->request->getPost('gender_id'),
        'email' => $email,
        'mdp' => password_hash(
            $this->request->getPost('mdp'),
            PASSWORD_DEFAULT
        )
    ]);

    return redirect()->to('/candidat/login')
        ->with('message', 'Inscription réussie. Connectez-vous.');
}

public function connexionCandidat()
{
    $model = new CandidatModel();

    $candidat = $model
        ->where('email', $this->request->getPost('email'))
        ->first();

    if ($candidat && password_verify(
        $this->request->getPost('mdp'),
        $candidat['mdp']
    )) {
        session()->set('candidat', $candidat);

        return redirect()->to('/candidat/accueil');
    }

    return redirect()->back()
        ->with('erreur', 'Email ou mot de passe incorrect.');
}

public function accueilCandidat()
{
    if (!session()->has('candidat')) {
        return redirect()->to('/candidat/login');
    }

    return view('accueilCandidat', [
        'candidat' => session()->get('candidat')
    ]);
}

public function logoutCandidat()
{
    session()->remove('candidat');

    return redirect()->to('/candidat/login');
}


public function postuler()
{
    if (!session()->has('candidat')) {
        return redirect()->to('/candidat/login');
    }

    $candidat = session()->get('candidat');
    $annonceId = $this->request->getPost('annonce_id');

    $db = db_connect();

    // Vérifier l'annonce
    $annonce = $db->table('annonce')
        ->where('id', $annonceId)
        ->get()
        ->getRowArray();

    if (!$annonce) {
        return redirect()->to('/annonces/liste');
    }

    // Vérifier les candidatures en double
    $existe = $db->table('candidature')
        ->where('candidat_id', $candidat['id'])
        ->where('annonce_id', $annonceId)
        ->get()
        ->getRowArray();

    if ($existe) {
        return redirect()->to('/annonces/liste')
            ->with('erreur', 'Vous avez déjà postulé.');
    }

    // Vérifier si le candidat possède déjà un CV
    $cv = $db->table('cv')
        ->where('candidat_id', $candidat['id'])
        ->get()
        ->getRowArray();

    if (!$cv) {
        $db->table('cv')->insert([
            'candidat_id' => $candidat['id']
        ]);

        $cvId = $db->insertID();
    } else {
        $cvId = $cv['id'];
    }

    // Enregistrer l'expérience si elle est renseignée
    $poste = $this->request->getPost('poste_occupe');
    $entreprise = $this->request->getPost('entreprise');
    $dateDebut = $this->request->getPost('date_debut');

    if ($poste && $entreprise && $dateDebut) {
        $db->table('experience')->insert([
            'cv_id' => $cvId,
            'poste_occupe' => $poste,
            'entreprise' => $entreprise,
            'date_debut' => $dateDebut,
            'date_fin' => $this->request->getPost('date_fin') ?: null,
            'description' => $this->request->getPost('description')
        ]);
    }

    // Enregistrer la candidature
    $db->table('candidature')->insert([
        'candidat_id' => $candidat['id'],
        'annonce_id' => $annonceId,
        'statut_id' => 1
    ]);

    return redirect()->to('/annonces/liste')
        ->with('message', 'Votre candidature a été envoyée.');
}


public function formulaireCV($annonceId)
{
    if (!session()->has('candidat')) {
        return redirect()->to('/candidat/login');
    }

    $annonce = db_connect()
        ->table('annonce')
        ->where('id', $annonceId)
        ->get()
        ->getRowArray();

    if (!$annonce) {
        return redirect()->to('/annonces/liste');
    }

    return view('formulaireCV', [
        'annonce_id' => $annonceId
    ]);
}






public function mesCandidatures()
{
    if (!session()->has('candidat')) {
        return redirect()->to('/candidat/login');
    }

    $candidat = session()->get('candidat');

    $candidatures = db_connect()
        ->table('candidature')
        ->select('candidature.daty, annonce.designation, statut.libelle AS statut')
        ->join('annonce', 'annonce.id = candidature.annonce_id')
        ->join('statut', 'statut.id = candidature.statut_id', 'left')
        ->where('candidature.candidat_id', $candidat['id'])
        ->orderBy('candidature.daty', 'DESC')
        ->get()
        ->getResultArray();

    return view('mesCandidatures', [
        'candidatures' => $candidatures
    ]);
}




}