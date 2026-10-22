<?php

namespace App\Models;

use CodeIgniter\Model;

class EntretienModel extends Model
{
    protected $table = 'entretien';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'candidature_id', 'planifie_par', 'date_entretien', 'type',
        'statut', 'compte_rendu', 'created_at',
    ];

    public function pourCandidature(int $candidatureId): array
    {
        return $this->where('candidature_id', $candidatureId)
            ->orderBy('date_entretien', 'DESC')
            ->findAll();
    }
}
