<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="card p-4">
    <h3>Email #<?= $email['id'] ?></h3>
    <p><strong>Destinataire :</strong> <?= esc($email['destinataire']) ?></p>
    <p><strong>Sujet :</strong> <?= esc($email['sujet']) ?></p>
    <p><strong>Date :</strong> <?= esc($email['date_envoi']) ?></p>
    <hr>
    <pre class="bg-light p-3 rounded" style="white-space:pre-wrap;"><?= esc($email['message']) ?></pre>
    <a href="/emails" class="btn btn-outline-secondary">← Retour</a>
</div>
<?= $this->endSection() ?>