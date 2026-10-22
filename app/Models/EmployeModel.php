<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeModel extends Model
{
    protected $table = 'employe';
    protected $primaryKey = 'id';
    protected $allowedFields = ['candidat_id', 'matricule', 'date_embauche', 'statut', 'created_at'];

    public function trouverParCandidat(int $candidatId): ?array
    {
        return $this->where('candidat_id', $candidatId)->first();
    }

    public function trouverParMatricule(string $matricule): ?array
    {
        return $this->where('matricule', $matricule)->first();
    }
}
