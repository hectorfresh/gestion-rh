<?php
namespace App\Models;
use CodeIgniter\Model;

class ParcoursCvModel extends Model
{
    protected $table      = 'parcours_cv';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'candidature_id', 'type', 'titre', 'lieu', 'date_debut', 'date_fin', 'description'
    ];

    public function listerParCandidature(int $candidature_id)
    {
        return $this->where('candidature_id', $candidature_id)
                    ->orderBy('type', 'ASC')
                    ->orderBy('date_debut', 'DESC')
                    ->findAll();
    }
}