<?php

namespace App\Models;

use CodeIgniter\Model;

class BudgetModel extends Model
{
    protected $table         = 'budget';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['montant_total'];

    // Montant total du budget (une seule ligne)
    public function montantTotal()
    {
        $ligne = $this->first();
        return $ligne ? (float) $ligne['montant_total'] : 0.0;
    }

    public function masseSalarialeAnnuelle(int $annee): float
    {
        $db = \Config\Database::connect();
        $debut = sprintf('%04d-01-01', $annee);
        $fin = sprintf('%04d-12-31', $annee);
        $result = $db->table('contrat')
            ->select('COALESCE(SUM(contrat.salaire * 12), 0) AS total', false)
            ->join('employe', 'employe.id = contrat.employe_id')
            ->where('employe.statut', 'ACTIF')
            ->where('contrat.date_debut <=', $fin)
            ->groupStart()
                ->where('contrat.date_fin IS NULL', null, false)
                ->orWhere('contrat.date_fin >=', $debut)
            ->groupEnd()
            ->get()
            ->getRow();

        return $result ? (float) $result->total : 0.0;
    }

    public function budgetRestantAnnuel(?int $annee = null): float
    {
        $annee ??= (int) date('Y');

        return $this->montantTotal() - $this->masseSalarialeAnnuelle($annee);
    }

    /**
     * $coutMensuel représente le coût mensuel total du besoin.
     * besoin.cout_engage contient déjà salaire_max × nombre_postes.
     */
    public function coutAnnuelBesoin(float $coutMensuel, int $nombrePostes = 1): float
    {
        return $coutMensuel * $nombrePostes * 12;
    }

    public function budgetSuffisantPourBesoin(float $coutMensuel, int $nombrePostes = 1, ?int $annee = null): bool
    {
        return $this->budgetRestantAnnuel($annee) >= $this->coutAnnuelBesoin($coutMensuel, $nombrePostes);
    }

    // Compatibilité avec les anciennes vues : le calcul utilise désormais la masse salariale annuelle.
    public function totalEngage(): float
    {
        return $this->montantTotal() - $this->budgetRestantAnnuel();
    }

    public function restant(): float
    {
        return $this->budgetRestantAnnuel();
    }
}