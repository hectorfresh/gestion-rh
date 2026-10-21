<?php
namespace App\Models;
use CodeIgniter\Model;

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
}