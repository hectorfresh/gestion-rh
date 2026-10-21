<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Mes besoins</h2>
<p><a href="/besoin/create">+ Nouveau besoin</a></p>

<?php if (empty($besoins)): ?>
    <p><em>Aucun besoin pour le moment.</em></p>
<?php else: ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>#</th>
            <th>Poste</th>
            <th>Nombre</th>
            <th>Coût estimé</th>
            <th>Date</th>
            <th>Statut</th>
            <th></th>
        </tr>
        <?php foreach ($besoins as $b): ?>
            <tr>
                <td><?= $b['id'] ?></td>
                <td><?= esc($b['poste_libelle']) ?></td>
                <td><?= $b['nombre_postes'] ?></td>
                <td><?= number_format($b['cout_engage'], 2, ',', ' ') ?> Ar</td>
                <td><?= esc($b['date_creation']) ?></td>
                <td><?= esc($b['statut_libelle']) ?></td>
                <td><a href="/besoin/<?= $b['id'] ?>">Détail</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?= $this->endSection() ?>