<?php

namespace App\Controllers;

use App\Models\CandidatureModel;
use App\Models\StatutCandidatureModel;
use App\Models\EssaiModel;
use App\Models\ContratModel;
use App\Models\TypeContratModel;
use App\Services\EmployeService;
use App\Services\ContratService;
use App\Services\OnboardingService;
use App\Services\AffectationService;
use App\Services\ValidationSalaireService;

class Candidature extends BaseController
{
    public function index()
    {
        $candidatures = (new CandidatureModel())->listerToutes();
        return view('candidature/index', ['candidatures' => $candidatures]);
    }
    // Liste des candidats embauchés
    public function embauches()
    {
        $embauches = (new CandidatureModel())->listerEmbauches();
        return view('candidature/embauches', ['embauches' => $embauches]);
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
        if (in_array($statut, ['EN_ATTENTE', 'REPONSES_A_VERIFIER'], true)) {
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
        // ============ SALAIRE_EN_NEGOCIATION ============
if ($statut === 'SALAIRE_EN_NEGOCIATION') {
    // Récupérer la fourchette salariale du poste
    $db = \Config\Database::connect();
    $poste = $db->table('poste')
                ->join('besoin', 'besoin.poste_id = poste.id')
                ->join('annonce', 'annonce.besoin_id = besoin.id')
                ->where('annonce.id', $candidature['annonce_id'])
                ->get()->getRow();

    if ($action === 'accepter_nego') {
        if ($candidature['salaire_negocie'] > $poste->salaire_max) {
            (new ValidationSalaireService())->soumettreFinance(
                (int) $id,
                (float) $candidature['salaire_negocie'],
                (int) session()->get('user_id'),
                'Dépassement du plafond salarial : validation RH demandée.'
            );
            $this->_changerStatut($id, 'SALAIRE_ATTENTE_FINANCE');
            return redirect()->to("/candidature/$id")
                ->with('success', 'Le salaire dépasse le plafond. La demande suit le workflow RH → Finance → DG.');
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
                         ->with('success', 'Négociation acceptée. Le candidat doit valider.');
    }

    if ($action === 'proposer_max') {
        // Contre-proposition : le salaire maximum autorisé
        (new CandidatureModel())->update($id, [
            'salaire_final' => $poste->salaire_max,
        ]);
        $this->_changerStatut($id, 'CONDITIONS_PROPOSEES');

        envoyer_email($email, 'Nouvelle proposition de salaire',
            "Bonjour $prenom,\n\nAprès étude, nous ne pouvons pas accéder à votre demande.\n"
            . "Nous vous proposons le salaire maximum du poste : {$poste->salaire_max} Ar.\n"
            . "Connectez-vous pour accepter ou refuser cette proposition finale.");
        return redirect()->to("/candidature/$id")
                         ->with('success', 'Salaire max proposé au candidat. En attente de sa réponse.');
    }

    if ($action === 'refuser_nego') {
        $this->_changerStatut($id, 'CANDIDAT_NON_RETENU');
        envoyer_email($email, 'Négociation refusée',
            "Bonjour $prenom,\n\nVotre demande de salaire n'a pas pu être acceptée.\n\nCordialement.");
        return redirect()->to("/candidature/$id")->with('success', 'Négociation refusée.');
    }
}


// ============ CONDITIONS_REFUSEES (le candidat a refusé la contre-proposition) ============
if ($statut === 'CONDITIONS_REFUSEES') {
    if ($action === 'cloturer') {
        $this->_changerStatut($id, 'CANDIDAT_NON_RETENU');
        envoyer_email($email, 'Candidature clôturée',
            "Bonjour $prenom,\n\nVotre candidature est clôturée suite au refus de la proposition.\n\nCordialement.");
        return redirect()->to("/candidature/$id")->with('success', 'Candidature clôturée.');
    }
    if ($action === 'reproposer') {
        // Permettre au RH de re-proposer les conditions initiales
        (new CandidatureModel())->update($id, ['salaire_final' => null]);
        $this->_changerStatut($id, 'CONDITIONS_PROPOSEES');
        envoyer_email($email, 'Nouvelle proposition',
            "Bonjour $prenom,\n\nNous revenons vers vous avec une nouvelle proposition.\n"
            . "Connectez-vous pour la consulter.");
        return redirect()->to("/candidature/$id")
                         ->with('success', 'Nouvelle proposition envoyée au candidat.');
    }
}


        // ============ CONDITIONS_ACCEPTEES → établir contrat ============
if ($statut === 'CONDITIONS_ACCEPTEES' && $action === 'etablir_contrat') {
    if (!$this->validate([
        'type_contrat' => 'required|in_list[CDI,CDD,Essai]',
        'salaire' => 'required|numeric|greater_than[0]',
        'date_debut' => 'required|valid_date[Y-m-d]',
        'date_fin' => 'permit_empty|valid_date[Y-m-d]',
    ])) {
        return redirect()->back()->withInput()->with('error', 'Informations de contrat invalides.');
    }

    $salaireSaisi = (float) $this->request->getPost('salaire');
    $dateDebut = (string) $this->request->getPost('date_debut');
    $dateFin = $this->request->getPost('date_fin') ?: null;
    $employeId = (new EmployeService())->creerDepuisCandidat(
        (int) $candidature['candidat_id'],
        $dateDebut
    );
    (new AffectationService())->affecter(
        $employeId,
        (int) $candidature['poste_id'],
        $candidature['departement_id'] ? (int) $candidature['departement_id'] : null,
        $dateDebut
    );
    (new ContratService())->creer(
        (int) $id,
        $employeId,
        (string) $this->request->getPost('type_contrat'),
        $salaireSaisi,
        $dateDebut,
        $dateFin
    );
    $onboardingId = (new OnboardingService())->creer($employeId, $dateDebut);
    $onboardingService = new OnboardingService();
    $onboardingService->ajouterTache($onboardingId, 'Signer et archiver le contrat', 'RH');
    $onboardingService->ajouterTache($onboardingId, 'Préparer le poste de travail', 'Manager');
    $this->_changerStatut($id, 'CANDIDAT_EMBAUCHE');

    // Clôturer l'annonce (1 annonce = 1 poste = pourvue dès qu'1 candidat est embauché)
    (new \App\Models\AnnonceModel())->marquerPourvue((int) $candidature['annonce_id']);

    envoyer_email($email, 'Félicitations, contrat établi',
        "Bonjour $prenom,\n\nFélicitations ! Votre contrat est établi.\nBienvenue dans l'équipe.");
    return redirect()->to("/candidature/$id")->with('success', 'Contrat établi. Annonce clôturée.');
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

}