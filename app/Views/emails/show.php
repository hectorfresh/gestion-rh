<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Administration / Email</div>
        <h1 class="page-title">Email #<?= $email['id'] ?></h1>
        <p class="page-description">Détail de l'email envoyé.</p>
    </div>
    <div class="btn-list">
        <a href="/emails" class="btn btn-outline-secondary">← Retour</a>
    </div>
</header>

<section class="content-card">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Contenu de l'email</h2>
        </div>
    </div>
    <div class="content-card-body">
        <p><strong>Destinataire :</strong> <?= esc($email['destinataire']) ?></p>
        <p><strong>Sujet :</strong> <?= esc($email['sujet']) ?></p>
        <p><strong>Date :</strong> <?= esc($email['date_envoi']) ?></p>
        <hr>
        <pre class="bg-light p-3 rounded" style="white-space:pre-wrap;"><?= esc($email['message']) ?></pre>
    </div>
</section>
<?= $this->endSection() ?>