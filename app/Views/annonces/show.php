<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Recrutement / Offre</div>
        <h1 class="page-title"><?= esc($annonce['titre']) ?></h1>
        <p class="page-description"><?= esc($annonce['poste_libelle']) ?></p>
    </div>
</header>

<section class="content-card mb-4">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">📝 Description du poste</h2>
        </div>
    </div>
    <div class="content-card-body">
        <p><?= nl2br(esc($annonce['description'])) ?></p>
    </div>
</section>

<?php if (!empty($annonce['autres_conditions'])): ?>
<section class="content-card mb-4">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">📋 Autres conditions</h2>
        </div>
    </div>
    <div class="content-card-body">
        <p class="mb-0"><?= nl2br(esc($annonce['autres_conditions'])) ?></p>
    </div>
</section>
<?php endif; ?>

<section class="content-card mb-4">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Informations clés</h2>
        </div>
    </div>
    <div class="content-card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <p class="text-muted small mb-1">💰 Salaire proposé</p>
                <h4 class="mb-0 text-success"><?= number_format($annonce['salaire_propose'], 0, ',', ' ') ?> Ar</h4>
            </div>
            <div class="col-md-4">
                <p class="text-muted small mb-1">🕐 Heure de début</p>
                <h5 class="mb-0"><?= substr($annonce['heure_debut'], 0, 5) ?></h5>
            </div>
            <div class="col-md-4">
                <p class="text-muted small mb-1">📅 Publiée le</p>
                <h5 class="mb-0"><?= date('d/m/Y', strtotime($annonce['date_publication'])) ?></h5>
            </div>
        </div>
    </div>
</section>

<?php if (session()->get('candidat_id')): ?>
    <div class="text-center">
        <a href="/postuler/<?= $annonce['id'] ?>" class="btn btn-primary btn-lg">Postuler à cette offre →</a>
    </div>
<?php else: ?>
    <div class="alert alert-light border text-center">
        <a href="/candidat/login" class="btn btn-primary">Se connecter pour postuler</a>
        <a href="/candidat/register" class="btn btn-outline-secondary ms-2">Créer un compte</a>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>