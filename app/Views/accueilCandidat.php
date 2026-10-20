<!DOCTYPE html>
<html>
<head>
    <title>Espace candidat</title>
</head>
<body>

    <h1>Espace candidat</h1>

    <p>
        Bienvenue <?= esc($candidat['prenom']) ?>
        <?= esc($candidat['nom']) ?> !
    </p>

    <a href="<?= site_url('annonces/liste') ?>">
        Consulter les annonces et postuler
    </a>

    <br><br>

    <a href="<?= site_url('candidat/logout') ?>">
        Se déconnecter
    </a>

</body>
</html>