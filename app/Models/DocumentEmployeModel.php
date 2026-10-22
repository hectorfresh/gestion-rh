<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentEmployeModel extends Model
{
    protected $table = 'document_employe';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employe_id', 'type', 'nom_fichier', 'chemin', 'statut', 'depose_le',
    ];

    public function pourEmploye(int $employeId): array
    {
        return $this->where('employe_id', $employeId)
            ->orderBy('depose_le', 'DESC')
            ->findAll();
    }
}
