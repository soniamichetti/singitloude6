<?php
// Inclure les fichiers nécessaires
include_once "../modele/security.php";
include_once "../modele/database.php";

// Vérifier que l'utilisateur est connecté
requireLogin();

$pageTitle = "Paiement";
$pageHeading = "PAIEMENT";
$currentPage = ""; // Pas de menu actif pour cette page

// Calculer le total du panier
function getCartTotal() {
    $total = 0;
    
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    
    return $total;
}

$cartTotal = getCartTotal();

// Vérifier si le panier est vide
if (count($_SESSION['cart'] ?? []) === 0) {
    header("Location: ../vue/cart.php");
    exit;
}

// Traitement du formulaire de paiement
$paymentSuccess = false;
$paymentError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données
    $cardName = cleanInput($_POST['card_name'] ?? '');
    $cardNumber = cleanInput($_POST['card_number'] ?? '');
    $cardExpiry = cleanInput($_POST['card_expiry'] ?? '');
    $cardCvv = cleanInput($_POST['card_cvv'] ?? '');
    $address = cleanInput($_POST['address'] ?? '');
    $city = cleanInput($_POST['city'] ?? '');
    $postalCode = cleanInput($_POST['postal_code'] ?? '');
    $country = cleanInput($_POST['country'] ?? '');
    
    // Valider les entrées
    $errors = [];
    
    if (empty($cardName)) {
        $errors[] = "Le nom sur la carte est requis";
    }
    
    if (empty($cardNumber) || !preg_match('/^[0-9]{16}$/', str_replace(' ', '', $cardNumber))) {
        $errors[] = "Numéro de carte invalide";
    }
    
    if (empty($cardExpiry) || !preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $cardExpiry)) {
        $errors[] = "Date d'expiration invalide (format MM/YY attendu)";
    } else {
        // Vérifier que la date d'expiration n'est pas passée
        list($month, $year) = explode('/', $cardExpiry);
        $expDate = \DateTime::createFromFormat('my', $month . $year);
        $now = new \DateTime();
        
        if ($expDate < $now) {
            $errors[] = "Carte expirée";
        }
    }
    
    if (empty($cardCvv) || !preg_match('/^[0-9]{3,4}$/', $cardCvv)) {
        $errors[] = "CVV invalide";
    }
    
    if (empty($address)) {
        $errors[] = "L'adresse est requise";
    }
    
    if (empty($city)) {
        $errors[] = "La ville est requise";
    }
    
    if (empty($postalCode)) {
        $errors[] = "Le code postal est requis";
    }
    
    if (empty($country)) {
        $errors[] = "Le pays est requis";
    }
    
    // Si pas d'erreur, traiter le paiement
    if (empty($errors)) {
        // Dans un environnement réel, ici on appellerait une API de paiement
        // Pour ce projet, on simule un paiement réussi
        
        // Vider le panier après paiement réussi
        $_SESSION['cart'] = [];
        
        // Rediriger vers une page de confirmation
        header("Location: ../vue/confirmation.php");
        exit;
    } else {
        $paymentError = implode("<br>", $errors);
    }
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
    <link href="../style/styleC.css" type="text/css" rel="stylesheet">
    <style>
        .payment-form label {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .payment-summary {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        
        .payment-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .payment-card-header {
            border-bottom: 1px solid #eee;
            margin-bottom: 15px;
            padding-bottom: 15px;
        }
        
        .payment-btn {
            font-weight: 600;
            padding: 10px 20px;
        }
        
        .credit-card-img {
            max-height: 40px;
            margin-right: 10px;
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
        <?php if ($paymentError): ?>
            <div class="alert alert-danger mb-4">
                <?php echo $paymentError; ?>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-lg-8">
                <form method="post" action="" class="payment-form">
                    <!-- Détails de la carte -->
                    <div class="payment-card">
                        <div class="payment-card-header d-flex align-items-center">
                            <h3 class="h5 mb-0">Informations de paiement</h3>
                            <div class="ms-auto">
                                <img src="../img/visa.png" alt="Visa" class="credit-card-img">
                                <img src="../img/mastercard.svg" alt="Mastercard" class="credit-card-img">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="card_name" class="form-label">Nom sur la carte</label>
                                <input type="text" id="card_name" name="card_name" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="card_number" class="form-label">Numéro de carte</label>
                                <input type="text" id="card_number" name="card_number" class="form-control" placeholder="XXXX XXXX XXXX XXXX" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="card_expiry" class="form-label">Date d'expiration</label>
                                <input type="text" id="card_expiry" name="card_expiry" class="form-control" placeholder="MM/YY" required>
                            </div>
                            <div class="col-md-6">
                                <label for="card_cvv" class="form-label">CVV</label>
                                <input type="text" id="card_cvv" name="card_cvv" class="form-control" placeholder="XXX" required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Adresse de facturation -->
                    <div class="payment-card">
                        <div class="payment-card-header">
                            <h3 class="h5 mb-0">Adresse de facturation</h3>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="address" class="form-label">Adresse</label>
                                <input type="text" id="address" name="address" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" id="city" name="city" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="postal_code" class="form-label">Code postal</label>
                                <input type="text" id="postal_code" name="postal_code" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="country" class="form-label">Pays</label>
                                <select id="country" name="country" class="form-select" required>
                                    <option value="">Sélectionnez un pays</option>
                                    <option value="FR">France</option>
                                    <option value="BE">Belgique</option>
                                    <option value="CH">Suisse</option>
                                    <option value="CA">Canada</option>
                                    <option value="LU">Luxembourg</option>
                                    <option value="MC">Monaco</option>
                                    <option value="DZ">Algérie</option>
                                    <option value="MA">Maroc</option>
                                    <option value="TN">Tunisie</option>
                                    <option value="SN">Sénégal</option>
                                    <option value="CI">Côte d'Ivoire</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary payment-btn">Confirmer le paiement</button>
                    </div>
                </form>
            </div>
            
            <div class="col-lg-4">
                <div class="payment-summary">
                    <h3 class="h5 mb-4">Récapitulatif de commande</h3>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total :</span>
                        <span><?php echo number_format($cartTotal, 2); ?> €</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Frais de livraison :</span>
                        <span>0.00 €</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>TVA (20%) :</span>
                        <span><?php echo number_format($cartTotal * 0.2, 2); ?> €</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2 fw-bold">
                        <span>Total :</span>
                        <span><?php echo number_format($cartTotal * 1.2, 2); ?> €</span>
                    </div>
                    
                    <div class="mt-4">
                        <div class="alert alert-info mb-0">
                            <small>
                                <i class="bi bi-info-circle"></i> En effectuant ce paiement, vous acceptez nos <a href="../vue/CGU.php" class="alert-link">Conditions Générales d'Utilisation</a>.
                            </small>
                        </div>
                    </div>
                </div>
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
    <script>
    // Formater automatiquement le numéro de carte
    document.getElementById('card_number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        // Ajouter des espaces tous les 4 chiffres
        if (value.length > 0) {
            value = value.match(/.{1,4}/g).join(' ');
        }
        
        // Limiter à 19 caractères (16 chiffres + 3 espaces)
        value = value.substring(0, 19);
        
        e.target.value = value;
    });
    
    // Formater automatiquement la date d'expiration
    document.getElementById('card_expiry').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        
        e.target.value = value;
    });
    
    // Limiter le CVV à 3-4 chiffres
    document.getElementById('card_cvv').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = value.substring(0, 4);
    });
    </script>
</body>
</html>