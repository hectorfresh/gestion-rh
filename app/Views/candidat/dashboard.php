<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Mes candidatures</h2>
    <a href="/annonces" class="btn btn-primary">🌐 Voir les annonces</a>
</div>

<?php if (empty($candidatures)): ?>
    <div class="alert alert-light border">Aucune candidature pour l'instant.</div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($candidatures as $c): ?>
            <div class="col-md-6">
                <div class="card p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-1"><?= esc($c['annonce_titre']) ?></h5>
                            <p class="text-muted small mb-2"><?= esc($c['poste_libelle']) ?> — <?= esc($c['date_candidature']) ?></p>
                        </div>
                        <span class="badge badge-statut statut-<?= strtolower($c['statut_code']) ?>"><?= esc($c['statut_libelle']) ?></span>
                    </div>
                    <a href="/candidat/candidature/<?= $c['id'] ?>" class="btn btn-outline-primary btn-sm mt-2">Voir le détail</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>