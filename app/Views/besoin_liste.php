
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un produit</title>

    <link rel="stylesheet" href="assets/dist/css/tabler.min.css">
</head>
<body>
<div class="page">

    <!-- En-tête -->
    <header class="navbar navbar-expand-md d-print-none">
        <div class="container-xl">
            <a class="navbar-brand" href="index.php">
                Mon application
            </a>
        </div>
    </header>

    <div class="page-wrapper">

        <!-- Titre -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="page-pretitle">Produits</div>
                        <h2 class="page-title">Ajouter un produit</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="page-body">
            <div class="container-xl">
                <div class="row row-cards">
                    <div class="col-lg-8">

                        <form action="" method="POST" class="card">

                            <div class="card-header">
                                <h3 class="card-title">
                                    Informations du produit
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="row g-3">

                                    <!-- Nom -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Nom du produit
                                        </label>
                                        <input type="text"
                                               name="nom"
                                               class="form-control"
                                               placeholder="Ex. Ordinateur"
                                               required>
                                    </div>

                                    <!-- Référence -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Référence
                                        </label>
                                        <input type="text"
                                               name="reference"
                                               class="form-control"
                                               placeholder="Ex. PROD-001"
                                               required>
                                    </div>

                                    <!-- Catégorie -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Catégorie
                                        </label>
                                        <select name="categorie"
                                                class="form-select"
                                                required>
                                            <option value="">
                                                Sélectionner une catégorie
                                            </option>
                                            <option value="informatique">
                                                Informatique
                                            </option>
                                            <option value="accessoires">
                                                Accessoires
                                            </option>
                                            <option value="bureautique">
                                                Bureautique
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Prix -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Prix (Ar)
                                        </label>
                                        <input type="number"
                                               name="prix"
                                               class="form-control"
                                               min="0"
                                               step="1"
                                               placeholder="500000"
                                               required>
                                    </div>

                                    <!-- Quantité -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Quantité en stock
                                        </label>
                                        <input type="number"
                                               name="quantite"
                                               class="form-control"
                                               min="0"
                                               step="1"
                                               value="0"
                                               required>
                                    </div>

                                    <!-- Statut -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Statut
                                        </label>
                                        <select name="statut"
                                                class="form-select">
                                            <option value="actif">
                                                Actif
                                            </option>
                                            <option value="inactif">
                                                Inactif
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-12">
                                        <label class="form-label">
                                            Description
                                        </label>
                                        <textarea name="description"
                                                  class="form-control"
                                                  rows="4"
                                                  placeholder="Description du produit..."></textarea>
                                    </div>

                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="card-footer text-end">
                                <a href="index.php"
                                   class="btn btn-link">
                                    Annuler
                                </a>

                                <button type="reset"
                                        class="btn btn-outline-secondary">
                                    Réinitialiser
                                </button>

                                <button type="submit"
                                        name="enregistrer"
                                        class="btn btn-primary">
                                    Enregistrer le produit
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <footer class="footer footer-transparent d-print-none">
            <div class="container-xl">
                <div class="text-center text-secondary">
                    © <?= date('Y') ?> Mon application
                </div>
            </div>
        </footer>

    </div>
</div>

<script src="assets/dist/js/tabler.min.js"></script>
</body>
</html>
