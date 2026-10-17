
-- ============================================
-- 1. POSTES
-- ============================================

INSERT INTO poste (libelle) VALUES
('Développeur web'),
('Comptable'),
('Responsable des ressources humaines'),
('Technicien réseau'),
('Assistant administratif');

-- ============================================
-- 2. BESOINS EN RECRUTEMENT
-- ============================================

INSERT INTO besoin (poste_id, nombre, daty) VALUES
(1, 3, '2026-01-10 09:00:00'),
(2, 2, '2026-01-15 10:00:00'),
(3, 1, '2026-02-01 08:30:00'),
(4, 2, '2026-02-10 14:00:00'),
(5, 1, '2026-03-01 09:00:00');

-- ============================================
-- 3. ANNONCES
-- ============================================

INSERT INTO annonce (daty, designation, besoin_id) VALUES
('2026-01-12 08:00:00', 'Recrutement de développeurs web', 1),
('2026-01-17 09:00:00', 'Recrutement de comptables', 2),
('2026-02-03 10:00:00', 'Recrutement d’un responsable RH', 3),
('2026-02-12 08:30:00', 'Recrutement d’un technicien réseau', 4),
('2026-03-03 09:00:00', 'Recrutement d’un assistant administratif', 5);

-- ============================================
-- 4. RÔLES
-- ============================================

INSERT INTO role (libelle) VALUES
('Manager'),
('RH'),
('Finance'),
('DG');

-- ============================================
-- 5. UTILISATEURS
-- ============================================

INSERT INTO utilisateur (nom, role_id, email, mdp) VALUES
('Jean Dupont', 1, 'manager@entreprise.fr', 'test123'),
('Marie Martin', 2, 'rh@entreprise.fr', 'test123'),
('Pierre Bernard', 3, 'finance@entreprise.fr', 'test123'),
('Sophie Petit', 4, 'dg@entreprise.fr', 'test123');

-- ============================================
-- 6. STATUTS
-- ============================================

INSERT INTO statut (libelle) VALUES
('En attente'),
('Présélectionnée'),
('Entretien'),
('Acceptée'),
('Refusée');

-- ============================================
-- 7. GENRES
-- ============================================

INSERT INTO gender (libelle) VALUES
('Homme'),
('Femme'),
('Autre');

-- ============================================
-- 8. TYPES DE CONTRAT
-- ============================================

INSERT INTO type_contrat (libelle) VALUES
('CDI'),
('CDD'),
('Stage'),
('Freelance');

-- ============================================
-- 9. DIPLÔMES
-- ============================================

INSERT INTO diplome (libelle) VALUES
('Baccalauréat'),
('BTS'),
('Licence'),
('Master'),
('Doctorat');

-- ============================================
-- 10. COMPÉTENCES
-- ============================================

INSERT INTO competence (libelle) VALUES
('HTML et CSS'),
('JavaScript'),
('PHP'),
('PostgreSQL'),
('Python'),
('Comptabilité générale'),
('Gestion des ressources humaines'),
('Communication'),
('Administration système'),
('Réseaux informatiques');

-- ============================================
-- 11. CANDIDATS
-- ============================================

INSERT INTO candidat
(nom, prenom, gender_id, email, mdp)
VALUES
('Dubois', 'Lucas', 1, 'lucas.dubois@exemple.fr', 'test123'),
('Lefebvre', 'Emma', 2, 'emma.lefebvre@exemple.fr', 'test123'),
('Moreau', 'Hugo', 1, 'hugo.moreau@exemple.fr', 'test123'),
('Laurent', 'Chloé', 2, 'chloe.laurent@exemple.fr', 'test123'),
('Simon', 'Nathan', 1, 'nathan.simon@exemple.fr', 'test123'),
('Michel', 'Camille', 2, 'camille.michel@exemple.fr', 'test123'),
('Garcia', 'Louis', 1, 'louis.garcia@exemple.fr', 'test123'),
('Roux', 'Manon', 2, 'manon.roux@exemple.fr', 'test123'),
('Fournier', 'Jules', 1, 'jules.fournier@exemple.fr', 'test123'),
('Girard', 'Inès', 2, 'ines.girard@exemple.fr', 'test123');

-- ============================================
-- 12. CANDIDATURES
-- ============================================

INSERT INTO candidature
(statut_id, daty, candidat_id, annonce_id)
VALUES
(1, '2026-03-05 10:00:00', 1, 1),
(2, '2026-03-06 09:30:00', 2, 1),
(3, '2026-03-07 14:00:00', 3, 1),
(4, '2026-03-08 08:00:00', 4, 2),
(5, '2026-03-09 11:00:00', 5, 2),
(1, '2026-03-10 13:00:00', 6, 3),
(3, '2026-03-11 10:30:00', 7, 4),
(2, '2026-03-12 15:00:00', 8, 5),
(1, '2026-03-13 09:00:00', 9, 3),
(4, '2026-03-14 14:30:00', 10, 4),
(2, '2026-03-15 09:00:00', 1, 2),
(1, '2026-03-16 10:00:00', 2, 5);

-- ============================================
-- 13. HISTORIQUE DES CANDIDATURES
-- ============================================

INSERT INTO historique_candidature
(candidature_id, statut_id, daty, commentaire)
VALUES
(1, 1, '2026-03-05 10:00:00', 'Candidature reçue'),
(2, 1, '2026-03-06 09:30:00', 'Dossier reçu par le service RH'),
(2, 2, '2026-03-07 10:00:00', 'Profil retenu après étude du CV'),
(3, 1, '2026-03-07 14:00:00', 'Candidature enregistrée'),
(3, 2, '2026-03-08 09:00:00', 'CV validé'),
(3, 3, '2026-03-10 14:00:00', 'Entretien programmé'),
(4, 1, '2026-03-08 08:00:00', 'Candidature reçue'),
(4, 2, '2026-03-09 09:00:00', 'Profil présélectionné'),
(4, 3, '2026-03-10 10:00:00', 'Entretien réalisé'),
(4, 4, '2026-03-12 16:00:00', 'Candidature acceptée'),
(5, 1, '2026-03-09 11:00:00', 'Dossier reçu'),
(5, 5, '2026-03-11 15:00:00', 'Candidature non retenue'),
(10, 1, '2026-03-14 14:30:00', 'Candidature reçue'),
(10, 4, '2026-03-16 16:00:00', 'Candidature acceptée');

-- ============================================
-- 14. CV DES CANDIDATS
-- ============================================

INSERT INTO cv (candidat_id, chemin_fichier, date_creation) VALUES
(1, 'fichiers/cv/lucas_dubois.pdf', '2026-02-01 09:00:00'),
(2, 'fichiers/cv/emma_lefebvre.pdf', '2026-02-05 10:00:00'),
(3, 'fichiers/cv/hugo_moreau.pdf', '2026-02-10 11:00:00'),
(4, 'fichiers/cv/chloe_laurent.pdf', '2026-02-15 08:30:00'),
(5, 'fichiers/cv/nathan_simon.pdf', '2026-02-20 14:00:00'),
(6, 'fichiers/cv/camille_michel.pdf', '2026-02-22 09:30:00'),
(7, 'fichiers/cv/louis_garcia.pdf', '2026-02-25 10:00:00'),
(8, 'fichiers/cv/manon_roux.pdf', '2026-03-01 15:00:00'),
(9, 'fichiers/cv/jules_fournier.pdf', '2026-03-02 09:00:00'),
(10, 'fichiers/cv/ines_girard.pdf', '2026-03-03 11:00:00');

-- ============================================
-- 15. DIPLÔMES DES CANDIDATS
-- ============================================

INSERT INTO diplome_cv
(cv_id, diplome_id, etablissement, annee_obtention)
VALUES
(1, 3, 'Université de Lyon', 2022),
(1, 4, 'École supérieure du numérique', 2024),
(2, 4, 'Université de Bordeaux', 2023),
(3, 3, 'Institut universitaire de technologie de Lille', 2021),
(4, 4, 'Université de Paris', 2022),
(5, 2, 'Lycée technique de Nantes', 2020),
(6, 3, 'Université de Toulouse', 2022),
(7, 4, 'École polytechnique de Grenoble', 2024),
(8, 3, 'Université de Strasbourg', 2023),
(9, 2, 'Institut universitaire de technologie de Rennes', 2021),
(10, 4, 'Université de Montpellier', 2024);

-- ============================================
-- 16. EXPÉRIENCES PROFESSIONNELLES
-- ============================================

INSERT INTO experience
(cv_id, poste_occupe, entreprise, date_debut, date_fin, description)
VALUES
(1, 'Développeur web junior', 'HexaTech',
 '2022-01-01', '2023-12-31', 'Création de sites web et maintenance applicative'),

(1, 'Développeur web', 'Digital France',
 '2024-01-01', NULL, 'Développement d’applications web et gestion de bases de données'),

(2, 'Assistante comptable', 'Cabinet Martin',
 '2021-03-01', '2024-02-28', 'Saisie comptable et préparation des rapports financiers'),

(3, 'Développeur backend', 'Informatique Conseil',
 '2022-06-01', '2025-01-31', 'Développement d’API et optimisation SQL'),

(4, 'Assistante RH', 'Groupe Horizon',
 '2021-01-01', '2023-12-31', 'Gestion administrative du personnel et recrutement'),

(5, 'Technicien informatique', 'Services Numériques',
 '2020-05-01', NULL, 'Maintenance informatique et assistance technique'),

(6, 'Assistante administrative', 'Distribution Centrale',
 '2022-01-01', '2025-02-28', 'Gestion des dossiers administratifs'),

(7, 'Administrateur système', 'Réseaux et Solutions',
 '2023-01-01', NULL, 'Administration Linux et supervision réseau'),

(8, 'Chargée de recrutement', 'Talents et Carrières',
 '2023-06-01', NULL, 'Tri des CV et organisation des entretiens'),

(9, 'Comptable junior', 'Finances Associées',
 '2021-09-01', '2024-06-30', 'Suivi des factures et rapprochements bancaires'),

(10, 'Responsable recrutement junior', 'Avenir RH',
 '2024-01-01', NULL, 'Gestion des candidatures et suivi des entretiens');

-- ============================================
-- 17. COMPÉTENCES DES CANDIDATS
-- ============================================

INSERT INTO cv_competence (cv_id, competence_id, niveau) VALUES
-- Lucas Dubois : développement web
(1, 1, 'Avancé'),
(1, 2, 'Avancé'),
(1, 3, 'Avancé'),
(1, 4, 'Intermédiaire'),

-- Emma Lefebvre : comptabilité
(2, 6, 'Avancé'),
(2, 8, 'Intermédiaire'),

-- Hugo Moreau : développement backend
(3, 3, 'Avancé'),
(3, 4, 'Avancé'),
(3, 5, 'Intermédiaire'),

-- Chloé Laurent : ressources humaines
(4, 7, 'Avancé'),
(4, 8, 'Avancé'),

-- Nathan Simon : informatique
(5, 9, 'Intermédiaire'),
(5, 10, 'Intermédiaire'),

-- Camille Michel : administration
(6, 8, 'Avancé'),
(6, 6, 'Intermédiaire'),

-- Louis Garcia : administration système
(7, 9, 'Avancé'),
(7, 10, 'Avancé'),
(7, 4, 'Intermédiaire'),

-- Manon Roux : recrutement
(8, 7, 'Avancé'),
(8, 8, 'Avancé'),

-- Jules Fournier : comptabilité
(9, 6, 'Avancé'),
(9, 8, 'Intermédiaire'),

-- Inès Girard : ressources humaines
(10, 7, 'Avancé'),
(10, 8, 'Avancé');

-- ============================================
-- 18. CONTRATS
-- ============================================

INSERT INTO contrat
(daty_debut, daty_fin, candidature_id, type_contrat_id)
VALUES
('2026-04-01 08:00:00', NULL, 4, 1),
('2026-04-15 08:00:00', '2026-10-15 17:00:00', 10, 2);

-- ============================================
-- 19. EMPLOYÉS
-- ============================================

INSERT INTO employe (nom, prenom, dtn, contrat_id) VALUES
('Laurent', 'Chloé', '1998-05-12', 1),
('Girard', 'Inès', '1996-08-20', 2);
