<?php

namespace App\Models;

use CodeIgniter\Model;

class PosteModel extends Model
{
    protected $table         = 'poste';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['libelle'];
}