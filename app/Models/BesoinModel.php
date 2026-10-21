<?php

namespace App\Models;

use CodeIgniter\Model;

class BesoinModel extends Model
{
    protected $table         = 'besoin';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'poste_id', 'manager_id', 'nombre_postes',
        'cout_engage', 'date_creation', 'statut_id'
    ];

    // Liste des besoins d'un manager (avec libellés poste + statut)
    public function listerParManager(int $manager_id)
    {
        return $this->select('besoin.*, poste.libelle AS poste_libelle,
                              statut_besoin.libelle AS statut_libelle,
                              statut_besoin.code AS statut_code')
                    ->join('poste', 'poste.id = besoin.poste_id')
                    ->join('statut_besoin', 'statut_besoin.id = besoin.statut_id')
                    ->where('besoin.manager_id', $manager_id)
                    ->orderBy('besoin.date_creation', 'DESC')
                    ->findAll();
    }

    // Détail d'un besoin avec les libellés utiles
    public function trouverAvecDetails(int $id)
    {
        return $this->select('besoin.*, poste.libelle AS poste_libelle,
                              statut_besoin.libelle AS statut_libelle,
                              statut_besoin.code AS statut_code,
                              utilisateur.nom AS manager_nom')
                    ->join('poste', 'poste.id = besoin.poste_id')
                    ->join('statut_besoin', 'statut_besoin.id = besoin.statut_id')
                    ->join('utilisateur', 'utilisateur.id = besoin.manager_id')
                    ->where('besoin.id', $id)
                    ->first();
    }

    // Besoins en attente de validation RH (pour l'écran RH)
public function listerEnAttenteRH()
{
    return $this->select('besoin.*, poste.libelle AS poste_libelle,
                          utilisateur.nom AS manager_nom,
                          statut_besoin.libelle AS statut_libelle')
                ->join('poste', 'poste.id = besoin.poste_id')
                ->join('utilisateur', 'utilisateur.id = besoin.manager_id')
                ->join('statut_besoin', 'statut_besoin.id = besoin.statut_id')
                ->where('statut_besoin.code', 'EN_ATTENTE_RH')
                ->orderBy('besoin.date_creation', 'ASC')
                ->findAll();
}


// Besoins en attente de validation DG
public function listerEnAttenteDG()
{
    return $this->select('besoin.*, poste.libelle AS poste_libelle,
                          utilisateur.nom AS manager_nom,
                          statut_besoin.libelle AS statut_libelle')
                ->join('poste', 'poste.id = besoin.poste_id')
                ->join('utilisateur', 'utilisateur.id = besoin.manager_id')
                ->join('statut_besoin', 'statut_besoin.id = besoin.statut_id')
                ->where('statut_besoin.code', 'EN_ATTENTE_DG')
                ->orderBy('besoin.date_creation', 'ASC')
                ->findAll();
}


// Besoins validés qui n'ont pas encore d'annonce (écran RH)
public function listerValidesSansAnnonce()
{
    return $this->select('besoin.*, poste.libelle AS poste_libelle,
                          poste.salaire_min, poste.salaire_max,
                          utilisateur.nom AS manager_nom')
                ->join('poste', 'poste.id = besoin.poste_id')
                ->join('utilisateur', 'utilisateur.id = besoin.manager_id')
                ->join('statut_besoin', 'statut_besoin.id = besoin.statut_id')
                ->join('annonce', 'annonce.besoin_id = besoin.id', 'left')
                ->where('statut_besoin.code', 'VALIDE')
                ->where('annonce.id', null)
                ->orderBy('besoin.date_creation', 'ASC')
                ->findAll();
}



}