-- =====================================================
-- init.sql : création de la base et des tables
-- =====================================================

DROP DATABASE IF EXISTS gestion_besoins;
CREATE DATABASE gestion_besoins CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_besoins;

-- 1) Rôles des utilisateurs
CREATE TABLE role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- 2) Utilisateurs
CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES role(id)
);

-- 3) Postes (catalogue)

CREATE TABLE poste (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL UNIQUE,
    salaire_min DECIMAL(15,2) NOT NULL,
    salaire_max DECIMAL(15,2) NOT NULL
);

-- 4) Statuts possibles d'un besoin
CREATE TABLE statut_besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) NOT NULL UNIQUE,
    libelle VARCHAR(100) NOT NULL
);

-- 5) Étapes de validation
CREATE TABLE etape_validation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- 6) Décisions (validé / refusé)
CREATE TABLE decision (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- 7) Budget global (une seule ligne)
CREATE TABLE budget (
    id INT AUTO_INCREMENT PRIMARY KEY,
    montant_total DECIMAL(15,2) NOT NULL
);

-- 8) Besoins exprimés par les managers
CREATE TABLE besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    poste_id INT NOT NULL,
    manager_id INT NOT NULL,
    departement_id INT NULL,
    nombre_postes INT NOT NULL DEFAULT 1,
    cout_engage DECIMAL(15,2) NOT NULL,
    date_creation DATETIME NOT NULL,
    date_souhaitee DATE NULL,
    motif VARCHAR(255) NULL,
    statut_id INT NOT NULL,
    FOREIGN KEY (poste_id)   REFERENCES poste(id),
    FOREIGN KEY (manager_id) REFERENCES utilisateur(id),
    FOREIGN KEY (statut_id)  REFERENCES statut_besoin(id)
);

-- 9) Historique des validations (RH, FINANCE, DG)
--    utilisateur_id NULL = validation automatique (finance)
CREATE TABLE validation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    besoin_id INT NOT NULL,
    utilisateur_id INT NULL,
    etape_validation_id INT NOT NULL,
    decision_id INT NOT NULL,
    date_validation DATETIME NOT NULL,
    commentaire TEXT NULL,
    FOREIGN KEY (besoin_id)           REFERENCES besoin(id),
    FOREIGN KEY (utilisateur_id)      REFERENCES utilisateur(id),
    FOREIGN KEY (etape_validation_id) REFERENCES etape_validation(id),
    FOREIGN KEY (decision_id)         REFERENCES decision(id)
);

-- 10) Annonces de recrutement (créées par le RH)

DROP TABLE IF EXISTS annonce;

CREATE TABLE annonce (
    id INT AUTO_INCREMENT PRIMARY KEY,
    besoin_id INT NOT NULL,
    rh_id INT NOT NULL,
    titre VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    salaire_propose DECIMAL(15,2) NOT NULL,
    heure_debut TIME NOT NULL,
    autres_conditions TEXT NULL,
    date_publication DATETIME NOT NULL,
    FOREIGN KEY (besoin_id) REFERENCES besoin(id),
    FOREIGN KEY (rh_id)     REFERENCES utilisateur(id)
);




-- =====================================================
-- Nouvelles tables : candidature
-- =====================================================

-- Référentiel des statuts de candidature
CREATE TABLE statut_candidature (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(40) NOT NULL UNIQUE,
    libelle VARCHAR(100) NOT NULL
);

-- Candidat (identité + CV + compte)
CREATE TABLE candidat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(30) NOT NULL,
    diplome VARCHAR(200) NOT NULL,
    annees_experience INT NOT NULL,
    competences TEXT NOT NULL,
    motivation TEXT NOT NULL
);

-- Candidature : lien candidat ↔ annonce
CREATE TABLE candidature (
    id INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT NOT NULL,
    candidat_id INT NOT NULL,
    statut_id INT NOT NULL,
    salaire_negocie DECIMAL(15,2) NULL,
    date_candidature DATETIME NOT NULL,
    UNIQUE KEY uk_candidature (annonce_id, candidat_id),
    FOREIGN KEY (annonce_id)  REFERENCES annonce(id),
    FOREIGN KEY (candidat_id) REFERENCES candidat(id),
    FOREIGN KEY (statut_id)   REFERENCES statut_candidature(id)
);

CREATE TABLE validation_salaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidature_id INT NOT NULL,
    etape VARCHAR(20) NOT NULL,
    decision VARCHAR(20) NOT NULL,
    salaire_propose DECIMAL(15,2) NOT NULL,
    utilisateur_id INT NULL,
    commentaire TEXT NULL,
    date_validation DATETIME NOT NULL,
    FOREIGN KEY (candidature_id) REFERENCES candidature(id) ON DELETE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id),
    UNIQUE KEY uk_validation_salaire_etape (candidature_id, etape)
);

-- Essai professionnel
CREATE TABLE essai (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidature_id INT NOT NULL UNIQUE,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    commentaire TEXT NULL,
    FOREIGN KEY (candidature_id) REFERENCES candidature(id)
);

-- Référentiel des types de contrat
CREATE TABLE type_contrat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- Contrat final
CREATE TABLE contrat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidature_id INT NOT NULL,
    employe_id INT NULL,
    type_contrat_id INT NOT NULL,
    salaire DECIMAL(15,2) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NULL,
    FOREIGN KEY (candidature_id) REFERENCES candidature(id),
    FOREIGN KEY (type_contrat_id) REFERENCES type_contrat(id)
);

-- Boîte d'envoi (emails simulés, projet offline)
CREATE TABLE email_envoye (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destinataire VARCHAR(150) NOT NULL,
    sujet VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    date_envoi DATETIME NOT NULL
);

ALTER TABLE candidature ADD COLUMN salaire_final DECIMAL(15,2) NULL AFTER salaire_negocie;







-- Référentiel des champs du CV
CREATE TABLE champ_cv (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    type VARCHAR(20) NOT NULL,           -- 'liste' ou 'texte'
    ordre INT NOT NULL
);

-- Options pour les champs de type 'liste'
CREATE TABLE option_champ_cv (
    id INT AUTO_INCREMENT PRIMARY KEY,
    champ_cv_id INT NOT NULL,
    valeur VARCHAR(100) NOT NULL,
    ordre INT NOT NULL,
    FOREIGN KEY (champ_cv_id) REFERENCES champ_cv(id)
);

-- Valeurs du CV pour chaque candidature
CREATE TABLE valeur_cv (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidature_id INT NOT NULL,
    champ_cv_id INT NOT NULL,
    option_champ_cv_id INT NULL,         -- si type='liste'
    valeur_texte TEXT NULL,              -- si type='texte'
    UNIQUE KEY uk_valeur (candidature_id, champ_cv_id),
    FOREIGN KEY (candidature_id) REFERENCES candidature(id),
    FOREIGN KEY (champ_cv_id) REFERENCES champ_cv(id),
    FOREIGN KEY (option_champ_cv_id) REFERENCES option_champ_cv(id)
);



ALTER TABLE annonce ADD COLUMN pourvue TINYINT(1) NOT NULL DEFAULT 0;

-- Parcours (formation / expérience) d'un candidat, saisis lors du postulage
CREATE TABLE IF NOT EXISTS parcours_cv (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidature_id INT NOT NULL,
    type ENUM('formation','experience') NOT NULL,
    titre VARCHAR(200) NOT NULL,
    lieu VARCHAR(200) NOT NULL,
    date_debut DATE NULL,
    date_fin DATE NULL,
    description TEXT NULL,
    FOREIGN KEY (candidature_id) REFERENCES candidature(id) ON DELETE CASCADE
);

-- =====================================================
-- Domaines RH complémentaires
-- =====================================================

CREATE TABLE departement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) NOT NULL UNIQUE,
    libelle VARCHAR(100) NOT NULL UNIQUE,
    actif TINYINT(1) NOT NULL DEFAULT 1
);

ALTER TABLE besoin
    ADD CONSTRAINT fk_besoin_departement
    FOREIGN KEY (departement_id) REFERENCES departement(id);

CREATE TABLE employe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidat_id INT NOT NULL UNIQUE,
    matricule VARCHAR(30) NOT NULL UNIQUE,
    date_embauche DATE NOT NULL,
    statut VARCHAR(30) NOT NULL DEFAULT 'ACTIF',
    created_at DATETIME NOT NULL,
    FOREIGN KEY (candidat_id) REFERENCES candidat(id)
);

ALTER TABLE contrat
    ADD CONSTRAINT fk_contrat_employe
    FOREIGN KEY (employe_id) REFERENCES employe(id);

CREATE TABLE affectation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employe_id INT NOT NULL,
    poste_id INT NOT NULL,
    departement_id INT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NULL,
    FOREIGN KEY (employe_id) REFERENCES employe(id),
    FOREIGN KEY (poste_id) REFERENCES poste(id),
    FOREIGN KEY (departement_id) REFERENCES departement(id)
);

CREATE TABLE entretien (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidature_id INT NOT NULL,
    planifie_par INT NOT NULL,
    date_entretien DATETIME NOT NULL,
    type VARCHAR(30) NOT NULL,
    statut VARCHAR(30) NOT NULL DEFAULT 'PLANIFIE',
    compte_rendu TEXT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (candidature_id) REFERENCES candidature(id) ON DELETE CASCADE,
    FOREIGN KEY (planifie_par) REFERENCES utilisateur(id)
);

CREATE TABLE evaluation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entretien_id INT NOT NULL,
    evaluateur_id INT NOT NULL,
    note DECIMAL(5,2) NOT NULL,
    decision VARCHAR(30) NOT NULL,
    commentaire TEXT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (entretien_id) REFERENCES entretien(id) ON DELETE CASCADE,
    FOREIGN KEY (evaluateur_id) REFERENCES utilisateur(id)
);

CREATE TABLE document_employe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employe_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    nom_fichier VARCHAR(255) NOT NULL,
    chemin VARCHAR(500) NOT NULL,
    statut VARCHAR(30) NOT NULL DEFAULT 'A_VERIFIER',
    depose_le DATETIME NOT NULL,
    FOREIGN KEY (employe_id) REFERENCES employe(id) ON DELETE CASCADE
);

CREATE TABLE onboarding (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employe_id INT NOT NULL UNIQUE,
    date_debut DATE NOT NULL,
    date_fin_prevue DATE NULL,
    statut VARCHAR(30) NOT NULL DEFAULT 'A_PREPARER',
    created_at DATETIME NOT NULL,
    FOREIGN KEY (employe_id) REFERENCES employe(id) ON DELETE CASCADE
);

CREATE TABLE onboarding_tache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    onboarding_id INT NOT NULL,
    libelle VARCHAR(150) NOT NULL,
    responsable VARCHAR(100) NULL,
    echeance DATE NULL,
    statut VARCHAR(30) NOT NULL DEFAULT 'A_FAIRE',
    terminee_le DATETIME NULL,
    FOREIGN KEY (onboarding_id) REFERENCES onboarding(id) ON DELETE CASCADE
);