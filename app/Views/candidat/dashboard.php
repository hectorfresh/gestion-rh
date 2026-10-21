<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Mes candidatures</h2>
<p><a href="/annonces" class="btn btn-primary">🌐 Voir les annonces disponibles</a></p>

<?php if (empty($candidatures)): ?>
    <p><em>Aucune candidature pour l'instant. Parcourez les annonces et postulez !</em></p>
<?php else: ?>
    <?php foreach ($candidatures as $c): ?>
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h3 style="margin:0 0 4px;"><?= esc($c['annonce_titre']) ?></h3>
                    <p style="margin:0; color:#666;"><?= esc($c['poste_libelle']) ?> — postulé le <?= esc($c['date_candidature']) ?></p>
                </div>
                <div style="text-align:right;">
                    <span class="badge badge-<?= strtolower($c['statut_code']) ?>">
                        <?= esc($c['statut_libelle']) ?></span>
                    <br><br>
                    <a href="/candidat/candidature/<?= $c['id'] ?>" class="btn btn-primary">Voir le détail</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>