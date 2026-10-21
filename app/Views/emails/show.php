<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2>Email #<?= $email['id'] ?></h2>
<p><strong>Destinataire :</strong> <?= esc($email['destinataire']) ?></p>
<p><strong>Sujet :</strong> <?= esc($email['sujet']) ?></p>
<p><strong>Date :</strong> <?= esc($email['date_envoi']) ?></p>
<h3>Message</h3>
<pre style="background:#f5f5f5; padding:15px; border:1px solid #ddd; white-space:pre-wrap;"><?= esc($email['message']) ?></pre>
<p><a href="/emails">← Retour</a></p>
<?= $this->endSection() ?>