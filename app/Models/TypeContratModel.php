<?php
namespace App\Models;
use CodeIgniter\Model;

class TypeContratModel extends Model
{
    protected $table      = 'type_contrat';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle'];

    public function idParLibelle(string $libelle)
    {
        $ligne = $this->where('libelle', $libelle)->first();
        return $ligne ? (int) $ligne['id'] : null;
    }
}