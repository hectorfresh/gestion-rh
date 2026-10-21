<?php

namespace App\Controllers;

use App\Models\BesoinModel;
use App\Models\StatutBesoinModel;
use App\Models\ValidationModel;

class ValidationDG extends BaseController
{
    // Liste des besoins en attente DG
    public function index()
    {
        $besoins = (new BesoinModel())->listerEnAttenteDG();

        return view('validation_dg/index', ['besoins' => $besoins]);
    }

    // Formulaire de décision DG
    public function show($id)
    {
        $besoin = (new BesoinModel())->trouverAvecDetails((int) $id);

        if (!$besoin || $besoin['statut_code'] !== 'EN_ATTENTE_DG') {
            return redirect()->to('/dg')
                             ->with('error', 'Besoin introuvable ou déjà traité.');
        }

        return view('validation_dg/show', ['besoin' => $besoin]);
    }

    // Traitement de la décision DG
    public function traiter($id)
    {
        $besoin = (new BesoinModel())->trouverAvecDetails((int) $id);

        if (!$besoin || $besoin['statut_code'] !== 'EN_ATTENTE_DG') {
            return redirect()->to('/dg')
                             ->with('error', 'Besoin introuvable ou déjà traité.');
        }

        $decision    = $this->request->getPost('decision'); // 'valider' ou 'refuser'
        $commentaire = $this->request->getPost('commentaire') ?: null;

        if ($decision === 'refuser') {
            $this->_traiterRefus($besoin['id'], $commentaire);
            return redirect()->to('/dg')
                             ->with('success', 'Besoin refusé par le DG.');
        }

        $this->_traiterValidation($besoin['id'], $commentaire);
        return redirect()->to('/dg')
                         ->with('success', 'Besoin validé par le DG. Le RH peut créer l\'annonce.');
    }

    // --- Méthodes privées ---

    private function _traiterValidation(int $besoin_id, ?string $commentaire)
    {
        $this->_enregistrerValidation($besoin_id, 'Validé', $commentaire);
        $this->_changerStatut($besoin_id, 'VALIDE');
    }

    private function _traiterRefus(int $besoin_id, ?string $commentaire)
    {
        $this->_enregistrerValidation($besoin_id, 'Refusé', $commentaire);
        $this->_changerStatut($besoin_id, 'REFUSE_DG');
    }

    private function _changerStatut(int $besoin_id, string $code)
    {
        $statutModel = new StatutBesoinModel();
        (new BesoinModel())->update($besoin_id, [
            'statut_id' => $statutModel->idParCode($code),
        ]);
    }

    private function _enregistrerValidation(int $besoin_id, string $decision_libelle, ?string $commentaire)
    {
        $db = \Config\Database::connect();

        $etape    = $db->table('etape_validation')->where('libelle', 'DG')->get()->getRow();
        $decision = $db->table('decision')->where('libelle', $decision_libelle)->get()->getRow();

        (new ValidationModel())->insert([
            'besoin_id'           => $besoin_id,
            'utilisateur_id'      => session()->get('user_id'),
            'etape_validation_id' => $etape->id,
            'decision_id'         => $decision->id,
            'date_validation'     => date('Y-m-d H:i:s'),
            'commentaire'         => $commentaire,
        ]);
    }
}