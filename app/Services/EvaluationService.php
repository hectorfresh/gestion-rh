<?php

namespace App\Services;

use App\Models\EvaluationModel;
use RuntimeException;

final class EvaluationService
{
    public function __construct(private readonly ?EvaluationModel $evaluations = null)
    {
    }

    public function enregistrer(
        int $entretienId,
        int $evaluateurId,
        float $note,
        string $decision,
        ?string $commentaire = null,
    ): int {
        if ($note < 0 || $note > 20) {
            throw new RuntimeException('La note doit être comprise entre 0 et 20.');
        }

        $id = ($this->evaluations ?? new EvaluationModel())->insert([
            'entretien_id' => $entretienId,
            'evaluateur_id' => $evaluateurId,
            'note' => $note,
            'decision' => $decision,
            'commentaire' => $commentaire,
            'created_at' => date('Y-m-d H:i:s'),
        ], true);

        if ($id === false) {
            throw new RuntimeException('Impossible d’enregistrer l’évaluation.');
        }

        return (int) $id;
    }
}
