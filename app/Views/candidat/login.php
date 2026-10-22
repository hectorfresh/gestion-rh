<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center" style="margin-top: 40px;">
    <div class="col-md-5">
        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2 class="content-card-title">Connexion candidat</h2>
                    <div class="content-card-description">Accédez à votre espace candidat.</div>
                </div>
            </div>
            <div class="content-card-body">
                <form method="post" action="/candidat/login">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="mot_de_passe" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
                <p class="text-center mt-3 mb-0">
                    <a href="/candidat/register">Pas encore de compte ? S'inscrire</a>
                </p>
            </div>
        </section>
    </div>
</div>
<?= $this->endSection() ?>