<?php
$estInterne  = session()->get('user_id') !== null;
$estCandidat = session()->get('candidat_id') !== null;
$role        = session()->get('role');
$uri         = uri_string();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Gestion des besoins' ?></title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f5f5f5; }

        .app { display: flex; min-height: 100vh; }
        .sidebar {
            width: 220px; background: #2c3e50; color: white; padding: 15px;
            display: flex; flex-direction: column;
        }
        .sidebar h1 { font-size: 16px; margin: 0 0 20px; color: white; }
        .sidebar nav a {
            display: block; color: #ddd; padding: 8px 10px;
            text-decoration: none; border-radius: 4px; margin-bottom: 3px; font-size: 14px;
        }
        .sidebar nav a:hover { background: #34495e; color: white; }
        .sidebar nav a.active { background: #3498db; color: white; }
        .sidebar .user-info {
            margin-top: auto; font-size: 12px; color: #bbb;
            border-top: 1px solid #444; padding-top: 12px; line-height: 1.6;
        }
        .sidebar .user-info a { color: #e74c3c; text-decoration: none; }

        .content { flex: 1; padding: 20px 25px; }

        .topbar {
            background: #2c3e50; color: white; padding: 10px 20px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .topbar a { color: #ddd; text-decoration: none; margin-left: 15px; }
        .topbar a:hover { color: white; }

        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-error   { background: #fde; color: #c00; }
        .alert-success { background: #efe; color: #060; }

        table { border-collapse: collapse; width: 100%; background: white; margin-bottom: 15px; }
        table th, table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        table th { background: #eee; }

        h2 { margin-top: 0; }
        button { padding: 6px 12px; cursor: pointer; }
        input[type="text"], input[type="email"], input[type="password"],
        input[type="number"], input[type="date"], input[type="time"],
        select, textarea {
            padding: 6px; border: 1px solid #ccc; border-radius: 3px;
            font-family: Arial, sans-serif;
        }

        .login-container {
            min-height: 100vh; display: flex; align-items: center;
            justify-content: center; padding: 20px;
        }
        .login-container .content {
            max-width: 420px; width: 100%; background: white;
            padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .page-publique { max-width: 900px; margin: 0 auto; padding: 20px; }
        .badge {
            display: inline-block; padding: 2px 8px; border-radius: 3px;
            font-size: 12px; background: #ddd; color: #333;
        }
        /* --- Badges de statut --- */
.badge {
    display: inline-block; padding: 3px 10px; border-radius: 12px;
    font-size: 12px; font-weight: bold; color: white;
}

.badge-en_attente            { background: #95a5a6; }
.badge-essai_propose         { background: #3498db; }
.badge-essai_effectue        { background: #9b59b6; }
.badge-essai_refuse          { background: #e74c3c; }
.badge-conditions_proposees  { background: #f39c12; }
.badge-salaire_en_negociation{ background: #e67e22; }
.badge-conditions_acceptees  { background: #27ae60; }
.badge-conditions_refusees   { background: #e74c3c; }
.badge-candidat_embauche     { background: #16a085; }
.badge-candidat_non_retenu   { background: #7f8c8d; }

/* --- Cartes --- */
.card {
    background: white; padding: 15px; margin-bottom: 12px;
    border-radius: 6px; border: 1px solid #ddd;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

/* --- Boutons --- */
.btn {
    display: inline-block; padding: 8px 16px; border: none;
    border-radius: 4px; cursor: pointer; text-decoration: none;
    color: white; font-size: 14px;
}
.btn-primary { background: #3498db; }
.btn-success { background: #27ae60; }
.btn-danger  { background: #e74c3c; }
.btn-warning { background: #f39c12; }
.btn:hover   { opacity: 0.9; }
    </style>
</head>
<body>

<?php if ($estInterne): ?>
    <div class="app">
        <aside class="sidebar">
            <h1>📋 Gestion des besoins</h1>
            <nav>
                <a href="/dashboard" class="<?= $uri === 'dashboard' ? 'active' : '' ?>">🏠 Dashboard</a>
                <?php if ($role === 'manager' || $role === 'dg'): ?>
                    <a href="/besoin" class="<?= str_starts_with($uri, 'besoin') ? 'active' : '' ?>">📝 Mes besoins</a>
                <?php endif; ?>
                <?php if ($role === 'rh' || $role === 'dg'): ?>
                    <a href="/validation" class="<?= str_starts_with($uri, 'validation') ? 'active' : '' ?>">✅ Validation RH</a>
                    <a href="/annonce" class="<?= str_starts_with($uri, 'annonce') ? 'active' : '' ?>">📢 Annonces</a>
                    <a href="/candidature" class="<?= str_starts_with($uri, 'candidature') && !str_contains($uri, 'embauches') ? 'active' : '' ?>">👥 Candidatures</a>
                    <a href="/candidature/embauches" class="<?= str_contains($uri, 'candidature/embauches') ? 'active' : '' ?>">🏆 Candidats embauchés</a>                    <a href="/emails" class="<?= str_starts_with($uri, 'emails') ? 'active' : '' ?>">📬 Boîte d'envoi</a>
                <?php endif; ?>
                <?php if ($role === 'dg'): ?>
                    <a href="/dg" class="<?= str_starts_with($uri, 'dg') ? 'active' : '' ?>">👔 Validation DG</a>
                <?php endif; ?>
                <a href="/annonces" target="_blank">🌐 Site public</a>
            </nav>
            <div class="user-info">
                <strong><?= esc(session()->get('nom')) ?></strong><br>
                Rôle : <?= esc($role) ?><br>
                <a href="/logout">Déconnexion</a>
            </div>
        </aside>
        <main class="content">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>
            <?= $this->renderSection('content') ?>
        </main>
    </div>

<?php elseif ($estCandidat): ?>
    <div class="topbar">
        <strong>📋 Espace Candidat</strong>
        <div>
            <span><?= esc(session()->get('candidat_prenom')) ?> <?= esc(session()->get('candidat_nom')) ?></span>
            <a href="/candidat/dashboard">Mon espace</a>
            <a href="/annonces">Annonces</a>
            <a href="/candidat/logout">Déconnexion</a>
        </div>
    </div>
    <main class="page-publique">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </main>

<?php else: ?>
    <div class="topbar">
        <strong>📋 Site de recrutement</strong>
        <div>
            <a href="/annonces">Annonces</a>
            <a href="/candidat/login">Connexion candidat</a>
            <a href="/candidat/register">Inscription</a>
            <a href="/login">Espace interne</a>
        </div>
    </div>
    <main class="page-publique">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </main>
<?php endif; ?>

</body>
</html>