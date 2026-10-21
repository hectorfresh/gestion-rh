<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2>📬 Boîte d'envoi (emails simulés)</h2>
<p><em>Tous les emails que l'application aurait envoyés.</em></p>
<?php if (empty($emails)): ?>
    <p><em>Aucun email pour le moment.</em></p>
<?php else: ?>
    <table>
        <tr><th>#</th><th>Destinataire</th><th>Sujet</th><th>Date</th><th></th></tr>
        <?php foreach ($emails as $e): ?>
            <tr>
                <td><?= $e['id'] ?></td>
                <td><?= esc($e['destinataire']) ?></td>
                <td><?= esc($e['sujet']) ?></td>
                <td><?= esc($e['date_envoi']) ?></td>
                <td><a href="/emails/<?= $e['id'] ?>">Voir</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<?= $this->endSection() ?>