<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Créer une annonce — Besoin #<?= $besoin['id'] ?></h2>

<p><strong>Poste :</strong> <?= esc($besoin['poste_libelle']) ?></p>
<p><strong>Nombre de postes à pourvoir :</strong> <?= $besoin['nombre_postes'] ?></p>
<p><strong>Fourchette salariale :</strong>
    <?= number_format($poste->salaire_min, 0, ',', ' ') ?>
    à <?= number_format($poste->salaire_max, 0, ',', ' ') ?> Ar
</p>

<form method="post" action="/annonce/create">
    <input type="hidden" name="besoin_id" value="<?= $besoin['id'] ?>">

    <p>
        <label>Titre de l'annonce :</label><br>
        <input type="text" name="titre" size="60"
               value="<?= esc(old('titre', 'Recrutement ' . $besoin['poste_libelle'])) ?>"
               required maxlength="200">
    </p>
    <p>
        <label>Description :</label><br>
        <textarea name="description" rows="8" cols="60" required><?= esc(old('description')) ?></textarea>
    </p>

    <button type="submit">Publier l'annonce</button>
    <a href="/annonce">Annuler</a>
</form>

<?= $this->endSection() ?>