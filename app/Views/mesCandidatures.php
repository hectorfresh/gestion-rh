<!DOCTYPE html>
<html>
<head>
    <title>Mes candidatures</title>
</head>
<body>

    <?= view('navbarCandidat') ?>

    <div class="contenu-candidat">

        <h1>Mes candidatures</h1>

        <?php if (empty($candidatures)): ?>

            <p>Vous n'avez envoyé aucune candidature.</p>

            <a href="<?= site_url('annonces/liste') ?>">
                Consulter les annonces
            </a>

        <?php else: ?>

            <?php foreach ($candidatures as $candidature): ?>

                <hr>

                <h2><?= esc($candidature['designation']) ?></h2>

                <p>
                    <strong>Date :</strong>
                    <?= esc($candidature['daty']) ?>
                </p>

                <p>
                    <strong>Statut :</strong>
                    <?= esc($candidature['statut'] ?? 'Non défini') ?>
                </p>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</body>
</html>