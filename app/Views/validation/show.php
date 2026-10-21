<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Traiter le besoin #<?= $besoin['id'] ?></h2>

<p><strong>Poste :</strong> <?= esc($besoin['poste_libelle']) ?></p>
<p><strong>Nombre de postes :</strong> <?= $besoin['nombre_postes'] ?></p>
<p><strong>Budget engagé demandé :</strong> <?= number_format($besoin['cout_engage'], 0, ',', ' ') ?> Ar</p>
<p><strong>Manager :</strong> <?= esc($besoin['manager_nom']) ?></p>
<p><strong>Date :</strong> <?= esc($besoin['date_creation']) ?></p>

<hr>
<p>
    <strong>Budget total :</strong> <?= number_format($total, 0, ',', ' ') ?> Ar —
    <strong>Engagé :</strong> <?= number_format($engage, 0, ',', ' ') ?> Ar —
    <strong>Restant :</strong> <?= number_format($restant, 0, ',', ' ') ?> Ar
</p>

<?php if ($besoin['cout_engage'] > $restant): ?>
    <p style="color:red;">
        ⚠️ Budget insuffisant : si vous validez, la finance refusera automatiquement.
    </p>
<?php endif; ?>

<h3>Décision</h3>
<form method="post" action="/validation/traiter/<?= $besoin['id'] ?>">
    <p>
        <label>Commentaire (optionnel) :</label><br>
        <textarea name="commentaire" rows="3" cols="50"></textarea>
    </p>
    <button type="submit" name="decision" value="valider">Valider</button>
    <button type="submit" name="decision" value="refuser">Refuser</button>
    <a href="/validation">Annuler</a>
</form>

<?= $this->endSection() ?>