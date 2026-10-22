<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionContratModel extends Model
{
    protected $table = 'question_contrat';
    protected $primaryKey = 'id';
    protected $allowedFields = ['candidature_id', 'libelle', 'proposition', 'ordre'];

    public function pourCandidature(int $candidatureId): array
    {
        return $this->where('candidature_id', $candidatureId)
            ->orderBy('ordre', 'ASC')
            ->findAll();
    }
}
