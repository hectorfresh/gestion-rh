<?php
namespace App\Models;
use CodeIgniter\Model;

class ChampCvModel extends Model
{
    protected $table      = 'champ_cv';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle', 'type', 'ordre'];

    // Tous les champs avec leurs options (tableau imbriqué)
    public function tousAvecOptions()
    {
        $db = \Config\Database::connect();

        $champs = $this->orderBy('ordre', 'ASC')->findAll();

        foreach ($champs as &$c) {
            $c['options'] = $db->table('option_champ_cv')
                               ->where('champ_cv_id', $c['id'])
                               ->orderBy('ordre', 'ASC')
                               ->get()->getResultArray();
        }
        return $champs;
    }
}