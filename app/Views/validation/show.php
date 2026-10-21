<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2 class="mb-3">Traiter le besoin #<?= $besoin['id'] ?></h2>

<div class="card p-4 mb-4">
    <p><strong>Poste :</strong> <?= esc($besoin['poste_libelle']) ?></p>
    <p><strong>Nombre :</strong> <?= $besoin['nombre_postes'] ?></p>
    <p><strong>Budget engagé demandé :</strong> <?= number_format($besoin['cout_engage'], 0, ',', ' ') ?> Ar</p>
    <p><strong>Manager :</strong> <?= esc($besoin['manager_nom']) ?></p>
    <p><strong>Date :</strong> <?= esc($besoin['date_creation']) ?></p>
    <hr>
    <p class="mb-0"><small>
        Budget total : <strong><?= number_format($total, 0, ',', ' ') ?> Ar</strong> —
        Engagé : <strong><?= number_format($engage, 0, ',', ' ') ?> Ar</strong> —
        Restant : <strong class="text-success"><?= number_format($restant, 0, ',', ' ') ?> Ar</strong>
    </small></p>
</div>

<?php if ($besoin['cout_engage'] > $restant): ?>
    <div class="alert alert-danger">⚠️ Budget insuffisant : la finance refusera automatiquement.</div>
<?php endif; ?>

<form method="post" action="/validation/traiter/<?= $besoin['id'] ?>">
    <div class="mb-3">
        <label class="form-label">Commentaire (optionnel)</label>
        <textarea name="commentaire" rows="2" class="form-control"></textarea>
    </div>
    <button type="submit" name="decision" value="valider" class="btn btn-success">✓ Valider</button>
    <button type="submit" name="decision" value="refuser" class="btn btn-danger">✗ Refuser</button>
    <a href="/validation" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>