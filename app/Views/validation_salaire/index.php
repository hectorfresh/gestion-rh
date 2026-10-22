<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Validation salariale / <?= esc($etape) ?></div>
        <h1 class="page-title">Demandes de salaire</h1>
        <p class="page-description">
        Le DG intervient uniquement pour la décision finale.
        </p>
    </div>
</header>

<?php if (empty($validations)): ?>
    <div class="alert alert-light border">Aucune demande en attente.</div>
<?php else: ?>
    <?php foreach ($validations as $validation): ?>
        <section class="content-card mb-3">
            <div class="content-card-body">
                <h3 class="h5"><?= esc($validation['candidat_prenom'] . ' ' . $validation['candidat_nom']) ?></h3>
                <p class="mb-1"><?= esc($validation['annonce_titre']) ?></p>
                <p class="mb-3"><strong>Proposition :</strong> <?= number_format((float) $validation['salaire_propose'], 0, ',', ' ') ?> Ar</p>
                <?php if (!($lectureSeule ?? false)): ?>
                    <form method="post" action="/validation-salaire/<?= strtolower($etape) ?>/<?= $validation['id'] ?>" class="row g-2">
                        <div class="col-md-7"><input type="text" name="commentaire" class="form-control" placeholder="Commentaire"></div>
                        <div class="col-md-2"><button name="decision" value="valider" class="btn btn-success w-100">Valider</button></div>
                        <div class="col-md-2"><button name="decision" value="refuser" class="btn btn-danger w-100">Refuser</button></div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-success mb-0">
                        Validée par le DG : cette décision est globale et s’applique à tous les RH et Finance.
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
