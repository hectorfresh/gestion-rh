<?php
namespace App\Models;
use CodeIgniter\Model;
use RuntimeException;

class StatutCandidatureModel extends Model
{
    protected $table      = 'statut_candidature';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'libelle'];

    public function idParCode(string $code)
    {
        $ligne = $this->where('code', $code)->first();
        return $ligne ? (int) $ligne['id'] : null;
    }

    public function idParCodeObligatoire(string $code): int
    {
        $id = $this->idParCode($code);
        if ($id === null) {
            throw new RuntimeException("Le statut de candidature « {$code} » est absent de la base de données.");
        }

        return $id;
    }
}