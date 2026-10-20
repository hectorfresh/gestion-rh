<!DOCTYPE html>
<html>
<head>
    <title>Espace candidat</title>
</head>
<body>

    <?= view('navbarCandidat') ?>

    <div class="contenu-candidat">

        <h1>Accueil candidat</h1>

        <p>
            Bienvenue <?= esc($candidat['prenom']) ?>
            <?= esc($candidat['nom']) ?> !
        </p>

        <p>
            Consultez les annonces de recrutement et suivez vos candidatures.
        </p>

    </div>

</body>
</html>