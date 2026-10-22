<?php

namespace App\Controllers;

use App\Models\ValidationSalaireModel;
use App\Services\ValidationSalaireService;
use RuntimeException;

class ValidationSalaire extends BaseController
{
    public function dg()
    {
        return view('validation_salaire/index', [
            'etape' => 'DG',
            'validations' => (new ValidationSalaireModel())->enAttente('DG'),
        ]);
    }

    public function validees()
    {
        return view('validation_salaire/index', [
            'etape' => 'VALIDÉES PAR LE DG',
            'validations' => (new ValidationSalaireModel())->valideesGlobalement(),
            'lectureSeule' => true,
        ]);
    }

    public function traiter(int $id)
    {
        $accepter = $this->request->getPost('decision') === 'valider';
        $commentaire = trim((string) $this->request->getPost('commentaire')) ?: null;

        try {
            (new ValidationSalaireService())->traiterDg(
                $id,
                $accepter,
                (int) session()->get('user_id'),
                $commentaire
            );
            return redirect()->to('/validation-salaire/dg')
                ->with('success', $accepter ? 'Salaire validé. Le candidat peut répondre.' : 'Salaire refusé.');
        } catch (RuntimeException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
