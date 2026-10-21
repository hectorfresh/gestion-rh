<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4 mt-4">
            <h3 class="mb-4">Créer un compte candidat</h3>
            <p class="text-muted small">Vous remplirez votre CV au moment de postuler.</p>
            <form method="post" action="/candidat/register">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="prenom" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="mot_de_passe" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-4">Créer mon compte</button>
            </form>
            <p class="text-center mt-3 mb-0"><a href="/candidat/login">Déjà inscrit ? Se connecter</a></p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>