<?php
$estInterne  = session()->get('user_id') !== null;
$estCandidat = session()->get('candidat_id') !== null;
$role        = session()->get('role');
$uri         = uri_string();
$nom         = session()->get('nom');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Gestion des besoins' ?></title>

    <!-- Tabler Core -->
    <link rel="stylesheet" href="<?= base_url('css/tabler.min.css') ?>">
    <!-- Design system AdminFlow (extrait des templates) -->
    <link rel="stylesheet" href="<?= base_url('css/adminflow.css') ?>">
</head>
<body>

<?php if ($estInterne): ?>
    <div class="app-layout">

        <!-- NAVIGATION LATÉRALE -->
        <aside class="sidebar" aria-label="Navigation principale">

            <a href="/dashboard" class="sidebar-brand">
                <span class="brand-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 7l8-4l8 4"></path>
                        <path d="M4 7v10l8 4l8-4V7"></path>
                        <path d="M12 11l8-4"></path>
                        <path d="M12 11v10"></path>
                        <path d="M12 11L4 7"></path>
                    </svg>
                </span>
                <span class="brand-name">Gestion<span>RH</span></span>
            </a>

            <div class="workspace">
                <div class="workspace-logo">RH</div>
                <div class="workspace-info">
                    <div class="workspace-name"><?= esc($nom) ?></div>
                    <div class="workspace-type">Rôle : <?= esc($role) ?></div>
                </div>
            </div>

            <div class="sidebar-content">

                <div class="nav-section">Général</div>
                <ul class="sidebar-nav">
                    <li>
                        <a href="/dashboard" class="sidebar-link <?= $uri === 'dashboard' ? 'active' : '' ?>" <?= $uri === 'dashboard' ? 'aria-current="page"' : '' ?>>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="7" height="9" rx="1"></rect>
                                <rect x="14" y="3" width="7" height="5" rx="1"></rect>
                                <rect x="14" y="12" width="7" height="9" rx="1"></rect>
                                <rect x="3" y="16" width="7" height="5" rx="1"></rect>
                            </svg>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                </ul>

                <?php if ($role === 'manager' || $role === 'dg'): ?>
                    <div class="nav-section">Besoins</div>
                    <ul class="sidebar-nav">
                        <li>
                            <a href="/besoin" class="sidebar-link <?= str_starts_with($uri, 'besoin') ? 'active' : '' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"></path>
                                    <rect x="9" y="3" width="6" height="4" rx="1"></rect>
                                    <path d="M9 12h6"></path>
                                    <path d="M9 16h6"></path>
                                </svg>
                                <span>Mes besoins</span>
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>

                <?php if ($role === 'rh' || $role === 'dg'): ?>
                    <div class="nav-section">Recrutement</div>
                    <ul class="sidebar-nav">
                        <li>
                            <a href="/validation" class="sidebar-link <?= str_starts_with($uri, 'validation') ? 'active' : '' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 11l3 3l8-8"></path>
                                    <path d="M20 12v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9"></path>
                                </svg>
                                <span>Validation RH</span>
                            </a>
                        </li>
                        <li>
                            <a href="/annonce" class="sidebar-link <?= str_starts_with($uri, 'annonce') && !str_contains($uri, 'annonces') ? 'active' : '' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 11l18-5v12L3 13z"></path>
                                    <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path>
                                </svg>
                                <span>Annonces</span>
                            </a>
                        </li>
                        <li>
                            <a href="/candidature" class="sidebar-link <?= str_starts_with($uri, 'candidature') && !str_contains($uri, 'embauches') ? 'active' : '' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span>Candidatures</span>
                            </a>
                        </li>
                        <li>
                            <a href="/candidature/embauches" class="sidebar-link <?= str_contains($uri, 'embauches') ? 'active' : '' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                                    <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                                    <path d="M4 22h16"></path>
                                    <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                                    <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                                    <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                                </svg>
                                <span>Embauches</span>
                            </a>
                        </li>
                        <li>
                            <a href="/emails" class="sidebar-link <?= str_starts_with($uri, 'emails') ? 'active' : '' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <path d="m3 7l9 6l9-6"></path>
                                </svg>
                                <span>Boîte d'envoi</span>
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>

                <?php if ($role === 'dg'): ?>
                    <div class="nav-section">Direction</div>
                    <ul class="sidebar-nav">
                        <li>
                            <a href="/dg" class="sidebar-link <?= str_starts_with($uri, 'dg') ? 'active' : '' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span>Validation DG</span>
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>

                <div class="nav-section">Public</div>
                <ul class="sidebar-nav">
                    <li>
                        <a href="/annonces" target="_blank" class="sidebar-link">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M2 12h20"></path>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10a15.3 15.3 0 0 1-4 10a15.3 15.3 0 0 1-4-10a15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                            <span>Site public</span>
                        </a>
                    </li>
                </ul>

            </div>

            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar"><?= strtoupper(substr($nom ?? 'U', 0, 1)) ?></div>
                    <div class="user-info">
                        <div class="user-name"><?= esc($nom) ?></div>
                        <div class="user-role">Rôle : <?= esc($role) ?></div>
                    </div>
                </div>
                <a href="/logout" class="sidebar-link" style="margin-top:8px; color:#dc2626;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Déconnexion</span>
                </a>
            </div>

        </aside>

        <!-- CONTENU PRINCIPAL -->
        <div class="main-wrapper">

            <header class="topbar">
                <nav aria-label="Fil d'Ariane">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= esc($title ?? 'Page') ?></li>
                    </ol>
                </nav>
                <div class="topbar-actions">
                    <a href="/annonces" target="_blank" class="topbar-icon" aria-label="Site public" title="Site public">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M2 12h20"></path>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10a15.3 15.3 0 0 1-4 10a15.3 15.3 0 0 1-4-10a15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                    </a>
                </div>
            </header>

            <main class="main-content">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger mb-3"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success mb-3"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>
                <?= $this->renderSection('content') ?>

                <footer class="footer-text">
                    © <?= date('Y') ?> Gestion RH. Tous droits réservés.
                </footer>
            </main>
        </div>
    </div>

<?php elseif ($estCandidat): ?>
    <div class="app-layout app-layout--public">
        <div class="main-wrapper" style="margin-left:0;">
            <header class="topbar">
                <a href="/annonces" class="sidebar-brand" style="border:none; min-height:auto; padding:0;">
                    <span class="brand-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7l8-4l8 4"></path><path d="M4 7v10l8 4l8-4V7"></path></svg></span>
                    <span class="brand-name">Gestion<span>RH</span></span>
                </a>
                <nav class="d-flex gap-3 align-items-center">
                    <a href="/annonces" class="text-secondary small text-decoration-none">Annonces</a>
                    <a href="/candidat/dashboard" class="text-secondary small text-decoration-none">Mon espace</a>
                    <a href="/candidat/logout" class="text-danger small text-decoration-none">Déconnexion</a>
                </nav>
            </header>
            <main class="main-content">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger mb-3"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success mb-3"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>
                <?= $this->renderSection('content') ?>
                <footer class="footer-text">© <?= date('Y') ?> Gestion RH.</footer>
            </main>
        </div>
    </div>

<?php else: ?>
    <div class="app-layout app-layout--public">
        <div class="main-wrapper" style="margin-left:0;">
            <header class="topbar">
                <a href="/annonces" class="sidebar-brand" style="border:none; min-height:auto; padding:0;">
                    <span class="brand-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7l8-4l8 4"></path><path d="M4 7v10l8 4l8-4V7"></path></svg></span>
                    <span class="brand-name">Gestion<span>RH</span></span>
                </a>
                <nav class="d-flex gap-3 align-items-center">
                    <a href="/annonces" class="text-secondary small text-decoration-none">Annonces</a>
                    <a href="/candidat/login" class="text-secondary small text-decoration-none">Connexion</a>
                    <a href="/candidat/register" class="text-secondary small text-decoration-none">Inscription</a>
                    <a href="/login" class="text-secondary small text-decoration-none">Espace interne</a>
                </nav>
            </header>
            <main class="main-content">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger mb-3"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success mb-3"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>
                <?= $this->renderSection('content') ?>
                <footer class="footer-text">© <?= date('Y') ?> Gestion RH.</footer>
            </main>
        </div>
    </div>
<?php endif; ?>

<!-- Tabler JS (optionnel, pour composants interactifs) -->
<script src="<?= base_url('js/tabler.min.js') ?>" defer></script>
</body>
</html>
