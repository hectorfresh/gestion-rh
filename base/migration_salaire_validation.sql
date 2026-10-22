USE gestion_besoins;

INSERT IGNORE INTO statut_candidature (code, libelle) VALUES
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
