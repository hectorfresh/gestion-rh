<!DOCTYPE html>
<html>
<head>
    <title>Accueil</title>
</head>
<body>

    <h1>Page d'accueil</h1>

    <p>
        Bienvenue <?= esc($user['nom']) ?> !
    </p>
   <a href="<?= site_url('logout') ?>">Se déconnecter</a>
</body>
</html>