<?php 
include_once "../modele/db.php";

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['mailU'], $_POST['mdpU'])) {
        $mailU = $_POST['mailU'];
        $mdpU = $_POST['mdpU'];
        
        if ($mailU != "" && $mdpU != "") {
            $requete = $bdd->prepare("SELECT * FROM utilisateur WHERE mailU = :mailU AND mdpU = :mdpU");
            $requete->execute(array("mailU" => $mailU, "mdpU" => $mdpU));
            $utilisateur = $requete->fetch();

            if ($utilisateur) {
                if ($utilisateur['userType'] == 'admin') {
                    // Stocker le pseudo dans la session
                    $_SESSION['pseudo'] = $utilisateur['pseudoU'];
                    echo "<script>alert('Vous êtes connecté en tant qu\'admin !');</script>";
                    // Inclure la vue de confirmation pour l'admin
                    include "../vue/vueConfirmationA.php";
                } else {
                    // Stocker le pseudo dans la session
                    $_SESSION['pseudo'] = $utilisateur['pseudoU'];
                    echo "<script>alert('Vous êtes connecté !');</script>";
                    // Inclure la vue de confirmation pour les utilisateurs
                    include "../vue/vueConfirmationU.php";
                }
            } else {
                echo "<script>alert('Email ou mot de passe incorrect.');</script>";
                // Redirection vers la page d'authentification
                echo "<script>window.location.href = '../vue/vueAuthentification.php';</script>";
            }
        } else {
            echo "<script>alert('Veuillez remplir tous les champs du formulaire.');</script>";
            // Redirection vers la page d'authentification
            echo "<script>window.location.href = '../vue/vueAuthentification.php';</script>";
        }
    }
}
?>