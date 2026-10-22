<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Recrutement / Candidatures</div>
        <h1 class="page-title">Candidatures</h1>
        <p class="page-description">Suivez et traitez les candidatures reçues.</p>
    </div>
</header>

<?php if (empty($candidatures)): ?>
    <div class="alert alert-light border text-center py-4">Aucune candidature.</div>
<?php else: ?>
    <section class="content-card">
        <div class="table-responsive">
            <table class="table data-table table-vcenter mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Candidat</th>
                        <th scope="col">Annonce</th>
                        <th scope="col">Poste</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Date</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($candidatures as $c): ?>
                    <tr>
                        <td><a href="/candidature/<?= $c['id'] ?>" class="record-link">#<?= $c['id'] ?></a></td>
                        <td>
                            <div class="client-cell">
                                <div class="client-avatar"><?= strtoupper(substr($c['candidat_prenom'],0,1).substr($c['candidat_nom'],0,1)) ?></div>
                                <div>
                                    <div class="client-name"><?= esc($c['candidat_prenom'] . ' ' . $c['candidat_nom']) ?></div>
                                    <div class="secondary-text"><?= esc($c['candidat_email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?= esc($c['annonce_titre']) ?></td>
                        <td><?= esc($c['poste_libelle']) ?></td>
                        <td><span class="badge badge-statut statut-<?= strtolower($c['statut_code']) ?>"><?= esc($c['statut_libelle']) ?></span></td>
                        <td><span class="date-value"><?= esc($c['date_candidature']) ?></span></td>
                        <td class="text-end">
                            <a href="/candidature/<?= $c['id'] ?>" class="btn btn-sm btn-primary">Traiter</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>
<?= $this->endSection() ?>