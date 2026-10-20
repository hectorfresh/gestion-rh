
-- =====================================================
-- BASE DE DONNEES DE GESTION DES RESSOURCES HUMAINES
-- =====================================================

DROP TABLE departement,type_finance,poste,statut,role,gender,competence,type_contrat,utilisateur,candidat,cv,diplome_cv,experience,cv_competence,besoin,historique_statut_besoin,annonce,candidature,historique_c
andidature,contrat,employe,finance;



CREATE DATABASE rh;

-- Connectez-vous a la base rh avant de continuer.
-- Dans psql :
-- \c rh

-- =====================================================
-- 1. TABLES DE REFERENCE
-- =====================================================

CREATE TABLE departement (
    id SERIAL PRIMARY KEY,
    nom TEXT
);

CREATE TABLE type_finance (
    id SERIAL PRIMARY KEY,
    libelle TEXT NOT NULL
);

CREATE TABLE poste (
    id SERIAL PRIMARY KEY,
    libelle TEXT NOT NULL
);

CREATE TABLE statut (
    id SERIAL PRIMARY KEY,
    libelle TEXT NOT NULL
);

CREATE TABLE role (
    id SERIAL PRIMARY KEY,
    libelle TEXT NOT NULL
);

CREATE TABLE gender (
    id SERIAL PRIMARY KEY,
    libelle TEXT NOT NULL
);

CREATE TABLE diplome (
    id SERIAL PRIMARY KEY,
    libelle TEXT
);

CREATE TABLE competence (
    id SERIAL PRIMARY KEY,
    libelle TEXT NOT NULL UNIQUE
);

CREATE TABLE type_contrat (
    id SERIAL PRIMARY KEY,
    libelle TEXT NOT NULL
);

-- =====================================================
-- 2. UTILISATEURS
-- =====================================================

CREATE TABLE utilisateur (
    id SERIAL PRIMARY KEY,
    nom TEXT NOT NULL,
    role_id INTEGER,
    email TEXT NOT NULL,
    mdp TEXT NOT NULL,

    CONSTRAINT fk_utilisateur_role
        FOREIGN KEY (role_id) REFERENCES role(id)
);

-- =====================================================
-- 3. CANDIDATS
-- =====================================================

CREATE TABLE candidat (
    id SERIAL PRIMARY KEY,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    gender_id INTEGER NOT NULL,
    email TEXT NOT NULL UNIQUE,
    mdp TEXT NOT NULL,

    CONSTRAINT fk_candidat_gender
        FOREIGN KEY (gender_id) REFERENCES gender(id)
);

-- =====================================================
-- 4. CV
-- =====================================================

CREATE TABLE cv (
    id SERIAL PRIMARY KEY,
    candidat_id INTEGER NOT NULL UNIQUE,
    chemin_fichier TEXT,
    date_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_cv_candidat
        FOREIGN KEY (candidat_id) REFERENCES candidat(id)
);

CREATE TABLE diplome_cv (
    id SERIAL PRIMARY KEY,
    cv_id INTEGER NOT NULL,
    diplome_id INTEGER NOT NULL,
    etablissement TEXT,
    annee_obtention INTEGER,

    CONSTRAINT fk_diplome_cv_cv
        FOREIGN KEY (cv_id) REFERENCES cv(id),

    CONSTRAINT fk_diplome_cv_diplome
        FOREIGN KEY (diplome_id) REFERENCES diplome(id)
);

CREATE TABLE experience (
    id SERIAL PRIMARY KEY,
    cv_id INTEGER NOT NULL,
    poste_occupe TEXT NOT NULL,
    entreprise TEXT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE,
    description TEXT,

    CONSTRAINT fk_experience_cv
        FOREIGN KEY (cv_id) REFERENCES cv(id),

    CONSTRAINT chk_dates_experience
        CHECK (date_fin IS NULL OR date_fin >= date_debut)
);

CREATE TABLE cv_competence (
    id SERIAL PRIMARY KEY,
    cv_id INTEGER NOT NULL,
    competence_id INTEGER NOT NULL,
    niveau TEXT,


    CONSTRAINT fk_cv_competence_cv
        FOREIGN KEY (cv_id) REFERENCES cv(id),

    CONSTRAINT fk_cv_competence_competence
        FOREIGN KEY (competence_id) REFERENCES competence(id)
);

-- =====================================================
-- 5. BESOINS DE RECRUTEMENT
-- =====================================================

CREATE TABLE besoin (
    id SERIAL PRIMARY KEY,
    statut_id INTEGER,
    departement_id INTEGER,
    utilisateur_id INTEGER,
    poste_id INTEGER,
    descriptions TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_souhaitee TIMESTAMP NOT NULL,
    montant_min NUMERIC(12,2),
    montant_max NUMERIC(12,2),

    CONSTRAINT fk_besoin_poste
        FOREIGN KEY (poste_id) REFERENCES poste(id),

    CONSTRAINT fk_besoin_statut
        FOREIGN KEY (statut_id) REFERENCES statut(id),

    CONSTRAINT fk_besoin_departement
        FOREIGN KEY (departement_id) REFERENCES departement(id),

    CONSTRAINT fk_besoin_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id)
);

CREATE TABLE historique_statut_besoin (
    id SERIAL PRIMARY KEY,
    besoin_id INTEGER,
    statut_id INTEGER,
    utilisateur_id INTEGER,
    daty TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_historique_besoin
        FOREIGN KEY (besoin_id) REFERENCES besoin(id),

    CONSTRAINT fk_historique_statut
        FOREIGN KEY (statut_id) REFERENCES statut(id),

    CONSTRAINT fk_historique_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id)
);

-- =====================================================
-- 6. ANNONCES
-- =====================================================

CREATE TABLE annonce (
    id SERIAL PRIMARY KEY,
    daty TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    designation TEXT,
    besoin_id INTEGER,

    CONSTRAINT fk_annonce_besoin
        FOREIGN KEY (besoin_id) REFERENCES besoin(id)
);

-- =====================================================
-- 7. CANDIDATURES
-- =====================================================

CREATE TABLE candidature (
    id SERIAL PRIMARY KEY,
    statut_id INTEGER,
    daty TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    candidat_id INTEGER,
    annonce_id INTEGER,
    salaire_estime NUMERIC(12,2),

    CONSTRAINT fk_candidature_candidat
        FOREIGN KEY (candidat_id) REFERENCES candidat(id),

    CONSTRAINT fk_candidature_statut
        FOREIGN KEY (statut_id) REFERENCES statut(id),

    CONSTRAINT fk_candidature_annonce
        FOREIGN KEY (annonce_id) REFERENCES annonce(id)
);

CREATE TABLE historique_candidature (
    id SERIAL PRIMARY KEY,
    candidature_id INTEGER NOT NULL,
    statut_id INTEGER NOT NULL,
    daty TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    commentaire TEXT,

    CONSTRAINT fk_historique_candidature
        FOREIGN KEY (candidature_id) REFERENCES candidature(id),

    CONSTRAINT fk_historique_statut
        FOREIGN KEY (statut_id) REFERENCES statut(id)
);

-- =====================================================
-- 8. CONTRATS
-- =====================================================

CREATE TABLE contrat (
    id SERIAL PRIMARY KEY,
    daty_debut TIMESTAMP,
    daty_fin TIMESTAMP,
    candidature_id INTEGER,
    type_contrat_id INTEGER,
    salaire NUMERIC(12,2),

    CONSTRAINT fk_contrat_candidature
        FOREIGN KEY (candidature_id) REFERENCES candidature(id),

    CONSTRAINT fk_contrat_type
        FOREIGN KEY (type_contrat_id) REFERENCES type_contrat(id)
);

-- =====================================================
-- 9. EMPLOYES
-- =====================================================

CREATE TABLE employe (
    id SERIAL PRIMARY KEY,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    dtn DATE,
    contrat_id INTEGER,

    CONSTRAINT fk_employe_contrat
        FOREIGN KEY (contrat_id) REFERENCES contrat(id)
);

-- =====================================================
-- 10. FINANCEMENTS
-- =====================================================

CREATE TABLE finance (
    id SERIAL PRIMARY KEY,
    type_id INTEGER,
    montant NUMERIC(12,2),
    annee INTEGER,

    CONSTRAINT fk_finance_type
        FOREIGN KEY (type_id) REFERENCES type_finance(id)
);

