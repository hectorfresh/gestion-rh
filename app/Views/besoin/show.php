<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Besoin #<?= $besoin['id'] ?></h2>

<?php if ($besoin['statut_code'] === 'VALIDE'
          && session()->get('role') === 'dg'
          && (int) $besoin['manager_id'] === (int) session()->get('user_id')): ?>
    <p style="background:#eef; padding:8px;">
        ℹ️ Ce besoin a été <strong>validé d'office</strong> lors de sa création par le DG.
        Il n'est plus modifiable.
    </p>
<?php endif; ?>




<p><strong>Poste :</strong> <?= esc($besoin['poste_libelle']) ?></p>
<p><strong>Nombre de postes :</strong> <?= $besoin['nombre_postes'] ?></p>
<p><strong>Budget engagé (max) :</strong> <?= number_format($besoin['cout_engage'], 2, ',', ' ') ?> Ar</p>
<p><strong>Date de création :</strong> <?= esc($besoin['date_creation']) ?></p>
<p><strong>Statut actuel :</strong> <?= esc($besoin['statut_libelle']) ?></p>
<p><strong>Manager :</strong> <?= esc($besoin['manager_nom']) ?></p>

<h3>Historique des validations</h3>
<?php if (empty($validations)): ?>
    <p><em>Aucune validation pour le moment.</em></p>
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

<p><a href="/besoin">← Retour à la liste</a></p>

<?= $this->endSection() ?>