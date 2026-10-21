<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2>Créer un compte candidat</h2>
<p><em>Vous remplirez votre CV au moment de postuler.</em></p>
<form method="post" action="/candidat/register">
    <p><label>Nom :</label><br><input type="text" name="nom" required style="width:100%"></p>
    <p><label>Prénom :</label><br><input type="text" name="prenom" required style="width:100%"></p>
    <p><label>Email :</label><br><input type="email" name="email" required style="width:100%"></p>
    <p><label>Mot de passe :</label><br><input type="password" name="mot_de_passe" required style="width:100%"></p>
    <p><label>Téléphone :</label><br><input type="text" name="telephone" required style="width:100%"></p>
    <p><button type="submit" style="width:100%">Créer mon compte</button></p>
</form>
<p style="text-align:center;"><a href="/candidat/login">Déjà inscrit ? Se connecter</a></p>
<?= $this->endSection() ?>