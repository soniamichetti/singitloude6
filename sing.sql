DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS Artistes;
DROP TABLE IF EXISTS Albums;

create table utilisateur(
    mailU varchar(150) PRIMARY KEY,
    mdpU varchar(50),
    pseudoU varchar(50),
    userType varchar(20) CHECK (userType IN ('utilisateur', 'admin'))
);

CREATE TABLE artistes (
    idArtiste INT PRIMARY KEY,
    nomArt VARCHAR(50),
    paysArt VARCHAR(50),
    genreArt VARCHAR(50)
);

CREATE TABLE albums (
    idAlbum INT PRIMARY KEY,
    titreAlb VARCHAR(50),  
    prixAlb DECIMAL,
    photo VARCHAR(50),
    anneeSortie INT,
    idArtiste INT NOT NULL,
    FOREIGN KEY(idArtiste) REFERENCES artistes(idArtiste)
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


INSERT INTO utilisateur (mailU, mdpU, pseudoU) VALUES 
('comeandgetit@gmail.com', 'TJ', 'bonjour', 'utilisateur');
