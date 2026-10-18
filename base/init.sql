CREATE DATABASE rh;


CREATE TABLE poste(
    id SERIAL PRIMARY KEY,
    libelle TEXT NOT NULL
);

CREATE TABLE statut(
    id SERIAL PRIMARY KEY,
    libelle TEXT NOT NULL
);

CREATE TABLE besoin(
    id SERIAL PRIMARY KEY,
    poste_id INTEGER NOT NULL,
    statut_id INTEGER, 
    nombre INTEGER NOT NULL CHECK (nombre > 0),
    daty TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_besoin_poste
        FOREIGN KEY(poste_id) REFERENCES poste(id), 
    
    CONSTRAINT fk_besoin_statut
        FOREIGN KEY(statut_id) REFERENCES statut(id), 
);

CREATE TABLE annonce(
    id SERIAL PRIMARY KEY,
    daty TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    designation TEXT NOT NULL,
    besoin_id INTEGER, 
    CONSTRAINT fk_annonce_besoin
        FOREIGN KEY(besoin_id) REFERENCES besoin(id)

);


CREATE TABLE role(
    id SERIAL PRIMARY KEY,
    libelle TEXT NOT NULL
);


CREATE TABLE utilisateur(
    id SERIAL PRIMARY KEY,
    nom TEXT NOT NULL,
    role_id INTEGER,
    email TEXT NOT NULL,
    mdp TEXT NOT NULL,

    CONSTRAINT fk_utilisateur_role
        FOREIGN KEY(role_id) REFERENCES role(id)
);




CREATE TABLE gender(
    id SERIAL PRIMARY KEY,
    libelle TEXT NOT NULL
);


CREATE TABLE candidat(
    id SERIAL PRIMARY KEY,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    gender_id INTEGER NOT NULL,
    email TEXT NOT NULL UNIQUE,
    mdp TEXT NOT NULL,
    CONSTRAINT fk_candidat_gender
        FOREIGN KEY(gender_id) REFERENCES gender(id)
);


CREATE TABLE candidature(
    id SERIAL PRIMARY KEY,
    statut_id INTEGER,
    daty TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    candidat_id INTEGER,
    annonce_id INTEGER,
    salaire_estime NUMERIC(12,2),

    CONSTRAINT fk_candidature_candidat
        FOREIGN KEY(candidat_id) REFERENCES candidat(id),

    CONSTRAINT fk_candidature_statut
        FOREIGN KEY(statut_id) REFERENCES statut(id),

    CONSTRAINT fk_candidature_annonce
        FOREIGN KEY(annonce_id) REFERENCES annonce(id)
);


CREATE TABLE historique_candidature(
    id SERIAL PRIMARY KEY,
    candidature_id INTEGER NOT NULL,
    statut_id INTEGER NOT NULL,
    daty TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    commentaire TEXT,

    CONSTRAINT fk_historique_candidature
        FOREIGN KEY(candidature_id) REFERENCES candidature(id),

    CONSTRAINT fk_historique_statut
        FOREIGN KEY(statut_id) REFERENCES statut(id)
);


CREATE TABLE type_contrat(
    id SERIAL PRIMARY KEY,
    libelle TEXT NOT NULL
);


CREATE TABLE contrat(
    id SERIAL PRIMARY KEY,
    daty_debut TIMESTAMP,
    daty_fin TIMESTAMP,
    candidature_id INTEGER,
    type_contrat_id INTEGER,

    CONSTRAINT fk_contrat_candidature
        FOREIGN KEY(candidature_id) REFERENCES candidature(id),

    CONSTRAINT fk_contrat_type
        FOREIGN KEY(type_contrat_id) REFERENCES type_contrat(id)
);


CREATE TABLE employe(
    id SERIAL PRIMARY KEY,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    dtn DATE,
    contrat_id INTEGER,

    CONSTRAINT fk_employe_contrat
        FOREIGN KEY(contrat_id) REFERENCES contrat(id)
);




CREATE TABLE diplome(
    id SERIAL PRIMARY KEY,
    libelle TEXT
);


CREATE TABLE cv(
    id SERIAL PRIMARY KEY,
    candidat_id INTEGER NOT NULL UNIQUE,
    chemin_fichier TEXT,
    date_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_cv_candidat
        FOREIGN KEY(candidat_id) REFERENCES candidat(id)
);


CREATE TABLE diplome_cv(
    id SERIAL PRIMARY KEY,
    cv_id INTEGER NOT NULL,
    diplome_id INTEGER NOT NULL,
    etablissement TEXT,
    annee_obtention INTEGER,

    CONSTRAINT fk_diplome_cv_cv
        FOREIGN KEY(cv_id) REFERENCES cv(id),

    CONSTRAINT fk_diplome_cv_diplome
        FOREIGN KEY(diplome_id) REFERENCES diplome(id)
);


CREATE TABLE experience(
    id SERIAL PRIMARY KEY,
    cv_id INTEGER NOT NULL,
    poste_occupe TEXT NOT NULL,
    entreprise TEXT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE,
    description TEXT,

    CONSTRAINT fk_experience_cv
        FOREIGN KEY(cv_id) REFERENCES cv(id),

    CONSTRAINT chk_dates_experience
        CHECK (date_fin IS NULL OR date_fin >= date_debut)
);


CREATE TABLE competence(
    id SERIAL PRIMARY KEY,
    libelle TEXT NOT NULL UNIQUE
);


CREATE TABLE cv_competence(
    cv_id INTEGER NOT NULL,
    competence_id INTEGER NOT NULL,
    niveau TEXT,

    PRIMARY KEY (cv_id, competence_id),

    CONSTRAINT fk_cv_competence_cv
        FOREIGN KEY(cv_id) REFERENCES cv(id),

    CONSTRAINT fk_cv_competence_competence
        FOREIGN KEY(competence_id) REFERENCES competence(id)
);