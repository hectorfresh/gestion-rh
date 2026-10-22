<?php

namespace App\Services;

use App\Models\CandidatureModel;
use App\Models\StatutCandidatureModel;
use App\Models\ValidationSalaireModel;
use RuntimeException;

final class ValidationSalaireService
{
    public function soumettreFinance(int $candidatureId, float $salaire, int $utilisateurId, ?string $commentaire): int
    {
        $validationModel = new ValidationSalaireModel();
        if ($validationModel->estValideeParDg($candidatureId)) {
            throw new RuntimeException('Cette proposition est déjà validée par le DG pour tous les services.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $financeId = $this->enregistrerDecision(
            $candidatureId,
            'FINANCE',
            'VALIDE',
            $salaire,
            null,
            $commentaire ?: 'Validation Finance automatique.'
        );
        $this->creerEtape(
            $candidatureId,
            'DG',
            $salaire,
            null,
            'Finance validée automatiquement : soumise au DG.'
        );

        (new CandidatureModel())->update($candidatureId, [
            'statut_id' => (new StatutCandidatureModel())->idParCode('SALAIRE_ATTENTE_DG'),
        ]);

        $db->transComplete();
        if ($db->transStatus() === false) {
            throw new RuntimeException('Impossible de transmettre la proposition au DG.');
        }

        return $financeId;
    }

    public function traiterFinance(int $validationId, bool $accepter, int $utilisateurId, ?string $commentaire): int
    {
        return $this->traiterEtape($validationId, 'FINANCE', $accepter, $utilisateurId, $commentaire, 'SALAIRE_ATTENTE_DG');
    }

    public function traiterDg(int $validationId, bool $accepter, int $utilisateurId, ?string $commentaire): int
    {
        $validationModel = new ValidationSalaireModel();
        $validation = $validationModel->find($validationId);
        if (!$validation || $validation['etape'] !== 'DG' || $validation['decision'] !== 'EN_ATTENTE') {
            throw new RuntimeException('Validation salariale DG introuvable ou déjà traitée.');
        }

        $validationModel->update($validationId, [
            'decision' => $accepter ? 'VALIDE' : 'REFUSE',
            'utilisateur_id' => $utilisateurId,
            'commentaire' => $commentaire,
            'date_validation' => date('Y-m-d H:i:s'),
        ]);

        $candidatureModel = new CandidatureModel();
        $candidatureModel->update($validation['candidature_id'], [
            'salaire_final' => $accepter ? $validation['salaire_propose'] : null,
            'statut_id' => (new StatutCandidatureModel())->idParCode(
                $accepter ? 'CONDITIONS_PROPOSEES' : 'CANDIDAT_NON_RETENU'
            ),
        ]);

        return (int) $validation['candidature_id'];
    }

    private function creerEtape(int $candidatureId, string $etape, float $salaire, ?int $utilisateurId, ?string $commentaire): int
    {
        $id = (new ValidationSalaireModel())->insert([
            'candidature_id' => $candidatureId,
            'etape' => $etape,
            'decision' => 'EN_ATTENTE',
            'salaire_propose' => $salaire,
            'utilisateur_id' => $utilisateurId,
            'commentaire' => $commentaire,
            'date_validation' => date('Y-m-d H:i:s'),
        ], true);

        if ($id === false) {
            throw new RuntimeException('Impossible de créer la validation salariale.');
        }

        return (int) $id;
    }

    private function enregistrerDecision(
        int $candidatureId,
        string $etape,
        string $decision,
        float $salaire,
        ?int $utilisateurId,
        ?string $commentaire
    ): int {
        $id = (new ValidationSalaireModel())->insert([
            'candidature_id' => $candidatureId,
            'etape' => $etape,
            'decision' => $decision,
            'salaire_propose' => $salaire,
            'utilisateur_id' => $utilisateurId,
            'commentaire' => $commentaire,
            'date_validation' => date('Y-m-d H:i:s'),
        ], true);

        if ($id === false) {
            throw new RuntimeException('Impossible d’enregistrer la validation salariale.');
        }

        return (int) $id;
    }

    private function traiterEtape(
        int $validationId,
        string $etape,
        bool $accepter,
        int $utilisateurId,
        ?string $commentaire,
        string $statutSuivant
    ): int {
        $model = new ValidationSalaireModel();
        $validation = $model->find($validationId);
        if (!$validation || $validation['etape'] !== $etape || $validation['decision'] !== 'EN_ATTENTE') {
            throw new RuntimeException('Validation salariale introuvable ou déjà traitée.');
        }

        $model->update($validationId, [
            'decision' => $accepter ? 'VALIDE' : 'REFUSE',
            'utilisateur_id' => $utilisateurId,
            'commentaire' => $commentaire,
            'date_validation' => date('Y-m-d H:i:s'),
        ]);

        $candidatureModel = new CandidatureModel();
        $statutModel = new StatutCandidatureModel();
        if (!$accepter) {
            $candidatureModel->update($validation['candidature_id'], [
                'statut_id' => $statutModel->idParCode('CANDIDAT_NON_RETENU'),
            ]);
            return (int) $validation['candidature_id'];
        }

        if ($etape === 'FINANCE') {
            $this->creerEtape(
                (int) $validation['candidature_id'],
                'DG',
                (float) $validation['salaire_propose'],
                $utilisateurId,
                'Validation Finance : soumise au DG.'
            );
        }

        $candidatureModel->update($validation['candidature_id'], [
            'statut_id' => $statutModel->idParCode($statutSuivant),
        ]);

        return (int) $validation['candidature_id'];
    }
}
