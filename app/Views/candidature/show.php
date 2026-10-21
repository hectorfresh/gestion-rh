<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2>Candidature #<?= $candidature['id'] ?> — <?= esc($candidature['annonce_titre']) ?></h2>
<p><strong>Statut actuel :</strong> <span class="badge"><?= esc($candidature['statut_libelle']) ?></span></p>

<h3>CV du candidat</h3>
<p><strong>Nom :</strong> <?= esc($candidature['candidat_prenom'] . ' ' . $candidature['candidat_nom']) ?></p>
<p><strong>Email :</strong> <?= esc($candidature['candidat_email']) ?></p>
<p><strong>Téléphone :</strong> <?= esc($candidature['candidat_telephone']) ?></p>

<?php $cv = (new \App\Models\ValeurCvModel())->listerParCandidature((int) $candidature['id']); ?>
<?php if (empty($cv)): ?>
    <p><em>Aucune valeur de CV enregistrée.</em></p>
<?php else: ?>
    <table>
        <tr><th>Champ</th><th>Réponse</th></tr>
        <?php foreach ($cv as $v): ?>
            <tr>
                <td><?= esc($v['champ_libelle']) ?></td>
                <td>
                    <?php if ($v['champ_type'] === 'liste'): ?>
                        <strong><?= esc($v['option_valeur']) ?></strong>
                    <?php else: ?>
                        <?= nl2br(esc($v['valeur_texte'])) ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>


<hr>
<h3>Actions</h3>

<?php if ($candidature['statut_code'] === 'EN_ATTENTE'): ?>
    <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
        <button type="submit" name="action" value="refuser">Refuser</button>
    </form>

    <h4>Proposer un essai</h4>
    <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
        <input type="hidden" name="action" value="proposer_essai">
        <label>Date début :</label> <input type="date" name="date_debut" required>
        <label>Date fin :</label> <input type="date" name="date_fin" required>
        <label>Commentaire :</label> <input type="text" name="commentaire">
        <button type="submit">Proposer l'essai</button>
    </form>

    <h4>Proposer directement les conditions (CDI direct)</h4>
    <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
        <button type="submit" name="action" value="proposer_conditions">Proposer les conditions</button>
    </form>

<?php elseif ($candidature['statut_code'] === 'ESSAI_EFFECTUE'): ?>
    <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
        <button type="submit" name="action" value="refuser">Refuser après essai</button>
        <button type="submit" name="action" value="proposer_conditions">Proposer les conditions</button>
    </form>


<?php elseif ($candidature['statut_code'] === 'ESSAI_REFUSE'): ?>
    <p style="background:#fff3cd; padding:10px; border-radius:4px;">
        ℹ️ Le candidat a refusé l'essai. Vous pouvez néanmoins, après évaluation, lui
        proposer directement les conditions (CDI/CDD) ou le refuser définitivement.
    </p>

    <h4>Proposer les conditions (CDI / CDD direct)</h4>
    <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
        <button type="submit" name="action" value="proposer_conditions" class="btn btn-success">
            ✓ Proposer les conditions au candidat
        </button>
    </form>

    <h4 style="margin-top:20px;">Ou refuser définitivement</h4>
    <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
        <button type="submit" name="action" value="refuser" class="btn btn-danger">
            ✗ Candidat non retenu
        </button>
    </form>







<?php elseif ($candidature['statut_code'] === 'SALAIRE_EN_NEGOCIATION'): ?>
    <h4>💰 Négociation de salaire</h4>
    <p>Salaire proposé initialement : <strong><?= number_format($candidature['salaire_propose'], 0, ',', ' ') ?> Ar</strong></p>
    <p>Demande du candidat : <strong><?= number_format($candidature['salaire_negocie'], 0, ',', ' ') ?> Ar</strong></p>
    <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
        <button type="submit" name="action" value="accepter_nego" class="btn btn-success">
            ✓ Accepter (le candidat devra valider)</button>
        <button type="submit" name="action" value="refuser_nego" class="btn btn-danger">
            ✗ Refuser la négociation</button>
    </form>




<?php elseif ($candidature['statut_code'] === 'CONDITIONS_ACCEPTEES'): ?>
    <?php
    // Salaire par défaut : final > négocié > proposé
    $salaireDefaut = $candidature['salaire_final']
        ?? $candidature['salaire_negocie']
        ?? $candidature['salaire_propose'];
    ?>
    <h4>📄 Établir le contrat</h4>
    <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
        <input type="hidden" name="action" value="etablir_contrat">
        <p>
            <label>Type :</label>
            <select name="type_contrat" required>
                <?php foreach ($types as $t): if ($t['libelle'] === 'CDD') continue; ?>
                    <option value="<?= esc($t['libelle']) ?>"><?= esc($t['libelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p><label>Salaire (Ar) :</label>
            <input type="number" name="salaire" step="0.01" min="0"
                   value="<?= $salaireDefaut ?>" required>
            <br><small>Salaire accepté par le candidat</small>
        </p>
        <p><label>Date début :</label> <input type="date" name="date_debut" required></p>
        <p><label>Date fin (optionnel) :</label> <input type="date" name="date_fin"></p>
        <button type="submit" class="btn btn-success">Établir le contrat</button>
    </form>
<?php endif; ?>

<p><a href="/candidature">← Retour à la liste</a></p>
<?= $this->endSection() ?>