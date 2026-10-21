<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Nouveau besoin</h2>

<form method="post" action="/besoin/create">
    <p>
    <label>Poste :</label><br>
    <select name="poste_id" required>
        <option value="">-- Choisir un poste --</option>
        <?php foreach ($postes as $p): ?>

            <option value="<?= $p['id'] ?>" <?= old('poste_id') == $p['id'] ? 'selected' : '' ?>>
                <?= esc($p['libelle']) ?>
                — <?= number_format($p['salaire_min'], 0, ',', ' ') ?>
                à <?= number_format($p['salaire_max'], 0, ',', ' ') ?> Ar
            </option>




        <?php endforeach; ?>



    </select>
</p>
<p>
    <label>Nombre de postes :</label><br>
    <input type="number" name="nombre_postes" min="1"
           value="<?= esc(old('nombre_postes', 1)) ?>" required>
</p>
<p><em>Le coût total sera calculé automatiquement (coût unitaire × nombre).</em></p>
<button type="submit">Créer le besoin</button>
<a href="/besoin">Annuler</a>
</form>

<?= $this->endSection() ?>