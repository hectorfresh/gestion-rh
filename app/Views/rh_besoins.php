<!DOCTYPE html>
<html>
<head>
    <title>Validation RH</title>
</head>
<body>
<?= view('navbar') ?>

<div class="contenu">
    <h1>Validation des besoins par les RH</h1>

    <?php if (session()->getFlashdata('message')): ?>
        <p><?= esc(session()->getFlashdata('message')) ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('erreur')): ?>
        <p><?= esc(session()->getFlashdata('erreur')) ?></p>
    <?php endif; ?>

    <form action="<?= site_url('rh/traiter') ?>" method="post">

        <?= csrf_field() ?>

        <label>Besoin :</label>

        <select name="besoin_id" required>
            <option value="">Choisir un besoin</option>

            <?php foreach ($besoins as $besoin): ?>
                <option value="<?= $besoin['id'] ?>">
                    <?= esc($besoin['poste']) ?>
                    - <?= esc($besoin['descriptions']) ?>
                    - <?= esc($besoin['montant_max']) ?> Ar/mois
                </option>
            <?php endforeach; ?>

        </select>

        <br><br>

        <button type="submit" name="action" value="valider">
            Valider
        </button>

        <button type="submit" name="action" value="refuser">
            Refuser
        </button>

    </form>

    <br>

    <a href="<?= site_url('accueil') ?>">Accueil</a>
</div>
</body>
</html>