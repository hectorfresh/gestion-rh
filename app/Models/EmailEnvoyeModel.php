<?php
namespace App\Models;
use CodeIgniter\Model;

class EmailEnvoyeModel extends Model
{
    protected $table      = 'email_envoye';
    protected $primaryKey = 'id';
    protected $allowedFields = ['destinataire', 'sujet', 'message', 'date_envoi'];
}