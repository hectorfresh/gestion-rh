<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Candidature / Postuler</div>
        <h1 class="page-title">Postuler — <?= esc($annonce['titre']) ?></h1>
        <p class="page-description">Complétez votre candidature et votre CV.</p>
    </div>
</header>

<section class="content-card">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Vos informations</h2>
            <div class="content-card-description">Renseignez votre CV et vos compétences.</div>
        </div>
    </div>
    <div class="content-card-body">
        <form method="post" action="/postuler/<?= $annonce['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Diplôme</label>
                <input type="text" name="diplome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Années d'expérience</label>
                <input type="number" name="annees_experience" min="0" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Compétences</label>
                <textarea name="competences" rows="3" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Motivation</label>
                <textarea name="motivation" rows="4" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Prétentions salariales (Ar)</label>
                <input type="number" name="salaire_negocie" step="0.01" class="form-control">
            </div>
            <div class="btn-list">
                <button type="submit" class="btn btn-primary">Envoyer ma candidature</button>
                <a href="/annonces/<?= $annonce['id'] ?>" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</section>
<?= $this->endSection() ?>