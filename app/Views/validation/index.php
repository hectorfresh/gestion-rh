<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Besoins en attente de validation RH</h2>

<p>
    <strong>Budget total :</strong> <?= number_format($total, 0, ',', ' ') ?> Ar —
    <strong>Engagé :</strong> <?= number_format($engage, 0, ',', ' ') ?> Ar —
    <strong>Restant :</strong> <?= number_format($restant, 0, ',', ' ') ?> Ar
</p>

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
                <td><a href="/validation/<?= $b['id'] ?>">Traiter</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?= $this->endSection() ?>