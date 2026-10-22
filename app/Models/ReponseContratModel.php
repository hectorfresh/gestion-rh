<?php

namespace App\Models;

use CodeIgniter\Model;

class ReponseContratModel extends Model
{
    protected $table = 'reponse_contrat';
    protected $primaryKey = 'id';
    protected $allowedFields = ['question_contrat_id', 'reponse', 'remarque', 'date_reponse'];

    public function pourQuestions(array $questionIds): array
    {
        if ($questionIds === []) {
            return [];
        }

        return $this->whereIn('question_contrat_id', $questionIds)->findAll();
    }
}
