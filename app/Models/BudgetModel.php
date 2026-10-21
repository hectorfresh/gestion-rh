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

    // Somme des besoins qui "engagent" du budget
    public function totalEngage()
    {
        $db = \Config\Database::connect();
        $result = $db->table('besoin')
                     ->selectSum('besoin.cout_engage', 'total')
                     ->join('statut_besoin', 'statut_besoin.id = besoin.statut_id')
                     ->whereIn('statut_besoin.code', [
                         'EN_ATTENTE_DG',
                         'VALIDE',
                         'RECRUTEMENT_OUVERT',
                     ])
                     ->get()
                     ->getRow();
        return $result && $result->total ? (float) $result->total : 0.0;
    }

    // Budget restant = total - engagé
    public function restant()
    {
        return $this->montantTotal() - $this->totalEngage();
    }
}