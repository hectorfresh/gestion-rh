<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
$salaireAffiche = $candidature['salaire_final']
    ?? $candidature['salaire_negocie']
    ?? $candidature['salaire_propose'];
$estNegocie = $candidature['statut_code'] === 'SALAIRE_EN_NEGOCIATION';
$estNouvelleProposition = in_array($candidature['statut_code'], ['CONDITIONS_PROPOSEES'])
                          && !empty($candidature['salaire_final']);
$cv = (new \App\Models\ValeurCvModel())->listerParCandidature((int) $candidature['id']);
?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Espace candidat / Candidature</div>
        <h1 class="page-title"><?= esc($candidature['annonce_titre']) ?></h1>
        <p class="page-description"><?= esc($candidature['poste_libelle']) ?></p>
    </div>
    <div class="btn-list">
        <a href="/candidat/dashboard" class="btn btn-outline-secondary">← Retour</a>
    </div>
</header>

<section class="content-card mb-4">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Statut de la candidature</h2>
        </div>
        <span class="badge badge-statut statut-<?= strtolower($candidature['statut_code']) ?>"><?= esc($candidature['statut_libelle']) ?></span>
    </div>
    <div class="content-card-body">
        <p class="mb-0 text-muted small">Candidature du <?= esc($candidature['date_candidature']) ?></p>
    </div>
</section>

<?php $parcours = (new \App\Models\ParcoursCvModel())->listerParCandidature((int) $candidature['id']); ?>
<?php if (!empty($parcours)): ?>
<section class="content-card mb-4">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Parcours (formation / expérience)</h2>
        </div>
    </div>
    <div class="content-card-body">
        <?php foreach ($parcours as $p): ?>
            <div class="mb-3 pb-3 border-bottom">
                <div class="d-flex justify-content-between">
                    <strong><?= esc($p['titre']) ?></strong>
                    <span class="badge bg-<?= $p['type'] === 'formation' ? 'info' : 'secondary' ?>"><?= esc($p['type']) ?></span>
                </div>
                <p class="text-muted small mb-1"><?= esc($p['lieu']) ?> · <?= esc($p['date_debut'] ?? '') ?> → <?= esc($p['date_fin'] ?? 'en cours') ?></p>
                <?php if (!empty($p['description'])): ?>
                    <p class="mb-0 small"><?= nl2br(esc($p['description'])) ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($cv)): ?>
<section class="content-card mb-4">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Mon CV (version envoyée)</h2>
        </div>
    </div>
    <div class="content-card-body">
        <div class="table-responsive">
            <table class="table data-table table-vcenter mb-0">
                <thead>
                    <tr><th scope="col">Champ</th><th scope="col">Ma réponse</th></tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="content-card mb-4">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Conditions proposées</h2>
        </div>
    </div>
    <div class="content-card-body">
        <p><strong>Salaire :</strong>
            <?= number_format($salaireAffiche, 0, ',', ' ') ?> Ar
            <?php if ($estNouvelleProposition): ?>
                <span class="text-success small">(après négociation acceptée)</span>
            <?php endif; ?>
        </p>
        <p><strong>Heure de début :</strong> <?= esc($candidature['heure_debut']) ?></p>
        <p class="mb-0"><strong>Autres conditions :</strong> <?= esc($candidature['autres_conditions'] ?? '—') ?></p>
        <?php if ($estNegocie && $candidature['salaire_negocie']): ?>
            <div class="alert alert-warning mt-3 mb-0">
                <i class="bi bi-hourglass me-1"></i> Votre demande de salaire (<strong><?= number_format($candidature['salaire_negocie'], 0, ',', ' ') ?> Ar</strong>)
                est en cours d'étude par le RH.
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if ($candidature['statut_code'] === 'ESSAI_PROPOSE' && !empty($essai)): ?>
    <section class="content-card mb-4">
        <div class="content-card-header">
            <div>
                <h2 class="content-card-title"><i class="bi bi-bullseye me-1"></i> Essai proposé</h2>
            </div>
        </div>
        <div class="content-card-body">
            <p>Du <strong><?= esc($essai['date_debut']) ?></strong> au <strong><?= esc($essai['date_fin']) ?></strong></p>
            <?php if ($essai['commentaire']): ?>
                <p><?= nl2br(esc($essai['commentaire'])) ?></p>
            <?php endif; ?>
            <form method="post" action="/candidat/candidature/<?= $candidature['id'] ?>/action" class="d-flex gap-2">
                <button type="submit" name="action" value="accepter_essai" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Accepter l'essai</button>
                <button type="submit" name="action" value="refuser_essai" class="btn btn-danger"><i class="bi bi-x-lg me-1"></i> Refuser l'essai</button>
            </form>
        </div>
    </section>
<?php elseif ($candidature['statut_code'] === 'CONDITIONS_PROPOSEES'): ?>
    <section class="content-card mb-4">
        <div class="content-card-header">
            <div>
                <h2 class="content-card-title"><i class="bi bi-handshake me-1"></i> Répondre à la proposition</h2>
            </div>
        </div>
        <div class="content-card-body">
            <p>Salaire proposé : <strong><?= number_format($salaireAffiche, 0, ',', ' ') ?> Ar</strong></p>
            <form method="post" action="/candidat/candidature/<?= $candidature['id'] ?>/action" class="d-flex gap-2 mb-3">
                <button type="submit" name="action" value="accepter_conditions" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Accepter</button>
                <button type="button" id="btn-refuser-conditions" class="btn btn-danger"><i class="bi bi-x-lg me-1"></i> Non, je souhaite proposer</button>
            </form>
            <?php if (empty($candidature['salaire_final'])): ?>
                <form method="post" action="/candidat/candidature/<?= $candidature['id'] ?>/action" id="form-negociation" class="row g-2 align-items-end d-none">
                    <input type="hidden" name="action" value="negocier">
                    <div class="col-md-4">
                        <label class="form-label small">Votre proposition salariale (Ar)</label>
                        <input type="number" name="salaire_negocie" step="0.01" min="1" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Envoyer ma proposition</button>
                    </div>
                </form>
                <p id="aide-negociation" class="small text-muted d-none">
                    Indiquez le salaire que vous proposez en remplacement de la proposition actuelle.
                </p>
                <script>
                    document.getElementById('btn-refuser-conditions').addEventListener('click', function () {
                        document.getElementById('form-negociation').classList.remove('d-none');
                        document.getElementById('aide-negociation').classList.remove('d-none');
                        this.classList.add('d-none');
                    });
                </script>
            <?php endif; ?>
        </div>
    </section>
<?php elseif (!empty($contrat)): ?>
    <section class="content-card mb-4">
        <div class="content-card-header">
            <div>
                <h2 class="content-card-title"><i class="bi bi-file-earmark me-1"></i> Contrat établi</h2>
            </div>
        </div>
        <div class="content-card-body">
            <p><strong>Type :</strong> <?= esc($contrat['type_libelle']) ?></p>
            <p><strong>Salaire :</strong> <?= number_format($contrat['salaire'], 0, ',', ' ') ?> Ar</p>
            <p><strong>Date début :</strong> <?= esc($contrat['date_debut']) ?></p>
            <?php if ($contrat['date_fin']): ?><p><strong>Date fin :</strong> <?= esc($contrat['date_fin']) ?></p><?php endif; ?>
            <div class="alert alert-success mb-0"><i class="bi bi-check2-circle me-1"></i> Félicitations, vous êtes embauché(e) !</div>
        </div>
    </section>
<?php endif; ?>
<?= $this->endSection() ?>