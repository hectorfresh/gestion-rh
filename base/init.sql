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
    nombre_postes INT NOT NULL DEFAULT 1,
    cout_engage DECIMAL(15,2) NOT NULL,
    date_creation DATETIME NOT NULL,
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
    candidature_id INT NOT NULL UNIQUE,
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