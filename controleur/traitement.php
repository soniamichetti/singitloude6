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

if(isset($_POST['pseudoU'], $_POST['mailU'], $_POST['mdpU'])) {
    // Vérifier si les champs ne sont pas vides
    if(!empty($_POST['pseudoU']) && !empty($_POST['mailU']) && !empty($_POST['mdpU'])) {
        $pseudoU = $_POST['pseudoU'];
        $mailU = $_POST['mailU'];
        $mdpU = $_POST['mdpU'];

        // Préparer et exécuter la requête d'insertion
        $requete = $bdd->prepare("INSERT INTO utilisateur (mailU, mdpU, pseudoU) VALUES (:mailU, :mdpU, :pseudoU)");
        $requete->execute(array(
            "mailU" => $mailU,
            "mdpU" => $mdpU,
            "pseudoU" => $pseudoU
        ));

        echo "Inscription réussie !";
    } else {
        echo "Veuillez remplir tous les champs du formulaire.";
    }
}
?>