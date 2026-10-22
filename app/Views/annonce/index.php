<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Recrutement / Annonces</div>
        <h1 class="page-title">Annonces de recrutement</h1>
        <p class="page-description">Gérez les annonces créées à partir des besoins validés.</p>
    </div>
</header>

<h2 class="content-card-title mb-3" style="font-size:1.1rem;">Besoins validés à traiter</h2>
<?php if (empty($aTraiter)): ?>
    <div class="alert alert-light border text-center py-4">Aucun besoin en attente.</div>
<?php else: ?>
    <div class="card mb-4"><table class="table mb-0">
        <thead class="table-light"><tr>
            <th>#</th><th>Poste</th><th>Annonces</th><th>Budget</th><th>Manager</th><th></th>
        </tr></thead>
        <tbody>
        <?php foreach ($aTraiter as $b): ?>
            <tr>
                <td><?= $b['id'] ?></td>
                <td><?= esc($b['poste_libelle']) ?></td>
                <td><?= $b['nb_annonces'] ?? 0 ?> / <?= $b['nombre_postes'] ?></td>
                <td><?= number_format($b['cout_engage'], 0, ',', ' ') ?> Ar</td>
                <td><?= esc($b['manager_nom']) ?></td>
                <td><a href="/annonce/create/<?= $b['id'] ?>" class="btn btn-sm btn-primary">Créer l'annonce</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
<?php endif; ?>

<h2 class="content-card-title mb-3" style="font-size:1.1rem;">Annonces publiées</h2>
<?php if (empty($annonces)): ?>
    <div class="alert alert-light border text-center py-4">Aucune annonce publiée.</div>
<?php else: ?>
    <section class="content-card">
        <div class="table-responsive">
            <table class="table data-table table-vcenter mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Poste</th>
                        <th scope="col">Salaire</th>
                        <th scope="col">Publiée</th>
                        <th scope="col">Statut</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($annonces as $a): ?>
                    <tr>
                        <td><a href="/annonce/<?= $a['id'] ?>" class="record-link">#<?= $a['id'] ?></a></td>
                        <td><?= esc($a['titre']) ?></td>
                        <td><?= esc($a['poste_libelle']) ?></td>
                        <td><span class="amount"><?= number_format($a['salaire_propose'], 0, ',', ' ') ?> Ar</span></td>
                        <td><span class="date-value"><?= esc($a['date_publication']) ?></span></td>
                        <td>
                            <?php if ($a['pourvue']): ?>
                                <span class="status status-cancelled"><span class="status-dot"></span>Clôturée</span>
                            <?php else: ?>
                                <span class="status status-completed"><span class="status-dot"></span>Ouverte</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="/annonce/<?= $a['id'] ?>" class="btn btn-sm btn-outline-primary">Voir</a>
                            <?php if (!$a['pourvue']): ?>
                                <a href="/annonce/cloturer/<?= $a['id'] ?>" class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Clôturer ?');">Clôturer</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>
<?= $this->endSection() ?>