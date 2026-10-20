<!DOCTYPE html>
<html>
<head>
    <title>Connexion candidat</title>
</head>
<body>

    <h1>Connexion candidat</h1>

    <?php if (session()->getFlashdata('erreur')): ?>
        <p><?= esc(session()->getFlashdata('erreur')) ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')): ?>
        <p><?= esc(session()->getFlashdata('message')) ?></p>
    <?php endif; ?>

    <form action="<?= site_url('candidat/connexion') ?>" method="post">

        <?= csrf_field() ?>

        <label>Email :</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Mot de passe :</label>
        <input type="password" name="mdp" required>

        <br><br>

        <button type="submit">Se connecter</button>

    </form>

    <p>
        Pas encore de compte ?
        <a href="<?= site_url('candidat/inscription') ?>">
            Créer un compte
        </a>
    </p>

    <p>
        <a href="<?= site_url('annonces/liste') ?>">
            Consulter les annonces
        </a>
    </p>

</body>
</html>