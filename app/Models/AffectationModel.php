<?php

namespace App\Models;

use CodeIgniter\Model;

class AffectationModel extends Model
{
    protected $table = 'affectation';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employe_id', 'poste_id', 'departement_id', 'date_debut', 'date_fin',
    ];

    public function activePourEmploye(int $employeId): ?array
    {
        return $this->where('employe_id', $employeId)
            ->where('date_fin IS NULL', null, false)
            ->orderBy('date_debut', 'DESC')
            ->first();
    }
}
