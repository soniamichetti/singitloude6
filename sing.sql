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

-- Insertion des artistes
INSERT INTO artistes VALUES 
(1, 'Justin Bieber', 'Canada', 'justin.png', 'Chanteur'),
(2, 'SZA', 'États-Unis', 'sza.jpeg', 'Chanteuse'),
(3, 'Tiakola', 'France', 'tiakola.png', 'Chanteur'),
(4, 'Code Kunst', 'Corée du Sud', 'ck.jpeg', 'Producteur'),
(5, 'Bob Marley', 'Jamaïque', 'bob.jpg', 'Chanteur'),
(6, 'The Weeknd', 'Canada', 'weeknd.jpg', 'Chanteur'),
(7, 'Miles Davis', 'États-Unis', 'miles.jpg', 'Trompettiste'),
(8, 'Jay Park', 'Corée du Sud', 'jaypark.jpg', 'Chanteur'),
(9, 'dvsn', 'Canada', 'dvsn.jpg', 'Duo R&B'),
(10, 'Summer Walker', 'États-Unis', 'summer.jpg', 'Chanteuse'),
(11, 'Tori Kelly', 'États-Unis', 'tori.jpg', 'Chanteuse'),
(12, 'pH-1', 'Corée du Sud', 'ph1.jpg', 'Rappeur');

-- Insertion des genres
INSERT INTO genres (nomGenre) VALUES 
('Pop'),
('Rap'),
('R&B'),
('Reggae'),
('Hip-hop'),
('Soul'),
('Jazz'),
('K-Hip-hop'),
('K-R&B'),
('K-Rap');

-- Insertion des albums
INSERT INTO albums (titreAlb, idArtiste, anneeSortie, prix, photo) VALUES 
('Purpose', 1, 2015, 12.00, 'purpose.jpg'),
('SOS', 2, 2022, 13.00, 'sos.jpg'),
('Mélo', 3, 2022, 14.00, 'melo.jpg'),
('Legend', 5, 1984, 10.00, 'legend.jpg'),
('After Hours', 6, 2020, 15.00, 'afterhours.jpg'),
('Kind of Blue', 7, 1959, 18.00, 'kindofblue.jpg'),
('Everything You Wanted', 8, 2016, 13.50, 'eyw.jpg'),
('Sept 5th', 9, 2016, 14.50, 'sept5th.jpg'),
('Last Day of Summer', 10, 2018, 12.50, 'lastdayofsummer.jpg'),
('Inspired by True Events', 11, 2019, 15.00, 'inspired.jpg'),
('HALO', 12, 2020, 13.00, 'halo.jpg');

-- Insertion des associations album-genre
INSERT INTO album_genre (idAlbum, idGenre) VALUES
(1, 1), -- Purpose - Pop
(2, 3), -- SOS - R&B
(3, 2), -- Mélo - Rap
(4, 4), -- Legend - Reggae
(5, 6), -- After Hours - Soul
(5, 3), -- After Hours - R&B (double genre)
(6, 7), -- Kind of Blue - Jazz
(7, 9), -- Everything You Wanted - K-R&B
(8, 3), -- Sept 5th - R&B
(9, 3), -- Last Day of Summer - R&B
(10, 1), -- Inspired by True Events - Pop
(11, 10); -- HALO - K-Rap

-- Insertion d'un utilisateur admin (mot de passe: admin123)
INSERT INTO utilisateur (mailU, mdpU, pseudoU, userType) VALUES 
('admin@singloud.com', '$2y$10$NVuKvMRpvRx7Qrn/IIKvdOh/Ar38OFGf1Iy3oZdKJ0JWEaZ4HGtqC', 'Admin', 'admin');

-- Insertion d'un utilisateur standard (mot de passe: user123)
INSERT INTO utilisateur (mailU, mdpU, pseudoU, userType) VALUES 
('user@example.com', '$2y$10$c16rZMoH8H9qYbXbxOG.CeUAjm1vJE0U0aI.jVbIdJxK6kskVTKhO', 'Utilisateur', 'utilisateur');

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