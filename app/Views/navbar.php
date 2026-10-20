<?php $user = session()->get('utilisateur'); ?>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        display: flex;
    }

    .sidebar {
        width: 200px;
        min-height: 100vh;
        background: #eeeeee;
        padding: 20px;
        box-sizing: border-box;
    }

    .sidebar a {
        display: block;
        padding: 10px;
        color: black;
        text-decoration: none;
    }

    .sidebar a:hover {
        background: #dddddd;
    }

    .contenu {
        padding: 25px;
        flex: 1;
    }
</style>

<div class="sidebar">

    <h3>Gestion RH</h3>

    <p>
        <?= esc($user['nom']) ?>
    </p>

    <a href="<?= site_url('accueil') ?>">Accueil</a>

    <?php if ($user['role_id'] == 1 || $user['role_id'] == 3): ?>
        <a href="<?= site_url('besoin') ?>">Créer un besoin</a>
    <?php endif; ?>

    <?php if ($user['role_id'] == 2): ?>
        <a href="<?= site_url('rh') ?>">Validation RH</a>
        <a href="<?= site_url('annonces') ?>">Créer une annonce</a>
        <a href="<?= site_url('rh/candidatures') ?>">Suivi des candidatures</a>
    <?php endif; ?>

    <?php if ($user['role_id'] == 1): ?>
        <a href="<?= site_url('dg') ?>">Validation DG</a>
    <?php endif; ?>

    <a href="<?= site_url('annonces/liste') ?>">Consulter les annonces</a>

    <a href="<?= site_url('logout') ?>">Déconnexion</a>

</div>