<?php

namespace App\Services;

use App\Models\OnboardingModel;
use App\Models\OnboardingTacheModel;
use RuntimeException;

final class OnboardingService
{
    public function __construct(
        private readonly ?OnboardingModel $onboardings = null,
        private readonly ?OnboardingTacheModel $taches = null,
    ) {
    }

    public function creer(int $employeId, string $dateDebut, ?string $dateFinPrevue = null): int
    {
        $model = $this->onboardings ?? new OnboardingModel();
        $existant = $model->pourEmploye($employeId);
        if ($existant) {
            return (int) $existant['id'];
        }

        $id = $model->insert([
            'employe_id' => $employeId,
            'date_debut' => $dateDebut,
            'date_fin_prevue' => $dateFinPrevue,
            'statut' => 'A_PREPARER',
            'created_at' => date('Y-m-d H:i:s'),
        ], true);

        if ($id === false) {
            throw new RuntimeException('Impossible de créer le parcours d’onboarding.');
        }

        return (int) $id;
    }

    public function ajouterTache(
        int $onboardingId,
        string $libelle,
        ?string $responsable = null,
        ?string $echeance = null,
    ): int {
        $id = ($this->taches ?? new OnboardingTacheModel())->insert([
            'onboarding_id' => $onboardingId,
            'libelle' => $libelle,
            'responsable' => $responsable,
            'echeance' => $echeance,
            'statut' => 'A_FAIRE',
        ], true);

        if ($id === false) {
            throw new RuntimeException('Impossible d’ajouter la tâche d’onboarding.');
        }

        return (int) $id;
    }
}
