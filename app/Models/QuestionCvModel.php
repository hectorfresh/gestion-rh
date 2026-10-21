<?php
namespace App\Models;
use CodeIgniter\Model;

class QuestionCvModel extends Model
{
    protected $table      = 'question_cv';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle', 'ordre'];

    // Liste des questions triées
    public function toutesOrdonnees()
    {
        return $this->orderBy('ordre', 'ASC')->findAll();
    }
}