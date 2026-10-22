<?php

namespace App\Services;

use App\Models\CandidatureModel;
use App\Models\QuestionContratModel;
use App\Models\ReponseContratModel;
use App\Models\StatutCandidatureModel;
use RuntimeException;

final class QuestionnaireContratService
{
    public function creerPourCandidature(array $candidature): void
    {
        $questions = new QuestionContratModel();
        if ($questions->pourCandidature((int) $candidature['id']) !== []) {
            return;
        }

        $salaire = $candidature['salaire_final']
            ?? $candidature['salaire_negocie']
            ?? $candidature['salaire_propose'];

        $questions->insertBatch([
            [
                'candidature_id' => $candidature['id'],
                'libelle' => 'Acceptez-vous le salaire proposé ?',
                'proposition' => number_format((float) $salaire, 0, ',', ' ') . ' Ar',
                'ordre' => 1,
            ],
            [
                'candidature_id' => $candidature['id'],
                'libelle' => 'Acceptez-vous l’heure de début proposée ?',
                'proposition' => (string) $candidature['heure_debut'],
                'ordre' => 2,
            ],
            [
                'candidature_id' => $candidature['id'],
                'libelle' => 'Acceptez-vous les autres conditions de l’annonce ?',
                'proposition' => (string) ($candidature['autres_conditions'] ?: 'Aucune condition supplémentaire'),
                'ordre' => 3,
            ],
        ]);
    }

    public function enregistrerReponses(int $candidatureId, array $reponses): bool
    {
        $questions = (new QuestionContratModel())->pourCandidature($candidatureId);
        if (count($questions) !== count($reponses)) {
            throw new RuntimeException('Toutes les questions du contrat doivent recevoir une réponse.');
        }

        $model = new ReponseContratModel();
        $toutesOui = true;
        foreach ($questions as $question) {
            $reponse = $reponses[$question['id']] ?? null;
            if (!is_array($reponse) || !in_array((string) ($reponse['reponse'] ?? ''), ['0', '1'], true)) {
                throw new RuntimeException('Réponse de questionnaire invalide.');
            }

            $oui = (int) $reponse['reponse'] === 1;
            if (!$oui && trim((string) ($reponse['remarque'] ?? '')) === '') {
                throw new RuntimeException('Une remarque est obligatoire pour chaque réponse Non.');
            }
            $toutesOui = $toutesOui && $oui;
            $model->replace([
                'question_contrat_id' => $question['id'],
                'reponse' => (int) $reponse['reponse'],
                'remarque' => trim((string) ($reponse['remarque'] ?? '')) ?: null,
                'date_reponse' => date('Y-m-d H:i:s'),
            ]);
        }

        $statut = $toutesOui ? 'CONTRAT_PRET' : 'ATTENTE_VALIDATION_RH';
        (new CandidatureModel())->update($candidatureId, [
            'statut_id' => (new StatutCandidatureModel())->idParCode($statut),
        ]);

        return $toutesOui;
    }

    public function accepterRemarques(int $candidatureId): void
    {
        (new CandidatureModel())->update($candidatureId, [
            'statut_id' => (new StatutCandidatureModel())->idParCode('CONTRAT_PRET'),
        ]);
    }

    public function refuserRemarques(int $candidatureId): void
    {
        (new CandidatureModel())->update($candidatureId, [
            'statut_id' => (new StatutCandidatureModel())->idParCode('CANDIDAT_NON_RETENU'),
        ]);
    }
}
