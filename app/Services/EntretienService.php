<?php

namespace App\Services;

use App\Models\EntretienModel;
use RuntimeException;

final class EntretienService
{
    public function __construct(private readonly ?EntretienModel $entretiens = null)
    {
    }

    public function planifier(
        int $candidatureId,
        int $planifiePar,
        string $dateEntretien,
        string $type,
    ): int {
        $id = ($this->entretiens ?? new EntretienModel())->insert([
            'candidature_id' => $candidatureId,
            'planifie_par' => $planifiePar,
            'date_entretien' => $dateEntretien,
            'type' => $type,
            'statut' => 'PLANIFIE',
            'created_at' => date('Y-m-d H:i:s'),
        ], true);

        if ($id === false) {
            throw new RuntimeException('Impossible de planifier l’entretien.');
        }

        return (int) $id;
    }
}
