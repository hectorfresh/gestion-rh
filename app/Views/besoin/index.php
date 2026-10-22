<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Besoins / Gestion</div>
        <h1 class="page-title">Mes besoins</h1>
        <p class="page-description">Créez et suivez vos demandes de personnel.</p>
    </div>
    <div class="btn-list">
        <a href="/besoin/create" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
            Nouveau besoin
        </a>
    </div>
</header>

<?php if (empty($besoins)): ?>
    <div class="alert alert-light border text-center py-4">Aucun besoin pour le moment.</div>
<?php else: ?>
    <section class="content-card">
        <div class="content-card-header">
            <div>
                <h2 class="content-card-title">Liste des besoins</h2>
                <div class="content-card-description">Ensemble de vos demandes de personnel.</div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table data-table table-vcenter mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Poste</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Budget engagé</th>
                        <th scope="col">Date</th>
                        <th scope="col">Statut</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($besoins as $b): ?>
                    <tr>
                        <td><a href="/besoin/<?= $b['id'] ?>" class="record-link">#<?= $b['id'] ?></a></td>
                        <td><?= esc($b['poste_libelle']) ?></td>
                        <td><?= $b['nombre_postes'] ?></td>
                        <td><span class="amount"><?= number_format($b['cout_engage'], 0, ',', ' ') ?> Ar</span></td>
                        <td><span class="date-value"><?= esc($b['date_creation']) ?></span></td>
                        <td><span class="badge badge-statut statut-<?= strtolower($b['statut_code']) ?>"><?= esc($b['statut_libelle']) ?></span></td>
                        <td class="text-end">
                            <a href="/besoin/<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary">Détail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>
<?= $this->endSection() ?>