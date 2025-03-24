<?php
// controleur/gestionFavoris.php
include_once "../modele/security.php";
include_once "../modele/database.php";

// Vérifier que l'utilisateur est connecté
requireLogin();

// Traitement des actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $idAlbum = (int)$_GET['id'];
    $mailU = $_SESSION['mailU'];
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '../vue/albums.php';
    
    switch ($action) {
        case 'ajouter':
            // Ajouter aux favoris
            if (ajouterFavori($mailU, $idAlbum)) {
                header("Location: $redirect?fav_added=true");
            } else {
                header("Location: $redirect?fav_error=true");
            }
            break;
            
        case 'supprimer':
            // Supprimer des favoris
            if (supprimerFavori($mailU, $idAlbum)) {
                header("Location: $redirect?fav_removed=true");
            } else {
                header("Location: $redirect?fav_error=true");
            }
            break;
            
        default:
            header("Location: $redirect");
    }
    exit;
} else {
    // Redirection par défaut si aucune action n'est spécifiée
    header("Location: ../vue/albums.php");
    exit;
}
?>