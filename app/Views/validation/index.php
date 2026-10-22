<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Validation / RH</div>
        <h1 class="page-title">Besoins en attente de validation RH</h1>
        <p class="page-description">Traitez les demandes de personnel soumises par les managers.</p>
    </div>
</header>

<div class="row row-cards g-3 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-label">Budget total</div>
            <div class="stat-value"><?= number_format($total, 0, ',', ' ') ?> Ar</div>
            <div class="stat-note">Montant global disponible</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-label">Engagé</div>
            <div class="stat-value"><?= number_format($engage, 0, ',', ' ') ?> Ar</div>
            <div class="stat-note">Déjà engagé sur les besoins</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-label">Restant</div>
            <div class="stat-value text-success"><?= number_format($restant, 0, ',', ' ') ?> Ar</div>
            <div class="stat-note">Budget disponible</div>
        </div>
    </div>
</div>

<?php if (empty($besoins)): ?>
    <div class="alert alert-light border text-center py-4">Aucun besoin en attente.</div>
<?php else: ?>
    <section class="content-card">
        <div class="content-card-header">
            <div>
                <h2 class="content-card-title">Besoins à traiter</h2>
                <div class="content-card-description">Liste des demandes en attente de décision.</div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table data-table table-vcenter mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Poste</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Budget</th>
                        <th scope="col">Manager</th>
                        <th scope="col">Date</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($besoins as $b): ?>
                    <tr>
                        <td><a href="/validation/<?= $b['id'] ?>" class="record-link">#<?= $b['id'] ?></a></td>
                        <td><?= esc($b['poste_libelle']) ?></td>
                        <td><?= $b['nombre_postes'] ?></td>
                        <td><span class="amount"><?= number_format($b['cout_engage'], 0, ',', ' ') ?> Ar</span></td>
                        <td><?= esc($b['manager_nom']) ?></td>
                        <td><span class="date-value"><?= esc($b['date_creation']) ?></span></td>
                        <td class="text-end">
                            <a href="/validation/<?= $b['id'] ?>" class="btn btn-sm btn-primary">Traiter</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>
<?= $this->endSection() ?>