<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Validation / RH</div>
        <h1 class="page-title">Traiter le besoin #<?= $besoin['id'] ?></h1>
        <p class="page-description">Examinez la demande et prenez une décision.</p>
    </div>
    <div class="btn-list">
        <a href="/validation" class="btn btn-outline-secondary">← Retour</a>
    </div>
</header>

<section class="content-card mb-4">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Détail du besoin</h2>
            <div class="content-card-description">Informations soumises par le manager.</div>
        </div>
    </div>
    <div class="content-card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Poste :</strong> <?= esc($besoin['poste_libelle']) ?></p>
                <p><strong>Nombre :</strong> <?= $besoin['nombre_postes'] ?></p>
                <p><strong>Coût mensuel demandé :</strong> <?= number_format($besoin['cout_engage'], 0, ',', ' ') ?> Ar</p>
                <p><strong>Coût annuel demandé :</strong> <?= number_format($coutAnnuel, 0, ',', ' ') ?> Ar</p>
            </div>
            <div class="col-md-6">
                <p><strong>Manager :</strong> <?= esc($besoin['manager_nom']) ?></p>
                <p><strong>Date :</strong> <?= esc($besoin['date_creation']) ?></p>
            </div>
        </div>
        <hr>
        <p class="mb-0 small text-muted">
            Budget annuel total : <strong><?= number_format($total, 0, ',', ' ') ?> Ar</strong> —
            Engagé : <strong><?= number_format($engage, 0, ',', ' ') ?> Ar</strong> —
            Restant : <strong class="text-success"><?= number_format($restant, 0, ',', ' ') ?> Ar</strong>
        </p>
    </div>
</section>

<?php if ($coutAnnuel > $restant): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-1"></i> Budget annuel insuffisant : la finance refusera automatiquement.</div>
<?php endif; ?>

<section class="content-card">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Décision</h2>
            <div class="content-card-description">Validez ou refusez ce besoin.</div>
        </div>
    </div>
    <div class="content-card-body">
        <form method="post" action="/validation/traiter/<?= $besoin['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Commentaire (optionnel)</label>
                <textarea name="commentaire" rows="2" class="form-control"></textarea>
            </div>
            <div class="btn-list">
                <button type="submit" name="decision" value="valider" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Valider</button>
                <button type="submit" name="decision" value="refuser" class="btn btn-danger"><i class="bi bi-x-lg me-1"></i> Refuser</button>
                <a href="/validation" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</section>
<?= $this->endSection() ?>