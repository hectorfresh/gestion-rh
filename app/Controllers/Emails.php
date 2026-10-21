<?php

namespace App\Controllers;

use App\Models\EmailEnvoyeModel;

class Emails extends BaseController
{
    public function index()
    {
        $emails = (new EmailEnvoyeModel())->orderBy('date_envoi', 'DESC')->findAll();
        return view('emails/index', ['emails' => $emails]);
    }

    public function show($id)
    {
        $email = (new EmailEnvoyeModel())->find((int) $id);
        if (!$email) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('emails/show', ['email' => $email]);
    }
}