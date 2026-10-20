<!DOCTYPE html>
<html>
<head>
    <title>Suivi des candidatures</title>
</head>
<body>

    <?= view('navbar') ?>

    <div class="contenu">

        <h1>Suivi des candidatures</h1>

        <?php if (empty($candidatures)): ?>

            <p>Aucune candidature reçue.</p>

        <?php else: ?>

            <?php foreach ($candidatures as $c): ?>

                <hr>

                <h3>
                    <?= esc($c['prenom']) ?>
                    <?= esc($c['nom']) ?>
                </h3>

                <p>
                    <strong>Email :</strong>
                    <?= esc($c['email']) ?>
                </p>

                <p>
                    <strong>Annonce :</strong>
                    <?= esc($c['designation']) ?>
                </p>

                <p>
                    <strong>Date :</strong>
                    <?= esc($c['daty']) ?>
                </p>

                <p>
                    <strong>Statut :</strong>
                    <?= esc($c['statut'] ?? 'Non défini') ?>
                </p>

                <a href="<?= site_url('rh/candidature/' . $c['id']) ?>">
                    Consulter le CV
                </a>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</body>
</html>