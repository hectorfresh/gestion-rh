<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Recrutement / Annonce</div>
        <h1 class="page-title">Créer une annonce — Besoin #<?= $besoin['id'] ?></h1>
        <p class="page-description">Publiez une annonce de recrutement pour ce besoin validé.</p>
    </div>
</header>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2 class="content-card-title">Informations de l'annonce</h2>
                    <div class="content-card-description">
                        <?= esc($besoin['poste_libelle']) ?> — <?= $nbAnnonces ?? 0 ?> / <?= $besoin['nombre_postes'] ?> annonce(s) créée(s)
                        <br>Fourchette : <?= number_format($poste->salaire_min, 0, ',', ' ') ?> à <?= number_format($poste->salaire_max, 0, ',', ' ') ?> Ar
                    </div>
                </div>
            </div>
            <div class="content-card-body">
                <form method="post" action="/annonce/create">
                    <input type="hidden" name="besoin_id" value="<?= $besoin['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="titre" class="form-control"
                               value="<?= esc(old('titre', 'Recrutement ' . $besoin['poste_libelle'])) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="5" class="form-control" required><?= esc(old('description')) ?></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Salaire proposé (Ar)</label>
                            <input type="number" name="salaire_propose" step="0.01"
                                   min="<?= $poste->salaire_min ?>" max="<?= $poste->salaire_max ?>"
                                   value="<?= esc(old('salaire_propose', $poste->salaire_min)) ?>"
                                   class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Heure de début</label>
                            <input type="time" name="heure_debut" class="form-control"
                                   value="<?= esc(old('heure_debut', '08:00')) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Autres conditions (optionnel)</label>
                        <textarea name="autres_conditions" rows="2" class="form-control"><?= esc(old('autres_conditions')) ?></textarea>
                    </div>
                    <div class="btn-list">
                        <button type="submit" class="btn btn-primary">Publier</button>
                        <a href="/annonce" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
<?= $this->endSection() ?>