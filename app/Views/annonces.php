<!DOCTYPE html>
<html>
<head>
    <title>Créer une annonce</title>
</head>
<body>

<?= view('navbar') ?>
<div class="contenu">

    <h1>Créer une annonce de recrutement</h1>

    <?php if (session()->getFlashdata('message')): ?>
        <p><?= esc(session()->getFlashdata('message')) ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('erreur')): ?>
        <p><?= esc(session()->getFlashdata('erreur')) ?></p>
    <?php endif; ?>

    <form action="<?= site_url('annonces/creer') ?>" method="post">

        <?= csrf_field() ?>

        <label>Besoin :</label>

        <select name="besoin_id" required>
            <option value="">Choisir un besoin</option>

            <?php foreach ($besoins as $besoin): ?>
                <option value="<?= $besoin['id'] ?>">
                    <?= esc($besoin['poste']) ?>
                    - <?= esc($besoin['descriptions']) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <br><br>

        <label>Titre de l'annonce :</label><br>

        <input type="text" name="designation" required>

        <br><br>

        <label>Description de l'annonce :</label><br>

        <textarea name="description" rows="6" cols="50" required></textarea>

        <br><br>

        <button type="submit">Créer l'annonce</button>

    </form>

    <br>

    <a href="<?= site_url('accueil') ?>">Accueil</a>
</div>
</body>
</html>