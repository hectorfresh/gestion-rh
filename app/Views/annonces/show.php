<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card p-4 mb-3">
            <span class="badge bg-primary mb-2 align-self-start"><?= esc($annonce['poste_libelle']) ?></span>
            <h1 class="mb-2"><?= esc($annonce['titre']) ?></h1>
            <p class="text-muted mb-4">
                Publiée le <?= date('d/m/Y', strtotime($annonce['date_publication'])) ?>
            </p>

            <h5 class="mb-2">📝 Description du poste</h5>
            <p class="mb-0"><?= nl2br(esc($annonce['description'])) ?></p>
        </div>

        <?php if (!empty($annonce['autres_conditions'])): ?>
            <div class="card p-4 mb-3">
                <h5 class="mb-2">📋 Autres conditions</h5>
                <p class="mb-0"><?= nl2br(esc($annonce['autres_conditions'])) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <div class="card p-4 mb-3" style="position: sticky; top: 20px;">
            <h5 class="mb-3">Informations clés</h5>

            <div class="mb-3">
                <p class="text-muted small mb-1">💰 Salaire proposé</p>
                <h4 class="mb-0 text-success"><?= number_format($annonce['salaire_propose'], 0, ',', ' ') ?> Ar</h4>
            </div>

            <div class="mb-3">
                <p class="text-muted small mb-1">🕐 Heure de début</p>
                <h5 class="mb-0"><?= substr($annonce['heure_debut'], 0, 5) ?></h5>
            </div>

            <?php if (!empty($annonce['salaire_min']) && !empty($annonce['salaire_max'])): ?>
                <div class="mb-3">
                    <p class="text-muted small mb-1">📊 Fourchette du poste</p>
                    <p class="mb-0 small">
                        <?= number_format($annonce['salaire_min'], 0, ',', ' ') ?>
                        à <?= number_format($annonce['salaire_max'], 0, ',', ' ') ?> Ar
                    </p>
                </div>
            <?php endif; ?>

            <hr>

            <?php if (session()->get('candidat_id')): ?>
                <a href="/postuler/<?= $annonce['id'] ?>" class="btn btn-success w-100 mb-2">
                    ➡️ Postuler à cette offre
                </a>
            <?php else: ?>
                <p class="text-muted small mb-2">Connectez-vous pour postuler.</p>
                <a href="/candidat/login" class="btn btn-primary w-100 mb-2">Se connecter</a>
                <a href="/candidat/register" class="btn btn-outline-primary w-100">Créer un compte</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<p class="mt-3"><a href="/annonces">← Retour aux annonces</a></p>

<?= $this->endSection() ?>