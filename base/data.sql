
-- =====================================================
-- DONNEES DE TEST : BASE RH
-- =====================================================
INSERT INTO role (libelle)
VALUES ('dg');

INSERT INTO role (libelle)
VALUES ('rh');

INSERT INTO role (libelle)
VALUES ('manager');




INSERT INTO utilisateur (nom, role_id, email, mdp)
VALUES ('DG', 1, 'dg@mail.com', '1234');

INSERT INTO utilisateur (nom, role_id, email, mdp)
VALUES ('RH', 2, 'rh@mail.com', '1234');

INSERT INTO utilisateur (nom, role_id, email, mdp)
VALUES ('Manager', 3, 'manager@mail.com', '1234');


INSERT INTO statut (libelle)
SELECT 'En attente'
WHERE NOT EXISTS (
    SELECT 1 FROM statut
    WHERE libelle = 'En attente'
);

INSERT INTO poste (libelle)
VALUES ('Développeur'), ('Comptable'), ('Technicien');


INSERT INTO type_finance (libelle)
VALUES ('Budget recrutement');

INSERT INTO finance (type_id, montant, annee)
VALUES (1, 60000000, 2026);



INSERT INTO statut (libelle)
VALUES ('Refusé par RH');

INSERT INTO statut (libelle)
VALUES ('En attente DG');

INSERT INTO statut (libelle)
VALUES ('Refusé - budget insuffisant');

INSERT INTO statut (libelle)
VALUES ('Refusé par DG');

INSERT INTO statut (libelle)
VALUES ('En attente ouverture RH');

INSERT INTO statut (libelle)
VALUES ('Ouverture validée');

INSERT INTO statut (libelle)
VALUES ('Ouverture refusée');



INSERT INTO gender (libelle)
VALUES ('Homme');

INSERT INTO gender (libelle)
VALUES ('Femme');