<?php

namespace App\Services;

use App\Models\EmployeModel;
use RuntimeException;

final class EmployeService
{
    public function __construct(private readonly ?EmployeModel $employes = null)
    {
    }

    public function creerDepuisCandidat(int $candidatId, string $dateEmbauche): int
    {
        $model = $this->employes ?? new EmployeModel();
        $existant = $model->trouverParCandidat($candidatId);

        if ($existant) {
            return (int) $existant['id'];
        }

        $id = $model->insert([
            'candidat_id' => $candidatId,
            'matricule' => $this->genererMatricule($model),
            'date_embauche' => $dateEmbauche,
            'statut' => 'ACTIF',
            'created_at' => date('Y-m-d H:i:s'),
        ], true);

        if ($id === false) {
            throw new RuntimeException('Impossible de créer le dossier salarié.');
        }

        return (int) $id;
    }

    private function genererMatricule(EmployeModel $model): string
    {
        do {
            $matricule = 'EMP-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
        } while ($model->trouverParMatricule($matricule));

        return $matricule;
    }
}
