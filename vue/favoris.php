<?php
// Inclure les fichiers nécessaires
include_once "../modele/security.php";
include_once "../modele/database.php";

// Vérifier que l'utilisateur est connecté
requireLogin();

$pageTitle = "Mes Favoris";
$pageHeading = "MES FAVORIS";

// Récupérer les favoris de l'utilisateur
$favoris = getFavoris($_SESSION['mailU']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> ⎸ SingLouder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style/styleF.css" type="text/css" rel="stylesheet">
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
                    <li class="nav-item">
                        <a class="nav-link active" href="../vue/favoris.php">Favoris</a>
                    </li>
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

    <main class="container my-4">
        <?php if(isset($_GET['fav_removed']) && $_GET['fav_removed'] == 'true'): ?>
            <div class="alert alert-success text-center my-3">
                L'album a été retiré de vos favoris.
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['fav_error']) && $_GET['fav_error'] == 'true'): ?>
            <div class="alert alert-danger text-center my-3">
                Une erreur s'est produite lors de la gestion des favoris.
            </div>
        <?php endif; ?>
        
        <?php if(count($favoris) > 0): ?>
            <div class="whole">
                <?php foreach ($favoris as $favori): ?>
                    <div class="album">
                        <h5 class="nom_ART"><?php echo htmlspecialchars($favori['nomArt']); ?></h5>
                        <div><img src="../img/<?php echo $favori['photo']; ?>" alt="<?php echo htmlspecialchars($favori['titreAlb']); ?>"></div>
                        <h4 class="nom_ART"><?php echo htmlspecialchars($favori['titreAlb']); ?> - <?php echo $favori['prix']; ?>€</h4>
                        <div class="d-flex justify-content-between px-3">
                            <form method="post" action="../vue/cart.php">
                                <button type="submit" class="btn btn-danger" name="add_to_cart">Ajouter au panier</button>
                                <input type="hidden" name="id_album" value="<?php echo $favori['idAlbum']; ?>">
                            </form>
                            <a href="../controleur/gestionFavoris.php?action=supprimer&id=<?php echo $favori['idAlbum']; ?>&redirect=<?php echo urlencode('../vue/favoris.php'); ?>" class="btn btn-outline-secondary">
                                Retirer des favoris
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center my-5">
                <h4>Vous n'avez pas encore d'albums favoris</h4>
                <p>Parcourez notre sélection d'albums et ajoutez ceux qui vous plaisent à vos favoris !</p>
                <a href="../vue/albums.php" class="btn btn-secondary">Voir les albums</a>
            </div>
        <?php endif; ?>
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