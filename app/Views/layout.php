<?php
$estConnecte = session()->get('user_id') !== null;
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

        /* --- Structure générale --- */
        .app { display: flex; min-height: 100vh; }

        /* --- Sidebar --- */
        .sidebar {
            width: 220px;
            background: #2c3e50;
            color: white;
            padding: 15px;
            display: flex;
            flex-direction: column;
        }
        .sidebar h1 {
            font-size: 16px;
            margin: 0 0 20px;
            color: white;
        }
        .sidebar nav a {
            display: block;
            color: #ddd;
            padding: 8px 10px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 3px;
            font-size: 14px;
        }
        .sidebar nav a:hover { background: #34495e; color: white; }
        .sidebar nav a.active { background: #3498db; color: white; }
        .sidebar .user-info {
            margin-top: auto;
            font-size: 12px;
            color: #bbb;
            border-top: 1px solid #444;
            padding-top: 12px;
            line-height: 1.6;
        }
        .sidebar .user-info a { color: #e74c3c; text-decoration: none; }

        /* --- Contenu principal --- */
        .content { flex: 1; padding: 20px 25px; }

        /* --- Messages flash --- */
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-error   { background: #fde; color: #c00; }
        .alert-success { background: #efe; color: #060; }

        /* --- Tableaux --- */
        table { border-collapse: collapse; width: 100%; background: white; margin-bottom: 15px; }
        table th, table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        table th { background: #eee; }

        /* --- Formulaires / boutons --- */
        h2 { margin-top: 0; }
        button { padding: 6px 12px; cursor: pointer; }
        input[type="text"], input[type="email"], input[type="password"],
        input[type="number"], select, textarea {
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        /* --- Page login (centrée, sans sidebar) --- */
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container .content {
            max-width: 400px;
            width: 100%;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<?php if ($estConnecte): ?>
    <div class="app">
        <aside class="sidebar">
            <h1>📋 Gestion des besoins</h1>

            <nav>
                <a href="/dashboard"
                   class="<?= $uri === 'dashboard' ? 'active' : '' ?>">🏠 Dashboard</a>

                <?php if ($role === 'manager' || $role === 'dg'): ?>
                    <a href="/besoin"
                       class="<?= str_starts_with($uri, 'besoin') ? 'active' : '' ?>">📝 Mes besoins</a>
                <?php endif; ?>

                <?php if ($role === 'rh' || $role === 'dg'): ?>
                    <a href="/validation"
                       class="<?= str_starts_with($uri, 'validation') ? 'active' : '' ?>">✅ Validation RH</a>
                    <a href="/annonce"
                       class="<?= str_starts_with($uri, 'annonce') ? 'active' : '' ?>">📢 Annonces</a>
                <?php endif; ?>

                <?php if ($role === 'dg'): ?>
                    <a href="/dg"
                       class="<?= str_starts_with($uri, 'dg') ? 'active' : '' ?>">👔 Validation DG</a>
                <?php endif; ?>
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

<?php else: ?>
    <div class="login-container">
        <main class="content">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>
    </div>
<?php endif; ?>

</body>
</html>