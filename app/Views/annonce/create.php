<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Créer une annonce — Besoin #<?= $besoin['id'] ?></h2>

<p><strong>Poste :</strong> <?= esc($besoin['poste_libelle']) ?></p>
<p><strong>Nombre de postes à pourvoir :</strong> <?= $besoin['nombre_postes'] ?></p>
<p><strong>Annonces déjà créées :</strong> <?= $nbAnnonces ?? 0 ?> / <?= $besoin['nombre_postes'] ?></p>
<p><strong>Fourchette salariale du poste :</strong>
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
        <textarea name="description" rows="6" cols="60" required><?= esc(old('description')) ?></textarea>
    </p>

    <p>
        <label>Salaire proposé (Ar) :</label><br>
        <input type="number" name="salaire_propose" step="0.01"
               min="<?= $poste->salaire_min ?>" max="<?= $poste->salaire_max ?>"
               value="<?= esc(old('salaire_propose', $poste->salaire_min)) ?>" required>
        <br><small>Doit être entre <?= number_format($poste->salaire_min, 0, ',', ' ') ?>
        et <?= number_format($poste->salaire_max, 0, ',', ' ') ?> Ar</small>
    </p>

    <p>
        <label>Heure de début du travail :</label><br>
        <input type="time" name="heure_debut"
               value="<?= esc(old('heure_debut', '08:00')) ?>" required>
    </p>

    <p>
        <label>Autres conditions (optionnel) :</label><br>
        <textarea name="autres_conditions" rows="3" cols="60"><?= esc(old('autres_conditions')) ?></textarea>
    </p>

    <button type="submit">Publier l'annonce</button>
    <a href="/annonce">Annuler</a>
</form>

<?= $this->endSection() ?>