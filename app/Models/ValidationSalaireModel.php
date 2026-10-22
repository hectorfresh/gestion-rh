<?php

namespace App\Models;

use CodeIgniter\Model;

class ValidationSalaireModel extends Model
{
    protected $table = 'validation_salaire';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'candidature_id', 'etape', 'decision', 'salaire_propose',
        'utilisateur_id', 'commentaire', 'date_validation',
    ];

    public function enAttente(string $etape): array
    {
        return $this->select('validation_salaire.*, candidature.annonce_id,
                              candidat.nom AS candidat_nom, candidat.prenom AS candidat_prenom,
                              annonce.titre AS annonce_titre')
            ->join('candidature', 'candidature.id = validation_salaire.candidature_id')
            ->join('candidat', 'candidat.id = candidature.candidat_id')
            ->join('annonce', 'annonce.id = candidature.annonce_id')
            ->where('validation_salaire.etape', $etape)
            ->where('validation_salaire.decision', 'EN_ATTENTE')
            ->orderBy('validation_salaire.date_validation', 'ASC')
            ->findAll();
    }

    public function pourCandidature(int $candidatureId): array
    {
        return $this->where('candidature_id', $candidatureId)
            ->orderBy('date_validation', 'ASC')
            ->findAll();
    }

    public function valideesGlobalement(): array
    {
        return $this->select('validation_salaire.*, candidature.annonce_id,
                              candidat.nom AS candidat_nom, candidat.prenom AS candidat_prenom,
                              annonce.titre AS annonce_titre')
            ->join('candidature', 'candidature.id = validation_salaire.candidature_id')
            ->join('candidat', 'candidat.id = candidature.candidat_id')
            ->join('annonce', 'annonce.id = candidature.annonce_id')
            ->where('validation_salaire.etape', 'DG')
            ->where('validation_salaire.decision', 'VALIDE')
            ->orderBy('validation_salaire.date_validation', 'DESC')
            ->findAll();
    }

    public function estValideeParDg(int $candidatureId): bool
    {
        return $this->where('candidature_id', $candidatureId)
            ->where('etape', 'DG')
            ->where('decision', 'VALIDE')
            ->countAllResults() > 0;
    }
}
