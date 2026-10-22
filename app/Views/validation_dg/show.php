<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Direction / Validation DG</div>
        <h1 class="page-title">Décision DG — Besoin #<?= $besoin['id'] ?></h1>
        <p class="page-description">Examinez l'historique et prenez la décision finale.</p>
    </div>
    <div class="btn-list">
        <a href="/dg" class="btn btn-outline-secondary">← Retour</a>
    </div>
</header>

<section class="content-card mb-4">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Détail du besoin</h2>
        </div>
    </div>
    <div class="content-card-body">
        <p><strong>Poste :</strong> <?= esc($besoin['poste_libelle']) ?></p>
        <p><strong>Nombre de postes :</strong> <?= $besoin['nombre_postes'] ?></p>
        <p><strong>Budget engagé :</strong> <?= number_format($besoin['cout_engage'], 0, ',', ' ') ?> Ar</p>
        <p><strong>Manager :</strong> <?= esc($besoin['manager_nom']) ?></p>
        <p><strong>Date :</strong> <?= esc($besoin['date_creation']) ?></p>
    </div>
</section>

<h2 class="content-card-title mb-3" style="font-size:1.1rem;">Historique des validations</h2>
<?php $validations = (new \App\Models\ValidationModel())->listerParBesoin($besoin['id']); ?>
<?php if (empty($validations)): ?>
    <div class="alert alert-light border">Aucune validation enregistrée.</div>
<?php else: ?>
    <section class="content-card mb-4">
        <div class="table-responsive">
            <table class="table data-table table-vcenter mb-0">
                <thead>
                    <tr>
                        <th scope="col">Étape</th>
                        <th scope="col">Décision</th>
                        <th scope="col">Par</th>
                        <th scope="col">Date</th>
                        <th scope="col">Commentaire</th>
                    </tr>
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

<section class="content-card">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Décision</h2>
            <div class="content-card-description">Validez ou refusez ce besoin.</div>
        </div>
    </div>
    <div class="content-card-body">
        <form method="post" action="/dg/traiter/<?= $besoin['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Commentaire (optionnel)</label>
                <textarea name="commentaire" rows="3" class="form-control"></textarea>
            </div>
            <div class="btn-list">
                <button type="submit" name="decision" value="valider" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Valider</button>
                <button type="submit" name="decision" value="refuser" class="btn btn-danger"><i class="bi bi-x-lg me-1"></i> Refuser</button>
                <a href="/dg" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</section>
<?= $this->endSection() ?>