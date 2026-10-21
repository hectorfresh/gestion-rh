<?php

namespace App\Models;

use CodeIgniter\Model;

class ValidationModel extends Model
{
    protected $table         = 'validation';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'besoin_id', 'utilisateur_id', 'etape_validation_id',
        'decision_id', 'date_validation', 'commentaire'
    ];

    // Historique des validations d'un besoin
    public function listerParBesoin(int $besoin_id)
    {
        return $this->select('validation.*,
                              etape_validation.libelle AS etape_libelle,
                              decision.libelle AS decision_libelle,
                              utilisateur.nom AS utilisateur_nom')
                    ->join('etape_validation', 'etape_validation.id = validation.etape_validation_id')
                    ->join('decision', 'decision.id = validation.decision_id')
                    ->join('utilisateur', 'utilisateur.id = validation.utilisateur_id', 'left')
                    ->where('validation.besoin_id', $besoin_id)
                    ->orderBy('validation.date_validation', 'ASC')
                    ->findAll();
    }
}