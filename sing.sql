create database sing;
use sing


-- Suppression des tables si elles existent déjà
DROP TABLE IF EXISTS album_genre;
DROP TABLE IF EXISTS favoris;
DROP TABLE IF EXISTS panier;
DROP TABLE IF EXISTS albums;
DROP TABLE IF EXISTS genres;
DROP TABLE IF EXISTS artistes;
DROP TABLE IF EXISTS utilisateur;

-- Création de la table utilisateur
CREATE TABLE utilisateur(
    mailU VARCHAR(150) PRIMARY KEY,
    mdpU VARCHAR(255), -- Taille augmentée pour stocker les hash bcrypt
    pseudoU VARCHAR(50) NOT NULL,
    userType VARCHAR(20) DEFAULT 'utilisateur' CHECK (userType IN ('utilisateur', 'admin'))
);

--admin@singloud

-- Création de la table artistes
CREATE TABLE artistes (
    idArtiste INT PRIMARY KEY,
    nomArt VARCHAR(50) NOT NULL,
    paysArt VARCHAR(50),
    photoArt VARCHAR(100),
    metier VARCHAR(50)
);

-- Création de la table genres
CREATE TABLE genres (
    idGenre INT AUTO_INCREMENT PRIMARY KEY,
    nomGenre VARCHAR(50) NOT NULL UNIQUE
);

-- Création de la table albums (relation 1,N vers 1,1 avec artistes)
CREATE TABLE albums (
    idAlbum INT AUTO_INCREMENT PRIMARY KEY,
    titreAlb VARCHAR(100) NOT NULL,  
    idArtiste INT NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    photo VARCHAR(100),
    anneeSortie INT,
    FOREIGN KEY (idArtiste) REFERENCES artistes(idArtiste)
);

-- Création de la table album_genre (association N,M entre albums et genres)
CREATE TABLE album_genre (
    idAlbum INT,
    idGenre INT,
    PRIMARY KEY (idAlbum, idGenre),
    FOREIGN KEY (idAlbum) REFERENCES albums(idAlbum) ON DELETE CASCADE,
    FOREIGN KEY (idGenre) REFERENCES genres(idGenre) ON DELETE CASCADE
);

-- Création de la table panier
CREATE TABLE panier (
    idPanier INT AUTO_INCREMENT PRIMARY KEY,
    mailU VARCHAR(150) NOT NULL,
    idAlbum INT NOT NULL,
    quantite INT DEFAULT 1,
    FOREIGN KEY (mailU) REFERENCES utilisateur(mailU) ON DELETE CASCADE,
    FOREIGN KEY (idAlbum) REFERENCES albums(idAlbum) ON DELETE CASCADE
);

-- Création de la table favoris (association N,M entre utilisateurs et albums)
CREATE TABLE favoris (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mailU VARCHAR(150) NOT NULL,
    idAlbum INT NOT NULL,
    dateFavoris TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mailU) REFERENCES utilisateur(mailU) ON DELETE CASCADE,
    FOREIGN KEY (idAlbum) REFERENCES albums(idAlbum) ON DELETE CASCADE,
    UNIQUE KEY unique_favoris (mailU, idAlbum)
);

-- Insertion de données pour les genres
INSERT INTO genres (nomGenre) VALUES 
('Pop'),
('Rap'),
('R&B');

-- Insertion de données pour les artistes
INSERT INTO artistes (idArtiste, nomArt, paysArt, genreArt, photoArt, sexe, metier) VALUES 
(1, 'Aya Nakamura', 'France', 'Pop', 'aya.jpeg', 'femme', 'Chanteuse'),
(2, 'Justin Bieber', 'Canada', 'Pop', 'justin.png', 'homme', 'Chanteur'),
(3, 'SZA', 'USA', 'R&B', 'sza.jpeg', 'femme', 'Chanteuse'),
(4, 'Tiakola', 'France', 'Rap', 'tiakola.png', 'homme', 'Chanteur');

-- Insertion de données pour les albums
INSERT INTO albums (idAlbum, titreAlb, idArtiste, anneeSortie, prix, photo) VALUES 
(11, 'NAKAMURA', 1, 2018, 11.00, 'nakamura.jpg'),
(12, 'Purpose', 2, 2015, 12.00, 'purpose.jpg'),
(13, 'SOS', 3, 2022, 13.00, 'sos.jpg'),
(14, 'Mélo', 4, 2022, 14.00, 'melo.jpg');

-- Insertion des relations album-genre
INSERT INTO album_genre (idAlbum, idGenre) VALUES 
(11, 1), -- NAKAMURA -> Pop
(12, 1), -- Purpose -> Pop
(13, 3), -- SOS -> R&B
(14, 2); -- Mélo -> Rap


-- Insertion d'un utilisateur admin (mot de passe: admin123)
INSERT INTO utilisateur (mailU, mdpU, pseudoU, userType) VALUES 
('admin@singloud.com', '$2y$10$pUe8WrIky7nGe17z6tUCIuXYCQfpFwhfChzeUa3ZKu9/mf7LzXaE.', 'Admin', 'admin');

-- Insertion d'un utilisateur standard (mot de passe: user123)
INSERT INTO utilisateur (mailU, mdpU, pseudoU, userType) VALUES 
('user@example.com', '$2y$10$jCY4mSxHQYjVxILrwZqrkuAQ1UWsL3mQDSLzpx.whM07ZFZgZR2zu', 'Utilisateur', 'utilisateur');

-- Insertion de favoris pour l'utilisateur (relation entre utilisateur et album)
INSERT INTO favoris (mailU, idAlbum) VALUES
('user@example.com', 1),
('user@example.com', 5),
('admin@singloud.com', 3),
('admin@singloud.com', 7);

-- Insertion dans le panier pour l'utilisateur
INSERT INTO panier (mailU, idAlbum, quantite) VALUES
('user@example.com', 2, 1),
('user@example.com', 4, 2),
('admin@singloud.com', 6, 1);