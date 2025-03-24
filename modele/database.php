<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure le fichier de connexion si ce n'est pas déjà fait
if (!isset($bdd)) {
    include_once "db.php";
}

// Fonctions pour la gestion des albums
function getAlbums() {
    global $bdd;
    $sql = "SELECT a.*, ar.nomArt 
            FROM albums a 
            JOIN artistes ar ON a.idArtiste = ar.idArtiste 
            ORDER BY a.titreAlb";
    $stmt = $bdd->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAlbumById($idAlbum) {
    global $bdd;
    $sql = "SELECT a.*, ar.nomArt 
            FROM albums a 
            JOIN artistes ar ON a.idArtiste = ar.idArtiste 
            WHERE a.idAlbum = :idAlbum";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':idAlbum', $idAlbum);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAlbumsByGenre($idGenre) {
    global $bdd;
    $sql = "SELECT a.*, ar.nomArt 
            FROM albums a 
            JOIN artistes ar ON a.idArtiste = ar.idArtiste 
            JOIN album_genre ag ON a.idAlbum = ag.idAlbum
            WHERE ag.idGenre = :idGenre
            ORDER BY a.titreAlb";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':idGenre', $idGenre);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addAlbum($titre, $prix, $photo, $anneeSortie, $idArtiste) {
    global $bdd;
    try {
        $sql = "INSERT INTO albums (titreAlb, prix, photo, anneeSortie, idArtiste) 
                VALUES (:titre, :prix, :photo, :anneeSortie, :idArtiste)";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':titre' => $titre,
            ':prix' => $prix,
            ':photo' => $photo,
            ':anneeSortie' => $anneeSortie,
            ':idArtiste' => $idArtiste
        ]);
        return $bdd->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

function updateAlbum($idAlbum, $titre, $prix, $photo, $anneeSortie, $idArtiste) {
    global $bdd;
    try {
        $sql = "UPDATE albums SET titreAlb = :titre, prix = :prix, 
                photo = :photo, anneeSortie = :anneeSortie, idArtiste = :idArtiste 
                WHERE idAlbum = :idAlbum";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':idAlbum' => $idAlbum,
            ':titre' => $titre,
            ':prix' => $prix,
            ':photo' => $photo,
            ':anneeSortie' => $anneeSortie,
            ':idArtiste' => $idArtiste
        ]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function deleteAlbum($idAlbum) {
    global $bdd;
    try {
        // Supprimer d'abord les associations avec les genres
        $sql = "DELETE FROM album_genre WHERE idAlbum = :idAlbum";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':idAlbum', $idAlbum);
        $stmt->execute();
        
        // Supprimer l'album
        $sql = "DELETE FROM albums WHERE idAlbum = :idAlbum";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':idAlbum', $idAlbum);
        $stmt->execute();
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Fonctions pour la gestion des artistes
function getArtistes() {
    global $bdd;
    $sql = "SELECT * FROM artistes ORDER BY nomArt";
    $stmt = $bdd->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getArtisteById($idArtiste) {
    global $bdd;
    $sql = "SELECT * FROM artistes WHERE idArtiste = :idArtiste";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':idArtiste', $idArtiste);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addArtiste($nomArt, $paysArt = null, $photoArt = null, $metier = null) {
    global $bdd;
    try {
        // Trouver le prochain ID disponible
        $sql = "SELECT MAX(idArtiste) as maxId FROM artistes";
        $stmt = $bdd->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nextId = ($result['maxId'] ?? 0) + 1;
        
        $sql = "INSERT INTO artistes (idArtiste, nomArt, paysArt, photoArt, metier) 
                VALUES (:idArtiste, :nomArt, :paysArt, :photoArt, :metier)";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':idArtiste' => $nextId,
            ':nomArt' => $nomArt,
            ':paysArt' => $paysArt,
            ':photoArt' => $photoArt,
            ':metier' => $metier
        ]);
        return $nextId;
    } catch (PDOException $e) {
        return false;
    }
}

function updateArtiste($idArtiste, $nomArt, $paysArt = null, $photoArt = null, $metier = null) {
    global $bdd;
    try {
        $sql = "UPDATE artistes SET nomArt = :nomArt, paysArt = :paysArt, 
                photoArt = :photoArt, metier = :metier 
                WHERE idArtiste = :idArtiste";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':idArtiste' => $idArtiste,
            ':nomArt' => $nomArt,
            ':paysArt' => $paysArt,
            ':photoArt' => $photoArt,
            ':metier' => $metier
        ]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function deleteArtiste($idArtiste) {
    global $bdd;
    try {
        // Vérifier si l'artiste a des albums
        $sql = "SELECT COUNT(*) FROM albums WHERE idArtiste = :idArtiste";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':idArtiste', $idArtiste);
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            return false; // L'artiste a des albums, impossible de le supprimer
        }
        
        // Supprimer l'artiste
        $sql = "DELETE FROM artistes WHERE idArtiste = :idArtiste";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':idArtiste', $idArtiste);
        $stmt->execute();
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function getAlbumCountByArtist($idArtiste) {
    global $bdd;
    $sql = "SELECT COUNT(*) as count FROM albums WHERE idArtiste = :idArtiste";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':idArtiste', $idArtiste);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

// Fonctions pour la gestion des genres
function getGenres() {
    global $bdd;
    $sql = "SELECT g.*, COUNT(ag.idAlbum) as nbAlbums 
            FROM genres g 
            LEFT JOIN album_genre ag ON g.idGenre = ag.idGenre 
            GROUP BY g.idGenre 
            ORDER BY g.nomGenre";
    $stmt = $bdd->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getGenreById($idGenre) {
    global $bdd;
    $sql = "SELECT * FROM genres WHERE idGenre = :idGenre";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':idGenre', $idGenre);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addGenre($nomGenre) {
    global $bdd;
    try {
        // Vérifier si le genre existe déjà
        $sql = "SELECT COUNT(*) FROM genres WHERE nomGenre = :nomGenre";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':nomGenre', $nomGenre);
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            return false; // Le genre existe déjà
        }
        
        $sql = "INSERT INTO genres (nomGenre) VALUES (:nomGenre)";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':nomGenre', $nomGenre);
        $stmt->execute();
        
        return $bdd->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

function updateGenre($idGenre, $nomGenre) {
    global $bdd;
    try {
        $sql = "UPDATE genres SET nomGenre = :nomGenre WHERE idGenre = :idGenre";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':idGenre' => $idGenre,
            ':nomGenre' => $nomGenre
        ]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function deleteGenre($idGenre) {
    global $bdd;
    try {
        // Vérifier si le genre est associé à des albums
        $sql = "SELECT COUNT(*) FROM album_genre WHERE idGenre = :idGenre";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':idGenre', $idGenre);
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            return false; // Le genre est associé à des albums, impossible de le supprimer
        }
        
        // Supprimer le genre
        $sql = "DELETE FROM genres WHERE idGenre = :idGenre";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':idGenre', $idGenre);
        $stmt->execute();
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Fonctions pour la gestion des associations Album-Genre
function associerAlbumGenre($idAlbum, $idGenre) {
    global $bdd;
    try {
        // Vérifier si l'association existe déjà
        $sql = "SELECT COUNT(*) FROM album_genre WHERE idAlbum = :idAlbum AND idGenre = :idGenre";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':idAlbum' => $idAlbum,
            ':idGenre' => $idGenre
        ]);
        
        if ($stmt->fetchColumn() > 0) {
            return false; // L'association existe déjà
        }
        
        // Créer l'association
        $sql = "INSERT INTO album_genre (idAlbum, idGenre) VALUES (:idAlbum, :idGenre)";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':idAlbum' => $idAlbum,
            ':idGenre' => $idGenre
        ]);
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function dissocierAlbumGenre($idAlbum, $idGenre) {
    global $bdd;
    try {
        // Supprimer l'association
        $sql = "DELETE FROM album_genre WHERE idAlbum = :idAlbum AND idGenre = :idGenre";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':idAlbum' => $idAlbum,
            ':idGenre' => $idGenre
        ]);
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function getAlbumGenres($idAlbum) {
    global $bdd;
    try {
        $sql = "SELECT g.* 
                FROM genres g
                JOIN album_genre ag ON g.idGenre = ag.idGenre
                WHERE ag.idAlbum = :idAlbum
                ORDER BY g.nomGenre";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':idAlbum', $idAlbum);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Fonction pour vérifier si un album est en favori
function estFavori($mailU, $idAlbum) {
    if (!$mailU) return false;
    
    global $bdd;
    try {
        $sql = "SELECT COUNT(*) FROM favoris WHERE mailU = :mailU AND idAlbum = :idAlbum";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':mailU' => $mailU,
            ':idAlbum' => $idAlbum
        ]);
        
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

// Fonctions pour la gestion des favoris
function ajouterFavori($mailU, $idAlbum) {
    global $bdd;
    
    try {
        // Vérifier si le favori existe déjà
        $sql = "SELECT COUNT(*) FROM favoris WHERE mailU = :mailU AND idAlbum = :idAlbum";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':mailU' => $mailU,
            ':idAlbum' => $idAlbum
        ]);
        
        if ($stmt->fetchColumn() > 0) {
            return false; // Le favori existe déjà
        }
        
        // Ajouter le favori
        $sql = "INSERT INTO favoris (mailU, idAlbum) VALUES (:mailU, :idAlbum)";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':mailU' => $mailU,
            ':idAlbum' => $idAlbum
        ]);
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function supprimerFavori($mailU, $idAlbum) {
    global $bdd;
    
    try {
        // Supprimer le favori
        $sql = "DELETE FROM favoris WHERE mailU = :mailU AND idAlbum = :idAlbum";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':mailU' => $mailU,
            ':idAlbum' => $idAlbum
        ]);
        
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

function getFavoris($mailU) {
    global $bdd;
    
    try {
        // Récupérer tous les favoris d'un utilisateur avec les détails des albums
        $sql = "SELECT f.*, a.*, art.nomArt 
                FROM favoris f
                JOIN albums a ON f.idAlbum = a.idAlbum
                JOIN artistes art ON a.idArtiste = art.idArtiste
                WHERE f.mailU = :mailU
                ORDER BY f.dateFavoris DESC";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':mailU', $mailU);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}
?>