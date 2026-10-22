<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluationModel extends Model
{
    protected $table = 'evaluation';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'entretien_id', 'evaluateur_id', 'note', 'decision', 'commentaire', 'created_at',
    ];

    public function pourEntretien(int $entretienId): array
    {
        return $this->where('entretien_id', $entretienId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
