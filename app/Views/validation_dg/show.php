<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Décision DG — Besoin #<?= $besoin['id'] ?></h2>

<p><strong>Poste :</strong> <?= esc($besoin['poste_libelle']) ?></p>
<p><strong>Nombre de postes :</strong> <?= $besoin['nombre_postes'] ?></p>
<p><strong>Budget engagé :</strong> <?= number_format($besoin['cout_engage'], 0, ',', ' ') ?> Ar</p>
<p><strong>Manager :</strong> <?= esc($besoin['manager_nom']) ?></p>
<p><strong>Date :</strong> <?= esc($besoin['date_creation']) ?></p>

<h3>Historique</h3>
<?php /* on affiche l'historique pour que le DG voie les validations RH + finance déjà passées */ ?>
<?php $validations = (new \App\Models\ValidationModel())->listerParBesoin($besoin['id']); ?>
<?php if (empty($validations)): ?>
    <p><em>Aucune validation enregistrée.</em></p>
<?php else: ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Étape</th>
            <th>Décision</th>
            <th>Par</th>
            <th>Date</th>
            <th>Commentaire</th>
        </tr>
        <?php foreach ($validations as $v): ?>
            <tr>
                <td><?= esc($v['etape_libelle']) ?></td>
                <td><?= esc($v['decision_libelle']) ?></td>
                <td><?= esc($v['utilisateur_nom'] ?? 'Automatique (finance)') ?></td>
                <td><?= esc($v['date_validation']) ?></td>
                <td><?= esc($v['commentaire'] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<hr>
<h3>Décision</h3>
<form method="post" action="/dg/traiter/<?= $besoin['id'] ?>">
    <p>
        <label>Commentaire (optionnel) :</label><br>
        <textarea name="commentaire" rows="3" cols="50"></textarea>
    </p>
    <button type="submit" name="decision" value="valider">Valider</button>
    <button type="submit" name="decision" value="refuser">Refuser</button>
    <a href="/dg">Annuler</a>
</form>

<?= $this->endSection() ?>