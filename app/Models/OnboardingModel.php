<?php

namespace App\Models;

use CodeIgniter\Model;

class OnboardingModel extends Model
{
    protected $table = 'onboarding';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employe_id', 'date_debut', 'date_fin_prevue', 'statut', 'created_at',
    ];

    public function pourEmploye(int $employeId): ?array
    {
        return $this->where('employe_id', $employeId)->first();
    }
}
