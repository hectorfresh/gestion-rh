<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2 class="mb-4">Postuler — <?= esc($annonce['titre']) ?></h2>

<div class="card p-3 mb-3">
    <h5>Vos informations</h5>
    <p class="mb-1"><strong>Nom :</strong> <?= esc($candidat['nom']) ?> <?= esc($candidat['prenom']) ?></p>
    <p class="mb-1"><strong>Email :</strong> <?= esc($candidat['email']) ?></p>
    <p class="mb-0"><strong>Téléphone :</strong> <?= esc($candidat['telephone']) ?></p>
</div>

<form method="post" action="/postuler/<?= $annonce['id'] ?>">
    <div class="card p-3 mb-3">
        <h5>📝 Mon CV</h5>
        <p class="text-muted small">Répondez à chaque champ marqué d'un astérisque.</p>
        <?php foreach ($champs as $c): ?>
            <div class="mb-3">
                <label class="form-label">
                    <?= esc($c['libelle']) ?>
                    <?php if ($c['type'] === 'liste'): ?><span class="text-danger">*</span><?php endif; ?>
                </label>
                <?php if ($c['type'] === 'liste'): ?>
                    <select name="champ_<?= $c['id'] ?>" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach ($c['options'] as $o): ?>
                            <option value="<?= $o['id'] ?>" <?= old('champ_' . $c['id']) == $o['id'] ? 'selected' : '' ?>>
                                <?= esc($o['valeur']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <textarea name="champ_<?= $c['id'] ?>" rows="3" class="form-control"><?= esc(old('champ_' . $c['id'])) ?></textarea>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="submit" class="btn btn-success">✓ Envoyer ma candidature</button>
    <a href="/annonces/<?= $annonce['id'] ?>" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>