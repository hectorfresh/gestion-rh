<?php

namespace App\Controllers;

use App\Models\AnnonceModel;
use App\Models\BesoinModel;
use App\Models\StatutBesoinModel;

class Annonce extends BaseController
{
    // Liste : besoins validés sans annonce + annonces existantes
    public function index()
    {
        $besoinModel  = new BesoinModel();
        $annonceModel = new AnnonceModel();

        return view('annonce/index', [
            'aTraiter' => $besoinModel->listerValidesSansAnnonce(),
            'annonces' => $annonceModel->listerToutes(),
        ]);
    }
// --- Nouvelles méthodes publiques ---
public function publiques()
{
    $annonces = (new \App\Models\AnnonceModel())->listerPubliques();
    return view('annonces/index', ['annonces' => $annonces]);
}

public function publique($id)
{
    $annonce = (new \App\Models\AnnonceModel())->trouverPublique((int) $id);
    if (!$annonce) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    return view('annonces/show', ['annonce' => $annonce]);
}


    // Formulaire de création à partir d'un besoin
    public function createForm($besoin_id)
{
    $besoin = (new BesoinModel())->trouverAvecDetails((int) $besoin_id);
    if (!$besoin) {
        return redirect()->to('/annonce')->with('error', 'Besoin introuvable.');
    }

    $db = \Config\Database::connect();
    $nbAnnonces  = $db->table('annonce')->where('besoin_id', (int) $besoin_id)->countAllResults();
    $nbRestantes = (int) $besoin['nombre_postes'] - $nbAnnonces;

    if ($nbRestantes <= 0) {
        return redirect()->to('/annonce')
                         ->with('error', 'Toutes les annonces de ce besoin sont déjà créées.');
    }

    $poste = $db->table('poste')->where('id', $besoin['poste_id'])->get()->getRow();

    return view('annonce/create', [
        'besoin'      => $besoin,
        'poste'       => $poste,
        'nbAnnonces'  => $nbAnnonces,
        'nbRestantes' => $nbRestantes,
    ]);
}

public function create()
{
    $besoin_id = (int) $this->request->getPost('besoin_id');
    $besoin    = (new BesoinModel())->trouverAvecDetails($besoin_id);

    if (!$besoin) {
        return redirect()->to('/annonce')->with('error', 'Besoin introuvable.');
    }

    $db = \Config\Database::connect();
    $nbAnnonces  = $db->table('annonce')->where('besoin_id', $besoin_id)->countAllResults();
    $nbRestantes = (int) $besoin['nombre_postes'] - $nbAnnonces;

    if ($nbRestantes <= 0) {
        return redirect()->to('/annonce')
                         ->with('error', 'Toutes les annonces de ce besoin sont déjà créées.');
    }

    // Validation
    $rules = [
        'titre'           => 'required|max_length[200]',
        'description'     => 'required',
        'salaire_propose' => 'required|numeric|greater_than[0]',
        'heure_debut'     => 'required',
    ];
    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('error', 'Formulaire invalide.');
    }

    // Vérification de la fourchette salariale
    $poste   = $db->table('poste')->where('id', $besoin['poste_id'])->get()->getRow();
    $salaire = (float) $this->request->getPost('salaire_propose');
    if ($salaire < $poste->salaire_min || $salaire > $poste->salaire_max) {
        return redirect()->back()->withInput()
                         ->with('error', "Salaire hors fourchette ({$poste->salaire_min} - {$poste->salaire_max}).");
    }

    // Création des N annonces (une par poste manquant)
    $titreBase    = $this->request->getPost('titre');
    $annonceModel = new \App\Models\AnnonceModel();

    for ($i = 1; $i <= $nbRestantes; $i++) {
        // Suffixe le titre pour différencier les annonces (1/2), (2/2)...
        $titre = ($nbRestantes > 1)
            ? "{$titreBase} (" . ($nbAnnonces + $i) . "/{$besoin['nombre_postes']})"
            : $titreBase;

        $annonceModel->insert([
            'besoin_id'         => $besoin_id,
            'rh_id'             => session()->get('user_id'),
            'titre'             => $titre,
            'description'       => $this->request->getPost('description'),
            'salaire_propose'   => $salaire,
            'heure_debut'       => $this->request->getPost('heure_debut'),
            'autres_conditions' => $this->request->getPost('autres_conditions') ?: null,
            'date_publication'  => date('Y-m-d H:i:s'),
            'pourvue'           => 0,
        ]);
    }

    // Mise à jour du statut du besoin
    $nbApres     = $db->table('annonce')->where('besoin_id', $besoin_id)->countAllResults();
    $statutModel = new \App\Models\StatutBesoinModel();
    $code        = ($nbApres >= (int) $besoin['nombre_postes']) ? 'RECRUTEMENT_OUVERT' : 'VALIDE';
    (new BesoinModel())->update($besoin_id, ['statut_id' => $statutModel->idParCode($code)]);

    $message = ($nbRestantes > 1)
        ? "{$nbRestantes} annonces publiées pour ce besoin."
        : "Annonce publiée.";

    return redirect()->to('/annonce')->with('success', $message);
}


// Détail d'une annonce
    public function show($id)
    {
        $annonce = (new AnnonceModel())->trouverAvecDetails((int) $id);

        if (!$annonce) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('annonce/show', ['annonce' => $annonce]);
    }

    // --- Méthode privée ---
    private function _changerStatut(int $besoin_id, string $code)
    {
        $statutModel = new StatutBesoinModel();
        (new BesoinModel())->update($besoin_id, [
            'statut_id' => $statutModel->idParCode($code),
        ]);
    }



    // Clôturer une annonce manuellement
public function cloturer($id)
{
    (new \App\Models\AnnonceModel())->marquerPourvue((int) $id);
    return redirect()->to('/annonce')->with('success', 'Annonce clôturée.');
}
}