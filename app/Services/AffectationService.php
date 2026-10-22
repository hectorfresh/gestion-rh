<?php

namespace App\Services;

use App\Models\AffectationModel;
use RuntimeException;

final class AffectationService
{
    public function __construct(private readonly ?AffectationModel $affectations = null)
    {
    }

    public function affecter(
        int $employeId,
        int $posteId,
        ?int $departementId,
        string $dateDebut,
    ): int {
        $id = ($this->affectations ?? new AffectationModel())->insert([
            'employe_id' => $employeId,
            'poste_id' => $posteId,
            'departement_id' => $departementId,
            'date_debut' => $dateDebut,
        ], true);

        if ($id === false) {
            throw new RuntimeException('Impossible de créer l’affectation.');
        }

        return (int) $id;
    }
}
