<!DOCTYPE html>
<html>
<head>
    <title>Mon CV</title>
</head>
<body>
    <?= view('navbarCandidat') ?>
    <div class="contenu-candidat">
        <h1>Compléter mon CV</h1>

        <?php if (session()->getFlashdata('erreur')): ?>
            <p><?= esc(session()->getFlashdata('erreur')) ?></p>
        <?php endif; ?>

    <form action="<?= site_url('candidat/postuler') ?>" method="post">

        <?= csrf_field() ?>

        <input type="hidden" name="annonce_id"
               value="<?= $annonce_id ?>">

        <h2>Expérience professionnelle (facultatif)</h2>

        <label>Poste occupé :</label>
        <input type="text" name="poste_occupe">

        <br><br>

        <label>Entreprise :</label>
        <input type="text" name="entreprise">

        <br><br>

        <label>Date de début :</label>
        <input type="date" name="date_debut">

        <br><br>

        <label>Date de fin :</label>
        <input type="date" name="date_fin">

        <br><br>

        <label>Description :</label><br>
        <textarea name="description"></textarea>

        <br><br>

        <button type="submit">Envoyer ma candidature</button>

    </form>
</div>
</body>
</html>