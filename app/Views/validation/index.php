<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2 class="mb-3">Besoins en attente de validation RH</h2>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">Budget total</small>
        <h5 class="mb-0"><?= number_format($total, 0, ',', ' ') ?> Ar</h5></div></div>
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">Engagé</small>
        <h5 class="mb-0"><?= number_format($engage, 0, ',', ' ') ?> Ar</h5></div></div>
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">Restant</small>
        <h5 class="mb-0 text-success"><?= number_format($restant, 0, ',', ' ') ?> Ar</h5></div></div>
</div>

<?php if (empty($besoins)): ?>
    <div class="alert alert-light border">Aucun besoin en attente.</div>
<?php else: ?>
    <div class="card"><table class="table table-hover mb-0">
        <thead class="table-light"><tr>
            <th>#</th><th>Poste</th><th>Nombre</th><th>Budget</th><th>Manager</th><th>Date</th><th></th>
        </tr></thead>
        <tbody>
        <?php foreach ($besoins as $b): ?>
            <tr>
                <td><?= $b['id'] ?></td>
                <td><?= esc($b['poste_libelle']) ?></td>
                <td><?= $b['nombre_postes'] ?></td>
                <td><?= number_format($b['cout_engage'], 0, ',', ' ') ?> Ar</td>
                <td><?= esc($b['manager_nom']) ?></td>
                <td><?= esc($b['date_creation']) ?></td>
                <td><a href="/validation/<?= $b['id'] ?>" class="btn btn-sm btn-primary">Traiter</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
<?php endif; ?>
<?= $this->endSection() ?>