<?php

namespace App\Controllers;

use App\Models\CandidatModel;
use App\Models\CandidatureModel;
use App\Models\EssaiModel;
use App\Models\ContratModel;
use App\Models\QuestionContratModel;
use App\Models\ReponseContratModel;
use App\Services\QuestionnaireContratService;
use RuntimeException;

class Candidat extends BaseController
{
    // ---------- INSCRIPTION ----------
    public function registerForm()
    {
        return view('candidat/register');
    }

public function register()
{
    $rules = [
        'nom'          => 'required',
        'prenom'       => 'required',
        'email'        => 'required|valid_email|is_unique[candidat.email]',
        'mot_de_passe' => 'required|min_length[4]',
        'telephone'    => 'required',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()
                         ->with('error', implode(' ', $this->validator->getErrors()));
    }

    (new CandidatModel())->insert([
        'nom'          => $this->request->getPost('nom'),
        'prenom'       => $this->request->getPost('prenom'),
        'email'        => $this->request->getPost('email'),
        'mot_de_passe' => password_hash($this->request->getPost('mot_de_passe'), PASSWORD_DEFAULT),
        'telephone'    => $this->request->getPost('telephone'),
    ]);

    return redirect()->to('/candidat/login')
                     ->with('success', 'Compte créé. Connectez-vous.');
}
    // ---------- CONNEXION ----------
    public function loginForm()
    {
        return view('candidat/login');
    }

    public function login()
    {
        $email = $this->request->getPost('email');
        $mdp   = $this->request->getPost('mot_de_passe');

        $candidat = (new CandidatModel())->trouverParEmail($email);

        if (!$candidat || !password_verify($mdp, $candidat['mot_de_passe'])) {
            return redirect()->to('/candidat/login')
                             ->with('error', 'Email ou mot de passe incorrect.');
        }

        session()->set([
            'candidat_id'     => $candidat['id'],
            'candidat_nom'    => $candidat['nom'],
            'candidat_prenom' => $candidat['prenom'],
            'candidat_email'  => $candidat['email'],
        ]);

        return redirect()->to('/candidat/dashboard');
    }

    public function logout()
    {
        // Ne supprime que la session candidat
        session()->remove(['candidat_id', 'candidat_nom', 'candidat_prenom', 'candidat_email']);
        return redirect()->to('/annonces');
    }

    // ---------- DASHBOARD ----------
    public function dashboard()
    {
        $candidatures = (new CandidatureModel())->listerParCandidat(session()->get('candidat_id'));
        return view('candidat/dashboard', ['candidatures' => $candidatures]);
    }

    // ---------- DÉTAIL D'UNE CANDIDATURE ----------
    public function detail($id)
    {
        $candidature = (new CandidatureModel())->trouverAvecDetails((int) $id);

        if (!$candidature || (int) $candidature['candidat_id'] !== (int) session()->get('candidat_id')) {
            return redirect()->to('/candidat/dashboard')
                             ->with('error', 'Candidature introuvable.');
        }

        $essai    = (new EssaiModel())->trouverParCandidature((int) $id);
        $contrat  = (new ContratModel())->trouverParCandidature((int) $id);
        $questions = (new QuestionContratModel())->pourCandidature((int) $id);
        $reponses = (new ReponseContratModel())->pourQuestions(array_column($questions, 'id'));

        return view('candidat/detail', [
            'candidature' => $candidature,
            'essai'       => $essai,
            'contrat'     => $contrat,
            'questions'   => $questions,
            'reponses'    => array_column($reponses, null, 'question_contrat_id'),
        ]);
    }

    // ---------- ACTIONS CANDIDAT ----------
    public function action($id)
    {
        $candidature = (new CandidatureModel())->trouverAvecDetails((int) $id);

        if (!$candidature || (int) $candidature['candidat_id'] !== (int) session()->get('candidat_id')) {
            return redirect()->to('/candidat/dashboard')->with('error', 'Candidature introuvable.');
        }

        $action = $this->request->getPost('action');

        // Essai proposé
        if ($candidature['statut_code'] === 'ESSAI_PROPOSE') {
            if ($action === 'accepter_essai') {
                $this->_changerStatut($id, 'ESSAI_EFFECTUE');
                return redirect()->to("/candidat/candidature/$id")
                                 ->with('success', "Essai accepté. L'entreprise vous contactera.");
            }
            if ($action === 'refuser_essai') {
                $this->_changerStatut($id, 'ESSAI_REFUSE');
                return redirect()->to("/candidat/candidature/$id")
                                 ->with('success', "Essai refusé.");
            }
        }

        // Conditions proposées
        if ($candidature['statut_code'] === 'CONDITIONS_PROPOSEES') {
            if ($action === 'accepter_conditions') {
                $service = new QuestionnaireContratService();
                $service->creerPourCandidature($candidature);
                $this->_changerStatut($id, 'QUESTIONNAIRE_CONTRAT');
                envoyer_email(
                    session()->get('candidat_email'),
                    'Questionnaire avant contrat',
                    "Bonjour {$candidature['candidat_prenom']},\n\n"
                    . "Merci de répondre au questionnaire de confirmation avant la génération de votre contrat."
                );
                return redirect()->to("/candidat/candidature/$id")
                    ->with('success', 'Conditions acceptées. Répondez au questionnaire avant le contrat.');
            }
            if ($action === 'refuser_conditions') {
                $this->_changerStatut($id, 'CONDITIONS_REFUSEES');
                return redirect()->to("/candidat/candidature/$id")
                                 ->with('success', 'Conditions refusées.');
            }

            if ($action === 'negocier') {
                $demande = $this->request->getPost('salaire_negocie');
                if (!$demande || $demande <= 0) {
                    return redirect()->to("/candidat/candidature/$id")
                                     ->with('error', 'Salaire négocié invalide.');
                }
                (new CandidatureModel())->update($id, [
                    'salaire_negocie' => (float) $demande,
                ]);
                $this->_changerStatut($id, 'SALAIRE_EN_NEGOCIATION');
                return redirect()->to("/candidat/candidature/$id")
                                 ->with('success', 'Demande de négociation envoyée.');
            }
        }

        return redirect()->to("/candidat/candidature/$id")
                         ->with('error', 'Action non valide.');
    }

    public function questionnaire(int $id)
    {
        $candidature = (new CandidatureModel())->trouverAvecDetails($id);
        if (!$candidature || (int) $candidature['candidat_id'] !== (int) session()->get('candidat_id')) {
            return redirect()->to('/candidat/dashboard')->with('error', 'Candidature introuvable.');
        }

        if (!in_array($candidature['statut_code'], ['QUESTIONNAIRE_CONTRAT', 'ATTENTE_VALIDATION_RH'], true)) {
            return redirect()->to("/candidat/candidature/$id")->with('error', 'Questionnaire non disponible.');
        }

        $questions = (new QuestionContratModel())->pourCandidature($id);
        $reponses = $this->request->getPost('reponses') ?? [];
        $remarques = $this->request->getPost('remarques') ?? [];
        $donnees = [];
        foreach ($questions as $question) {
            $donnees[$question['id']] = [
                'reponse' => $reponses[$question['id']] ?? null,
                'remarque' => $remarques[$question['id']] ?? null,
            ];
        }

        try {
            $toutesOui = (new QuestionnaireContratService())->enregistrerReponses($id, $donnees);
        } catch (RuntimeException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        $message = $toutesOui
            ? 'Toutes les réponses sont positives. Le RH peut générer votre contrat.'
            : 'Vos remarques ont été transmises au RH pour validation.';
        return redirect()->to("/candidat/candidature/$id")->with('success', $message);
    }

    private function _changerStatut(int $id, string $code)
    {
        $statutModel = new \App\Models\StatutCandidatureModel();
        (new CandidatureModel())->update($id, ['statut_id' => $statutModel->idParCode($code)]);
    }


    // Retourne le salaire actuel affiché au candidat (négocié si présent, sinon proposé)
private function _salaireActuel(array $candidature): float
{
    if (!empty($candidature['salaire_final'])) return (float) $candidature['salaire_final'];
    if (!empty($candidature['salaire_negocie'])) return (float) $candidature['salaire_negocie'];
    return (float) $candidature['salaire_propose'];
}
}