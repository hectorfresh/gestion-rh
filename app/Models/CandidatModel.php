<?php
namespace App\Models;
use CodeIgniter\Model;

class CAndidatModel extends MOdel{

    protected $table = 'candidat';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nom',
        'prenom',
        'gender_id',
        'email',
        'mdp',
    ];
}