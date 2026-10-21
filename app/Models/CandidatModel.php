<?php
namespace App\Models;
use CodeIgniter\Model;

class CandidatModel extends Model
{
    protected $table      = 'candidat';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nom', 'prenom', 'email', 'mot_de_passe', 'telephone',
        'diplome', 'annees_experience', 'competences', 'motivation'
    ];

    public function trouverParEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }
}