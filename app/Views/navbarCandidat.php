<?php $candidat = session()->get('candidat'); ?>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        display: flex;
    }

    .sidebar-candidat {
        width: 200px;
        min-height: 100vh;
        background: #eeeeee;
        padding: 20px;
        box-sizing: border-box;
    }

    .sidebar-candidat a {
        display: block;
        padding: 10px;
        color: black;
        text-decoration: none;
    }

    .sidebar-candidat a:hover {
        background: #dddddd;
    }

    .contenu-candidat {
        padding: 25px;
        flex: 1;
    }
</style>

<div class="sidebar-candidat">

    <h3>Espace candidat</h3>

    <p>
        <?= esc($candidat['prenom']) ?>
        <?= esc($candidat['nom']) ?>
    </p>

    <a href="<?= site_url('candidat/accueil') ?>">Accueil</a>

    <a href="<?= site_url('annonces/liste') ?>">
        Consulter les annonces
    </a>

    <a href="<?= site_url('candidat/candidatures') ?>">
        Mes candidatures
    </a>

    <a href="<?= site_url('candidat/logout') ?>">
        Déconnexion
    </a>

</div>