<?php

namespace App\Controllers;

use App\Models\AnnonceModel;
use App\Models\CandidatureModel;
use App\Models\CandidatModel;
use App\Models\StatutCandidatureModel;
use App\Models\ChampCvModel;
use App\Models\ValeurCvModel;
use App\Models\ParcoursCvModel;
use App\Models\QuestionCvModel;
use App\Models\ReponseCvModel;

class Postuler extends BaseController
{
    public function form($annonce_id)
    {
        $annonce = (new AnnonceModel())->trouverPublique((int) $annonce_id);
        if (!$annonce) return redirect()->to('/annonces')->with('error', 'Annonce introuvable.');

        $existante = (new CandidatureModel())
                        ->where('annonce_id', (int) $annonce_id)
                        ->where('candidat_id', session()->get('candidat_id'))
                        ->first();
        if ($existante) {
            return redirect()->to("/candidat/candidature/{$existante['id']}")
                             ->with('error', 'Vous avez déjà postulé à cette annonce.');
        }

        return view('postuler/form', [
            'annonce'  => $annonce,
            'candidat' => (new CandidatModel())->find(session()->get('candidat_id')),
            'champs'   => (new ChampCvModel())->tousAvecOptions(),
            'questions' => (new QuestionCvModel())->toutesOrdonnees(),
        ]);
    }

    public function enregistrer($annonce_id)
    {
        $annonce = (new AnnonceModel())->trouverPublique((int) $annonce_id);
        if (!$annonce) return redirect()->to('/annonces')->with('error', 'Annonce introuvable.');

        $existante = (new CandidatureModel())
                        ->where('annonce_id', (int) $annonce_id)
                        ->where('candidat_id', session()->get('candidat_id'))
                        ->first();
        if ($existante) {
            return redirect()->to('/candidat/dashboard')
                             ->with('error', 'Vous avez déjà postulé à cette annonce.');
        }

        $champs = (new ChampCvModel())->tousAvecOptions();
        $questions = (new QuestionCvModel())->toutesOrdonnees();

        // Vérifier que tous les champs liste ont une valeur choisie
        foreach ($champs as $c) {
            if ($c['type'] === 'liste' && !$this->request->getPost('champ_' . $c['id'])) {
                return redirect()->back()->withInput()
                                 ->with('error', 'Veuillez répondre à tous les champs obligatoires.');
            }

            $reponses = [];
            foreach ($questions as $question) {
                $valeur = $this->request->getPost('question_' . $question['id']);
                if ($valeur === null || !in_array((string) $valeur, ['0', '1'], true)) {
                    return redirect()->back()->withInput()
                        ->with('error', 'Veuillez répondre à toutes les questions Oui/Non.');
                }
                $reponses[] = [
                    'question_cv_id' => (int) $question['id'],
                    'valeur' => (int) $valeur,
                ];
            }

            $aUneReponseNegative = count(array_filter($reponses, static fn (array $r): bool => $r['valeur'] === 0)) > 0;
        }

        // 1) Créer la candidature
        $candidatureModel = new CandidatureModel();
        $candidatureModel->insert([
            'annonce_id'       => (int) $annonce_id,
            'candidat_id'      => session()->get('candidat_id'),
            'statut_id'        => (new StatutCandidatureModel())->idParCode(
                $aUneReponseNegative ? 'REPONSES_A_VERIFIER' : 'EN_ATTENTE'
            ),
            'date_candidature' => date('Y-m-d H:i:s'),
        ]);
        $candidature_id = $candidatureModel->getInsertID();

        $reponseModel = new ReponseCvModel();
        foreach ($reponses as $reponse) {
            $reponseModel->insert([
                'candidature_id' => $candidature_id,
                ...$reponse,
            ]);
        }

        // 2) Enregistrer les valeurs du CV
        $valeurModel = new ValeurCvModel();
        foreach ($champs as $c) {
            $valeur = $this->request->getPost('champ_' . $c['id']);
            if ($valeur === null || $valeur === '') continue;

            $data = [
                'candidature_id' => $candidature_id,
                'champ_cv_id'    => $c['id'],
            ];

            if ($c['type'] === 'liste') {
                $data['option_champ_cv_id'] = (int) $valeur;
            } else {
                $data['valeur_texte'] = $valeur;
            }

            $valeurModel->insert($data);
        }

        // 2bis) Enregistrer les parcours (formation / expérience) multiples
        $types  = $this->request->getPost('parcours_type')  ?? [];
        $titres = $this->request->getPost('parcours_titre') ?? [];
        $lieux  = $this->request->getPost('parcours_lieu')  ?? [];
        $debut  = $this->request->getPost('parcours_debut') ?? [];
        $fin    = $this->request->getPost('parcours_fin')    ?? [];
        $descs  = $this->request->getPost('parcours_desc')   ?? [];
        $parcoursModel = new ParcoursCvModel();
        foreach ($titres as $i => $t) {
            $t = trim((string) $t);
            if ($t === '') continue;
            $parcoursModel->insert([
                'candidature_id' => $candidature_id,
                'type'           => in_array($types[$i] ?? '', ['formation','experience']) ? $types[$i] : 'experience',
                'titre'          => $t,
                'lieu'           => trim((string) ($lieux[$i] ?? '')),
                'date_debut'     => ($debut[$i] ?? '') ?: null,
                'date_fin'       => ($fin[$i] ?? '') ?: null,
                'description'    => trim((string) ($descs[$i] ?? '')) ?: null,
            ]);
        }

        // 3) Notification
        envoyer_email(
            session()->get('candidat_email'),
            'Candidature reçue',
            "Bonjour " . session()->get('candidat_prenom') . ",\n\n"
            . "Votre candidature pour l'annonce « {$annonce['titre']} » a bien été reçue.\n"
            . "Vous pouvez suivre son évolution depuis votre espace candidat.\n\n"
            . "Cordialement,\nLe service RH"
        );

        return redirect()->to('/candidat/dashboard')
                         ->with('success', 'Candidature envoyée ! Suivez son évolution ici.');
    }
}