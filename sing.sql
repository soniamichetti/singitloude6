DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS Artistes;
DROP TABLE IF EXISTS Albums;
DROP TABLE IF EXISTS TypeMusique;
DROP TABLE IF EXISTS Musiques;
DROP TABLE IF EXISTS Ecouter;

create table utilisateur(
    mailU varchar(150) PRIMARY KEY,
    mdpU varchar(50),
    pseudoU varchar(50)
);

CREATE TABLE artistes (
    idArtiste INT PRIMARY KEY,
    nomArt VARCHAR(50)
);

CREATE TABLE albums (
    idAlbum INT PRIMARY KEY,
    titreAlb VARCHAR(50),
    aSortie INT,
    idArtiste INT NOT NULL,
    FOREIGN KEY(idArtiste) REFERENCES artistes(idArtiste)
);

CREATE TABLE typeMusique (
    idTypeMus VARCHAR(50) PRIMARY KEY,
    nomTypeMus VARCHAR(50)
);

CREATE TABLE musiques (
    idMusique INT PRIMARY KEY,
    titreMus VARCHAR(50),
    paroles VARCHAR(250),
    idAlbum INT NOT NULL,
    idTypeMus VARCHAR(50) NOT NULL,
    FOREIGN KEY(idAlbum) REFERENCES albums(idAlbum),
    FOREIGN KEY(idTypeMus) REFERENCES typeMusique(idTypeMus)
);

CREATE TABLE ecouter (
    mailU varchar(150),
    idMusique INT,
    favoris BOOLEAN,
    PRIMARY KEY(mailU, idMusique),
    FOREIGN KEY(mailU) REFERENCES utilisateur(mailU),
    FOREIGN KEY(idMusique) REFERENCES musiques(idMusique)
);


INSERT INTO artistes VALUES 
(01, 'Aya Nakamura'),
(02, 'Justin Bieber'),
(03, 'SZA'),
(04, 'Tiakola');

INSERT INTO albums VALUES 
(11,'NAKAMURA',2018,01),
(12,'Purpose',2015,02),
(13,'SOS',2022,03),
(14,'Mélo',2022,04);

INSERT INTO typeMusique VALUES 
('rnb','R&B'),
('pop','POP');

INSERT INTO utilisateur (mailU, mdpU, pseudoU) VALUES 
('comeandgetit@gmail.com', 'TJ', 'bonjour');
