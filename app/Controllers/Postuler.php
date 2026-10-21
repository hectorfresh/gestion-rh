<?php

namespace App\Controllers;

use App\Models\AnnonceModel;
use App\Models\CandidatureModel;
use App\Models\CandidatModel;
use App\Models\StatutCandidatureModel;
use App\Models\ChampCvModel;
use App\Models\ValeurCvModel;

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

        // Vérifier que tous les champs liste ont une valeur choisie
        foreach ($champs as $c) {
            if ($c['type'] === 'liste' && !$this->request->getPost('champ_' . $c['id'])) {
                return redirect()->back()->withInput()
                                 ->with('error', 'Veuillez répondre à tous les champs obligatoires.');
            }
        }

        // 1) Créer la candidature
        $candidatureModel = new CandidatureModel();
        $candidatureModel->insert([
            'annonce_id'       => (int) $annonce_id,
            'candidat_id'      => session()->get('candidat_id'),
            'statut_id'        => (new StatutCandidatureModel())->idParCode('EN_ATTENTE'),
            'date_candidature' => date('Y-m-d H:i:s'),
        ]);
        $candidature_id = $candidatureModel->getInsertID();

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