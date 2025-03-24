<?php
// Inclure les fichiers nécessaires
include_once "../modele/security.php";
include_once "../modele/database.php";

$pageTitle = "Albums";
$pageHeading = "ALBUMS";

// Récupérer tous les genres
$genres = getGenres();

// Vérifier si un genre est sélectionné
$selectedGenre = null;
$genreName = "Tous les albums";

if (isset($_GET['genre']) && is_numeric($_GET['genre'])) {
    $selectedGenre = (int)$_GET['genre'];
    
    // Récupérer le nom du genre sélectionné
    foreach ($genres as $genre) {
        if ($genre['idGenre'] == $selectedGenre) {
            $genreName = "Albums de genre : " . $genre['nomGenre'];
            break;
        }
    }
    
    // Récupérer les albums du genre sélectionné
    $albums = getAlbumsByGenre($selectedGenre);
} else {
    // Récupérer tous les albums
    $albums = getAlbums();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> ⎸ SingLouder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style/styleAlbs.css" type="text/css" rel="stylesheet">
    <link href="../style/styleCommon.css" type="text/css" rel="stylesheet">
    <style>
        .genres-slider {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding: 10px 0;
            scroll-behavior: smooth;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        
        .genres-slider::-webkit-scrollbar {
            height: 8px;
        }
        
        .genres-slider::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .genres-slider::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        .genres-slider::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        .genre-button {
            display: inline-block;
            white-space: nowrap;
            min-width: fit-content;
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
                        <a class="nav-link" href="../vue/artistes.php">Artistes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../vue/albums.php">Albums</a>
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
    
    <main class="container my-4">
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center my-3">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['added']) && $_GET['added'] == 'true'): ?>
            <div class="alert alert-success text-center my-3">
                L'album a été ajouté à votre panier.
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['fav_added']) && $_GET['fav_added'] == 'true'): ?>
            <div class="alert alert-success text-center my-3">
                L'album a été ajouté à vos favoris.
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['fav_removed']) && $_GET['fav_removed'] == 'true'): ?>
            <div class="alert alert-success text-center my-3">
                L'album a été retiré de vos favoris.
            </div>
        <?php endif; ?>
        
        <!-- Barre de genres défilante -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Filtrer par genre</h5>
            </div>
            <div class="card-body p-2">
                <div class="genres-slider">
                    <a href="../vue/albums.php" class="btn <?php echo !$selectedGenre ? 'btn-primary' : 'btn-outline-primary'; ?> genre-button">
                        Tous les genres
                    </a>
                    <?php foreach ($genres as $genre): ?>
                        <a href="../vue/albums.php?genre=<?php echo $genre['idGenre']; ?>" 
                           class="btn <?php echo $selectedGenre == $genre['idGenre'] ? 'btn-primary' : 'btn-outline-primary'; ?> genre-button">
                            <?php echo $genre['nomGenre']; ?> 
                            <span class="badge bg-secondary"><?php echo $genre['nbAlbums']; ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <h2 class="mb-4"><?php echo $genreName; ?></h2>
        
        <?php if(count($albums) === 0): ?>
            <div class="alert alert-info text-center">
                Aucun album disponible dans cette catégorie.
            </div>
        <?php else: ?>
            <div class="whole">
                <?php foreach ($albums as $album): ?>
                    <div class="album">
                        <h5 class="nom_ART"><?php echo $album['nomArt']; ?></h5>
                        <div><img src="../img/<?php echo $album['photo']; ?>" alt="<?php echo $album['titreAlb']; ?>"></div>
                        <h4 class="nom_ART"><?php echo $album['titreAlb']; ?> - <?php echo $album['prix']; ?>€</h4>
                        <div class="d-flex justify-content-between px-3">
                            <?php if(isLoggedIn()): ?>
                                <form method="post" action="../vue/cart.php">
                                    <button type="submit" class="btn btn-danger" name="add_to_cart">Ajouter au panier</button>
                                    <input type="hidden" name="id_album" value="<?php echo $album['idAlbum']; ?>">
                                </form>
                                
                                <?php if(estFavori($_SESSION['mailU'], $album['idAlbum'])): ?>
                                    <a href="../controleur/gestionFavoris.php?action=supprimer&id=<?php echo $album['idAlbum']; ?>&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-outline-secondary">
                                        Retirer des favoris
                                    </a>
                                <?php else: ?>
                                    <a href="../controleur/gestionFavoris.php?action=ajouter&id=<?php echo $album['idAlbum']; ?>&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-outline-success">
                                        Ajouter aux favoris
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="../vue/vueAuthentification.php" class="btn btn-secondary w-100">Connectez-vous pour acheter</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

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

    <!-- Modal pour le message de connexion réussie -->
    <div class="modal fade" id="loginSuccessModal" tabindex="-1" aria-labelledby="loginSuccessModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="loginSuccessModalLabel">Connexion réussie</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Bienvenue <?php echo isset($_SESSION['pseudo']) ? $_SESSION['pseudo'] : ''; ?> ! Vous êtes maintenant connecté.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vérifier si le paramètre de connexion réussie est présent dans l'URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('login_success') === 'true') {
            // Afficher le modal de connexion réussie
            const loginSuccessModal = new bootstrap.Modal(document.getElementById('loginSuccessModal'));
            loginSuccessModal.show();
            
            // Modifier l'URL pour supprimer le paramètre
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
    </script>
</body>
</html>