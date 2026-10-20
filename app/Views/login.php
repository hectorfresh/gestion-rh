<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h1>Connexion</h1>

    <?php if (session()->getFlashdata('erreur')): ?>
        <p><?= session()->getFlashdata('erreur') ?></p>
    <?php endif; ?>

    <form action="<?= site_url('connexion') ?>" method="post">

        <?= csrf_field() ?>

        <label>Email :</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Mot de passe :</label>
        <input type="password" name="mdp" required>

        <br><br>

        <button type="submit">Se connecter</button>

    </form>

</body>
</html>