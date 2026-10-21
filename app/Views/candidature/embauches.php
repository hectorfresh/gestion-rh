<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>👥 Candidats embauchés</h2>
<p><a href="/candidature" class="btn btn-primary">← Retour aux candidatures</a></p>

<?php if (empty($embauches)): ?>
    <p><em>Aucun candidat embauché pour le moment.</em></p>
<?php else: ?>
    <table>
        <tr>
            <th>#</th>
            <th>Candidat</th>
            <th>Poste</th>
            <th>Contrat</th>
            <th>Salaire</th>
            <th>Date début</th>
            <th>Date fin</th>
            <th></th>
        </tr>
        <?php foreach ($embauches as $e): ?>
            <tr>
                <td><?= $e['id'] ?></td>
                <td>
                    <strong><?= esc($e['candidat_prenom'] . ' ' . $e['candidat_nom']) ?></strong><br>
                    <small><?= esc($e['candidat_email']) ?></small><br>
                    <small><?= esc($e['candidat_telephone']) ?></small>
                </td>
                <td>
                    <?= esc($e['poste_libelle']) ?><br>
                    <small><?= esc($e['annonce_titre']) ?></small>
                </td>
                <td><strong><?= esc($e['contrat_type'] ?? '—') ?></strong></td>
                <td>
                    <?= $e['contrat_salaire']
                        ? number_format($e['contrat_salaire'], 0, ',', ' ') . ' Ar'
                        : '—' ?>
                </td>
                <td><?= esc($e['contrat_date_debut'] ?? '—') ?></td>
                <td><?= esc($e['contrat_date_fin'] ?? '—') ?></td>
                <td><a href="/candidature/<?= $e['id'] ?>" class="btn btn-primary">Voir</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?= $this->endSection() ?>