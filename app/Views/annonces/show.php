<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2><?= esc($annonce['titre']) ?></h2>
<p><strong>Poste :</strong> <?= esc($annonce['poste_libelle']) ?></p>
<p><strong>Salaire proposé :</strong> <?= number_format($annonce['salaire_propose'], 0, ',', ' ') ?> Ar</p>
<p><strong>Heure de début :</strong> <?= esc($annonce['heure_debut']) ?></p>
<p><strong>Autres conditions :</strong> <?= esc($annonce['autres_conditions'] ?? '—') ?></p>
<h3>Description</h3>
<p><?= nl2br(esc($annonce['description'])) ?></p>

<?php if (session()->get('candidat_id')): ?>
    <p><a href="/postuler/<?= $annonce['id'] ?>">➡️ Postuler à cette offre</a></p>
<?php else: ?>
    <p><a href="/candidat/login">Connectez-vous</a> pour postuler.</p>
<?php endif; ?>

<p><a href="/annonces">← Retour aux annonces</a></p>
<?= $this->endSection() ?>