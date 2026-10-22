<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <section class="content-card mt-4">
            <div class="content-card-header">
                <div>
                    <h2 class="content-card-title">Créer un compte candidat</h2>
                    <div class="content-card-description">Vous remplirez votre CV au moment de postuler.</div>
                </div>
            </div>
            <div class="content-card-body">
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
        </section>
    </div>
</div>
<?= $this->endSection() ?>