<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center"><div class="col-md-6">
    <div class="card p-4">
        <h3 class="mb-4">Nouveau besoin</h3>
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
                <label class="form-label">Nombre de postes</label>
                <input type="number" name="nombre_postes" min="1" value="<?= esc(old('nombre_postes', 1)) ?>" class="form-control" required>
            </div>
            <p class="text-muted small">Le budget engagé sera calculé (salaire max × nombre).</p>
            <button type="submit" class="btn btn-primary">Créer le besoin</button>
            <a href="/besoin" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div></div>
<?= $this->endSection() ?>