<?php

namespace App\Controllers;

use App\Models\PosteModel;
use App\Models\BesoinModel;

class Besoin extends BaseController
{
    public function formulaire()
    {
        if (!session()->has('utilisateur')) {
            return redirect()->to('/login');
        }

        $role = session()->get('utilisateur')['role_id'];

        if ($role != 1 && $role != 3) {
            return redirect()->to('/accueil');
        }

        $posteModel = new PosteModel();

        return view('besoin', [
            'postes' => $posteModel->findAll()
        ]);
    }

    public function enregistrer()
    {

        $besoinModel = new BesoinModel();

        if (!session()->has('utilisateur')) {
            return redirect()->to('/login');
        }

        $user = session()->get('utilisateur');

        if ($user['role_id'] == 1) {
            // Le DG passe directement à la création de l'annonce
            $statutId = 6;
        } else {
            // Le manager attend la validation RH
            $statutId = 1;
        }

        $besoinModel->insert([
            'poste_id' => $this->request->getPost('poste_id'),
            'descriptions' => $this->request->getPost('descriptions'),
            'date_souhaitee' => $this->request->getPost('date_souhaitee'),
            'montant_max' => $this->request->getPost('montant_max'),
            'statut_id' => $statutId,
            'utilisateur_id' => $user['id']
        ]);

        $id = $besoinModel->getInsertID();

        db_connect()->table('historique_statut_besoin')->insert([
            'besoin_id' => $id,
            'statut_id' => $statutId,
            'utilisateur_id' => $user['id']
        ]);

        return redirect()->to('/besoin')
            ->with('message', 'Besoin enregistré.');
    }

    public function rh()
    {
        if (!session()->has('utilisateur')) {
            return redirect()->to('/login');
        }

        if (session()->get('utilisateur')['role_id'] != 2) {
            return redirect()->to('/accueil');
        }

        $besoins = db_connect()
            ->table('besoin')
            ->select('besoin.*, poste.libelle AS poste')
            ->join('poste', 'poste.id = besoin.poste_id')
            ->where('besoin.statut_id', 1)
            ->get()
            ->getResultArray();

        return view('rh_besoins', [
            'besoins' => $besoins
        ]);
    }

    public function traiterRh()
    {
        if (!session()->has('utilisateur')) {
            return redirect()->to('/login');
        }

        $user = session()->get('utilisateur');

        if ($user['role_id'] != 2) {
            return redirect()->to('/accueil');
        }

        $id = $this->request->getPost('besoin_id');
        $action = $this->request->getPost('action');

        $besoin = db_connect()
            ->table('besoin')
            ->where('id', $id)
            ->where('statut_id', 1)
            ->get()
            ->getRowArray();

        if (!$besoin) {
            return redirect()->to('/rh')
                ->with('erreur', 'Besoin introuvable ou déjà traité.');
        }

        if ($action == 'refuser') {
            $statutId = 2;
        } else {
            // Chercher le budget de l'année demandée
            $annee = date('Y', strtotime($besoin['date_souhaitee']));

            $budget = db_connect()
                ->table('finance')
                ->join(
                    'type_finance',
                    'type_finance.id = finance.type_id'
                )
                ->where('type_finance.libelle', 'Budget recrutement')
                ->where('finance.annee', $annee)
                ->get()
                ->getRowArray();

            $coutAnnuel = $besoin['montant_max'] * 12;

            if (!$budget || $budget['montant'] < $coutAnnuel) {
                $statutId = 4;
            } else {
                // Vérifier si le créateur est le DG
                $createur = db_connect()
                    ->table('utilisateur')
                    ->where('id', $besoin['utilisateur_id'])
                    ->get()
                    ->getRowArray();

                if ($createur['role_id'] == 1) {
                    // Le DG a déjà donné son approbation
                    $statutId = 6;
                } else {
                    $statutId = 3;
                }
            }
        }

        // Modifier le statut du besoin
        db_connect()
            ->table('besoin')
            ->where('id', $id)
            ->update(['statut_id' => $statutId]);

        // Enregistrer le changement dans l'historique
        db_connect()
            ->table('historique_statut_besoin')
            ->insert([
                'besoin_id' => $id,
                'statut_id' => $statutId,
                'utilisateur_id' => $user['id']
            ]);

        return redirect()->to('/rh')
            ->with('message', 'Besoin traité.');
    }



public function dg()
{
    if (!session()->has('utilisateur')) {
        return redirect()->to('/login');
    }

    if (session()->get('utilisateur')['role_id'] != 1) {
        return redirect()->to('/accueil');
    }

    $besoins = db_connect()
        ->table('besoin')
        ->select('besoin.*, poste.libelle AS poste')
        ->join('poste', 'poste.id = besoin.poste_id')
        ->where('besoin.statut_id', 3)
        ->get()
        ->getResultArray();

    return view('dg_besoins', [
        'besoins' => $besoins
    ]);
}

public function traiterDg()
{
    if (!session()->has('utilisateur')) {
        return redirect()->to('/login');
    }

    $user = session()->get('utilisateur');

    if ($user['role_id'] != 1) {
        return redirect()->to('/accueil');
    }

    $id = $this->request->getPost('besoin_id');
    $action = $this->request->getPost('action');

    $besoin = db_connect()
        ->table('besoin')
        ->where('id', $id)
        ->where('statut_id', 3)
        ->get()
        ->getRowArray();

    if (!$besoin) {
        return redirect()->to('/dg')
            ->with('erreur', 'Besoin introuvable ou déjà traité.');
    }

    if ($action == 'valider') {
        $statutId = 6;
    } else {
        $statutId = 5;
    }

    db_connect()
        ->table('besoin')
        ->where('id', $id)
        ->update(['statut_id' => $statutId]);

    db_connect()
        ->table('historique_statut_besoin')
        ->insert([
            'besoin_id' => $id,
            'statut_id' => $statutId,
            'utilisateur_id' => $user['id']
        ]);

    return redirect()->to('/dg')
        ->with('message', 'Besoin traité.');
}




public function annonces()
{
    if (!session()->has('utilisateur')) {
        return redirect()->to('/login');
    }

    if (session()->get('utilisateur')['role_id'] != 2) {
        return redirect()->to('/accueil');
    }

    $besoins = db_connect()
        ->table('besoin')
        ->select('besoin.*, poste.libelle AS poste')
        ->join('poste', 'poste.id = besoin.poste_id')
        ->where('besoin.statut_id', 6)
        ->get()
        ->getResultArray();

    return view('annonces', [
        'besoins' => $besoins
    ]);
}

public function creerAnnonce()
{
    if (!session()->has('utilisateur')) {
        return redirect()->to('/login');
    }

    $user = session()->get('utilisateur');

    if ($user['role_id'] != 2) {
        return redirect()->to('/accueil');
    }

    $id = $this->request->getPost('besoin_id');

    $besoin = db_connect()
        ->table('besoin')
        ->where('id', $id)
        ->where('statut_id', 6)
        ->get()
        ->getRowArray();

    if (!$besoin) {
        return redirect()->to('/annonces')
            ->with('erreur', 'Besoin introuvable ou déjà traité.');
    }

// Créer l'annonce
db_connect()->table('annonce')->insert([
    'designation' => $this->request->getPost('designation'),
    'besoin_id' => $besoin['id']
]);

    // Terminer le circuit du besoin
    db_connect()->table('besoin')
        ->where('id', $id)
        ->update(['statut_id' => 7]);

    // Enregistrer l'historique
    db_connect()->table('historique_statut_besoin')->insert([
        'besoin_id' => $id,
        'statut_id' => 7,
        'utilisateur_id' => $user['id']
    ]);

    return redirect()->to('/annonces')
        ->with('message', 'Annonce créée avec succès.');
}


public function listeAnnonces()
{
    if (!session()->has('utilisateur')) {
        return redirect()->to('/login');
    }

    $annonces = db_connect()
        ->table('annonce')
        ->select('annonce.*, poste.libelle AS poste, besoin.descriptions AS description_besoin')
        ->join('besoin', 'besoin.id = annonce.besoin_id')
        ->join('poste', 'poste.id = besoin.poste_id')
        ->orderBy('annonce.daty', 'DESC')
        ->get()
        ->getResultArray();

    return view('liste_annonces', [
        'annonces' => $annonces
    ]);
}



}