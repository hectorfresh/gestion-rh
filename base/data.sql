
-- =====================================================
-- DONNEES DE TEST : BASE RH
-- =====================================================
INSERT INTO role (libelle)
VALUES ('admin');

INSERT INTO utilisateur (nom, role_id, email, mdp)
VALUES ('Admin', 1, 'admin@mail.com', '1234');
