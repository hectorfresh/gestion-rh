<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Espace candidat</div>
        <h1 class="page-title">Mes candidatures</h1>
        <p class="page-description">Suivez l'état de vos candidatures.</p>
    </div>
    <div class="btn-list">
        <a href="/annonces" class="btn btn-primary"><i class="bi bi-globe me-1"></i> Voir les annonces</a>
    </div>
</header>

<?php if (empty($candidatures)): ?>
    <div class="alert alert-light border text-center py-4">Aucune candidature pour l'instant.</div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($candidatures as $c): ?>
            <div class="col-md-6">
                <section class="content-card h-100">
                    <div class="content-card-header">
                        <div>
                            <h2 class="content-card-title"><?= esc($c['annonce_titre']) ?></h2>
                            <div class="content-card-description"><?= esc($c['poste_libelle']) ?> — <?= esc($c['date_candidature']) ?></div>
                        </div>
                        <span class="badge badge-statut statut-<?= strtolower($c['statut_code']) ?>"><?= esc($c['statut_libelle']) ?></span>
                    </div>
                    <div class="content-card-body">
                        <a href="/candidat/candidature/<?= $c['id'] ?>" class="btn btn-outline-primary btn-sm">Voir le détail</a>
                    </div>
                </section>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>