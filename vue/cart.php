<?php
session_start();
include_once "../modele/db.php";
include_once "../modele/database.php";

// Check if mailU is set
if (!isset($_SESSION['mailU'])) {
    // Redirect to login page
    header("Location: ../vue/vueAuthentification.php");
    exit();
}

// Get cart items
$cartItems = [];
$totalPrice = 0;

if (isset($_SESSION['mailU'])) {
    $cartItems = getCartItems($_SESSION['mailU']);
    $totalPrice = getTotalPrice($_SESSION['mailU']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart ⎸ SingLouder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <h1>Cart</h1>
    <div class="row">
        <div class="col-md-8">
            <table class="table">
                <thead>
                    <tr>
                        <th>Album</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?php echo $item['titreAlb']; ?></td>
                            <td><?php echo $item['prix']; ?>€</td>
                            <td>
                                <form method="post">
                                    <button type="submit" class="btn btn-danger" name="remove_from_cart">Supprimer</button>
                                    <input type="hidden" name="id_album" value="<?php echo $item['idAlbum']; ?>">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total</h5>
                    <p class="card-text"><?php echo $totalPrice; ?>€</p>
                    <a href="#" class="btn btn-primary">Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkm
