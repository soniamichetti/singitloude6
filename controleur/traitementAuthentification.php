<?php 
$servername = "localhost";
$username = "root";
$password = "" ;

try {
    $bdd = new PDO("mysql:host=$servername;dbname=SING", $username, $password); 
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur : ".$e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['mailU'], $_POST['mdpU'])) {
        $mailU = $_POST['mailU'];
        $mdpU = $_POST['mdpU'];
        
        if ($mailU != "" && $mdpU != "") {
            $requete = $bdd->prepare("SELECT * FROM utilisateur WHERE mailU = :mailU AND mdpU = :mdpU");
            $requete->execute(array("mailU" => $mailU, "mdpU" => $mdpU));
            $utilisateur = $requete->fetch();

            if ($utilisateur) {
                echo "<script>alert('Vous êtes connecté !');</script>";
                // Inclure la vue de confirmation de la connexion
                include "../vue/vueConfirmationInscriptionU.php";
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
