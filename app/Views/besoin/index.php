<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Mes besoins</h2>
    <a href="/besoin/create" class="btn btn-primary">+ Nouveau besoin</a>
</div>

<?php if (empty($besoins)): ?>
    <div class="alert alert-light border">Aucun besoin pour le moment.</div>
<?php else: ?>
    <div class="card"><div class="table-responsive"><table class="table table-hover mb-0">
        <thead class="table-light"><tr>
            <th>#</th><th>Poste</th><th>Nombre</th><th>Budget engagé</th><th>Date</th><th>Statut</th><th></th>
        </tr></thead>
        <tbody>
        <?php foreach ($besoins as $b): ?>
            <tr>
                <td><?= $b['id'] ?></td>
                <td><?= esc($b['poste_libelle']) ?></td>
                <td><?= $b['nombre_postes'] ?></td>
                <td><?= number_format($b['cout_engage'], 0, ',', ' ') ?> Ar</td>
                <td><?= esc($b['date_creation']) ?></td>
                <td><span class="badge badge-statut statut-<?= strtolower($b['statut_code']) ?>"><?= esc($b['statut_libelle']) ?></span></td>
                <td><a href="/besoin/<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary">Détail</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div></div>
<?php endif; ?>
<?= $this->endSection() ?>