-- =====================================================
-- data.sql : données de départ
-- =====================================================
USE gestion_besoins;

-- Rôles
INSERT INTO role (id, libelle) VALUES
    (1, 'Manager'),
    (2, 'RH'),
    (3, 'DG');

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