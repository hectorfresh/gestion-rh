<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="page-pretitle">Vue d'ensemble</div>
<h1 class="page-title">Tableau de bord</h1>
<p class="page-description">
    Bonjour <?= esc($nom) ?> 👋 — bienvenue dans votre espace de gestion.
</p>

<div class="row row-cards g-3 mt-2">
    <?php if ($role === 'manager'): ?>
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-label">Mes besoins</div>
                <div class="stat-value">📝</div>
                <div class="stat-note">Créer et suivre vos demandes de personnel</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-8">
            <section class="content-card">
                <div class="content-card-header">
                    <div>
                        <h2 class="content-card-title">Accès rapide</h2>
                        <div class="content-card-description">Gérer vos besoins de recrutement.</div>
                    </div>
                </div>
                <div class="content-card-body">
                    <a href="/besoin" class="btn btn-primary">📝 Mes besoins</a>
                    <a href="/besoin/create" class="btn btn-outline-primary ms-2">+ Nouveau besoin</a>
                </div>
            </section>
        </div>
    <?php elseif ($role === 'rh'): ?>
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-label">Validation RH</div>
                <div class="stat-value">✅</div>
                <div class="stat-note">Traiter les besoins en attente</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-label">Annonces</div>
                <div class="stat-value">📢</div>
                <div class="stat-note">Publier les annonces de recrutement</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-label">Candidatures</div>
                <div class="stat-value">👥</div>
                <div class="stat-note">Suivre les candidatures reçues</div>
            </div>
        </div>
        <div class="col-12">
            <section class="content-card">
                <div class="content-card-header">
                    <div>
                        <h2 class="content-card-title">Accès rapide</h2>
                        <div class="content-card-description">Raccourcis vers les modules RH.</div>
                    </div>
                </div>
                <div class="content-card-body d-flex flex-wrap gap-2">
                    <a href="/validation" class="btn btn-primary">✅ Validation RH</a>
                    <a href="/annonce" class="btn btn-primary">📢 Annonces</a>
                    <a href="/candidature" class="btn btn-primary">👥 Candidatures</a>
                    <a href="/candidature/embauches" class="btn btn-outline-primary">🏆 Embauches</a>
                    <a href="/emails" class="btn btn-outline-primary">📬 Boîte d'envoi</a>
                </div>
            </section>
        </div>
    <?php elseif ($role === 'dg'): ?>
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-label">Mes besoins</div>
                <div class="stat-value">📝</div>
                <div class="stat-note">Créer un besoin (validé d'office)</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-label">Validation RH</div>
                <div class="stat-value">✅</div>
                <div class="stat-note">Traiter les besoins en attente</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-label">Validation DG</div>
                <div class="stat-value">👔</div>
                <div class="stat-note">Décision finale sur les besoins</div>
            </div>
        </div>
        <div class="col-12">
            <section class="content-card">
                <div class="content-card-header">
                    <div>
                        <h2 class="content-card-title">Accès rapide</h2>
                        <div class="content-card-description">Raccourcis direction.</div>
                    </div>
                </div>
                <div class="content-card-body d-flex flex-wrap gap-2">
                    <a href="/besoin" class="btn btn-primary">📝 Mes besoins</a>
                    <a href="/validation" class="btn btn-primary">✅ Validation RH</a>
                    <a href="/dg" class="btn btn-primary">👔 Validation DG</a>
                </div>
            </section>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>