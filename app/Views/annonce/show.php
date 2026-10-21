<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2><?= esc($annonce['titre']) ?></h2>

<p><strong>Poste :</strong> <?= esc($annonce['poste_libelle']) ?></p>
<p><strong>Nombre de postes :</strong> <?= $annonce['nombre_postes'] ?></p>
<p><strong>Salaire proposé :</strong>
    <?= number_format($annonce['salaire_min'], 0, ',', ' ') ?>
    à <?= number_format($annonce['salaire_max'], 0, ',', ' ') ?> Ar
</p>
<p><strong>Publié le :</strong> <?= esc($annonce['date_publication']) ?></p>
<p><strong>Par :</strong> <?= esc($annonce['rh_nom']) ?></p>

<h3>Description</h3>
<p><?= nl2br(esc($annonce['description'])) ?></p>

<p><a href="/annonce">← Retour aux annonces</a></p>

<?= $this->endSection() ?>