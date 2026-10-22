<?php

namespace App\Controllers;

use App\Services\EntretienService;
use App\Services\EvaluationService;
use App\Services\OnboardingService;

class Recrutement extends BaseController
{
    public function planifierEntretien(int $candidatureId)
    {
        $rules = [
            'date_entretien' => 'required',
            'type' => 'required|max_length[30]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Données d’entretien invalides.');
        }

        $dateEntretien = str_replace('T', ' ', trim((string) $this->request->getPost('date_entretien')));
        if (strlen($dateEntretien) === 16) {
            $dateEntretien .= ':00';
        }

        if (!\DateTime::createFromFormat('Y-m-d H:i:s', $dateEntretien)) {
            return redirect()->back()->withInput()->with('error', 'Date d’entretien invalide.');
        }

        (new EntretienService())->planifier(
            $candidatureId,
            (int) session()->get('user_id'),
            $dateEntretien,
            (string) $this->request->getPost('type')
        );

        return redirect()->back()->with('success', 'Entretien planifié.');
    }

    public function evaluerEntretien(int $entretienId)
    {
        $rules = [
            'note' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[20]',
            'decision' => 'required|in_list[RETENU,NON_RETENU,A_REVOIR]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Évaluation invalide.');
        }

        (new EvaluationService())->enregistrer(
            $entretienId,
            (int) session()->get('user_id'),
            (float) $this->request->getPost('note'),
            (string) $this->request->getPost('decision'),
            trim((string) $this->request->getPost('commentaire')) ?: null
        );

        return redirect()->back()->with('success', 'Évaluation enregistrée.');
    }

    public function ajouterTacheOnboarding(int $onboardingId)
    {
        if (!$this->validate(['libelle' => 'required|max_length[150]'])) {
            return redirect()->back()->withInput()->with('error', 'Libellé de tâche invalide.');
        }

        (new OnboardingService())->ajouterTache(
            $onboardingId,
            trim((string) $this->request->getPost('libelle')),
            trim((string) $this->request->getPost('responsable')) ?: null,
            $this->request->getPost('echeance') ?: null
        );

        return redirect()->back()->with('success', 'Tâche d’onboarding ajoutée.');
    }
}
