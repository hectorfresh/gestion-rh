<!DOCTYPE html>
<html>
<head>
    <title>Consulter le CV</title>
</head>
<body>

    <?= view('navbar') ?>

    <div class="contenu">

        <h1>CV du candidat</h1>




            <?php if (session()->getFlashdata('message')): ?>
                <p><?= esc(session()->getFlashdata('message')) ?></p>
            <?php endif; ?>

            <h2>Décision RH</h2>

            <form method="post"
                action="<?= site_url('rh/candidature/entretien/' . $candidature['id']) ?>">

                <?= csrf_field() ?>

                <button type="submit">Retenir pour un entretien</button>

            </form>

            <br>

            <form method="post"
                action="<?= site_url('rh/candidature/refuser/' . $candidature['id']) ?>">

                <?= csrf_field() ?>

                <button type="submit">Refuser la candidature</button>

            </form>






        <h2>
            <?= esc($candidature['prenom']) ?>
            <?= esc($candidature['nom']) ?>
        </h2>

        <p>
            <strong>Email :</strong>
            <?= esc($candidature['email']) ?>
        </p>

        <p>
            <strong>Annonce :</strong>
            <?= esc($candidature['designation']) ?>
        </p>

        <hr>

        <h2>Expériences professionnelles</h2>

        <?php if (empty($experiences)): ?>

            <p>Aucune expérience renseignée.</p>

        <?php else: ?>

            <?php foreach ($experiences as $experience): ?>

                <h3><?= esc($experience['poste_occupe']) ?></h3>

                <p>
                    Entreprise :
                    <?= esc($experience['entreprise']) ?>
                </p>

                <p>
                    Du <?= esc($experience['date_debut']) ?>
                    au <?= esc($experience['date_fin'] ?? 'En cours') ?>
                </p>

                <p><?= esc($experience['description'] ?? '') ?></p>

            <?php endforeach; ?>

        <?php endif; ?>

        <hr>

        <h2>Diplômes</h2>

        <?php if (empty($diplomes)): ?>

            <p>Aucun diplôme renseigné.</p>

        <?php else: ?>

            <?php foreach ($diplomes as $diplome): ?>

                <p>
                    <?= esc($diplome['libelle']) ?> -
                    <?= esc($diplome['etablissement'] ?? '') ?> -
                    <?= esc($diplome['annee_obtention'] ?? '') ?>
                </p>

            <?php endforeach; ?>

        <?php endif; ?>

        <hr>

        <h2>Compétences</h2>

        <?php if (empty($competences)): ?>

            <p>Aucune compétence renseignée.</p>

        <?php else: ?>

            <?php foreach ($competences as $competence): ?>

                <p>
                    <?= esc($competence['libelle']) ?>
                    - Niveau : <?= esc($competence['niveau'] ?? 'Non précisé') ?>
                </p>

            <?php endforeach; ?>

        <?php endif; ?>

        <br>

        <a href="<?= site_url('rh/candidatures') ?>">
            Retour aux candidatures
        </a>

    </div>

</body>
</html>