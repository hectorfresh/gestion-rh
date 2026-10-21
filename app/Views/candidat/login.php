<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2>Connexion candidat</h2>
<form method="post" action="/candidat/login">
    <p><label>Email :</label><br><input type="email" name="email" required style="width:100%"></p>
    <p><label>Mot de passe :</label><br><input type="password" name="mot_de_passe" required style="width:100%"></p>
    <p><button type="submit" style="width:100%">Se connecter</button></p>
</form>
<p style="text-align:center;"><a href="/candidat/register">Pas encore de compte ? S'inscrire</a></p>
<?= $this->endSection() ?>