<?php
namespace App\Models;
use CodeIgniter\Model;

class ContratModel extends Model
{
    protected $table      = 'contrat';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'candidature_id', 'employe_id', 'type_contrat_id', 'salaire', 'date_debut', 'date_fin'
    ];

    public function trouverParCandidature(int $candidature_id)
    {
        return $this->select('contrat.*, type_contrat.libelle AS type_libelle')
                    ->join('type_contrat', 'type_contrat.id = contrat.type_contrat_id')
                    ->where('contrat.candidature_id', $candidature_id)
                    ->first();
    }

    public function historiquePourEmploye(int $employeId): array
    {
        return $this->select('contrat.*, type_contrat.libelle AS type_libelle')
            ->join('type_contrat', 'type_contrat.id = contrat.type_contrat_id')
            ->where('contrat.employe_id', $employeId)
            ->orderBy('contrat.date_debut', 'DESC')
            ->findAll();
    }
}