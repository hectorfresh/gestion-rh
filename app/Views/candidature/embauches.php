<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Recrutement / Embauches</div>
        <h1 class="page-title">Candidats embauchés</h1>
        <p class="page-description">Historique de tous les recrutements finalisés.</p>
    </div>
    <div class="btn-list">
        <a href="/candidature" class="btn btn-outline-secondary">← Retour aux candidatures</a>
    </div>
</header>

<?php if (empty($embauches)): ?>
    <div class="alert alert-light border text-center py-4">Aucun candidat embauché pour le moment.</div>
<?php else: ?>
    <?php
    $totalEmbauches = count($embauches);
    $nbCDI = 0;
    $nbEssai = 0;
    $masseSalariale = 0;
    foreach ($embauches as $e) {
        if (!empty($e['contrat_type'])) {
            if ($e['contrat_type'] === 'CDI')   $nbCDI++;
            if ($e['contrat_type'] === 'Essai') $nbEssai++;
        }
        if (!empty($e['contrat_salaire'])) {
            $masseSalariale += (float) $e['contrat_salaire'];
        }
    }
    ?>

    <div class="row row-cards g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-label">Total embauches</div>
                <div class="stat-value"><?= $totalEmbauches ?></div>
                <div class="stat-note">Recrutements finalisés</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-label">CDI</div>
                <div class="stat-value text-success"><?= $nbCDI ?></div>
                <div class="stat-note">Contrats à durée indéterminée</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-label">Contrats d'essai</div>
                <div class="stat-value text-info"><?= $nbEssai ?></div>
                <div class="stat-note">En période d'essai</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-label">Masse salariale</div>
                <div class="stat-value"><?= number_format($masseSalariale, 0, ',', ' ') ?> Ar</div>
                <div class="stat-note">Annuelle</div>
            </div>
        </div>
    </div>

    <section class="content-card">
        <div class="table-responsive">
            <table class="table data-table table-vcenter mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Candidat</th>
                        <th scope="col">Poste</th>
                        <th scope="col">Contrat</th>
                        <th scope="col">Salaire</th>
                        <th scope="col">Dates</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($embauches as $e): ?>
                    <tr>
                        <td class="text-muted"><?= $e['id'] ?></td>
                        <td>
                            <div class="client-cell">
                                <div class="client-avatar"><?= strtoupper(substr($e['candidat_prenom'],0,1).substr($e['candidat_nom'],0,1)) ?></div>
                                <div>
                                    <div class="client-name"><?= esc($e['candidat_prenom'] . ' ' . $e['candidat_nom']) ?></div>
                                    <div class="secondary-text"><?= esc($e['candidat_email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div><?= esc($e['poste_libelle']) ?></div>
                            <div class="secondary-text"><?= esc($e['annonce_titre']) ?></div>
                        </td>
                        <td>
                            <?php if (!empty($e['contrat_type'])): ?>
                                <span class="badge bg-<?= $e['contrat_type'] === 'CDI' ? 'success' : 'info' ?>"><?= esc($e['contrat_type']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($e['contrat_salaire'])): ?>
                                <span class="amount"><?= number_format($e['contrat_salaire'], 0, ',', ' ') ?> Ar</span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($e['contrat_date_debut'])): ?>
                                <span class="date-value"><?= esc($e['contrat_date_debut']) ?></span>
                                <?php if (!empty($e['contrat_date_fin'])): ?>
                                    <div class="secondary-text">→ <?= esc($e['contrat_date_fin']) ?></div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="/candidature/<?= $e['id'] ?>" class="btn btn-sm btn-outline-primary">Voir la fiche</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>
<?= $this->endSection() ?>