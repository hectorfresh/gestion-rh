<?php
namespace App\Models;
use CodeIgniter\Model;

class EssaiModel extends Model
{
    protected $table      = 'essai';
    protected $primaryKey = 'id';
    protected $allowedFields = ['candidature_id', 'date_debut', 'date_fin', 'commentaire'];

    public function trouverParCandidature(int $candidature_id)
    {
        return $this->where('candidature_id', $candidature_id)->first();
    }
}