<?php

namespace App\Models;

use CodeIgniter\Model;

class StatutBesoinModel extends Model
{
    protected $table         = 'statut_besoin';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['code', 'libelle'];

    // Renvoie l'id d'un statut à partir de son code (ex: 'EN_ATTENTE_RH')
    public function idParCode(string $code)
    {
        $ligne = $this->where('code', $code)->first();
        return $ligne ? (int) $ligne['id'] : null;
    }
}