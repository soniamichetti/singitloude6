DROP TABLE IF EXISTS Utilisateur;
DROP TABLE IF EXISTS Artistes;
DROP TABLE IF EXISTS Albums;
DROP TABLE IF EXISTS TypeMusique;
DROP TABLE IF EXISTS Musiques;
DROP TABLE IF EXISTS Ecouter;

CREATE TABLE Utilisateur (
    idUtilisateur INT PRIMARY KEY,
    pseudoU VARCHAR(30),
    emailU VARCHAR(50),
    mdpU VARCHAR(50)
);

CREATE TABLE Artistes (
    idArtiste INT PRIMARY KEY,
    nomArt VARCHAR(50)
);

CREATE TABLE Albums (
    idAlbum INT PRIMARY KEY,
    titreAlb VARCHAR(50),
    aSortie INT,
    idArtiste INT NOT NULL,
    FOREIGN KEY(idArtiste) REFERENCES Artistes(idArtiste)
);

CREATE TABLE TypeMusique (
    idTypeMus VARCHAR(50) PRIMARY KEY,
    nomTypeMus VARCHAR(50)
);

CREATE TABLE Musiques (
    idMusique INT PRIMARY KEY,
    titreMus VARCHAR(50),
    paroles VARCHAR(250),
    idAlbum INT NOT NULL,
    idTypeMus VARCHAR(50) NOT NULL,
    FOREIGN KEY(idAlbum) REFERENCES Albums(idAlbum),
    FOREIGN KEY(idTypeMus) REFERENCES TypeMusique(idTypeMus)
);

CREATE TABLE Ecouter (
    idUtilisateur INT,
    idMusique INT,
    favoris BOOLEAN,
    PRIMARY KEY(idUtilisateur, idMusique),
    FOREIGN KEY(idUtilisateur) REFERENCES Utilisateur(idUtilisateur),
    FOREIGN KEY(idMusique) REFERENCES Musiques(idMusique)
);


INSERT INTO Artistes VALUES 
(01, 'Aya Nakamura'),
(02, 'Justin Bieber'),
(03, 'SZA'),
(04, 'Tiakola');

INSERT INTO Albums VALUES 
(11,'NAKAMURA',2018,01),
(12,'Purpose',2015,02),
(13,'SOS',2022,03),
(14,'Mélo',2022,04);

INSERT INTO TypeMusique VALUES 
('rnb','R&B'),
('pop','POP');
