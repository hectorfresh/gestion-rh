<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2 class="mb-3">📬 Boîte d'envoi (emails simulés)</h2>
<p class="text-muted small">Tous les emails que l'application aurait envoyés.</p>

<?php if (empty($emails)): ?>
    <div class="alert alert-light border">Aucun email.</div>
<?php else: ?>
    <div class="card"><table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>#</th><th>Destinataire</th><th>Sujet</th><th>Date</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($emails as $e): ?>
            <tr>
                <td><?= $e['id'] ?></td>
                <td><?= esc($e['destinataire']) ?></td>
                <td><?= esc($e['sujet']) ?></td>
                <td><?= esc($e['date_envoi']) ?></td>
                <td><a href="/emails/<?= $e['id'] ?>" class="btn btn-sm btn-outline-primary">Voir</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
<?php endif; ?>
<?= $this->endSection() ?>