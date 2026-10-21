<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">🏆 Candidats embauchés</h2>
        <p class="text-muted mb-0">Historique de tous les recrutements finalisés.</p>
    </div>
    <a href="/candidature" class="btn btn-outline-secondary">← Retour aux candidatures</a>
</div>

<?php if (empty($embauches)): ?>
    <div class="alert alert-light border">
        Aucun candidat embauché pour le moment.
    </div>
<?php else: ?>
    <?php
    // --- Statistiques ---
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

    <!-- ==================== STATISTIQUES ==================== -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <p class="text-muted small mb-1">Total embauches</p>
                <h3 class="mb-0"><?= $totalEmbauches ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <p class="text-muted small mb-1">CDI</p>
                <h3 class="mb-0 text-success"><?= $nbCDI ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <p class="text-muted small mb-1">Contrats d'essai</p>
                <h3 class="mb-0 text-info"><?= $nbEssai ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <p class="text-muted small mb-1">Masse salariale annuelle</p>
                <h3 class="mb-0"><?= number_format($masseSalariale, 0, ',', ' ') ?> Ar</h3>
            </div>
        </div>
    </div>

    <!-- ==================== LISTE ==================== -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Candidat</th>
                        <th>Poste</th>
                        <th>Contrat</th>
                        <th>Salaire</th>
                        <th>Dates</th>
                        <th style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($embauches as $e): ?>
                    <tr>
                        <td class="text-muted"><?= $e['id'] ?></td>
                        <td>
                            <div class="fw-bold"><?= esc($e['candidat_prenom'] . ' ' . $e['candidat_nom']) ?></div>
                            <small class="text-muted d-block">📧 <?= esc($e['candidat_email']) ?></small>
                            <small class="text-muted d-block">📞 <?= esc($e['candidat_telephone']) ?></small>
                        </td>
                        <td>
                            <div><?= esc($e['poste_libelle']) ?></div>
                            <small class="text-muted"><?= esc($e['annonce_titre']) ?></small>
                        </td>
                        <td>
                            <?php if (!empty($e['contrat_type'])): ?>
                                <span class="badge bg-<?= $e['contrat_type'] === 'CDI' ? 'success' : 'info' ?>">
                                    <?= esc($e['contrat_type']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($e['contrat_salaire'])): ?>
                                <strong><?= number_format($e['contrat_salaire'], 0, ',', ' ') ?> Ar</strong>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($e['contrat_date_debut'])): ?>
                                <small class="d-block">
                                    <span class="text-muted">Début :</span> <?= esc($e['contrat_date_debut']) ?>
                                </small>
                                <?php if (!empty($e['contrat_date_fin'])): ?>
                                    <small class="d-block">
                                        <span class="text-muted">Fin :</span> <?= esc($e['contrat_date_fin']) ?>
                                    </small>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/candidature/<?= $e['id'] ?>" class="btn btn-sm btn-outline-primary">
                                Voir la fiche
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>