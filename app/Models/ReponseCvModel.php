<?php
namespace App\Models;
use CodeIgniter\Model;

class ReponseCvModel extends Model
{
    protected $table      = 'reponse_cv';
    protected $primaryKey = 'id';
    protected $allowedFields = ['candidature_id', 'question_cv_id', 'valeur'];

    // Réponses d'une candidature avec libellé de question
    public function listerParCandidature(int $candidature_id)
    {
        return $this->select('reponse_cv.*, question_cv.libelle, question_cv.ordre')
                    ->join('question_cv', 'question_cv.id = reponse_cv.question_cv_id')
                    ->where('reponse_cv.candidature_id', $candidature_id)
                    ->orderBy('question_cv.ordre', 'ASC')
                    ->findAll();
    }
}