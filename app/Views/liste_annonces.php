<!DOCTYPE html>
<html>
<head>
    <title>Liste des annonces</title>
</head>
<body>

<?= view('navbar') ?>
<div class="contenu">
    <h1>Annonces de recrutement</h1>

    <?php if (empty($annonces)): ?>

        <p>Aucune annonce disponible.</p>

    <?php else: ?>

        <?php foreach ($annonces as $annonce): ?>

            <hr>

            <h2><?= esc($annonce['designation']) ?></h2>

            <p>
                <strong>Poste :</strong>
                <?= esc($annonce['poste']) ?>
            </p>

            <p>
                <strong>Date de création :</strong>
                <?= esc($annonce['daty']) ?>
            </p>

            <p>
                <strong>Description du besoin :</strong><br>
                <?= nl2br(esc($annonce['description_besoin'])) ?>
            </p>

        <?php endforeach; ?>

    <?php endif; ?>

    <br>

    <a href="<?= site_url('accueil') ?>">Retour à l'accueil</a>
</div>
</body>
</html>