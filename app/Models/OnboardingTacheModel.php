<?php

namespace App\Models;

use CodeIgniter\Model;

class OnboardingTacheModel extends Model
{
    protected $table = 'onboarding_tache';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'onboarding_id', 'libelle', 'responsable', 'echeance', 'statut', 'terminee_le',
    ];

    public function pourOnboarding(int $onboardingId): array
    {
        return $this->where('onboarding_id', $onboardingId)
            ->orderBy('echeance', 'ASC')
            ->findAll();
    }
}
