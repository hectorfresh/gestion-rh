<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2 class="mb-4">Besoin #<?= $besoin['id'] ?></h2>

<div class="card p-4 mb-4">
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

<h4>Historique des validations</h4>
<?php if (empty($validations)): ?>
    <div class="alert alert-light border">Aucune validation pour le moment.</div>
<?php else: ?>
    <div class="card"><table class="table mb-0">
        <thead class="table-light"><tr><th>Étape</th><th>Décision</th><th>Par</th><th>Date</th><th>Commentaire</th></tr></thead>
        <tbody>
        <?php foreach ($validations as $v): ?>
            <tr>
                <td><?= esc($v['etape_libelle']) ?></td>
                <td><?= esc($v['decision_libelle']) ?></td>
                <td><?= esc($v['utilisateur_nom'] ?? 'Automatique (finance)') ?></td>
                <td><?= esc($v['date_validation']) ?></td>
                <td><?= esc($v['commentaire'] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
<?php endif; ?>

<a href="/besoin" class="btn btn-outline-secondary mt-3">← Retour</a>
<?= $this->endSection() ?>