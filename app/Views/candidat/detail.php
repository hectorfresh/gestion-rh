<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
// Salaire à afficher : final > négocié > proposé
$salaireAffiche = $candidature['salaire_final']
    ?? $candidature['salaire_negocie']
    ?? $candidature['salaire_propose'];
$estNegocie = $candidature['statut_code'] === 'SALAIRE_EN_NEGOCIATION';
$estNouvelleProposition = in_array($candidature['statut_code'], ['CONDITIONS_PROPOSEES'])
                          && !empty($candidature['salaire_final']);
?>

<h2>Candidature — <?= esc($candidature['annonce_titre']) ?></h2>
<p><strong>Poste :</strong> <?= esc($candidature['poste_libelle']) ?></p>
<p><strong>Statut :</strong> <span class="badge badge-<?= strtolower($candidature['statut_code']) ?>">
<?php $cv = (new \App\Models\ValeurCvModel())->listerParCandidature((int) $candidature['id']); ?>
<?php if (!empty($cv)): ?>
    <h3>Mon CV (version envoyée)</h3>
    <table>
        <tr><th>Champ</th><th>Ma réponse</th></tr>
        <?php foreach ($cv as $v): ?>
            <tr>
                <td><?= esc($v['champ_libelle']) ?></td>
                <td>
                    <?php if ($v['champ_type'] === 'liste'): ?>
                        <?= esc($v['option_valeur']) ?>
                    <?php else: ?>
                        <?= nl2br(esc($v['valeur_texte'])) ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<p><strong>Date :</strong> <?= esc($candidature['date_candidature']) ?></p>

<hr>
<h3>Conditions proposées</h3>
<p><strong>Salaire :</strong>
    <?= number_format($salaireAffiche, 0, ',', ' ') ?> Ar
    <?php if ($estNouvelleProposition): ?>
        <span style="color:#27ae60;">(après négociation acceptée)</span>
    <?php endif; ?>
</p>
<p><strong>Heure de début :</strong> <?= esc($candidature['heure_debut']) ?></p>
<p><strong>Autres conditions :</strong> <?= esc($candidature['autres_conditions'] ?? '—') ?></p>

<?php if ($estNegocie && $candidature['salaire_negocie']): ?>
    <p style="background:#fff3cd; padding:10px; border-radius:4px;">
        ⏳ Votre demande de salaire (<strong><?= number_format($candidature['salaire_negocie'], 0, ',', ' ') ?> Ar</strong>)
        est en cours d'étude par le RH.
    </p>
<?php endif; ?>

<hr>

<?php if ($candidature['statut_code'] === 'ESSAI_PROPOSE' && $essai): ?>
    <h3>🎯 Essai proposé</h3>
    <p>Du <strong><?= esc($essai['date_debut']) ?></strong> au <strong><?= esc($essai['date_fin']) ?></strong></p>
    <?php if ($essai['commentaire']): ?>
        <p><?= nl2br(esc($essai['commentaire'])) ?></p>
    <?php endif; ?>
    <form method="post" action="/candidat/candidature/<?= $candidature['id'] ?>/action">
        <button type="submit" name="action" value="accepter_essai" style="background:#27ae60; color:white; padding:8px 16px; border:none; border-radius:4px;">✓ Accepter l'essai</button>
        <button type="submit" name="action" value="refuser_essai" style="background:#e74c3c; color:white; padding:8px 16px; border:none; border-radius:4px;">✗ Refuser l'essai</button>
    </form>







<?php elseif ($candidature['statut_code'] === 'CONDITIONS_PROPOSEES'): ?>
    <h3>🤝 Répondre à la proposition</h3>
    <p>Salaire proposé : <strong><?= number_format($salaireAffiche, 0, ',', ' ') ?> Ar</strong></p>
    <form method="post" action="/candidat/candidature/<?= $candidature['id'] ?>/action">
        <button type="submit" name="action" value="accepter_conditions" style="background:#27ae60; color:white; padding:8px 16px; border:none; border-radius:4px;">✓ Accepter</button>
        <button type="submit" name="action" value="refuser_conditions" style="background:#e74c3c; color:white; padding:8px 16px; border:none; border-radius:4px;">✗ Refuser</button>
    </form>

    <?php if (empty($candidature['salaire_final'])): ?>
        <h4>Ou négocier le salaire</h4>
        <form method="post" action="/candidat/candidature/<?= $candidature['id'] ?>/action">
            <input type="hidden" name="action" value="negocier">
            <label>Votre salaire souhaité (Ar) :</label>
            <input type="number" name="salaire_negocie" step="0.01" min="0" required>
            <button type="submit">Envoyer la négociation</button>
        </form>
    <?php endif; ?>

<?php elseif ($contrat): ?>
    <h3>📄 Contrat établi</h3>
    <p><strong>Type :</strong> <?= esc($contrat['type_libelle']) ?></p>
    <p><strong>Salaire :</strong> <?= number_format($contrat['salaire'], 0, ',', ' ') ?> Ar</p>
    <p><strong>Date début :</strong> <?= esc($contrat['date_debut']) ?></p>
    <?php if ($contrat['date_fin']): ?><p><strong>Date fin :</strong> <?= esc($contrat['date_fin']) ?></p><?php endif; ?>
    <p style="background:#d4edda; padding:10px; border-radius:4px;">🎉 Félicitations, vous êtes embauché(e) !</p>
<?php endif; ?>

<p><a href="/candidat/dashboard">← Retour à mes candidatures</a></p>

<?= $this->endSection() ?>