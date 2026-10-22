<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Recrutement / Candidature</div>
        <h1 class="page-title">Candidature #<?= $candidature['id'] ?></h1>
        <p class="page-description">Détail et traitement de la candidature.</p>
    </div>
    <div class="btn-list">
        <a href="/candidature" class="btn btn-outline-secondary">← Retour à la liste</a>
    </div>
</header>

<!-- ==================== EN-TÊTE ==================== -->
<div class="card p-4 mb-4">
    <div class="row">
        <div class="col-md-8">
            <h4 class="mb-1"><?= esc($candidature['annonce_titre']) ?></h4>
            <p class="text-muted mb-0"><?= esc($candidature['poste_libelle']) ?></p>
        </div>

        <?php $reponses = (new \App\Models\ReponseCvModel())->listerParCandidature((int) $candidature['id']); ?>
        <?php if (!empty($reponses)): ?>
        <div class="card p-4 mb-4">
            <h4 class="mb-3"><i class="bi bi-check2-square me-1"></i> Questionnaire Oui / Non</h4>
            <?php foreach ($reponses as $reponse): ?>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span><?= esc($reponse['libelle']) ?></span>
                    <strong class="<?= (int) $reponse['valeur'] === 1 ? 'text-success' : 'text-danger' ?>">
                        <?= (int) $reponse['valeur'] === 1 ? 'Oui' : 'Non' ?>
                    </strong>
                </div>
            <?php endforeach; ?>
            <?php if ($candidature['statut_code'] === 'REPONSES_A_VERIFIER'): ?>
                <div class="alert alert-warning mt-3 mb-0">
                    Le candidat a répondu Non à au moins une question. Vérifiez son dossier avant de proposer un essai ou les conditions.
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="col-md-4 text-md-end">
            <span class="badge badge-statut statut-<?= strtolower($candidature['statut_code']) ?> fs-6">
                <?= esc($candidature['statut_libelle']) ?>
            </span>
            <p class="text-muted small mb-0 mt-2">Candidature du <?= esc($candidature['date_candidature']) ?></p>
        </div>
    </div>
</div>

<!-- ==================== CV DU CANDIDAT ==================== -->
<div class="card p-4 mb-4">
    <h4 class="mb-3"><i class="bi bi-person me-1"></i> CV du candidat</h4>
    <div class="row mb-3">
        <div class="col-md-4">
            <p class="mb-1"><strong>Nom :</strong> <?= esc($candidature['candidat_prenom'] . ' ' . $candidature['candidat_nom']) ?></p>
            <p class="mb-0"><strong>Email :</strong> <?= esc($candidature['candidat_email']) ?></p>
        </div>
        <div class="col-md-4">
            <p class="mb-0"><strong>Téléphone :</strong> <?= esc($candidature['candidat_telephone']) ?></p>
        </div>
    </div>

    <?php $cv = (new \App\Models\ValeurCvModel())->listerParCandidature((int) $candidature['id']); ?>
    <?php if (empty($cv)): ?>
        <div class="alert alert-light border mb-0">Aucune valeur de CV enregistrée.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                    <tr><th style="width: 40%;">Champ</th><th>Réponse</th></tr>
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
    <?php endif; ?>
</div>

<!-- ==================== ENTRETIENS ET EVALUATIONS ==================== -->
<div class="card p-4 mb-4">
    <h4 class="mb-3"><i class="bi bi-calendar-event me-1"></i> Entretiens et évaluations</h4>
    <?php
    $entretiens = (new \App\Models\EntretienModel())->pourCandidature((int) $candidature['id']);
    ?>
    <?php if (empty($entretiens)): ?>
        <p class="text-muted">Aucun entretien planifié.</p>
    <?php else: ?>
        <?php foreach ($entretiens as $entretien): ?>
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between">
                    <strong><?= esc($entretien['type']) ?></strong>
                    <span class="badge bg-secondary"><?= esc($entretien['statut']) ?></span>
                </div>
                <p class="small text-muted mb-2"><?= esc($entretien['date_entretien']) ?></p>
                <?php $evaluations = (new \App\Models\EvaluationModel())->pourEntretien((int) $entretien['id']); ?>
                <?php foreach ($evaluations as $evaluation): ?>
                    <p class="mb-1">
                        Note : <strong><?= esc($evaluation['note']) ?>/20</strong> —
                        <?= esc($evaluation['decision']) ?>
                    </p>
                    <?php if ($evaluation['commentaire']): ?>
                        <p class="small mb-0"><?= nl2br(esc($evaluation['commentaire'])) ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>
                <form method="post" action="/recrutement/entretien/<?= $entretien['id'] ?>/evaluation" class="row g-2 mt-2">
                    <div class="col-md-2"><input type="number" name="note" min="0" max="20" step="0.01" class="form-control" placeholder="Note" required></div>
                    <div class="col-md-3">
                        <select name="decision" class="form-select" required>
                            <option value="RETENU">Retenu</option>
                            <option value="NON_RETENU">Non retenu</option>
                            <option value="A_REVOIR">À revoir</option>
                        </select>
                    </div>
                    <div class="col-md-5"><input type="text" name="commentaire" class="form-control" placeholder="Commentaire"></div>
                    <div class="col-md-2"><button class="btn btn-outline-primary w-100">Évaluer</button></div>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <form method="post" action="/recrutement/candidature/<?= $candidature['id'] ?>/entretien" class="row g-2">
        <div class="col-md-4"><input type="datetime-local" name="date_entretien" class="form-control" required></div>
        <div class="col-md-3"><input type="text" name="type" maxlength="30" class="form-control" placeholder="Type d'entretien" required></div>
        <div class="col-md-3"><button class="btn btn-primary">Planifier l'entretien</button></div>
    </form>
</div>

<!-- ==================== PARCOURS ==================== -->
<?php $parcours = (new \App\Models\ParcoursCvModel())->listerParCandidature((int) $candidature['id']); ?>
<?php if (!empty($parcours)): ?>
<div class="content-card p-4 mb-4">
    <h4 class="mb-3"><i class="bi bi-book me-1"></i> Parcours (formation / expérience)</h4>
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
<?php endif; ?>

<!-- ==================== CONDITIONS PROPOSÉES ==================== -->
<div class="card p-4 mb-4">
    <h4 class="mb-3"><i class="bi bi-clipboard me-1"></i> Conditions de l'annonce</h4>
    <div class="row">
        <div class="col-md-4">
            <p class="mb-1"><strong>Salaire proposé :</strong>
                <?php if (!empty($candidature['salaire_final'])): ?>
                    <span class="text-success fw-bold">
                        <?= number_format($candidature['salaire_final'], 0, ',', ' ') ?> Ar
                    </span>
                    <small class="text-muted">(après négociation)</small>
                <?php else: ?>
                    <?= number_format($candidature['salaire_propose'], 0, ',', ' ') ?> Ar
                <?php endif; ?>
            </p>
            <p class="mb-0"><strong>Heure de début :</strong> <?= esc($candidature['heure_debut']) ?></p>
        </div>
        <div class="col-md-8">
            <p class="mb-0"><strong>Autres conditions :</strong><br>
                <?= nl2br(esc($candidature['autres_conditions'] ?? '—')) ?></p>
        </div>
    </div>
</div>

<!-- ==================== ACTIONS SELON STATUT ==================== -->
<h3 class="mb-3">Actions</h3>

<?php if ($candidature['statut_code'] === 'EN_ATTENTE'): ?>
    <div class="card p-4 mb-3">
        <h5>Proposer un essai</h5>
        <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
            <input type="hidden" name="action" value="proposer_essai">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Date début</label>
                    <input type="date" name="date_debut" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Date fin</label>
                    <input type="date" name="date_fin" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Commentaire (optionnel)</label>
                    <input type="text" name="commentaire" class="form-control">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Proposer</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card p-4 mb-3">
        <h5>Ou embaucher directement (CDI)</h5>
        <p class="text-muted small mb-2">Propose directement les conditions de l'annonce au candidat.</p>
        <form method="post" action="/candidature/<?= $candidature['id'] ?>/action" class="d-inline">
            <button type="submit" name="action" value="proposer_conditions" class="btn btn-success">
                <i class="bi bi-check-lg me-1"></i> Proposer les conditions
            </button>
        </form>
    </div>

    <div class="card p-4 mb-3">
        <h5>Refuser la candidature</h5>
        <form method="post" action="/candidature/<?= $candidature['id'] ?>/action" class="d-inline">
            <button type="submit" name="action" value="refuser" class="btn btn-danger"
                    onclick="return confirm('Refuser définitivement cette candidature ?');">
                <i class="bi bi-x-lg me-1"></i> Refuser
            </button>
        </form>
    </div>

<?php elseif ($candidature['statut_code'] === 'ESSAI_PROPOSE'): ?>
    <div class="alert alert-info">
        <i class="bi bi-hourglass me-1"></i> <strong>En attente de la réponse du candidat à la proposition d'essai.</strong>
    </div>
    <?php $essai = (new \App\Models\EssaiModel())->trouverParCandidature((int) $candidature['id']); ?>
    <?php if ($essai): ?>
        <div class="card p-4 mb-3">
            <h5>Essai proposé</h5>
            <p class="mb-1"><strong>Du :</strong> <?= esc($essai['date_debut']) ?> <strong>au :</strong> <?= esc($essai['date_fin']) ?></p>
            <?php if ($essai['commentaire']): ?>
                <p class="mb-0 text-muted"><?= nl2br(esc($essai['commentaire'])) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="card p-4 mb-3">
        <h5>Le candidat refuse l'essai ?</h5>
        <form method="post" action="/candidature/<?= $candidature['id'] ?>/action" class="d-inline">
            <button type="submit" name="action" value="refuser" class="btn btn-danger"
                    onclick="return confirm('Refuser ce candidat ?');">
                <i class="bi bi-x-lg me-1"></i> Marquer comme non retenu
            </button>
        </form>
    </div>

<?php elseif ($candidature['statut_code'] === 'ESSAI_EFFECTUE'): ?>
    <div class="card p-4 mb-3">
        <h5>Après essai</h5>
        <form method="post" action="/candidature/<?= $candidature['id'] ?>/action" class="d-inline">
            <button type="submit" name="action" value="proposer_conditions" class="btn btn-success">
                <i class="bi bi-check-lg me-1"></i> Proposer les conditions
            </button>
        </form>
        <form method="post" action="/candidature/<?= $candidature['id'] ?>/action" class="d-inline ms-2">
            <button type="submit" name="action" value="refuser" class="btn btn-danger"
                    onclick="return confirm('Refuser ce candidat après essai ?');">
                <i class="bi bi-x-lg me-1"></i> Refuser après essai
            </button>
        </form>
    </div>

<?php elseif ($candidature['statut_code'] === 'ESSAI_REFUSE'): ?>
    <div class="alert alert-warning">
        <i class="bi bi-info-circle me-1"></i> Le candidat a refusé l'essai. Vous pouvez néanmoins, après évaluation, lui proposer
        directement les conditions ou le refuser définitivement.
    </div>

    <div class="card p-4 mb-3">
        <h5>Proposer les conditions</h5>
        <form method="post" action="/candidature/<?= $candidature['id'] ?>/action" class="d-inline">
            <button type="submit" name="action" value="proposer_conditions" class="btn btn-success">
                <i class="bi bi-check-lg me-1"></i> Proposer les conditions
            </button>
        </form>
    </div>

    <div class="card p-4 mb-3">
        <h5>Refuser définitivement</h5>
        <form method="post" action="/candidature/<?= $candidature['id'] ?>/action" class="d-inline">
            <button type="submit" name="action" value="refuser" class="btn btn-danger"
                    onclick="return confirm('Refuser définitivement ?');">
                <i class="bi bi-x-lg me-1"></i> Candidat non retenu
            </button>
        </form>
    </div>

<?php elseif ($candidature['statut_code'] === 'CONDITIONS_PROPOSEES'): ?>
    <?php $salaireAffiche = $candidature['salaire_final'] ?? $candidature['salaire_propose']; ?>
    <div class="alert alert-info">
        <i class="bi bi-hourglass me-1"></i> <strong>En attente de la réponse du candidat.</strong><br>
        Conditions proposées : <strong><?= number_format($salaireAffiche, 0, ',', ' ') ?> Ar</strong>
        — Heure : <strong><?= esc($candidature['heure_debut']) ?></strong>
        <?php if (!empty($candidature['autres_conditions'])): ?>
            <br><small><?= nl2br(esc($candidature['autres_conditions'])) ?></small>
        <?php endif; ?>
        <?php if (!empty($candidature['salaire_final'])): ?>
            <br><small><em>(après acceptation de la négociation)</em></small>
        <?php endif; ?>
    </div>

<?php elseif ($candidature['statut_code'] === 'SALAIRE_EN_NEGOCIATION'): ?>
    <?php
    $db = \Config\Database::connect();
    $poste = $db->table('poste')
                ->join('besoin', 'besoin.poste_id = poste.id')
                ->join('annonce', 'annonce.besoin_id = besoin.id')
                ->where('annonce.id', $candidature['annonce_id'])
                ->get()->getRow();
    ?>
    <div class="card p-4 mb-3">
        <h5 class="mb-3"><i class="bi bi-cash me-1"></i> Négociation de salaire</h5>
        <div class="row mb-3">
            <div class="col-md-4">
                <p class="mb-1 text-muted small">Salaire initial</p>
                <p class="mb-0"><strong><?= number_format($candidature['salaire_propose'], 0, ',', ' ') ?> Ar</strong></p>
            </div>
            <div class="col-md-4">
                <p class="mb-1 text-muted small">Demande du candidat</p>
                <p class="mb-0 text-warning fw-bold fs-5">
                    <?= number_format($candidature['salaire_negocie'], 0, ',', ' ') ?> Ar
                </p>
            </div>
            <div class="col-md-4">
                <p class="mb-1 text-muted small">Maximum autorisé</p>
                <p class="mb-0"><strong><?= number_format($poste->salaire_max, 0, ',', ' ') ?> Ar</strong></p>
            </div>
        </div>

        <?php if ($candidature['salaire_negocie'] > $poste->salaire_max): ?>
            <div class="alert alert-warning mb-3">
                <i class="bi bi-diagram-3 me-1"></i> La demande dépasse le maximum du poste.
                L’acceptation suit obligatoirement le workflow <strong>RH → Finance → DG</strong>.
            </div>
        <?php endif; ?>

        <div class="d-flex flex-wrap gap-2">
            <?php if ($candidature['salaire_negocie'] <= $poste->salaire_max): ?>
                <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
                    <button type="submit" name="action" value="accepter_nego" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i> Accepter la demande (<?= number_format($candidature['salaire_negocie'], 0, ',', ' ') ?> Ar)
                    </button>
                </form>
            <?php endif; ?>

            <?php if ($candidature['salaire_negocie'] <= $poste->salaire_max): ?>
                <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
                    <button type="submit" name="action" value="proposer_max" class="btn btn-warning">
                        <i class="bi bi-arrow-left-right me-1"></i> Contre-proposer le max (<?= number_format($poste->salaire_max, 0, ',', ' ') ?> Ar)
                    </button>
                </form>
            <?php endif; ?>

            <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
                <button type="submit" name="action" value="refuser_nego" class="btn btn-danger"
                        onclick="return confirm('Refuser définitivement ce candidat ?');">
                    <i class="bi bi-x-lg me-1"></i> Refuser définitivement
                </button>
            </form>
        </div>
    </div>

<?php elseif (in_array($candidature['statut_code'], ['SALAIRE_ATTENTE_FINANCE', 'SALAIRE_ATTENTE_DG'], true)): ?>
    <div class="alert alert-warning">
        <i class="bi bi-hourglass-split me-1"></i>
        <strong>Validation salariale en cours.</strong>
        La proposition supérieure au plafond suit le workflow
        <strong>RH → Finance → DG</strong>.
        <?php if ($candidature['statut_code'] === 'SALAIRE_ATTENTE_FINANCE'): ?>
            Étape actuelle : Finance.
        <?php else: ?>
            Étape actuelle : DG.
        <?php endif; ?>
    </div>

<?php elseif ($candidature['statut_code'] === 'CONDITIONS_REFUSEES'): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-1"></i> Le candidat a refusé la proposition.
    </div>

    <div class="card p-4 mb-3">
        <h5>Actions possibles</h5>
        <div class="d-flex flex-wrap gap-2">
            <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
                <button type="submit" name="action" value="cloturer" class="btn btn-danger"
                        onclick="return confirm('Clôturer définitivement cette candidature ?');">
                    <i class="bi bi-x-lg me-1"></i> Clôturer la candidature
                </button>
            </form>
            <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
                <button type="submit" name="action" value="reproposer" class="btn btn-warning">
                    ↻ Re-proposer les conditions
                </button>
            </form>
        </div>
    </div>

<?php elseif ($candidature['statut_code'] === 'CONDITIONS_ACCEPTEES'): ?>
    <?php
    $salaireDefaut = $candidature['salaire_final']
        ?? $candidature['salaire_negocie']
        ?? $candidature['salaire_propose'];
    ?>
    <div class="card p-4 mb-3">
        <h5 class="mb-3"><i class="bi bi-file-earmark me-1"></i> Établir le contrat</h5>
        <form method="post" action="/candidature/<?= $candidature['id'] ?>/action">
            <input type="hidden" name="action" value="etablir_contrat">
            <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Type de contrat</label>
                <select name="type_contrat" id="type_contrat" class="form-select" required>
                    <?php foreach ($types as $t): ?>
                        <option value="<?= esc($t['libelle']) ?>"><?= esc($t['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Salaire (Ar)</label>
                <input type="number" name="salaire" step="0.01" min="0"
                    value="<?= $salaireDefaut ?>" class="form-control" required>
                <small class="text-muted">Salaire accepté par le candidat</small>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date début</label>
                <input type="date" name="date_debut" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label" id="label-date-fin">Date fin (optionnel)</label>
                <input type="date" name="date_fin" id="date_fin" class="form-control">
            </div>

            <script>
                // Adapte le champ "Date fin" selon le type de contrat choisi
                (function () {
                    const select  = document.getElementById('type_contrat');
                    const input   = document.getElementById('date_fin');
                    const label   = document.getElementById('label-date-fin');

                    function ajuster() {
                        if (select.value === 'CDI') {
                            input.required = false;
                            input.value = '';
                            label.textContent = 'Date fin (optionnel — non applicable au CDI)';
                            input.disabled = true;
                        } else if (select.value === 'CDD') {
                            input.required = true;
                            label.textContent = 'Date fin (obligatoire pour un CDD)';
                            input.disabled = false;
                        } else { // Essai
                            input.required = true;
                            label.textContent = 'Date fin (obligatoire pour un essai)';
                            input.disabled = false;
                        }
                    }

                    select.addEventListener('change', ajuster);
                    ajuster(); // au chargement
                })();
            </script>


            </div>
            
            <button type="submit" class="btn btn-success mt-3"><i class="bi bi-check-lg me-1"></i> Établir le contrat</button>
        </form>
    </div>

<?php elseif ($candidature['statut_code'] === 'CANDIDAT_EMBAUCHE'): ?>
    <?php $contratInfo = (new \App\Models\ContratModel())->trouverParCandidature((int) $candidature['id']); ?>
    <div class="alert alert-success">
        <i class="bi bi-check2-circle me-1"></i> <strong>Candidat embauché.</strong> Le contrat a été établi et l'annonce a été clôturée.
    </div>
    <?php if ($contratInfo): ?>
        <div class="card p-4 mb-3">
            <h5 class="mb-3">Détails du contrat</h5>
            <div class="row">
                <div class="col-md-3">
                    <p class="text-muted small mb-1">Type</p>
                    <p class="mb-0 fw-bold"><?= esc($contratInfo['type_libelle']) ?></p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted small mb-1">Salaire</p>
                    <p class="mb-0 fw-bold"><?= number_format($contratInfo['salaire'], 0, ',', ' ') ?> Ar</p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted small mb-1">Date début</p>
                    <p class="mb-0 fw-bold"><?= esc($contratInfo['date_debut']) ?></p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted small mb-1">Date fin</p>
                    <p class="mb-0 fw-bold"><?= esc($contratInfo['date_fin'] ?? '—') ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php elseif ($candidature['statut_code'] === 'CANDIDAT_NON_RETENU'): ?>
    <div class="alert alert-secondary">
        <i class="bi bi-x-circle me-1"></i> <strong>Candidat non retenu.</strong> La candidature est clôturée.
    </div>

<?php endif; ?>

<?= $this->endSection() ?>