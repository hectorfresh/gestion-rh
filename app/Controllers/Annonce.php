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

    // Formulaire de création à partir d'un besoin
    public function createForm($besoin_id)
    {
        $besoin = (new BesoinModel())->trouverAvecDetails((int) $besoin_id);

        if (!$besoin || $besoin['statut_code'] !== 'VALIDE') {
            return redirect()->to('/annonce')
                             ->with('error', 'Besoin introuvable ou déjà traité.');
        }

        // Sécurité : déjà une annonce ?
        $existante = (new AnnonceModel())->where('besoin_id', (int) $besoin_id)->first();
        if ($existante) {
            return redirect()->to('/annonce')
                             ->with('error', 'Une annonce existe déjà pour ce besoin.');
        }

        // On récupère la fourchette salariale du poste
        $db = \Config\Database::connect();
        $poste = $db->table('poste')->where('id', $besoin['poste_id'])->get()->getRow();

        return view('annonce/create', [
            'besoin' => $besoin,
            'poste'  => $poste,
        ]);
    }

    // Enregistrement de l'annonce
    public function create()
    {
        $besoin_id  = (int) $this->request->getPost('besoin_id');
        $titre      = $this->request->getPost('titre');
        $description = $this->request->getPost('description');

        // 1) Vérifier le besoin
        $besoin = (new BesoinModel())->trouverAvecDetails($besoin_id);
        if (!$besoin || $besoin['statut_code'] !== 'VALIDE') {
            return redirect()->to('/annonce')
                             ->with('error', 'Besoin introuvable ou déjà traité.');
        }

        // 2) Vérifier champs
        $rules = [
            'titre'       => 'required|max_length[200]',
            'description' => 'required',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                             ->with('error', 'Formulaire invalide.');
        }

        // 3) Récupérer la fourchette salariale du poste
        $db = \Config\Database::connect();
        $poste = $db->table('poste')->where('id', $besoin['poste_id'])->get()->getRow();

        // 4) Insérer l'annonce
        (new AnnonceModel())->insert([
            'besoin_id'        => $besoin_id,
            'rh_id'            => session()->get('user_id'),
            'titre'            => $titre,
            'description'      => $description,
            'salaire_min'      => $poste->salaire_min,
            'salaire_max'      => $poste->salaire_max,
            'date_publication' => date('Y-m-d H:i:s'),
        ]);

        // 5) Faire passer le besoin en RECRUTEMENT_OUVERT
        $this->_changerStatut($besoin_id, 'RECRUTEMENT_OUVERT');

        return redirect()->to('/annonce')
                         ->with('success', 'Annonce publiée. Le recrutement est ouvert.');
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
}