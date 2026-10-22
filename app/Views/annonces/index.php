<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="text-center mb-4">
    <div class="page-eyebrow">Recrutement / Offres</div>
    <h1 class="page-title"><i class="bi bi-briefcase me-1"></i> Rejoignez notre équipe</h1>
    <p class="page-description">Découvrez nos offres d'emploi et postulez en quelques minutes.</p>
</div>

<?php if (empty($annonces)): ?>
    <div class="alert alert-light border text-center py-5">
        <h5 class="mb-2">Aucune offre disponible</h5>
        <p class="text-muted mb-0">Revenez bientôt, de nouvelles offres sont régulièrement publiées.</p>
    </div>
<?php else: ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">
            <strong><?= count($annonces) ?></strong> offre(s) disponible(s)
        </p>
        <div class="input-group" style="max-width: 320px;">
            <span class="input-group-text bg-white"><i class="bi bi-search me-1"></i></span>
            <input type="text" id="recherche" class="form-control" placeholder="Rechercher un poste...">
        </div>
    </div>

    <div class="row g-3" id="liste-annonces">
        <?php foreach ($annonces as $a): ?>
            <div class="col-md-6 annonce-item"
                 data-titre="<?= esc(strtolower($a['titre'] . ' ' . $a['poste_libelle'])) ?>">
                <section class="content-card h-100">
                    <div class="content-card-header">
                        <div>
                            <h2 class="content-card-title"><?= esc($a['titre']) ?></h2>
                            <div class="content-card-description">Publiée le <?= date('d/m/Y', strtotime($a['date_publication'])) ?></div>
                        </div>
                        <span class="badge bg-primary"><?= esc($a['poste_libelle']) ?></span>
                    </div>
                    <div class="content-card-body">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <small class="text-muted d-block"><i class="bi bi-cash me-1"></i> Salaire</small>
                                    <strong><?= number_format($a['salaire_propose'], 0, ',', ' ') ?> Ar</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <small class="text-muted d-block"><i class="bi bi-clock me-1"></i> Début</small>
                                    <strong><?= substr($a['heure_debut'], 0, 5) ?></strong>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($a['autres_conditions'])): ?>
                            <p class="small text-muted mb-3">
                                <strong>Conditions :</strong>
                                <?= esc(substr($a['autres_conditions'], 0, 100)) ?><?= strlen($a['autres_conditions']) > 100 ? '…' : '' ?>
                            </p>
                        <?php endif; ?>
                        <a href="/annonces/<?= $a['id'] ?>" class="btn btn-primary mt-auto align-self-start">
                            Voir l'offre →
                        </a>
                    </div>
                </section>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="aucun-resultat" class="alert alert-light border text-center py-4 d-none">
        Aucune offre ne correspond à votre recherche.
    </div>
<?php endif; ?>

<script>
    const inputRecherche = document.getElementById('recherche');
    if (inputRecherche) {
        inputRecherche.addEventListener('input', function () {
            const recherche = this.value.toLowerCase().trim();
            let visibles = 0;
            document.querySelectorAll('.annonce-item').forEach(function (item) {
                const match = item.dataset.titre.includes(recherche);
                item.style.display = match ? '' : 'none';
                if (match) visibles++;
            });
            const aucun = document.getElementById('aucun-resultat');
            if (aucun) aucun.classList.toggle('d-none', visibles > 0);
        });
    }
</script>
<?= $this->endSection() ?>