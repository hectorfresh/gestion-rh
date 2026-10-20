
-- =====================================================
-- DONNEES DE TEST : BASE RH
-- =====================================================

-- 1. DEPARTEMENTS

INSERT INTO departement (nom) VALUES
('Informatique'),
('Ressources humaines'),
('Comptabilite'),
('Marketing');

-- 2. TYPES DE FINANCEMENT

INSERT INTO type_finance (libelle) VALUES
('Budget de recrutement'),
('Budget de formation'),
('Budget de fonctionnement');

-- 3. POSTES

INSERT INTO poste (libelle) VALUES
('Developpeur web'),
('Responsable des ressources humaines'),
('Comptable'),
('Assistant administratif');

-- 4. STATUTS
-- Uniquement les 4 statuts de validation des besoins

INSERT INTO statut (libelle) VALUES
('Valide par manager'),
('Valide par RH'),
('Valide par finance'),
('Valide par DG');

-- 5. ROLES

INSERT INTO role (libelle) VALUES
('Administrateur'),
('Manager'),
('Responsable RH'),
('Responsable finance'),
('Directeur general'),
('Responsable de departement');

-- 6. GENRES

INSERT INTO gender (libelle) VALUES
('Homme'),
('Femme'),
('Autre');

-- 7. TYPES DE CONTRAT

INSERT INTO type_contrat (libelle) VALUES
('CDI'),
('CDD'),
('Stage');

-- 8. DIPLOMES

INSERT INTO diplome (libelle) VALUES
('Licence en informatique'),
('Master en informatique'),
('Licence en gestion'),
('Master en comptabilite');

-- 9. COMPETENCES

INSERT INTO competence (libelle) VALUES
('SQL'),
('PostgreSQL'),
('Java'),
('JavaScript'),
('Gestion de projet'),
('Comptabilite'),
('Gestion administrative');

-- 10. UTILISATEURS

INSERT INTO utilisateur (nom, role_id, email, mdp) VALUES
('Jean Dupont', 1, 'admin@entreprise.fr', 'test123'),
('Pierre Bernard', 2, 'manager@entreprise.fr', 'test123'),
('Sophie Martin', 3, 'rh@entreprise.fr', 'test123'),
('Marc Petit', 4, 'finance@entreprise.fr', 'test123'),
('Paul Durand', 5, 'dg@entreprise.fr', 'test123'),
('Claire Moreau', 6, 'departement@entreprise.fr', 'test123');

-- 11. CANDIDATS

INSERT INTO candidat
(nom, prenom, gender_id, email, mdp)
VALUES
('Durand', 'Thomas', 1, 'thomas.durand@candidat.fr', 'test123'),
('Moreau', 'Camille', 2, 'camille.moreau@candidat.fr', 'test123'),
('Lefebvre', 'Antoine', 1, 'antoine.lefebvre@candidat.fr', 'test123'),
('Laurent', 'Julie', 2, 'julie.laurent@candidat.fr', 'test123'),
('Michel', 'Nicolas', 1, 'nicolas.michel@candidat.fr', 'test123');

-- 12. CV

INSERT INTO cv (candidat_id, chemin_fichier) VALUES
(1, 'cv/thomas_durand.pdf'),
(2, 'cv/camille_moreau.pdf'),
(3, 'cv/antoine_lefebvre.pdf'),
(4, 'cv/julie_laurent.pdf'),
(5, 'cv/nicolas_michel.pdf');

-- 13. DIPLOMES DES CV

INSERT INTO diplome_cv
(cv_id, diplome_id, etablissement, annee_obtention)
VALUES
(1, 2, 'Universite de Lyon', 2023),
(2, 3, 'Universite de Bordeaux', 2022),
(3, 1, 'Universite de Lille', 2024),
(4, 4, 'Universite de Nantes', 2021),
(5, 1, 'Universite de Toulouse', 2023);

-- 14. EXPERIENCES PROFESSIONNELLES

INSERT INTO experience
(cv_id, poste_occupe, entreprise, date_debut, date_fin, description)
VALUES
(1, 'Developpeur web', 'Solutions Numeriques',
 '2023-01-01', '2025-06-30',
 'Developpement de sites et applications web'),

(2, 'Assistante administrative', 'Services Entreprise',
 '2022-03-01', '2024-02-29',
 'Gestion des dossiers et suivi administratif'),

(3, 'Developpeur junior', 'Informatique Conseil',
 '2024-01-15', NULL,
 'Developpement et maintenance des applications'),

(4, 'Comptable', 'Gestion et Finance',
 '2021-06-01', '2025-01-31',
 'Suivi des factures et preparation des bilans'),

(5, 'Technicien informatique', 'Numerique France',
 '2023-04-01', NULL,
 'Assistance technique et maintenance informatique');

-- 15. COMPETENCES DES CV

INSERT INTO cv_competence (cv_id, competence_id, niveau) VALUES
(1, 1, 'Avance'),
(1, 2, 'Avance'),
(1, 4, 'Intermediaire'),
(2, 5, 'Avance'),
(2, 7, 'Avance'),
(3, 3, 'Intermediaire'),
(3, 4, 'Avance'),
(4, 6, 'Avance'),
(4, 7, 'Intermediaire'),
(5, 1, 'Intermediaire'),
(5, 2, 'Intermediaire');

-- 16. BESOINS DE RECRUTEMENT
-- statut_id :
-- 1 = Valide par manager
-- 2 = Valide par RH
-- 3 = Valide par finance
-- 4 = Valide par DG

INSERT INTO besoin
(statut_id, departement_id, utilisateur_id, poste_id,
 descriptions, date_souhaitee, montant_min, montant_max)
VALUES
(1, 1, 2, 1,
 'Recrutement dun developpeur web pour renforcer lequipe informatique.',
 '2026-11-15 09:00:00', 1800, 2800),

(2, 2, 3, 2,
 'Recherche dun responsable des ressources humaines.',
 '2026-12-01 09:00:00', 2500, 3500),

(3, 3, 4, 3,
 'Recrutement dun comptable pour assurer le suivi financier.',
 '2026-11-20 09:00:00', 2000, 3000),

(4, 2, 5, 4,
 'Recrutement dun assistant administratif valide par la direction generale.',
 '2026-12-10 09:00:00', 1600, 2200);

-- 17. HISTORIQUE DES VALIDATIONS DES BESOINS

INSERT INTO historique_statut_besoin
(besoin_id, statut_id, utilisateur_id)
VALUES
(1, 1, 2),
(1, 2, 3),
(2, 1, 2),
(2, 2, 3),
(2, 3, 4),
(3, 1, 2),
(3, 2, 3),
(3, 3, 4),
(4, 1, 2),
(4, 2, 3),
(4, 3, 4),
(4, 4, 5);

-- 18. ANNONCES

INSERT INTO annonce (designation, besoin_id) VALUES
('Offre de developpeur web', 1),
('Offre de responsable RH', 2),
('Offre de comptable', 3),
('Offre dassistant administratif', 4);

-- 19. CANDIDATURES
-- Les statuts disponibles sont uniquement les 4 statuts
-- de validation. Ils sont donc reutilises ici.

INSERT INTO candidature
(statut_id, candidat_id, annonce_id, salaire_estime)
VALUES
(1, 1, 1, 2500),
(4, 2, 2, 3200),
(1, 3, 1, 2300),
(2, 4, 3, 2700),
(1, 5, 4, 1900),
(3, 2, 4, 2000);

-- 20. HISTORIQUE DES CANDIDATURES

INSERT INTO historique_candidature
(candidature_id, statut_id, commentaire)
VALUES
(1, 1, 'Candidature recue et dossier transmis au manager'),
(1, 2, 'Dossier examine par les ressources humaines'),
(2, 4, 'Candidature retenue apres entretien'),
(3, 1, 'Candidature en cours dexamen'),
(4, 2, 'Profil correspondant au poste'),
(5, 1, 'Candidature recue'),
(6, 3, 'Dossier transmis au service financier');

-- 21. CONTRATS

INSERT INTO contrat
(daty_debut, daty_fin, candidature_id, type_contrat_id, salaire)
VALUES
('2026-11-01 09:00:00', '2027-10-31 17:00:00', 2, 2, 3200),
('2026-11-15 09:00:00', NULL, 4, 1, 2700);

-- 22. EMPLOYES

INSERT INTO employe (nom, prenom, dtn, contrat_id) VALUES
('Moreau', 'Camille', '1995-04-12', 1),
('Laurent', 'Julie', '1992-08-23', 2);

-- 23. FINANCEMENTS

INSERT INTO finance (type_id, montant, annee) VALUES
(1, 25000, 2026),
(2, 8000, 2026),
(3, 12000, 2026),
(1, 30000, 2027);

-- =====================================================
-- FIN DES DONNEES DE TEST
-- =====================================================
