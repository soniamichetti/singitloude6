<?php
// Inclure les fichiers nécessaires
include_once "../modele/security.php";
include_once "../modele/database.php";

$pageTitle = "Artistes";
$pageHeading = "ARTISTES";

// Récupérer les artistes depuis la base de données
$artistes = getArtistes();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> ⎸ SingLouder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style/styleart.css" type="text/css" rel="stylesheet">
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
                    <?php if(isLoggedIn()): ?>
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
                    <?php else: ?>
                        <a href="../vue/vueAuthentification.php"><img src="../img/inscription.webp" alt="inscription" width="30px"></a>
                    <?php endif; ?>
                    
                    <a href="../vue/cart.php"><img src="../img/shopping.webp" alt="panier" width="30px"></a>
                </div>
            </div>
        </div>

        <div class="main-content">
            <nav class="navbar navbar-expand-sm bg-bisque justify-content-center sticky-top">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="../vue/artistes.php">Artistes</a>
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

    <main>
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center my-3">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        
        <div class="whole">
            <?php if (!empty($artistes)): ?>
                <?php foreach ($artistes as $artiste): ?>
                    <div class="artiste">
                        <h4><?php echo htmlspecialchars($artiste['nomArt']); ?></h4>
                        <?php if (isset($artiste['photoArt']) && !empty($artiste['photoArt'])): ?>
                            <img src="../img/<?php echo htmlspecialchars($artiste['photoArt']); ?>" alt="<?php echo htmlspecialchars($artiste['nomArt']); ?>" />
                        <?php else: ?>
                            <img src="../img/default_artist.png" alt="<?php echo htmlspecialchars($artiste['nomArt']); ?>" />
                        <?php endif; ?>
                        
                        <p><?php echo formatArtistDescription($artiste); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    Aucun artiste n'est disponible pour le moment.
                </div>
            <?php endif; ?>
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

<?php
// Fonction pour formater la description de l'artiste selon le nouveau format demandé
function formatArtistDescription($artiste) {
    $description = "";
    
    // Commencer par le métier
    if (!empty($artiste['metier'])) {
        $description = htmlspecialchars($artiste['metier']);
    } else {
        $description = "Artiste";
    }
    
    // Ajouter la provenance si disponible
    if (!empty($artiste['paysArt'])) {
        // Cas spécial pour les États-Unis
        if ($artiste['paysArt'] == 'États-Unis') {
            $description .= " originaire des États-Unis";
        } else {
            $description .= " originaire de " . htmlspecialchars($artiste['paysArt']);
        }
    }
    
    return $description;
}
?>