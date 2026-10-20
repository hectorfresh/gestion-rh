<!DOCTYPE html>
<html>
<head>
    <title>Liste des annonces</title>
</head>
<body>

<?php if (session()->has('candidat')): ?>

    <?= view('navbarCandidat') ?>

<?php elseif (session()->has('utilisateur')): ?>

    <?= view('navbar') ?>

<?php endif; ?>


<div class="contenu">
    <h1>Annonces de recrutement</h1>
        <?php if (session()->getFlashdata('message')): ?>
            <p><?= esc(session()->getFlashdata('message')) ?></p>
        <?php endif; ?>

        <?php if (session()->getFlashdata('erreur')): ?>
            <p><?= esc(session()->getFlashdata('erreur')) ?></p>
        <?php endif; ?>
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

            <?php if (session()->has('candidat')): ?>

                <a href="<?= site_url('candidat/cv/' . $annonce['id']) ?>">
                    Postuler
                </a>

            <?php else: ?>

                <a href="<?= site_url('candidat/login') ?>">
                    Connectez-vous pour postuler
                </a>

            <?php endif; ?>


        <?php endforeach; ?>

    <?php endif; ?>

    <br>

    <a href="<?= site_url('accueil') ?>">Retour à l'accueil</a>
</div>
</body>
</html>