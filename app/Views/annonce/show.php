<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Recrutement / Annonce</div>
        <h1 class="page-title"><?= esc($annonce['titre']) ?></h1>
        <p class="page-description">Détail de l'annonce de recrutement.</p>
    </div>
    <div class="btn-list">
        <a href="/annonce" class="btn btn-outline-secondary">← Retour</a>
    </div>
</header>

<section class="content-card mb-4">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Informations clés</h2>
            <div class="content-card-description">Poste, salaire et publication.</div>
        </div>
    </div>
    <div class="content-card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Poste :</strong> <?= esc($annonce['poste_libelle']) ?></p>
                <p><strong>Nombre de postes :</strong> <?= $annonce['nombre_postes'] ?></p>
                <p><strong>Salaire proposé :</strong>
                    <?= number_format($annonce['salaire_min'], 0, ',', ' ') ?>
                    à <?= number_format($annonce['salaire_max'], 0, ',', ' ') ?> Ar
                </p>
            </div>
            <div class="col-md-6">
                <p><strong>Publié le :</strong> <?= esc($annonce['date_publication']) ?></p>
                <p><strong>Par :</strong> <?= esc($annonce['rh_nom']) ?></p>
            </div>
        </div>
    </div>
</section>

<section class="content-card">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Description</h2>
        </div>
    </div>
    <div class="content-card-body">
        <p><?= nl2br(esc($annonce['description'])) ?></p>
    </div>
</section>
<?= $this->endSection() ?>