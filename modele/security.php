<?php
/**
 * security.php - Fichier à inclure au début de chaque page pour la sécurité
 */

// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Vérifie si l'utilisateur est connecté
 * @return bool True si l'utilisateur est connecté, false sinon
 */
function isLoggedIn() {
    return isset($_SESSION['pseudo']) && !empty($_SESSION['pseudo']) && isset($_SESSION['mailU']) && !empty($_SESSION['mailU']);
}

/**
 * Vérifie si l'utilisateur est un administrateur
 * @return bool True si l'utilisateur est un administrateur, false sinon
 */
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['userType']) && $_SESSION['userType'] === 'admin';
}

/**
 * Vérifie si l'utilisateur a accès à une page réservée aux utilisateurs connectés
 * Redirige vers la page de connexion si non connecté
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../vue/vueAuthentification.php?error=Vous devez être connecté pour accéder à cette page");
        exit;
    }
}

/**
 * Vérifie si l'utilisateur a accès à une page réservée aux administrateurs
 * Redirige vers la page de connexion ou la page d'accueil selon le cas
 */
function requireAdmin() {
    if (!isLoggedIn()) {
        header("Location: ../vue/vueAuthentification.php?error=Vous devez être connecté pour accéder à cette page");
        exit;
    }
    
    if (!isAdmin()) {
        header("Location: ../vue/albums.php?error=Accès réservé aux administrateurs");
        exit;
    }
}

/**
 * Nettoie les données d'entrée pour éviter les attaques XSS
 * @param string $data Donnée à nettoyer
 * @return string Donnée nettoyée
 */
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>