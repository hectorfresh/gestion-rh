<?php

namespace App\Models;

use CodeIgniter\Model;

class BesoinModel extends Model
{
    protected $table = 'besoin';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'statut_id',
        'departement_id',
        'utilisateur_id',
        'poste_id',
        'descriptions',
        'date_souhaitee',
        'montant_min',
        'montant_max'
    ];
}