<?php
namespace App\Models;
use CodeIgniter\Model;

class CandidatureModel extends Model
{
    protected $table      = 'candidature';
    protected $primaryKey = 'id';
protected $allowedFields = [
    'annonce_id', 'candidat_id', 'statut_id',
    'salaire_negocie', 'salaire_final', 'date_candidature'
];
    // Candidatures d'un candidat (dashboard candidat)
    public function listerParCandidat(int $candidat_id)
    {
        return $this->select('candidature.*, annonce.titre AS annonce_titre,
                              annonce.salaire_propose, annonce.heure_debut,
                              annonce.autres_conditions,
                              poste.libelle AS poste_libelle,
                              statut_candidature.libelle AS statut_libelle,
                              statut_candidature.code AS statut_code')
                    ->join('annonce', 'annonce.id = candidature.annonce_id')
                    ->join('besoin', 'besoin.id = annonce.besoin_id')
                    ->join('poste', 'poste.id = besoin.poste_id')
                    ->join('statut_candidature', 'statut_candidature.id = candidature.statut_id')
                    ->where('candidature.candidat_id', $candidat_id)
                    ->orderBy('candidature.date_candidature', 'DESC')
                    ->findAll();
    }

    // Détail d'une candidature avec toutes les infos utiles
public function trouverAvecDetails(int $id)
{
    return $this->select('candidature.*, annonce.titre AS annonce_titre,
                          annonce.salaire_propose, annonce.heure_debut,
                          annonce.autres_conditions, annonce.id AS annonce_id,
                          poste.libelle AS poste_libelle,
                          besoin.nombre_postes,
                          statut_candidature.libelle AS statut_libelle,
                          statut_candidature.code AS statut_code,
                          candidat.nom AS candidat_nom,
                          candidat.prenom AS candidat_prenom,
                          candidat.email AS candidat_email,
                          candidat.telephone AS candidat_telephone')
                ->join('annonce', 'annonce.id = candidature.annonce_id')
                ->join('besoin', 'besoin.id = annonce.besoin_id')
                ->join('poste', 'poste.id = besoin.poste_id')
                ->join('statut_candidature', 'statut_candidature.id = candidature.statut_id')
                ->join('candidat', 'candidat.id = candidature.candidat_id')
                ->where('candidature.id', $id)
                ->first();
}
    // Liste toutes les candidatures (côté RH)
    public function listerToutes()
    {
        return $this->select('candidature.*, annonce.titre AS annonce_titre,
                              poste.libelle AS poste_libelle,
                              candidat.nom AS candidat_nom,
                              candidat.prenom AS candidat_prenom,
                              candidat.email AS candidat_email,
                              statut_candidature.libelle AS statut_libelle,
                              statut_candidature.code AS statut_code')
                    ->join('annonce', 'annonce.id = candidature.annonce_id')
                    ->join('besoin', 'besoin.id = annonce.besoin_id')
                    ->join('poste', 'poste.id = besoin.poste_id')
                    ->join('candidat', 'candidat.id = candidature.candidat_id')
                    ->join('statut_candidature', 'statut_candidature.id = candidature.statut_id')
                    ->orderBy('candidature.date_candidature', 'DESC')
                    ->findAll();
    }
}