<!DOCTYPE html>
<html>
<head>
    <title>Accueil</title>
</head>
<body>

    <?= view('navbar') ?>

    <div class="contenu">

        <h1>Page d'accueil</h1>

        <p>
            Bienvenue <?= esc($user['nom']) ?> !
        </p>

        <p>Bienvenue dans l'application de gestion des ressources humaines.</p>

    </div>

</body>
</html>