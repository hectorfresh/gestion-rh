<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Besoins en attente de validation DG</h2>

<?php if (empty($besoins)): ?>
    <p><em>Aucun besoin en attente.</em></p>
<?php else: ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>#</th>
            <th>Poste</th>
            <th>Nombre</th>
            <th>Budget engagé</th>
            <th>Manager</th>
            <th>Date</th>
            <th></th>
        </tr>
        <?php foreach ($besoins as $b): ?>
            <tr>
                <td><?= $b['id'] ?></td>
                <td><?= esc($b['poste_libelle']) ?></td>
                <td><?= $b['nombre_postes'] ?></td>
                <td><?= number_format($b['cout_engage'], 0, ',', ' ') ?> Ar</td>
                <td><?= esc($b['manager_nom']) ?></td>
                <td><?= esc($b['date_creation']) ?></td>
                <td><a href="/dg/<?= $b['id'] ?>">Traiter</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?= $this->endSection() ?>