<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2 class="mb-3">Recrutement — Annonces</h2>

<h4>Besoins validés à traiter</h4>
<?php if (empty($aTraiter)): ?>
    <div class="alert alert-light border">Aucun besoin en attente.</div>
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
          <td>
    <a href="/annonce/create/<?= $b['id'] ?>" class="btn btn-sm btn-primary">
        Créer <?= (int) ($b['nombre_postes'] - ($b['nb_annonces'] ?? 0)) ?> annonce(s)
    </a>
</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
<?php endif; ?>

<h4>Annonces publiées</h4>
<?php if (empty($annonces)): ?>
    <div class="alert alert-light border">Aucune annonce publiée.</div>
<?php else: ?>
    <div class="card"><table class="table mb-0">
        <thead class="table-light"><tr>
            <th>#</th><th>Titre</th><th>Poste</th><th>Salaire</th><th>Publiée</th><th>Statut</th><th>Actions</th>
        </tr></thead>
        <tbody>
        <?php foreach ($annonces as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= esc($a['titre']) ?></td>
                <td><?= esc($a['poste_libelle']) ?></td>
                <td><?= number_format($a['salaire_propose'], 0, ',', ' ') ?> Ar</td>
                <td><?= esc($a['date_publication']) ?></td>
                <td>
                    <?php if ($a['pourvue']): ?>
                        <span class="badge bg-secondary">Clôturée</span>
                    <?php else: ?>
                        <span class="badge bg-success">Ouverte</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="/annonce/<?= $a['id'] ?>" class="btn btn-sm btn-outline-primary">Voir</a>
                    <?php if (!$a['pourvue']): ?>
                        <a href="/annonce/cloturer/<?= $a['id'] ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Clôturer ?');">Clôturer</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
<?php endif; ?>
<?= $this->endSection() ?>