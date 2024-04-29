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
                // Stocker le pseudo dans la session
                $_SESSION['pseudo'] = $utilisateur['pseudoU'];
                if ($utilisateur['userType'] == 'admin') {
                    $_SESSION['admin'] = true; // Set admin session
                    echo "<script>alert('Vous êtes connecté en tant qu\'admin !');</script>";
                    // Inclure la vue de confirmation pour l'admin
                    include "../vue/vueConfirmationA.php";
                } else {
                    echo "<script>alert('Vous êtes connecté !');</script>";
                    // Redirect to user albums page
                    header("Location: ../vue/vueConfirmationU.php");
                    exit();
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
