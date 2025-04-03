<?php
// Inclure les fichiers nécessaires
include_once "../modele/security.php";
include_once "../modele/database.php";

// Vérifier que l'utilisateur est connecté
requireLogin();

$pageTitle = "Confirmation de commande";
$pageHeading = "COMMANDE CONFIRMÉE";
$currentPage = ""; // Pas de menu actif pour cette page

// Rediriger si l'utilisateur arrive sur cette page par erreur (sans passer par le paiement)
if (!isset($_SESSION['cart_cleared']) && count($_SESSION['cart'] ?? []) > 0) {
    $_SESSION['cart_cleared'] = true;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> ⎸ SingLouder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style/styleCommon.css" type="text/css" rel="stylesheet">
    <link href="../style/styleAlbs.css" type="text/css" rel="stylesheet">
    <style>
        .confirmation-card {
            max-width: 700px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        
        .confirmation-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .confirmation-header i {
            font-size: 5rem;
            color: #28a745;
            margin-bottom: 20px;
        }
        
        .confirmation-details {
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .confirmation-footer {
            text-align: center;
            margin-top: 30px;
        }
        
        .order-number {
            font-weight: 600;
            color: #173B3E;
        }
    </style>
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
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle text-decoration-none" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../img/inscription.webp" alt="compte" width="30px">
                            <span class="ms-1"><?php echo $_SESSION['pseudo']; ?></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <?php if(isAdmin()): ?>
                                <li><a class="dropdown-item" href="../vue/admin.php">Administration</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="../vue/cart.php">Mon panier</a></li>
                            <li><a class="dropdown-item" href="../vue/favoris.php">Mes favoris</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../controleur/deconnexion.php">Déconnexion</a></li>
                        </ul>
                    </div>
                    <a href="../vue/cart.php"><img src="../img/shopping.webp" alt="panier" width="30px"></a>
                </div>
            </div>
        </div>

        <div class="main-content">
            <nav class="navbar navbar-expand-sm bg-bisque justify-content-center sticky-top">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../vue/artistes.php">Artistes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../vue/albums.php">Albums</a>
                    </li>
                    <?php if(isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../vue/favoris.php">Favoris</a>
                    </li>
                    <?php endif; ?>
                    <?php if(isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../vue/admin.php">Administration</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="content">
                <h1><?php echo $pageHeading; ?></h1>
            </div>
        </div>
    </header>

    <main class="container my-5">
        <div class="confirmation-card">
            <div class="confirmation-header">
                <div class="text-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#28a745" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                </div>
                <h2>Merci pour votre commande !</h2>
                <p>Votre commande a été confirmée et sera traitée dans les plus brefs délais.</p>
            </div>
            
            <div class="confirmation-details">
                <p>Numéro de commande : <span class="order-number"><?php echo strtoupper(uniqid('SL-')); ?></span></p>
                <p>Date de commande : <?php echo date('d/m/Y à H:i'); ?></p>
                <p>Un email de confirmation a été envoyé à <strong><?php echo $_SESSION['mailU']; ?></strong></p>
            </div>
            
            <div class="confirmation-footer">
                <p>Vous pouvez <a href="../vue/albums.php">continuer vos achats</a> ou consulter <a href="#">l'historique de vos commandes</a>.</p>
                <a href="../vue/albums.php" class="btn btn-primary mt-3">Retour à la boutique</a>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col">
                    <ul>
                        <li><a href="../vue/mentions.php">Mentions Légales</a></li>
                        <li><a href="../vue/CGU.php">CGU</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>