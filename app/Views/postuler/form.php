<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Postuler — <?= esc($annonce['titre']) ?></h2>

<div class="card">
    <h3 style="margin-top:0;">Vos informations</h3>
    <p><strong>Nom :</strong> <?= esc($candidat['nom']) ?> <?= esc($candidat['prenom']) ?></p>
    <p><strong>Email :</strong> <?= esc($candidat['email']) ?></p>
    <p><strong>Téléphone :</strong> <?= esc($candidat['telephone']) ?></p>
</div>

<form method="post" action="/postuler/<?= $annonce['id'] ?>">
    <div class="card">
        <h3 style="margin-top:0;">📝 Mon CV</h3>

        <?php foreach ($champs as $c): ?>
            <div style="padding:12px 0; border-bottom:1px solid #eee;">
                <label style="display:block; font-weight:bold; margin-bottom:6px;">
                    <?= esc($c['libelle']) ?>
                    <?php if ($c['type'] === 'liste'): ?>
                        <span style="color:#e74c3c;">*</span>
                    <?php endif; ?>
                </label>

                <?php if ($c['type'] === 'liste'): ?>
                    <select name="champ_<?= $c['id'] ?>" required style="width:100%; padding:8px;">
                        <option value="">-- Choisir --</option>
                        <?php foreach ($c['options'] as $o): ?>
                            <option value="<?= $o['id'] ?>"
                                <?= old('champ_' . $c['id']) == $o['id'] ? 'selected' : '' ?>>
                                <?= esc($o['valeur']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <textarea name="champ_<?= $c['id'] ?>" rows="4" style="width:100%;"><?= esc(old('champ_' . $c['id'])) ?></textarea>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <p>
        <button type="submit" class="btn btn-success">✓ Envoyer ma candidature</button>
        <a href="/annonces/<?= $annonce['id'] ?>" class="btn btn-primary">Annuler</a>
    </p>
</form>

<?= $this->endSection() ?>