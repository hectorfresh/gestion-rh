<?php
namespace App\Models;
use CodeIgniter\Model;

class ValeurCvModel extends Model
{
    protected $table      = 'valeur_cv';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'candidature_id', 'champ_cv_id', 'option_champ_cv_id', 'valeur_texte'
    ];

    // Renvoie le CV formaté : libellé du champ + valeur affichable
    public function listerParCandidature(int $candidature_id)
    {
        return $this->select('valeur_cv.*, champ_cv.libelle AS champ_libelle,
                              champ_cv.type AS champ_type, champ_cv.ordre,
                              option_champ_cv.valeur AS option_valeur')
                    ->join('champ_cv', 'champ_cv.id = valeur_cv.champ_cv_id')
                    ->join('option_champ_cv', 'option_champ_cv.id = valeur_cv.option_champ_cv_id', 'left')
                    ->where('valeur_cv.candidature_id', $candidature_id)
                    ->orderBy('champ_cv.ordre', 'ASC')
                    ->findAll();
    }
}