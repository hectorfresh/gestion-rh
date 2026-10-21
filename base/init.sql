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

CREATE TABLE annonce (
    id INT AUTO_INCREMENT PRIMARY KEY,
    besoin_id INT NOT NULL UNIQUE,
    rh_id INT NOT NULL,
    titre VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    salaire_min DECIMAL(15,2) NOT NULL,
    salaire_max DECIMAL(15,2) NOT NULL,
    date_publication DATETIME NOT NULL,
    FOREIGN KEY (besoin_id) REFERENCES besoin(id),
    FOREIGN KEY (rh_id)     REFERENCES utilisateur(id)
);