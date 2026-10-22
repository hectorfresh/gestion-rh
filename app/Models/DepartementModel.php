<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartementModel extends Model
{
    protected $table = 'departement';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'libelle', 'actif'];

    public function actifs(): array
    {
        return $this->where('actif', 1)->orderBy('libelle')->findAll();
    }
}
