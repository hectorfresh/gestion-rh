<!DOCTYPE html>
<html>
<head>
    <title>Inscription candidat</title>
</head>
<body>

    <h1>Créer un compte candidat</h1>

    <?php if (session()->getFlashdata('erreur')): ?>
        <p><?= esc(session()->getFlashdata('erreur')) ?></p>
    <?php endif; ?>

    <form action="<?= site_url('candidat/enregistrer') ?>" method="post">

        <?= csrf_field() ?>

        <label>Nom :</label>
        <input type="text" name="nom" required>

        <br><br>

        <label>Prénom :</label>
        <input type="text" name="prenom" required>

        <br><br>

        <label>Genre :</label>

        <select name="gender_id" required>
            <option value="">Choisir</option>

            <?php foreach ($genres as $genre): ?>
                <option value="<?= $genre['id'] ?>">
                    <?= esc($genre['libelle']) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <br><br>

        <label>Email :</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Mot de passe :</label>
        <input type="password" name="mdp" required>

        <br><br>

        <button type="submit">S'inscrire</button>

    </form>

    <p>
        Déjà inscrit ?
        <a href="<?= site_url('candidat/login') ?>">Se connecter</a>
    </p>

</body>
</html>