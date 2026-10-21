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



    // Liste des candidats embauchés (statut CANDIDAT_EMBAUCHE)
public function listerEmbauches()
{
    return $this->select('candidature.*,
                          annonce.titre AS annonce_titre,
                          poste.libelle AS poste_libelle,
                          candidat.nom AS candidat_nom,
                          candidat.prenom AS candidat_prenom,
                          candidat.email AS candidat_email,
                          candidat.telephone AS candidat_telephone,
                          contrat.salaire AS contrat_salaire,
                          contrat.date_debut AS contrat_date_debut,
                          contrat.date_fin AS contrat_date_fin,
                          type_contrat.libelle AS contrat_type,
                          statut_candidature.libelle AS statut_libelle,
                          statut_candidature.code AS statut_code')
                ->join('annonce', 'annonce.id = candidature.annonce_id')
                ->join('besoin', 'besoin.id = annonce.besoin_id')
                ->join('poste', 'poste.id = besoin.poste_id')
                ->join('candidat', 'candidat.id = candidature.candidat_id')
                ->join('statut_candidature', 'statut_candidature.id = candidature.statut_id')
                ->join('contrat', 'contrat.candidature_id = candidature.id', 'left')
                ->join('type_contrat', 'type_contrat.id = contrat.type_contrat_id', 'left')
                ->where('statut_candidature.code', 'CANDIDAT_EMBAUCHE')
                ->orderBy('contrat.date_debut', 'DESC')
                ->findAll();
}



}