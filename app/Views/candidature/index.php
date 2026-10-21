<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2 class="mb-3">Candidatures</h2>

<?php if (empty($candidatures)): ?>
    <div class="alert alert-light border">Aucune candidature.</div>
<?php else: ?>
    <div class="card"><table class="table table-hover mb-0">
        <thead class="table-light"><tr>
            <th>#</th><th>Candidat</th><th>Annonce</th><th>Poste</th><th>Statut</th><th>Date</th><th></th>
        </tr></thead>
        <tbody>
        <?php foreach ($candidatures as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td>
                    <strong><?= esc($c['candidat_prenom'] . ' ' . $c['candidat_nom']) ?></strong><br>
                    <small class="text-muted"><?= esc($c['candidat_email']) ?></small>
                </td>
                <td><?= esc($c['annonce_titre']) ?></td>
                <td><?= esc($c['poste_libelle']) ?></td>
                <td><span class="badge badge-statut statut-<?= strtolower($c['statut_code']) ?>"><?= esc($c['statut_libelle']) ?></span></td>
                <td><?= esc($c['date_candidature']) ?></td>
                <td><a href="/candidature/<?= $c['id'] ?>" class="btn btn-sm btn-primary">Traiter</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
<?php endif; ?>
<?= $this->endSection() ?>