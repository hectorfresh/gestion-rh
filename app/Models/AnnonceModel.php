<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnonceModel extends Model
{
    protected $table         = 'annonce';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'besoin_id', 'rh_id', 'titre', 'description',
        'salaire_min', 'salaire_max', 'date_publication'
    ];

    // Toutes les annonces (avec libellé poste et nom RH)
    public function listerToutes()
    {
        return $this->select('annonce.*, poste.libelle AS poste_libelle,
                              besoin.nombre_postes, utilisateur.nom AS rh_nom')
                    ->join('besoin', 'besoin.id = annonce.besoin_id')
                    ->join('poste', 'poste.id = besoin.poste_id')
                    ->join('utilisateur', 'utilisateur.id = annonce.rh_id')
                    ->orderBy('annonce.date_publication', 'DESC')
                    ->findAll();
    }

    // Détail d'une annonce
    public function trouverAvecDetails(int $id)
    {
        return $this->select('annonce.*, poste.libelle AS poste_libelle,
                              besoin.nombre_postes, besoin.cout_engage,
                              utilisateur.nom AS rh_nom')
                    ->join('besoin', 'besoin.id = annonce.besoin_id')
                    ->join('poste', 'poste.id = besoin.poste_id')
                    ->join('utilisateur', 'utilisateur.id = annonce.rh_id')
                    ->where('annonce.id', $id)
                    ->first();
    }
}