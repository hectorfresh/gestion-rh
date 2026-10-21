<?php

namespace App\Controllers;

use App\Models\CandidatureModel;
use App\Models\StatutCandidatureModel;
use App\Models\EssaiModel;
use App\Models\ContratModel;
use App\Models\TypeContratModel;

class Candidature extends BaseController
{
    public function index()
    {
        $candidatures = (new CandidatureModel())->listerToutes();
        return view('candidature/index', ['candidatures' => $candidatures]);
    }

    public function show($id)
    {
        $candidature = (new CandidatureModel())->trouverAvecDetails((int) $id);
        if (!$candidature) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('candidature/show', [
            'candidature' => $candidature,
            'essai'       => (new EssaiModel())->trouverParCandidature((int) $id),
            'contrat'     => (new ContratModel())->trouverParCandidature((int) $id),
            'types'       => (new TypeContratModel())->findAll(),
        ]);
    }

    public function action($id)
    {
        $candidature = (new CandidatureModel())->trouverAvecDetails((int) $id);
        if (!$candidature) {
            return redirect()->to('/candidature')->with('error', 'Candidature introuvable.');
        }

        $action = $this->request->getPost('action');
        $statut = $candidature['statut_code'];
        $email  = $candidature['candidat_email'];
        $prenom = $candidature['candidat_prenom'];
        $titre  = $candidature['annonce_titre'];

        // ============ EN_ATTENTE ============
        if ($statut === 'EN_ATTENTE') {
            if ($action === 'refuser') {
                $this->_changerStatut($id, 'CANDIDAT_NON_RETENU');
                envoyer_email($email, 'Candidature non retenue',
                    "Bonjour $prenom,\n\nVotre candidature pour « $titre » n'a pas été retenue.\n\nCordialement.");
                return redirect()->to("/candidature/$id")->with('success', 'Candidat refusé.');
            }
            if ($action === 'proposer_essai') {
                $this->_creerEssai($id);
                $this->_changerStatut($id, 'ESSAI_PROPOSE');
                envoyer_email($email, 'Essai proposé',
                    "Bonjour $prenom,\n\nUn essai vous est proposé pour « $titre ».\nConnectez-vous pour répondre.");
                return redirect()->to("/candidature/$id")->with('success', 'Essai proposé.');
            }
            if ($action === 'proposer_conditions') {
                $this->_changerStatut($id, 'CONDITIONS_PROPOSEES');
                envoyer_email($email, 'Conditions proposées',
                    "Bonjour $prenom,\n\nLes conditions pour « $titre » vous ont été proposées.\n"
                    . "Salaire : {$candidature['salaire_propose']} Ar.\nConnectez-vous pour répondre.");
                return redirect()->to("/candidature/$id")->with('success', 'Conditions proposées.');
            }
        }

        // ============ ESSAI_EFFECTUE ============
        if ($statut === 'ESSAI_EFFECTUE') {
            if ($action === 'refuser') {
                $this->_changerStatut($id, 'CANDIDAT_NON_RETENU');
                envoyer_email($email, 'Candidature non retenue',
                    "Bonjour $prenom,\n\nAprès votre essai, votre candidature n'a pas été retenue.\n\nCordialement.");
                return redirect()->to("/candidature/$id")->with('success', 'Candidat refusé après essai.');
            }
            if ($action === 'proposer_conditions') {
                $this->_changerStatut($id, 'CONDITIONS_PROPOSEES');
                envoyer_email($email, 'Conditions proposées',
                    "Bonjour $prenom,\n\nAprès votre essai, nous vous proposons les conditions pour « $titre ».\n"
                    . "Connectez-vous pour répondre.");
                return redirect()->to("/candidature/$id")->with('success', 'Conditions proposées.');
            }
        }

        // ============ ESSAI_REFUSE (le candidat a refusé, mais le RH peut quand même embaucher) ============
        if ($statut === 'ESSAI_REFUSE') {
            if ($action === 'refuser') {
                $this->_changerStatut($id, 'CANDIDAT_NON_RETENU');
                envoyer_email($email, 'Candidature non retenue',
                    "Bonjour $prenom,\n\nAprès étude de votre dossier, votre candidature n'a pas été retenue.\n\nCordialement.");
                return redirect()->to("/candidature/$id")->with('success', 'Candidat non retenu.');
            }
            if ($action === 'proposer_conditions') {
                $this->_changerStatut($id, 'CONDITIONS_PROPOSEES');
                envoyer_email($email, 'Conditions proposées',
                    "Bonjour $prenom,\n\nMalgré votre refus de l'essai, nous vous proposons finalement "
                    . "les conditions pour « $titre ».\nConnectez-vous pour répondre.");
                return redirect()->to("/candidature/$id")->with('success', 'Conditions proposées.');
            }
        }

        // ============ SALAIRE_EN_NEGOCIATION ============
        if ($statut === 'SALAIRE_EN_NEGOCIATION') {
            if ($action === 'accepter_nego') {
                $db = \Config\Database::connect();
                $poste = $db->table('poste')
                            ->join('besoin', 'besoin.poste_id = poste.id')
                            ->join('annonce', 'annonce.besoin_id = besoin.id')
                            ->where('annonce.id', $candidature['annonce_id'])
                            ->get()->getRow();

                if ($candidature['salaire_negocie'] > $poste->salaire_max) {
                    return redirect()->to("/candidature/$id")
                                     ->with('error', 'Négociation refusée : salaire demandé ('
                                         . number_format($candidature['salaire_negocie'], 0, ',', ' ')
                                         . ' Ar) supérieur au salaire maximum du poste ('
                                         . number_format($poste->salaire_max, 0, ',', ' ') . ' Ar).');
                }

                (new CandidatureModel())->update($id, [
                    'salaire_final' => $candidature['salaire_negocie'],
                ]);
                $this->_changerStatut($id, 'CONDITIONS_PROPOSEES');

                envoyer_email($email, 'Nouvelle proposition de salaire',
                    "Bonjour $prenom,\n\nNous avons accepté votre demande de négociation.\n"
                    . "Salaire proposé : {$candidature['salaire_negocie']} Ar.\n"
                    . "Connectez-vous pour accepter ou refuser cette nouvelle proposition.");
                return redirect()->to("/candidature/$id")
                                 ->with('success', 'Négociation acceptée. Le candidat doit valider la nouvelle proposition.');
            }
            if ($action === 'refuser_nego') {
                $this->_changerStatut($id, 'CANDIDAT_NON_RETENU');
                envoyer_email($email, 'Négociation refusée',
                    "Bonjour $prenom,\n\nVotre demande de salaire n'a pas pu être acceptée.\n\nCordialement.");
                return redirect()->to("/candidature/$id")->with('success', 'Négociation refusée.');
            }
        }

        // ============ CONDITIONS_ACCEPTEES → établir contrat ============
        if ($statut === 'CONDITIONS_ACCEPTEES' && $action === 'etablir_contrat') {
            $salaireSaisi = (float) $this->request->getPost('salaire');
            $this->_creerContrat($id, $salaireSaisi);
            $this->_changerStatut($id, 'CANDIDAT_EMBAUCHE');
            envoyer_email($email, 'Félicitations, contrat établi',
                "Bonjour $prenom,\n\nFélicitations ! Votre contrat est établi.\nBienvenue dans l'équipe.");
            return redirect()->to("/candidature/$id")->with('success', 'Contrat établi. Candidat embauché !');
        }

        // Si on arrive ici, aucun cas n'a matché
        return redirect()->to("/candidature/$id")
                         ->with('error', 'Action non valide pour ce statut (' . $statut . ' / ' . $action . ').');
    }

    private function _changerStatut(int $id, string $code)
    {
        $statutModel = new StatutCandidatureModel();
        (new CandidatureModel())->update($id, ['statut_id' => $statutModel->idParCode($code)]);
    }

    private function _creerEssai(int $candidature_id)
    {
        (new EssaiModel())->insert([
            'candidature_id' => $candidature_id,
            'date_debut'     => $this->request->getPost('date_debut'),
            'date_fin'       => $this->request->getPost('date_fin'),
            'commentaire'    => $this->request->getPost('commentaire') ?: null,
        ]);
    }

    private function _creerContrat(int $candidature_id, float $salaire)
    {
        $typeLibelle = $this->request->getPost('type_contrat');
        $typeId = (new TypeContratModel())->idParLibelle($typeLibelle);

        (new ContratModel())->insert([
            'candidature_id'  => $candidature_id,
            'type_contrat_id' => $typeId,
            'salaire'         => $salaire,
            'date_debut'      => $this->request->getPost('date_debut'),
            'date_fin'        => $this->request->getPost('date_fin') ?: null,
        ]);
    }
}