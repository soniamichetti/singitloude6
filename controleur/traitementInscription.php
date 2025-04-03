<?php 
include_once "../modele/db.php";
include_once "../modele/security.php";

// Rediriger si déjà connecté
if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: ../vue/admin.php");
    } else {
        header("Location: ../vue/albums.php");
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['pseudoU'], $_POST['mailU'], $_POST['mdpU'])) {
        // Nettoyage et validation des entrées
        $pseudoU = cleanInput($_POST['pseudoU']);
        $mailU = cleanInput($_POST['mailU']);
        $mdpU_clair = $_POST['mdpU']; // Ne pas appliquer htmlspecialchars au mot de passe avant le hashage

        // Vérification des champs vides
        if (empty($pseudoU) || empty($mailU) || empty($mdpU_clair)) {
            header("Location: ../vue/vueInscription.php?error=Veuillez remplir tous les champs");
            exit;
        }

        // Validation de l'email
        if (!filter_var($mailU, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../vue/vueInscription.php?error=Adresse email invalide");
            exit;
        }

        // Validation du mot de passe (au moins 8 caractères)
        if (strlen($mdpU_clair) < 8) {
            header("Location: ../vue/vueInscription.php?error=Le mot de passe doit avoir au moins 8 caractères");
            exit;
        }

        // MODIFICATION IMPORTANTE : 
        // Utiliser un coût plus élevé pour plus de sécurité
        $mdpU = password_hash($mdpU_clair, PASSWORD_DEFAULT, ['cost' => 12]);

        // Vérification du pseudo unique
        $requete_verif_pseudo = $bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE pseudoU = :pseudoU");
        $requete_verif_pseudo->execute(array("pseudoU" => $pseudoU));
        $count_pseudo = $requete_verif_pseudo->fetchColumn();

        if ($count_pseudo > 0) {
            header("Location: ../vue/vueInscription.php?error=Ce pseudo est déjà utilisé. Veuillez en choisir un autre");
            exit;
        }

        // Vérification de l'email unique
        $requete_verif_compte = $bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE mailU = :mailU");
        $requete_verif_compte->execute(array("mailU" => $mailU));
        $count_compte = $requete_verif_compte->fetchColumn();

        if ($count_compte > 0) {
            header("Location: ../vue/vueInscription.php?error=Un compte existe déjà avec cette adresse email");
            exit;
        }

        // Insertion du nouvel utilisateur
        try {
            $requete = $bdd->prepare("INSERT INTO utilisateur (mailU, mdpU, pseudoU, userType) VALUES (:mailU, :mdpU, :pseudoU, 'utilisateur')");
            $requete->execute(array(
                "mailU" => $mailU,
                "mdpU" => $mdpU,
                "pseudoU" => $pseudoU
            ));

            // Connexion automatique après inscription
            $_SESSION['pseudo'] = $pseudoU;
            $_SESSION['mailU'] = $mailU;
            $_SESSION['userType'] = 'utilisateur';

            // Rediriger vers la page albums avec notification de succès
            header("Location: ../vue/albums.php?login_success=true");
            exit;
        } catch (PDOException $e) {
            header("Location: ../vue/vueInscription.php?error=Erreur lors de l'inscription: " . $e->getMessage());
            exit;
        }
    } else {
        header("Location: ../vue/vueInscription.php?error=Formulaire incomplet");
        exit;
    }
} else {
    // Accès direct au script
    header("Location: ../vue/vueInscription.php");
    exit;
}
?>