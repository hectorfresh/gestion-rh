<!DOCTYPE html>
<html>
<head>
    <title>Créer un besoin</title>
</head>
<body>
<?= view('navbar') ?>
<div class="contenu">
    <h1>Créer un besoin de recrutement</h1>

    <?php if (session()->getFlashdata('erreur')): ?>
        <p><?= esc(session()->getFlashdata('erreur')) ?></p>
    <?php endif; ?>

    <form action="<?= site_url('besoin/enregistrer') ?>" method="post">

        <?= csrf_field() ?>

        <label>Poste :</label>

        <select name="poste_id" required>
            <option value="">Choisir un poste</option>

            <?php foreach ($postes as $poste): ?>
                <option value="<?= $poste['id'] ?>">
                    <?= esc($poste['libelle']) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <br><br>

        <label>Description du poste :</label><br>

        <textarea name="descriptions" required></textarea>

        <br><br>

        <label>Date souhaitée :</label>

        <input type="datetime-local"
               name="date_souhaitee"
               required>

        <br><br>

        <label>Salaire mensuel maximal (Ar) :</label>

        <input type="number"
               name="montant_max"
               min="0"
               step="0.01"
               required>

        <br><br>

        <button type="submit">Valider</button>

    </form>
</div>
</body>
</html>