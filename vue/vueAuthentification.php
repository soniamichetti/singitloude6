<?php
// Inclure le fichier de sécurité
include_once "../modele/security.php";

// Si l'utilisateur est déjà connecté, rediriger vers la page d'accueil
if (isLoggedIn()) {
    header("Location: ../vue/albums.php");
    exit();
}

$pageTitle = "Connexion";
$error_msg = isset($_GET['error']) ? $_GET['error'] : "";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> ⎸ SingLouder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style/stylelo.css" type="text/css" rel="stylesheet">
    <link href="../style/styleCommon.css" type="text/css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar-top">
                <div class="logo">
                    <a href="../vue/artistes.php">
                        <img src="../img/logo.png" alt="SingLouder" width="200px">
                    </a>
                </div>
                <div class="icons">
                    <a href="../vue/vueInscription.php"><img src="../img/inscription.webp" alt="inscription" width="30px"></a>
                    <a href="../vue/cart.php"><img src="../img/shopping.webp" alt="panier" width="30px"></a>
                </div>
            </div>
        </div>
    </header>

    <div class="wrapper">
        <div class="container main">
            <div class="row">
                <div class="col-md-6 side-image">
                    <div class="text">
                        <p>Écoutez vos artistes préférés et chantez avec eux !<br><i>Inscrivez-vous pour acheter des albums</i></p>
                    </div>
                </div>

                <div class="col-md-6 right">
                    <form method="POST" action="../controleur/traitementAuthentification.php">
                        <div class="input-box">
                            <header>Connexion</header>
                            <?php if($error_msg): ?>
                                <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                            <?php endif; ?>
                            <div class="input-field">
                                <input type="text" class="input" id="mailU" name="mailU" required autocomplete="on">
                                <label for="mailU">Email</label>
                            </div>
                            <div class="input-field">
                                <input type="password" class="input" id="mdpU" name="mdpU" required>
                                <label for="mdpU">Mot de Passe</label>
                            </div>
                            <div class="input-field">
                                <input type="submit" class="submit" value="Connexion">
                            </div> 
                            <div class="signin">
                                <span>Vous n'avez pas de compte ? <a href="../vue/vueInscription.php">Inscrivez-vous ici</a></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col">
                    <ul>
                        <li><a href="../vue/mentions.html">Mentions Légales</a></li>
                        <li><a href="../vue/CGU.html">CGU</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>