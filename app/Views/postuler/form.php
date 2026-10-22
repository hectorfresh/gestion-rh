<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<header class="page-header">
    <div>
        <div class="page-eyebrow">Candidature / Postuler</div>
        <h1 class="page-title">Postuler — <?= esc($annonce['titre']) ?></h1>
        <p class="page-description">Complétez votre CV et votre parcours.</p>
    </div>
</header>

<section class="content-card mb-4">
    <div class="content-card-header">
        <div>
            <h2 class="content-card-title">Champs du CV</h2>
            <div class="content-card-description">Répondez aux questions du référentiel RH.</div>
        </div>
    </div>
    <div class="content-card-body">
        <form method="post" action="/postuler/<?= $annonce['id'] ?>" id="form-postuler">
            <?php foreach ($champs as $c): ?>
                <div class="mb-3">
                    <label class="form-label"><?= esc($c['libelle']) ?></label>
                    <?php if ($c['type'] === 'liste'): ?>
                        <select name="champ_<?= $c['id'] ?>" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach ($c['options'] as $o): ?>
                                <option value="<?= $o['id'] ?>"><?= esc($o['valeur']) ?></option>
                            <?php endforeach; ?>

                            <hr class="my-4">
                            <h3 class="content-card-title mb-3" style="font-size:1.1rem;">Questionnaire de présélection</h3>
                            <p class="text-muted small">Répondez Oui ou Non. Une réponse Non sera vérifiée par le RH.</p>
                            <?php foreach ($questions as $question): ?>
                                <div class="mb-3">
                                    <label class="form-label d-block"><?= esc($question['libelle']) ?></label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="question_<?= $question['id'] ?>" value="1" required>
                                        <label class="form-check-label">Oui</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="question_<?= $question['id'] ?>" value="0" required>
                                        <label class="form-check-label">Non</label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <textarea name="champ_<?= $c['id'] ?>" rows="3" class="form-control"></textarea>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <hr class="my-4">
            <h3 class="content-card-title mb-3" style="font-size:1.1rem;">Parcours (formation / expérience)</h3>
            <p class="text-muted small">Ajoutez un ou plusieurs parcours. Cliquez sur « + » pour en ajouter.</p>

            <div id="parcours-container">
                <!-- gabarit d'une ligne -->
            </div>
            <button type="button" id="btn-add-parcours" class="btn btn-outline-primary btn-sm mb-3">
                + Ajouter un parcours
            </button>

            <div class="btn-list">
                <button type="submit" class="btn btn-primary">Envoyer ma candidature</button>
                <a href="/annonces/<?= $annonce['id'] ?>" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</section>

<template id="tpl-parcours">
    <div class="card p-3 mb-3 parcours-item">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>Parcours</strong>
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-parcours">−</button>
        </div>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label small">Type</label>
                <select name="parcours_type[]" class="form-select">
                    <option value="formation">Formation</option>
                    <option value="experience">Expérience</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small">Titre / Diplôme / Poste</label>
                <input type="text" name="parcours_titre[]" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small">Établissement / Entreprise</label>
                <input type="text" name="parcours_lieu[]" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Date début</label>
                <input type="date" name="parcours_debut[]" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Date fin</label>
                <input type="date" name="parcours_fin[]" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label small">Description</label>
                <textarea name="parcours_desc[]" rows="2" class="form-control"></textarea>
            </div>
        </div>
    </div>
</template>

<script>
    const container = document.getElementById('parcours-container');
    const tpl = document.getElementById('tpl-parcours');
    function addParcours() {
        const node = tpl.content.cloneNode(true);
        const item = node.querySelector('.parcours-item');
        item.querySelector('.btn-remove-parcours').addEventListener('click', () => item.remove());
        container.appendChild(item);
    }
    document.getElementById('btn-add-parcours').addEventListener('click', addParcours);
    // ajoute une première ligne par défaut
    addParcours();
</script>
<?= $this->endSection() ?>