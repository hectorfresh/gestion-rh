<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Administration / Emails</div>
        <h1 class="page-title">Boîte d'envoi</h1>
        <p class="page-description">Tous les emails que l'application aurait envoyés.</p>
    </div>
</header>

<?php if (empty($emails)): ?>
    <div class="alert alert-light border text-center py-4">Aucun email.</div>
<?php else: ?>
    <section class="content-card">
        <div class="table-responsive">
            <table class="table data-table table-vcenter mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Destinataire</th>
                        <th scope="col">Sujet</th>
                        <th scope="col">Date</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($emails as $e): ?>
                    <tr>
                        <td><a href="/emails/<?= $e['id'] ?>" class="record-link">#<?= $e['id'] ?></a></td>
                        <td><?= esc($e['destinataire']) ?></td>
                        <td><?= esc($e['sujet']) ?></td>
                        <td><span class="date-value"><?= esc($e['date_envoi']) ?></span></td>
                        <td class="text-end">
                            <a href="/emails/<?= $e['id'] ?>" class="btn btn-sm btn-outline-primary">Voir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>
<?= $this->endSection() ?>