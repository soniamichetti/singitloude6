<?php 
include_once "../modele/db.php";
include_once "../modele/security.php";

// Rediriger si déjà connecté
if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: ../vue/admin.php?login_success=true");
    } else {
        header("Location: ../vue/albums.php?login_success=true");
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['mailU'], $_POST['mdpU'])) {
        $mailU = cleanInput($_POST['mailU']);
        $mdpU = $_POST['mdpU']; 
        
        if ($mailU !== "" && $mdpU !== "") {
            // Préparation de la requête
            $requete = $bdd->prepare("SELECT * FROM utilisateur WHERE mailU = :mailU");
            $requete->execute(array("mailU" => $mailU));
            $utilisateur = $requete->fetch();
            
            // Vérification du mot de passe avec password_verify
            if ($utilisateur && password_verify($mdpU, $utilisateur['mdpU'])) {
                // Démarrer la session
                $_SESSION['pseudo'] = $utilisateur['pseudoU'];
                $_SESSION['mailU'] = $utilisateur['mailU'];
                $_SESSION['userType'] = $utilisateur['userType'];
                
                if ($_SESSION['userType'] == 'admin') {
                    header("Location: ../vue/admin.php?login_success=true");
                } else {
                    header("Location: ../vue/albums.php?login_success=true");
                }
                exit;
            } else {
                header("Location: ../vue/vueAuthentification.php?error=Identifiants incorrects");
                exit;
            }
        } else {
            // Champs vides
            header("Location: ../vue/vueAuthentification.php?error=Veuillez remplir tous les champs");
            exit;
        }
    } else {
        // Champs manquants
        header("Location: ../vue/vueAuthentification.php?error=Veuillez remplir tous les champs");
        exit;
    }
} else {
    // Accès direct au script
    header("Location: ../vue/vueAuthentification.php");
    exit;
}
?>