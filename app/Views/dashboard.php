<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Bonjour <?= esc($nom) ?> 👋</h2>

<div class="row g-3">
    <?php if ($role === 'manager'): ?>
        <div class="col-md-6"><div class="card p-3">
            <h5>📝 Mes besoins</h5>
            <p class="text-muted mb-2">Créer et suivre vos demandes de personnel.</p>
            <a href="/besoin" class="btn btn-primary btn-sm">Y aller</a>
        </div></div>
    <?php elseif ($role === 'rh'): ?>
        <div class="col-md-4"><div class="card p-3">
            <h5>✅ Validation RH</h5>
            <p class="text-muted mb-2">Traiter les besoins en attente.</p>
            <a href="/validation" class="btn btn-primary btn-sm">Y aller</a>
        </div></div>
        <div class="col-md-4"><div class="card p-3">
            <h5>📢 Annonces</h5>
            <p class="text-muted mb-2">Publier les annonces de recrutement.</p>
            <a href="/annonce" class="btn btn-primary btn-sm">Y aller</a>
        </div></div>
        <div class="col-md-4"><div class="card p-3">
            <h5>👥 Candidatures</h5>
            <p class="text-muted mb-2">Suivre les candidatures reçues.</p>
            <a href="/candidature" class="btn btn-primary btn-sm">Y aller</a>
        </div></div>
    <?php elseif ($role === 'dg'): ?>
        <div class="col-md-4"><div class="card p-3">
            <h5>📝 Mes besoins</h5>
            <p class="text-muted mb-2">Créer un besoin (validé d'office).</p>
            <a href="/besoin" class="btn btn-primary btn-sm">Y aller</a>
        </div></div>
        <div class="col-md-4"><div class="card p-3">
            <h5>✅ Validation RH</h5>
            <p class="text-muted mb-2">Traiter les besoins en attente.</p>
            <a href="/validation" class="btn btn-primary btn-sm">Y aller</a>
        </div></div>
        <div class="col-md-4"><div class="card p-3">
            <h5>👔 Validation DG</h5>
            <p class="text-muted mb-2">Décision finale sur les besoins.</p>
            <a href="/dg" class="btn btn-primary btn-sm">Y aller</a>
        </div></div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>