<?php
// vue/genres.php
include_once "../modele/security.php";
include "../modele/db.php";

$pageTitle = "Genres Musicaux";
$pageHeading = "GENRES MUSICAUX";

// Fonction pour récupérer tous les genres
function getGenres() {
    global $bdd;
    $sql = "SELECT * FROM genres ORDER BY nomGenre";
    $stmt = $bdd->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les albums d'un genre spécifique
function getAlbumsByGenre($idGenre) {
    global $bdd;
    $sql = "SELECT a.*, art.nomArt FROM albums a 
            JOIN album_genre ag ON a.idAlbum = ag.idAlbum 
            JOIN artistes art ON a.idArtiste = art.idArtiste 
            WHERE ag.idGenre = :idGenre
            ORDER BY a.titreAlb";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':idGenre', $idGenre);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour vérifier si un album est en favori
function estFavori($mailU, $idAlbum) {
    if (!isLoggedIn()) return false;
    
    global $bdd;
    try {
        $req_check = $bdd->prepare("SELECT COUNT(*) FROM favoris WHERE mailU = :mailU AND idAlbum = :idAlbum");
        $req_check->execute([
            ':mailU' => $mailU,
            ':idAlbum' => $idAlbum
        ]);
        
        return $req_check->fetchColumn() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

// Récupérer tous les genres
$genres = getGenres();

// Vérifier si un genre est sélectionné
$selectedGenre = null;
$albumsFiltered = [];
$genreName = "Tous les genres";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $selectedGenre = (int)$_GET['id'];
    $albumsFiltered = getAlbumsByGenre($selectedGenre);
    
    // Récupérer le nom du genre sélectionné
    foreach ($genres as $genre) {
        if ($genre['idGenre'] == $selectedGenre) {
            $genreName = $genre['nomGenre'];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> ⎸ SingLouder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- D'abord le style spécifique à la page pour préserver les couleurs -->
    <link href="../style/styleAlbs.css" type="text/css" rel="stylesheet">
    <!-- Puis le style commun pour la structure -->
    <link href="../style/styleCommon.css" type="text/css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar-top">
                <div class="search-bar">
                    <input type="text" placeholder="Rechercher..." aria-label="Rechercher">
                </div>
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
                        <a class="nav-link" href="../vue/albums.php">Albums</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../vue/genres.php">Genres</a>
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
        <?php if(isset($_GET['fav_added'])): ?>
            <div class="alert alert-success text-center my-3">
                L'album a été ajouté à vos favoris.
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['fav_removed'])): ?>
            <div class="alert alert-success text-center my-3">
                L'album a été retiré de vos favoris.
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['fav_error'])): ?>
            <div class="alert alert-danger text-center my-3">
                Une erreur s'est produite lors de la gestion des favoris.
            </div>
        <?php endif; ?>
        
        <!-- Liste des genres -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Parcourir par genre</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="../vue/genres.php" class="btn <?php echo !$selectedGenre ? 'btn-primary' : 'btn-outline-primary'; ?>">
                                Tous les genres
                            </a>
                            <?php foreach ($genres as $genre): ?>
                                <a href="../vue/genres.php?id=<?php echo $genre['idGenre']; ?>" 
                                   class="btn <?php echo $selectedGenre == $genre['idGenre'] ? 'btn-primary' : 'btn-outline-primary'; ?>">
                                    <?php echo $genre['nomGenre']; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Affichage des albums filtrés par genre -->
        <h2 class="mb-4"><?php echo $genreName; ?></h2>
        
        <?php if($selectedGenre && count($albumsFiltered) === 0): ?>
            <div class="alert alert-info">
                Aucun album trouvé pour ce genre.
            </div>
        <?php elseif($selectedGenre): ?>
            <div class="whole">
                <?php foreach ($albumsFiltered as $album): ?>
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
                                    <a href="../controleur/gestionFavoris.php?action=supprimer&id=<?php echo $album['idAlbum']; ?>&redirect=../vue/genres.php?id=<?php echo $selectedGenre; ?>" class="btn btn-outline-secondary">
                                        Retirer des favoris
                                    </a>
                                <?php else: ?>
                                    <a href="../controleur/gestionFavoris.php?action=ajouter&id=<?php echo $album['idAlbum']; ?>&redirect=../vue/genres.php?id=<?php echo $selectedGenre; ?>" class="btn btn-outline-success">
                                        Ajouter aux favoris
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="../vue/vueAuthentification.php" class="btn btn-secondary">Connectez-vous pour acheter</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">Veuillez sélectionner un genre pour voir les albums correspondants.</p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>