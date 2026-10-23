-- =====================================================
-- data2.sql : données de test (contexte Madagascar)
-- À exécuter après init.sql + data.sql + migration_salaire_validation.sql
-- =====================================================
USE gestion_besoins;

-- -----------------------------------------------------
-- Utilisateurs malgaches (mot de passe = "password")
-- -----------------------------------------------------
INSERT IGNORE INTO utilisateur (id, nom, email, mot_de_passe, role_id) VALUES
    (4, 'Rakotoarisoa Jean',   'rakoto.manager@test.mg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
    (5, 'Rabe Nasolo',         'rabe.rh@test.mg',        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
    (6, 'Rasolofo Hery',       'rasolofo.dg@test.mg',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3);

-- -----------------------------------------------------
-- Candidats malgaches
-- -----------------------------------------------------
INSERT IGNORE INTO candidat (id, nom, prenom, email, mot_de_passe, telephone) VALUES
    (2, 'Andriamamonjy', 'Solo',  'solo.andria@test.mg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '034 11 111 11'),
    (3, 'Razafindrakoto', 'Miora','miora.raz@test.mg',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '033 22 222 22'),
    (4, 'Rakotondrabe',   'Hery', 'hery.rak@test.mg',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '032 33 333 33');

-- -----------------------------------------------------
-- Besoins exprimés par le manager malgache (utilisateur 4)
-- -----------------------------------------------------
INSERT IGNORE INTO besoin (id, poste_id, manager_id, departement_id, nombre_postes, cout_engage, date_creation, date_souhaitee, motif, statut_id) VALUES
    (2, 1, 4, 1, 2, 28000.00, '2026-10-01 09:00:00', '2026-12-01', 'Renforcement équipe développement - Antananarivo', 1),
    (3, 2, 4, 2, 1,  9000.00, '2026-10-02 09:00:00', '2026-11-15', 'Comptable supplémentaire - Antananarivo',        6);

-- -----------------------------------------------------
-- Annonce de recrutement (RH malgache = utilisateur 5)
-- -----------------------------------------------------
INSERT IGNORE INTO annonce (id, besoin_id, rh_id, titre, description, salaire_propose, heure_debut, autres_conditions, date_publication, pourvue) VALUES
    (2, 3, 5, 'Comptable', 'Poste de comptable pour le service finance (Toamasina)', 8000.00, '08:00:00', 'CDI, primes selon performance', '2026-10-05 10:00:00', 0);

-- -----------------------------------------------------
-- Candidatures de candidats malgaches sur l'annonce 2
-- -----------------------------------------------------
INSERT IGNORE INTO candidature (id, annonce_id, candidat_id, statut_id, salaire_negocie, date_candidature) VALUES
    (2, 2, 2, 1, NULL, '2026-10-06 11:00:00'),
    (3, 2, 3, 1, NULL, '2026-10-06 12:00:00');

-- -----------------------------------------------------
-- Parcours CV (formation / expérience) pour ces candidatures
-- -----------------------------------------------------
INSERT IGNORE INTO parcours_cv (candidature_id, type, titre, lieu, date_debut, date_fin, description) VALUES
    (2, 'formation',  'Licence en Comptabilité', 'Université d''Antananarivo', '2018-09-01', '2021-06-30', 'Parcours comptabilité et gestion'),
    (2, 'experience', 'Comptable junior',       'Société ABC - Antananarivo', '2021-09-01', '2024-08-31', 'Tenue de comptes, déclarations fiscales'),
    (3, 'formation',  'Master en Finance',      'Université de Toamasina',    '2017-09-01', '2020-06-30', 'Finance d''entreprise'),
    (3, 'experience', 'Assistante comptable',    'Cabinet XYZ - Toamasina',    '2020-09-01', '2024-09-30', 'Saisie comptable, rapprochements bancaires');

-- -----------------------------------------------------
-- Valeurs CV (champs guidés) pour ces candidatures
-- -----------------------------------------------------
INSERT IGNORE INTO valeur_cv (candidature_id, champ_cv_id, option_champ_cv_id, valeur_texte) VALUES
    (2, 1, 4, NULL),   -- Bac+3
    (2, 2, 9, NULL),   -- Confirmé (3-5 ans)
    (2, 5, 11, NULL),  -- Disponibilité immédiate
    (2, 6, 14, NULL),  -- Permis : Oui
    (2, 7, NULL, 'Comptable rigoureuse, à l''aise avec les outils bureautiques.'),
    (3, 1, 5, NULL),   -- Bac+5
    (3, 2, 8, NULL),   -- Junior (1-3 ans)
    (3, 5, 12, NULL),  -- Sous 1 mois
    (3, 6, 15, NULL),  -- Permis : Non
    (3, 7, NULL, 'Passionnée par la finance et l''analyse de données.');

-- -----------------------------------------------------
-- Réponses au questionnaire CV (oui/non)
-- -----------------------------------------------------
INSERT IGNORE INTO reponse_cv (candidature_id, question_cv_id, valeur) VALUES
    (2, 1, 1), (2, 2, 1), (2, 3, 1), (2, 4, 1), (2, 5, 1), (2, 6, 0), (2, 7, 1),
    (3, 1, 1), (3, 2, 1), (3, 3, 0), (3, 4, 1), (3, 5, 0), (3, 6, 1), (3, 7, 0);
