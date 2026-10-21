<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilisateurModel extends Model
{
    protected $table         = 'utilisateur';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['nom', 'email', 'mot_de_passe', 'role_id'];

    // Trouve un utilisateur par email
    public function trouverParEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    // Récupère un utilisateur avec le libellé de son rôle
    public function trouverAvecRole(int $id)
    {
        return $this->select('utilisateur.*, role.libelle AS role_libelle')
                    ->join('role', 'role.id = utilisateur.role_id')
                    ->where('utilisateur.id', $id)
                    ->first();
    }
}