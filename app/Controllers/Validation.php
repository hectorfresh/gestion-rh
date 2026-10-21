<?php

namespace App\Controllers;

use App\Models\BesoinModel;
use App\Models\StatutBesoinModel;
use App\Models\ValidationModel;
use App\Models\BudgetModel;

class Validation extends BaseController
{
    // Liste des besoins en attente de validation RH
    public function index()
    {
        $besoins = (new BesoinModel())->listerEnAttenteRH();
        $budget  = new BudgetModel();

        return view('validation/index', [
            'besoins'  => $besoins,
            'restant'  => $budget->restant(),
            'total'    => $budget->montantTotal(),
            'engage'   => $budget->totalEngage(),
        ]);
    }

    // Formulaire de décision RH
    public function show($id)
    {
        $besoin = (new BesoinModel())->trouverAvecDetails((int) $id);

        if (!$besoin || $besoin['statut_code'] !== 'EN_ATTENTE_RH') {
            return redirect()->to('/validation')
                             ->with('error', 'Besoin introuvable ou déjà traité.');
        }

        $budget = new BudgetModel();

        return view('validation/show', [
            'besoin'   => $besoin,
            'restant'  => $budget->restant(),
            'engage'   => $budget->totalEngage(),
            'total'    => $budget->montantTotal(),
        ]);
    }

    // Traitement de la décision RH
    public function traiter($id)
    {
        $besoinModel = new BesoinModel();
        $besoin      = $besoinModel->trouverAvecDetails((int) $id);

        if (!$besoin || $besoin['statut_code'] !== 'EN_ATTENTE_RH') {
            return redirect()->to('/validation')
                             ->with('error', 'Besoin introuvable ou déjà traité.');
        }

        $decision    = $this->request->getPost('decision'); // 'valider' ou 'refuser'
        $commentaire = $this->request->getPost('commentaire') ?: null;

        if ($decision === 'refuser') {
            $this->_refuser($besoin, $commentaire);
            return redirect()->to('/validation')
                             ->with('success', 'Besoin refusé par le RH.');
        }

        // Le RH valide → on délègue la suite à la logique budget
        return $this->_validerPuisVerifierBudget($besoin, $commentaire);
    }

    // --- Méthodes privées ---

    private function _refuser(array $besoin, ?string $commentaire)
    {
        $this->_enregistrerValidation(
            $besoin['id'],
            session()->get('user_id'),
            'RH',
            'Refusé',
            $commentaire
        );
        $this->_changerStatut($besoin['id'], 'REFUSE_RH');
    }

    private function _validerPuisVerifierBudget(array $besoin, ?string $commentaire)
    {
        // 1) Enregistrer la validation RH
        $this->_enregistrerValidation(
            $besoin['id'],
            session()->get('user_id'),
            'RH',
            'Validé',
            $commentaire
        );

        // 2) Vérification automatique du budget (rôle "finance")
        $budget  = new BudgetModel();
        $restant = $budget->restant();

        if ($besoin['cout_engage'] <= $restant) {
            // Budget suffisant → validation finance automatique
            $this->_enregistrerValidation(
                $besoin['id'],
                null,                 // pas d'utilisateur : automatique
                'FINANCE',
                'Validé',
                'Validation automatique : budget suffisant.'
            );
            $this->_changerStatut($besoin['id'], 'EN_ATTENTE_DG');

            return redirect()->to('/validation')
                             ->with('success', 'Besoin validé. En attente du DG.');
        }

        // Budget insuffisant → refus finance automatique
        $this->_enregistrerValidation(
            $besoin['id'],
            null,
            'FINANCE',
            'Refusé',
            'Budget insuffisant (coût : ' . $besoin['cout_engage']
                . ' Ar, restant : ' . $restant . ' Ar).'
        );
        $this->_changerStatut($besoin['id'], 'REFUSE_FINANCE');

        return redirect()->to('/validation')
                         ->with('error', 'Budget insuffisant : besoin refusé automatiquement par la finance.');
    }

    private function _changerStatut(int $besoin_id, string $code)
    {
        $statutModel = new StatutBesoinModel();
        (new BesoinModel())->update($besoin_id, [
            'statut_id' => $statutModel->idParCode($code),
        ]);
    }

    private function _enregistrerValidation(
        int $besoin_id,
        ?int $utilisateur_id,
        string $etape_libelle,
        string $decision_libelle,
        ?string $commentaire
    ) {
        $db = \Config\Database::connect();

        $etape    = $db->table('etape_validation')->where('libelle', $etape_libelle)->get()->getRow();
        $decision = $db->table('decision')->where('libelle', $decision_libelle)->get()->getRow();

        (new ValidationModel())->insert([
            'besoin_id'           => $besoin_id,
            'utilisateur_id'      => $utilisateur_id,
            'etape_validation_id' => $etape->id,
            'decision_id'         => $decision->id,
            'date_validation'     => date('Y-m-d H:i:s'),
            'commentaire'         => $commentaire,
        ]);
    }
}