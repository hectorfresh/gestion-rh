USE gestion_besoins;

INSERT IGNORE INTO statut_candidature (code, libelle) VALUES
    ('EN_ATTENTE', 'En attente'),
    ('SALAIRE_ATTENTE_FINANCE', 'Salaire en attente de validation Finance'),
    ('SALAIRE_ATTENTE_DG', 'Salaire en attente de validation DG');

CREATE TABLE IF NOT EXISTS validation_salaire (
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

INSERT IGNORE INTO statut_candidature (code, libelle) VALUES
    ('QUESTIONNAIRE_CONTRAT', 'Questionnaire contrat à remplir'),
    ('ATTENTE_VALIDATION_RH', 'Réponse candidat à valider par le RH'),
    ('CONTRAT_PRET', 'Contrat prêt à être généré');

CREATE TABLE IF NOT EXISTS question_contrat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidature_id INT NOT NULL,
    libelle VARCHAR(255) NOT NULL,
    proposition TEXT NOT NULL,
    ordre INT NOT NULL DEFAULT 1,
    FOREIGN KEY (candidature_id) REFERENCES candidature(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS reponse_contrat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_contrat_id INT NOT NULL UNIQUE,
    reponse TINYINT(1) NOT NULL,
    remarque TEXT NULL,
    date_reponse DATETIME NOT NULL,
    FOREIGN KEY (question_contrat_id) REFERENCES question_contrat(id) ON DELETE CASCADE
);
