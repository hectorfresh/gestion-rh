<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Besoins / Création</div>
        <h1 class="page-title">Nouveau besoin</h1>
        <p class="page-description">Exprimez une demande de personnel pour votre service.</p>
    </div>
</header>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2 class="content-card-title">Formulaire de création</h2>
                    <div class="content-card-description">Renseignez les informations du besoin.</div>
                </div>
            </div>
            <div class="content-card-body">
                <form method="post" action="/besoin/create">
                    <div class="mb-3">
                        <label class="form-label">Poste</label>
                        <select name="poste_id" class="form-select" required>
                            <option value="">-- Choisir un poste --</option>
                            <?php foreach ($postes as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= old('poste_id') == $p['id'] ? 'selected' : '' ?>>
                                    <?= esc($p['libelle']) ?> — <?= number_format($p['salaire_min'], 0, ',', ' ') ?>
                                    à <?= number_format($p['salaire_max'], 0, ',', ' ') ?> Ar
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Département</label>
                        <select name="departement_id" class="form-select">
                            <option value="">-- Choisir un département --</option>
                            <?php foreach ($departements as $departement): ?>
                                <option value="<?= $departement['id'] ?>" <?= old('departement_id') == $departement['id'] ? 'selected' : '' ?>>
                                    <?= esc($departement['libelle']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre de postes</label>
                        <input type="number" name="nombre_postes" min="1" value="<?= esc(old('nombre_postes', 1)) ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date souhaitée</label>
                        <input type="date" name="date_souhaitee" value="<?= esc(old('date_souhaitee')) ?>" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motif du recrutement</label>
                        <textarea name="motif" maxlength="255" class="form-control" rows="2"><?= esc(old('motif')) ?></textarea>
                    </div>
                    <p class="text-muted small">Le budget engagé sera calculé (salaire max × nombre).</p>
                    <div class="btn-list">
                        <button type="submit" class="btn btn-primary">Créer le besoin</button>
                        <a href="/besoin" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
<?= $this->endSection() ?>