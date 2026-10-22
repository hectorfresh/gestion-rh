<?php

namespace App\Services;

use App\Models\ContratModel;
use App\Models\TypeContratModel;
use RuntimeException;

final class ContratService
{
    public function __construct(
        private readonly ?ContratModel $contrats = null,
        private readonly ?TypeContratModel $types = null,
    ) {
    }

    public function creer(
        int $candidatureId,
        int $employeId,
        string $type,
        float $salaire,
        string $dateDebut,
        ?string $dateFin = null,
    ): int {
        $typeId = ($this->types ?? new TypeContratModel())->idParLibelle($type);
        if (!$typeId) {
            throw new RuntimeException('Type de contrat inconnu.');
        }

        $model = $this->contrats ?? new ContratModel();
        $id = $model->insert([
            'candidature_id' => $candidatureId,
            'employe_id' => $employeId,
            'type_contrat_id' => $typeId,
            'salaire' => $salaire,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
        ], true);

        if ($id === false) {
            throw new RuntimeException('Impossible de créer le contrat.');
        }

        return (int) $id;
    }
}
