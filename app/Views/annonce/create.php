<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center"><div class="col-md-7">
    <div class="card p-4">
        <h3 class="mb-4">Créer une annonce — Besoin #<?= $besoin['id'] ?></h3>
        <p class="text-muted small">
            <?= esc($besoin['poste_libelle']) ?> — <?= $nbAnnonces ?? 0 ?> / <?= $besoin['nombre_postes'] ?> annonce(s) créée(s)
            <br>Fourchette : <?= number_format($poste->salaire_min, 0, ',', ' ') ?>
            à <?= number_format($poste->salaire_max, 0, ',', ' ') ?> Ar
        </p>
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
            <button type="submit" class="btn btn-primary">Publier</button>
            <a href="/annonce" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div></div>
<?= $this->endSection() ?>