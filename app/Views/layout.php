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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Gestion des besoins' ?></title>
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <style>
        body { background: #f4f6f9; }

        /* --- Sidebar interne --- */
        .sidebar {
            width: 250px; min-height: 100vh;
            background: #1e293b; color: #cbd5e1;
            display: flex; flex-direction: column;
            position: sticky; top: 0;
        }
        .sidebar .brand {
            padding: 20px 16px; font-size: 18px; font-weight: 700;
            color: white; border-bottom: 1px solid #334155;
        }
        .sidebar .nav-link {
            color: #cbd5e1; padding: 10px 16px; border-radius: 6px;
            margin: 2px 8px; font-size: 14px;
        }
        .sidebar .nav-link:hover { background: #334155; color: white; }
        .sidebar .nav-link.active { background: #3b82f6; color: white; }
        .sidebar .user-box {
            margin-top: auto; padding: 16px;
            border-top: 1px solid #334155; font-size: 13px;
        }

        /* --- Badges de statut --- */
        .badge-statut { font-size: 12px; padding: 5px 10px; border-radius: 12px; }
        .statut-en_attente, .statut-en_attente_rh, .statut-en_attente_dg { background: #6b7280 !important; }
        .statut-essai_propose, .statut-essai_effectue { background: #3b82f6 !important; }
        .statut-essai_refuse, .statut-refuse_rh, .statut-refuse_finance, .statut-refuse_dg { background: #dc2626 !important; }
        .statut-conditions_proposees, .statut-salaire_en_negociation { background: #f59e0b !important; color: #1f2937 !important; }
        .statut-conditions_acceptees, .statut-valide, .statut-candidat_embauche { background: #16a34a !important; }
        .statut-conditions_refusees, .statut-candidat_non_retenu { background: #4b5563 !important; }
        .statut-recrutement_ouvert { background: #8b5cf6 !important; }

        /* --- Card --- */
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    </style>
</head>
<body>

<?php if ($estInterne): ?>
    <div class="d-flex">
        <aside class="sidebar">
            <div class="brand">📋 Gestion RH</div>
            <nav class="nav flex-column py-2">
                <a href="/dashboard" class="nav-link <?= $uri === 'dashboard' ? 'active' : '' ?>">🏠 Dashboard</a>
                <?php if ($role === 'manager' || $role === 'dg'): ?>
                    <a href="/besoin" class="nav-link <?= str_starts_with($uri, 'besoin') ? 'active' : '' ?>">📝 Mes besoins</a>
                <?php endif; ?>
                <?php if ($role === 'rh' || $role === 'dg'): ?>
                    <a href="/validation" class="nav-link <?= str_starts_with($uri, 'validation') ? 'active' : '' ?>">✅ Validation RH</a>
                    <a href="/annonce" class="nav-link <?= str_starts_with($uri, 'annonce') && !str_contains($uri, 'annonces') ? 'active' : '' ?>">📢 Annonces</a>
                    <a href="/candidature" class="nav-link <?= str_starts_with($uri, 'candidature') && !str_contains($uri, 'embauches') ? 'active' : '' ?>">👥 Candidatures</a>
                    <a href="/candidature/embauches" class="nav-link <?= str_contains($uri, 'embauches') ? 'active' : '' ?>">🏆 Embauches</a>
                    <a href="/emails" class="nav-link <?= str_starts_with($uri, 'emails') ? 'active' : '' ?>">📬 Boîte d'envoi</a>
                <?php endif; ?>
                <?php if ($role === 'dg'): ?>
                    <a href="/dg" class="nav-link <?= str_starts_with($uri, 'dg') ? 'active' : '' ?>">👔 Validation DG</a>
                <?php endif; ?>
                <a href="/annonces" target="_blank" class="nav-link">🌐 Site public</a>
            </nav>
            <div class="user-box">
                <div class="fw-bold text-white"><?= esc(session()->get('nom')) ?></div>
                <div class="text-secondary">Rôle : <?= esc($role) ?></div>
                <a href="/logout" class="text-danger small">Déconnexion</a>
            </div>
        </aside>

        <main class="flex-grow-1 p-4">
            <div class="container-fluid">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

<?php elseif ($estCandidat): ?>
    <nav class="navbar navbar-dark bg-dark px-3">
        <span class="navbar-brand">📋 Espace Candidat</span>
        <div class="d-flex text-white align-items-center gap-3">
            <span class="small"><?= esc(session()->get('candidat_prenom')) ?> <?= esc(session()->get('candidat_nom')) ?></span>
            <a href="/candidat/dashboard" class="text-decoration-none text-light">Mon espace</a>
            <a href="/annonces" class="text-decoration-none text-light">Annonces</a>
            <a href="/candidat/logout" class="text-decoration-none text-danger">Déconnexion</a>
        </div>
    </nav>
    <main class="container py-4" style="max-width: 960px;">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </main>

<?php else: ?>
    <nav class="navbar navbar-dark bg-dark px-3">
        <span class="navbar-brand">📋 Recrutement</span>
        <div class="d-flex gap-3">
            <a href="/annonces" class="text-decoration-none text-light">Annonces</a>
            <a href="/candidat/login" class="text-decoration-none text-light">Connexion</a>
            <a href="/candidat/register" class="text-decoration-none text-light">Inscription</a>
            <a href="/login" class="text-decoration-none text-warning">Espace interne</a>
        </div>
    </nav>
    <main class="container py-4" style="max-width: 960px;">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </main>
<?php endif; ?>

<script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>