<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2>Offres de recrutement</h2>
<?php if (empty($annonces)): ?>
    <p><em>Aucune annonce pour le moment.</em></p>
<?php else: ?>
    <?php foreach ($annonces as $a): ?>
        <div style="background:white; padding:15px; margin-bottom:12px; border-radius:6px; border:1px solid #ddd;">
            <h3 style="margin:0 0 6px;"><?= esc($a['titre']) ?></h3>
            <p style="margin:4px 0;"><strong>Poste :</strong> <?= esc($a['poste_libelle']) ?></p>
            <p style="margin:4px 0;"><strong>Salaire proposé :</strong> <?= number_format($a['salaire_propose'], 0, ',', ' ') ?> Ar</p>
            <p style="margin:4px 0;"><strong>Heure de début :</strong> <?= esc($a['heure_debut']) ?></p>
            <p><a href="/annonces/<?= $a['id'] ?>">Voir l'annonce →</a></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?= $this->endSection() ?>