-- =====================================================
-- data.sql : données de départ
-- =====================================================
USE gestion_besoins;

-- Rôles
INSERT INTO role (id, libelle) VALUES
    (1, 'Manager'),
    (2, 'RH'),
    (3, 'DG');

INSERT INTO departement (code, libelle) VALUES
    ('IT', 'Informatique'),
    ('FIN', 'Finance'),
    ('RH', 'Ressources humaines'),
    ('COM', 'Commercial');

-- Utilisateurs (mot de passe = "password" pour tous, à changer après)
-- Hash bcrypt connu pour "password"
INSERT INTO utilisateur (id, nom, email, mot_de_passe, role_id) VALUES
    (1, 'Manager Test', 'manager@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
    (2, 'RH Test',      'rh@test.com',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
    (3, 'DG Test',      'dg@test.com',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3);

-- Postes
INSERT INTO poste (id, libelle, salaire_min, salaire_max) VALUES
    (1, 'Développeur',      9000.00, 14000.00),
    (2, 'Comptable',        6000.00,  9000.00),
    (3, 'Commercial',       5000.00,  7000.00),
    (4, 'Designer',         7000.00, 11000.00),
    (5, 'Chef de projet',  15000.00, 20000.00);
-- Statuts
INSERT INTO statut_besoin (id, code, libelle) VALUES
    (1, 'EN_ATTENTE_RH',      'En attente validation RH'),
    (2, 'REFUSE_RH',          'Refusé par le RH'),
    (3, 'REFUSE_FINANCE',     'Refusé par la finance (budget)'),
    (4, 'EN_ATTENTE_DG',      'En attente validation DG'),
    (5, 'REFUSE_DG',          'Refusé par le DG'),
    (6, 'VALIDE',             'Validé (en attente création annonce)'),
    (7, 'RECRUTEMENT_OUVERT', 'Recrutement ouvert');

-- Étapes de validation
INSERT INTO etape_validation (id, libelle) VALUES
    (1, 'RH'),
    (2, 'FINANCE'),
    (3, 'DG');

-- Décisions
INSERT INTO decision (id, libelle) VALUES
    (1, 'Validé'),
    (2, 'Refusé');

-- Budget global initial
INSERT INTO budget (id, montant_total) VALUES
    (1, 50000000.00);



INSERT INTO statut_candidature (id, code, libelle) VALUES
    (1,  'EN_ATTENTE',            'En attente'),
    (11, 'REPONSES_A_VERIFIER',   'Réponses à vérifier par le RH'),
    (12, 'SALAIRE_ATTENTE_FINANCE','Salaire en attente de validation Finance'),
    (13, 'SALAIRE_ATTENTE_DG',     'Salaire en attente de validation DG'),
    (14, 'QUESTIONNAIRE_CONTRAT',  'Questionnaire contrat à remplir'),
    (15, 'ATTENTE_VALIDATION_RH',  'Réponse candidat à valider par le RH'),
    (16, 'CONTRAT_PRET',           'Contrat prêt à être généré'),
    (2,  'ESSAI_PROPOSE',         'Essai proposé'),
    (3,  'ESSAI_REFUSE',          'Essai refusé par le candidat'),
    (4,  'ESSAI_EFFECTUE',        'Essai effectué'),
    (5,  'CONDITIONS_PROPOSEES',  'Conditions proposées au candidat'),
    (6,  'SALAIRE_EN_NEGOCIATION','Salaire en négociation'),
    (7,  'CONDITIONS_ACCEPTEES',  'Conditions acceptées'),
    (8,  'CONDITIONS_REFUSEES',   'Conditions refusées'),
    (9,  'CANDIDAT_EMBAUCHE',     'Candidat embauché'),
    (10, 'CANDIDAT_NON_RETENU',   'Candidat non retenu');


INSERT INTO type_contrat (id, libelle) VALUES
    (1, 'CDI'),
    (2, 'CDD'),
    (3, 'Essai');


-- mot de passe : password
INSERT INTO candidat (nom, prenom, email, mot_de_passe, telephone,
                      diplome, annees_experience, competences, motivation)
VALUES (
    'Dupont', 'Marie', 'marie@test.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    '0340000000', 'Master Informatique', 3,
    'PHP, MySQL, JavaScript',
    'Je souhaite rejoindre votre équipe.'
);



USE gestion_besoins;

-- 1) Retirer les champs CV du compte candidat (ils deviennent dans reponse_cv)
ALTER TABLE candidat
    DROP COLUMN diplome,
    DROP COLUMN annees_experience,
    DROP COLUMN competences,
    DROP COLUMN motivation;

-- 2) Référentiel des questions du CV guidé
CREATE TABLE question_cv (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    ordre INT NOT NULL
);

-- 3) Réponses par candidature
CREATE TABLE reponse_cv (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidature_id INT NOT NULL,
    question_cv_id INT NOT NULL,
    valeur TINYINT(1) NOT NULL,       -- 1 = oui, 0 = non
    UNIQUE KEY uk_reponse (candidature_id, question_cv_id),
    FOREIGN KEY (candidature_id) REFERENCES candidature(id),
    FOREIGN KEY (question_cv_id) REFERENCES question_cv(id)
);

-- 4) Commentaire libre du candidat (optionnel)
ALTER TABLE candidature ADD COLUMN commentaire TEXT NULL AFTER salaire_final;

-- 5) Questions de base
INSERT INTO question_cv (libelle, ordre) VALUES
    ('Avez-vous un diplôme Bac+3 ou supérieur ?', 1),
    ('Avez-vous plus de 2 ans d''expérience dans ce domaine ?', 2),
    ('Maîtrisez-vous PHP ?', 3),
    ('Maîtrisez-vous MySQL ?', 4),
    ('Êtes-vous disponible immédiatement ?', 5),
    ('Acceptez-vous le télétravail ?', 6),
    ('Avez-vous le permis de conduire ?', 7);




INSERT INTO champ_cv (id, libelle, type, ordre) VALUES
    (1, 'Diplôme le plus élevé',  'liste', 1),
    (2, 'Années d''expérience',   'liste', 2),
    (5, 'Disponibilité',          'liste', 5),
    (6, 'Permis de conduire',     'liste', 6),
    (7, 'Présentation rapide',    'texte', 7);

INSERT INTO option_champ_cv (champ_cv_id, valeur, ordre) VALUES
    -- 1) Diplôme
    (1, 'BEP / CAP', 1),
    (1, 'Baccalauréat', 2),
    (1, 'Bac+2 (BTS / DUT)', 3),
    (1, 'Bac+3 (Licence)', 4),
    (1, 'Bac+5 (Master)', 5),
    (1, 'Doctorat', 6),
    -- 2) Expérience
    (2, 'Débutant (0-1 an)', 1),
    (2, 'Junior (1-3 ans)', 2),
    (2, 'Confirmé (3-5 ans)', 3),
    (2, 'Senior (5 ans et +)', 4),
     -- 5) Disponibilité
    (5, 'Immédiate', 1),
    (5, 'Sous 1 mois', 2),
    (5, 'Sous 3 mois', 3),
    -- 6) Permis
    (6, 'Oui', 1),
    (6, 'Non', 2);


INSERT INTO champ_cv (libelle, type, ordre) VALUES ('Anglais courant', 'liste', 8);
INSERT INTO option_champ_cv (champ_cv_id, valeur, ordre) 
VALUES (LAST_INSERT_ID(), 'Oui', 1), (LAST_INSERT_ID(), 'Non', 2);