<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnonceModel extends Model
{
    protected $table         = 'annonce';
    protected $primaryKey    = 'id';
protected $allowedFields = [
    'besoin_id', 'rh_id', 'titre', 'description',
    'salaire_propose', 'heure_debut', 'autres_conditions',
    'date_publication', 'pourvue'
];
    // --- Pour l'affichage public (candidat) ---
  public function listerPubliques()
{
    return $this->select('annonce.*, poste.libelle AS poste_libelle,
                          poste.salaire_min, poste.salaire_max')
                ->join('besoin', 'besoin.id = annonce.besoin_id')
                ->join('poste', 'poste.id = besoin.poste_id')
                ->where('annonce.pourvue', 0)         // ← NOUVEAU
                ->orderBy('annonce.date_publication', 'DESC')
                ->findAll();
}

 public function trouverPublique(int $id)
{
    return $this->select('annonce.*, poste.libelle AS poste_libelle,
                          poste.salaire_min, poste.salaire_max,
                          besoin.nombre_postes')
                ->join('besoin', 'besoin.id = annonce.besoin_id')
                ->join('poste', 'poste.id = besoin.poste_id')
                ->where('annonce.id', $id)
                ->where('annonce.pourvue', 0)         // ← NOUVEAU
                ->first();
}

    // --- Pour l'écran RH /annonce ---
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

    public function trouverAvecDetails(int $id)
    {
        return $this->select('annonce.*, poste.libelle AS poste_libelle,
                              poste.salaire_min, poste.salaire_max,
                              besoin.nombre_postes, utilisateur.nom AS rh_nom')
                    ->join('besoin', 'besoin.id = annonce.besoin_id')
                    ->join('poste', 'poste.id = besoin.poste_id')
                    ->join('utilisateur', 'utilisateur.id = annonce.rh_id')
                    ->where('annonce.id', $id)
                    ->first();
    }


    public function marquerPourvue(int $id)
{
    return $this->update($id, ['pourvue' => 1]);
}
}