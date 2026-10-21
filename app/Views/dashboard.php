<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Bonjour <?= esc($nom) ?> 👋</h2>
<p>Bienvenue sur l'application de gestion des besoins en personnel.</p>
<p>Votre rôle : <strong><?= esc($role) ?></strong></p>

<h3>Que pouvez-vous faire ?</h3>
<ul>
    <?php if ($role === 'manager'): ?>
        <li><strong>Mes besoins</strong> : créer et suivre vos demandes de personnel.</li>
    <?php elseif ($role === 'rh'): ?>
    <li><strong>Validation RH</strong> : valider ou refuser les besoins en attente.</li>
    <li><strong>Annonces</strong> : publier les annonces de recrutement.</li>
    <li><strong>Candidatures</strong> : traiter les candidatures reçues.</li>
    <li><strong>Candidats embauchés</strong> : voir la liste des nouveaux embauchés.</li>




        <?php elseif ($role === 'dg'): ?>
        <li><strong>Mes besoins</strong> : créer un besoin → il sera <em>validé d'office</em>.</li>
        <li><strong>Validation RH</strong> et <strong>Validation DG</strong> : traiter les besoins en attente.</li>
        <li><strong>Annonces</strong> : publier des annonces de recrutement.</li>
    <?php endif; ?>
</ul>

<?= $this->endSection() ?>