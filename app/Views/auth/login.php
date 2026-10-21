<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2 style="text-align:center;">🔐 Connexion</h2>

<form method="post" action="/login">
    <p>
        <label>Email :</label><br>
        <input type="email" name="email" style="width:100%;" required>
    </p>
    <p>
        <label>Mot de passe :</label><br>
        <input type="password" name="password" style="width:100%;" required>
    </p>
    <p style="text-align:center;">
        <button type="submit" style="width:100%;">Se connecter</button>
    </p>
</form>

<p style="font-size:12px; color:#666; border-top:1px solid #ddd; padding-top:10px;">
    <strong>Comptes de test</strong> (mot de passe : <em>password</em>) :<br>
    manager@test.com · rh@test.com · dg@test.com
</p>

<?= $this->endSection() ?>