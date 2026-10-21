<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Recrutement — Annonces</h2>

<h3>Besoins validés sans annonce</h3>
<?php if (empty($aTraiter)): ?>
    <p><em>Aucun besoin en attente de création d'annonce.</em></p>
<?php else: ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>#</th>
            <th>Poste</th>
            <th>Annonces créés</th>
            <th>Budget engagé</th>
            <th>Manager</th>
            <th></th>
        </tr>
        <?php foreach ($aTraiter as $b): ?>
            <tr>
                <td><?= $b['id'] ?></td>
                <td><?= esc($b['poste_libelle']) ?></td>
                <td><?= $b['nb_annonces'] ?> / <?= $b['nombre_postes'] ?></td>
                <td><?= number_format($b['cout_engage'], 0, ',', ' ') ?> Ar</td>
                <td><?= esc($b['manager_nom']) ?></td>
                <td><a href="/annonce/create/<?= $b['id'] ?>">Créer l'annonce</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<hr>

<h3>Annonces publiées</h3>
<?php if (empty($annonces)): ?>
    <p><em>Aucune annonce publiée.</em></p>
<?php else: ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>#</th>
            <th>Titre</th>
            <th>Poste</th>
            <th>Nombre</th>
            <th>Salaire proposé</th>
            <th>Date publication</th>
            <th></th>
        </tr>
        <?php foreach ($annonces as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= esc($a['titre']) ?></td>
                <td><?= esc($a['poste_libelle']) ?></td>
                <td><?= $a['nombre_postes'] ?></td>
                <td><?= number_format($a['salaire_propose'], 0, ',', ' ') ?> Ar</td>
                <td><?= esc($a['date_publication']) ?></td>
                <td><a href="/annonce/<?= $a['id'] ?>">Voir</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?= $this->endSection() ?>