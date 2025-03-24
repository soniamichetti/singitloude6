<?php
// Inclure les fichiers nécessaires
include_once "../modele/security.php";
include_once "../modele/database.php";

$pageTitle = "Mon Panier";
$pageHeading = "MON PANIER";

// Initialize cart in session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle adding item to cart - nécessite d'être connecté
if (isset($_POST['add_to_cart']) && isset($_POST['id_album'])) {
    if (!isLoggedIn()) {
        header("Location: ../vue/vueAuthentification.php?error=Vous devez être connecté pour ajouter des produits au panier");
        exit;
    }
    
    $albumId = (int)$_POST['id_album'];
    
    // Check if the album exists
    $album = getAlbumById($albumId);
    
    if ($album) {
        // Add to cart in session
        if (isset($_SESSION['cart'][$albumId])) {
            // Increment quantity if already in cart
            $_SESSION['cart'][$albumId]['quantity']++;
        } else {
            // Add new item to cart
            $_SESSION['cart'][$albumId] = [
                'id' => $albumId,
                'title' => $album['titreAlb'],
                'price' => $album['prix'],
                'quantity' => 1,
                'photo' => $album['photo']
            ];
        }
        
        // Redirect back to albums page
        header("Location: ../vue/albums.php?added=true");
        exit();
    }
}

// Handle removing item from cart
if (isset($_GET['remove']) && isset($_GET['id'])) {
    $albumId = (int)$_GET['id'];
    
    // Remove from session cart
    if (isset($_SESSION['cart'][$albumId])) {
        unset($_SESSION['cart'][$albumId]);
    }
    
    // Redirect back to cart
    header("Location: ../vue/cart.php");
    exit();
}

// Calculate cart total
function getCartTotal() {
    $total = 0;
    
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    
    return $total;
}

$cartItems = $_SESSION['cart'] ?? [];
$cartTotal = getCartTotal();
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

    <main class="container my-4">
        <?php if(!isLoggedIn()): ?>
            <div class="alert alert-info text-center my-4">
                Vous devez être connecté pour utiliser le panier. <a href="../vue/vueAuthentification.php" class="btn btn-primary btn-sm ms-2">Se connecter</a>
            </div>
        <?php else: ?>
            <?php if (count($cartItems) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Album</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td><img src="../img/<?php echo $item['photo']; ?>" class="img-thumbnail" alt="<?php echo htmlspecialchars($item['title']); ?>" style="max-width: 80px;"></td>
                                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                                    <td><?php echo number_format($item['price'], 2); ?> €</td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?> €</td>
                                    <td>
                                        <a href="../vue/cart.php?remove=true&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total :</td>
                                <td class="fw-bold"><?php echo number_format($cartTotal, 2); ?> €</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end my-3">
                    <a href="../vue/albums.php" class="btn btn-secondary me-2">Continuer mes achats</a>
                    <a href="#" class="btn btn-primary">Procéder au paiement</a>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center my-5">
                    <h4>Votre panier est vide</h4>
                    <p>Découvrez nos albums et ajoutez-les à votre panier.</p>
                    <a href="../vue/albums.php" class="btn btn-primary mt-3">Voir les albums</a>
                </div>
            <?php endif; ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>