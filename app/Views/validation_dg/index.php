<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Direction / Validation DG</div>
        <h1 class="page-title">Besoins en attente de validation DG</h1>
        <p class="page-description">Décision finale sur les besoins validés automatiquement par la finance.</p>
    </div>
</header>

<?php if (empty($besoins)): ?>
    <div class="alert alert-light border text-center py-4">Aucun besoin en attente.</div>
<?php else: ?>
    <section class="content-card">
        <div class="table-responsive">
            <table class="table data-table table-vcenter mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Poste</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Budget engagé</th>
                        <th scope="col">Manager</th>
                        <th scope="col">Date</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($besoins as $b): ?>
                    <tr>
                        <td><a href="/dg/<?= $b['id'] ?>" class="record-link">#<?= $b['id'] ?></a></td>
                        <td><?= esc($b['poste_libelle']) ?></td>
                        <td><?= $b['nombre_postes'] ?></td>
                        <td><span class="amount"><?= number_format($b['cout_engage'], 0, ',', ' ') ?> Ar</span></td>
                        <td><?= esc($b['manager_nom']) ?></td>
                        <td><span class="date-value"><?= esc($b['date_creation']) ?></span></td>
                        <td class="text-end">
                            <a href="/dg/<?= $b['id'] ?>" class="btn btn-sm btn-primary">Traiter</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>
<?= $this->endSection() ?>