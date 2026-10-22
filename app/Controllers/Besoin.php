<?php

namespace App\Controllers;

use App\Models\BesoinModel;
use App\Models\PosteModel;
use App\Models\StatutBesoinModel;
use App\Models\ValidationModel;
use App\Models\DepartementModel;

class Besoin extends BaseController
{
    // Liste des besoins du manager connecté
    public function index()
    {
        $model   = new BesoinModel();
        $besoins = $model->listerParManager(session()->get('user_id'));

        return view('besoin/index', ['besoins' => $besoins]);
    }

    // Formulaire de création
    public function createForm()
    {
        $postes = (new PosteModel())->orderBy('libelle', 'ASC')->findAll();

        return view('besoin/create', [
            'postes' => $postes,
            'departements' => (new DepartementModel())->actifs(),
        ]);
    }

    // Enregistrement du besoin
public function create()
{
    $rules = [
        'poste_id'      => 'required|integer',
        'nombre_postes' => 'required|integer|greater_than[0]',
        'departement_id' => 'permit_empty|integer',
        'date_souhaitee' => 'permit_empty|valid_date[Y-m-d]',
        'motif'         => 'permit_empty|max_length[255]',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()
                         ->with('error', 'Formulaire invalide.');
    }

    $poste = (new PosteModel())->find((int) $this->request->getPost('poste_id'));
    if (!$poste) {
        return redirect()->back()->withInput()
                         ->with('error', 'Poste introuvable.');
    }

    $nombre = (int) $this->request->getPost('nombre_postes');
    $cout   = $poste['salaire_max'] * $nombre;

    // 🔑 Différenciation manager / DG
    $estDG      = (session()->get('role') === 'dg');
    $statutCode = $estDG ? 'VALIDE' : 'EN_ATTENTE_RH';

    $besoinModel = new BesoinModel();
    $besoinModel->insert([
        'poste_id'      => $poste['id'],
        'manager_id'    => session()->get('user_id'),
        'departement_id' => $this->request->getPost('departement_id') ?: null,
        'nombre_postes' => $nombre,
        'cout_engage'   => $cout,
        'date_creation' => date('Y-m-d H:i:s'),
        'date_souhaitee' => $this->request->getPost('date_souhaitee') ?: null,
        'motif'         => trim((string) $this->request->getPost('motif')) ?: null,
        'statut_id'     => (new StatutBesoinModel())->idParCode($statutCode),
    ]);

    // Si DG → on trace une validation "DG" automatique
    if ($estDG) {
        $this->_enregistrerValidationDirecteDG($besoinModel->getInsertID());
    }

    $message = $estDG
        ? 'Besoin créé et validé d\'office (DG).'
        : 'Besoin créé. En attente de validation RH.';

    return redirect()->to('/besoin')->with('success', $message);
}

// --- Méthode privée : tracer la validation d'office du DG ---
private function _enregistrerValidationDirecteDG(int $besoin_id)
{
    $db = \Config\Database::connect();

    $etape    = $db->table('etape_validation')->where('libelle', 'DG')->get()->getRow();
    $decision = $db->table('decision')->where('libelle', 'Validé')->get()->getRow();

    (new \App\Models\ValidationModel())->insert([
        'besoin_id'           => $besoin_id,
        'utilisateur_id'      => session()->get('user_id'),
        'etape_validation_id' => $etape->id,
        'decision_id'         => $decision->id,
        'date_validation'     => date('Y-m-d H:i:s'),
        'commentaire'         => 'Validation automatique : besoin créé directement par le DG.',
    ]);
}

    // Détail d'un besoin
    public function show($id)
    {
        $model  = new BesoinModel();
        $besoin = $model->trouverAvecDetails((int) $id);

        if (!$besoin) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Un manager ne peut voir que ses propres besoins
        if ((int) $besoin['manager_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/besoin')->with('error', 'Accès refusé.');
        }

        $validations = (new ValidationModel())->listerParBesoin((int) $id);

        return view('besoin/show', [
            'besoin'      => $besoin,
            'validations' => $validations,
        ]);
    }
}