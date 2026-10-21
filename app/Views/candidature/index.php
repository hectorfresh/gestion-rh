<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Candidatures</h2>

<?php if (empty($candidatures)): ?>
    <p><em>Aucune candidature pour le moment.</em></p>
<?php else: ?>
    <table>
        <tr>
            <th>#</th><th>Candidat</th><th>Annonce</th>
            <th>Poste</th><th>Statut</th><th>Date</th><th></th>
        </tr>
        <?php foreach ($candidatures as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td>
                    <strong><?= esc($c['candidat_prenom'] . ' ' . $c['candidat_nom']) ?></strong><br>
                    <small><?= esc($c['candidat_email']) ?></small>
                </td>
                <td><?= esc($c['annonce_titre']) ?></td>
                <td><?= esc($c['poste_libelle']) ?></td>
                <td><span class="badge badge-<?= strtolower($c['statut_code']) ?>">
                    <?= esc($c['statut_libelle']) ?></span></td>
                <td><?= esc($c['date_candidature']) ?></td>
                <td><a href="/candidature/<?= $c['id'] ?>" class="btn btn-primary">Traiter</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?= $this->endSection() ?>