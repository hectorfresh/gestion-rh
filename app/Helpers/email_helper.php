<?php

use App\Models\EmailEnvoyeModel;

// Simule l'envoi d'un email en l'enregistrant en base.
// Pour un vrai envoi SMTP : remplacer le corps par Services::email()->send()
function envoyer_email(string $to, string $sujet, string $message)
{
    $model = new EmailEnvoyeModel();
    $model->insert([
        'destinataire' => $to,
        'sujet'        => $sujet,
        'message'      => $message,
        'date_envoi'   => date('Y-m-d H:i:s'),
    ]);
}