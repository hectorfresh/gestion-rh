<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Besoins / Détail</div>
        <h1 class="page-title">Besoin #<?= $besoin['id'] ?></h1>
        <p class="page-description">Détail de la demande de personnel.</p>
    </div>
    <div class="btn-list">
        <a href="/besoin" class="btn btn-outline-secondary">← Retour</a>
    </div>
</header>

<section class="content-card mb-4">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Informations</h2>
            <div class="content-card-description">Détails du besoin exprimé.</div>
        </div>
    </div>
    <div class="content-card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Poste :</strong> <?= esc($besoin['poste_libelle']) ?></p>
                <p><strong>Nombre :</strong> <?= $besoin['nombre_postes'] ?></p>
                <p><strong>Budget engagé :</strong> <?= number_format($besoin['cout_engage'], 0, ',', ' ') ?> Ar</p>
            </div>
            <div class="col-md-6">
                <p><strong>Manager :</strong> <?= esc($besoin['manager_nom']) ?></p>
                <p><strong>Date :</strong> <?= esc($besoin['date_creation']) ?></p>
                <p><strong>Statut :</strong>
                    <span class="badge badge-statut statut-<?= strtolower($besoin['statut_code']) ?>">
                        <?= esc($besoin['statut_libelle']) ?></span>
                </p>
            </div>
        </div>
    </div>
</section>

<h2 class="page-title" style="font-size:1.25rem;">Historique des validations</h2>
<?php if (empty($validations)): ?>
    <div class="alert alert-light border">Aucune validation pour le moment.</div>
<?php else: ?>
    <section class="content-card">
        <div class="table-responsive">
            <table class="table data-table table-vcenter mb-0">
                <thead>
                    <tr><th scope="col">Étape</th><th scope="col">Décision</th><th scope="col">Par</th><th scope="col">Date</th><th scope="col">Commentaire</th></tr>
                </thead>
                <tbody>
                <?php foreach ($validations as $v): ?>
                    <tr>
                        <td><?= esc($v['etape_libelle']) ?></td>
                        <td><?= esc($v['decision_libelle']) ?></td>
                        <td><?= esc($v['utilisateur_nom'] ?? 'Automatique (finance)') ?></td>
                        <td><span class="date-value"><?= esc($v['date_validation']) ?></span></td>
                        <td><?= esc($v['commentaire'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>
<?= $this->endSection() ?>