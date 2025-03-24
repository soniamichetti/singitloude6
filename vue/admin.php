<?php
// Inclure les fichiers nécessaires
include_once "../modele/security.php";
include_once "../modele/database.php";

// Vérifier que l'utilisateur est un administrateur
requireAdmin();

$pageTitle = "Administration";
$pageHeading = "ADMINISTRATION";

// Récupérer les données nécessaires
$artistes = getArtistes();
$albums = getAlbums();
$genres = getGenres();

// Initialiser les messages
$message = '';
$messageType = '';
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'albums';

// Traitement des formulaires
// 1. Ajouter un artiste
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_artiste'])) {
    $nomArt = cleanInput($_POST['nomArt']);
    $paysArt = cleanInput($_POST['paysArt']);
    $metier = cleanInput($_POST['metier']);
    $photoArt = cleanInput($_POST['photoArt']);
    
    if (empty($nomArt)) {
        $message = "Le nom de l'artiste est obligatoire.";
        $messageType = "danger";
    } else {
        $result = addArtiste($nomArt, $paysArt, $photoArt, $metier);
        
        if ($result) {
            $message = "Artiste ajouté avec succès !";
            $messageType = "success";
            // Recharger la liste des artistes
            $artistes = getArtistes();
        } else {
            $message = "Erreur lors de l'ajout de l'artiste.";
            $messageType = "danger";
        }
    }
    $activeTab = 'artistes';
}

// 2. Supprimer un artiste
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_artiste'])) {
    $idArtiste = (int)$_POST['id_artiste'];
    
    $albumCount = getAlbumCountByArtist($idArtiste);
    if ($albumCount > 0) {
        $message = "Impossible de supprimer cet artiste car il possède des albums.";
        $messageType = "warning";
    } else {
        $result = deleteArtiste($idArtiste);
        
        if ($result) {
            $message = "Artiste supprimé avec succès !";
            $messageType = "success";
            // Recharger la liste des artistes
            $artistes = getArtistes();
        } else {
            $message = "Erreur lors de la suppression de l'artiste.";
            $messageType = "danger";
        }
    }
    $activeTab = 'artistes';
}

// 3. Ajouter un album
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_album'])) {
    $titre = cleanInput($_POST['titre']);
    $prix = (float)$_POST['prix'];
    $photo = cleanInput($_POST['photo']);
    $anneeSortie = (int)$_POST['anneeSortie'];
    $idArtiste = (int)$_POST['id_artiste'];
    
    if (empty($titre) || empty($photo) || $prix <= 0 || $anneeSortie <= 0 || $idArtiste <= 0) {
        $message = "Tous les champs sont obligatoires et doivent être valides.";
        $messageType = "danger";
    } else {
        $result = addAlbum($titre, $prix, $photo, $anneeSortie, $idArtiste);
        
        if ($result) {
            $message = "Album ajouté avec succès !";
            $messageType = "success";
            // Recharger la liste des albums
            $albums = getAlbums();
        } else {
            $message = "Erreur lors de l'ajout de l'album.";
            $messageType = "danger";
        }
    }
    $activeTab = 'albums';
}

// 4. Supprimer un album
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_album'])) {
    $idAlbum = (int)$_POST['id_album'];
    
    $result = deleteAlbum($idAlbum);
    
    if ($result) {
        $message = "Album supprimé avec succès !";
        $messageType = "success";
        // Recharger la liste des albums
        $albums = getAlbums();
    } else {
        $message = "Erreur lors de la suppression de l'album.";
        $messageType = "danger";
    }
    $activeTab = 'albums';
}

// 5. Ajouter un genre
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_genre'])) {
    $nomGenre = cleanInput($_POST['nomGenre']);
    
    if (empty($nomGenre)) {
        $message = "Le nom du genre est obligatoire.";
        $messageType = "danger";
    } else {
        $result = addGenre($nomGenre);
        
        if ($result) {
            $message = "Genre ajouté avec succès !";
            $messageType = "success";
            // Recharger la liste des genres
            $genres = getGenres();
        } else {
            $message = "Erreur lors de l'ajout du genre. Il existe peut-être déjà.";
            $messageType = "danger";
        }
    }
    $activeTab = 'genres';
}

// 6. Supprimer un genre
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_genre'])) {
    $idGenre = (int)$_POST['id_genre'];
    
    $result = deleteGenre($idGenre);
    
    if ($result) {
        $message = "Genre supprimé avec succès !";
        $messageType = "success";
        // Recharger la liste des genres
        $genres = getGenres();
    } else {
        $message = "Erreur lors de la suppression du genre. Il est peut-être associé à des albums.";
        $messageType = "warning";
    }
    $activeTab = 'genres';
}

// 7. Associer un genre à un album
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['associate_genre'])) {
    $idAlbum = (int)$_POST['id_album'];
    $idGenre = (int)$_POST['id_genre'];
    
    if ($idAlbum <= 0 || $idGenre <= 0) {
        $message = "Veuillez sélectionner un album et un genre valides.";
        $messageType = "danger";
    } else {
        $result = associerAlbumGenre($idAlbum, $idGenre);
        
        if ($result) {
            $message = "Genre associé à l'album avec succès !";
            $messageType = "success";
        } else {
            $message = "Erreur lors de l'association du genre à l'album. Cette association existe peut-être déjà.";
            $messageType = "warning";
        }
    }
    $activeTab = 'association';
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
    <style>
        .nav-tabs .nav-link {
            color: #173B3E;
            background-color: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(23, 59, 62, 0.2);
            opacity: 0.7;
        }

        .nav-tabs .nav-link.active {
            color: #173B3E;
            background-color: #ffffff;
            border-color: #dee2e6 #dee2e6 #fff;
            opacity: 1;
        }
        
        .admin-card {
            margin-bottom: 20px;
        }
        
        .table-responsive {
            margin-top: 20px;
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
                            <li><a class="dropdown-item" href="../vue/admin.php">Administration</a></li>
                            <li><a class="dropdown-item" href="../vue/cart.php">Mon panier</a></li>
                            <li><a class="dropdown-item" href="../vue/favoris.php">Mes favoris</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../controleur/deconnexion.php">Déconnexion</a></li>
                        </ul>
                    </div>
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
                        <a class="nav-link" href="../vue/favoris.php">Favoris</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../vue/admin.php">Administration</a>
                    </li>
                </ul>
            </nav>
            <div class="content">
                <h1><?php echo $pageHeading; ?></h1>
            </div>
        </div>
    </header>

    <main class="container py-4">
        <?php if(!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <!-- Onglets de navigation -->
        <ul class="nav nav-tabs" id="adminTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $activeTab == 'albums' ? 'active' : ''; ?>" id="albums-tab" data-bs-toggle="tab" data-bs-target="#albums" type="button" role="tab" aria-controls="albums" aria-selected="<?php echo $activeTab == 'albums' ? 'true' : 'false'; ?>">Gestion des Albums</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $activeTab == 'artistes' ? 'active' : ''; ?>" id="artistes-tab" data-bs-toggle="tab" data-bs-target="#artistes" type="button" role="tab" aria-controls="artistes" aria-selected="<?php echo $activeTab == 'artistes' ? 'true' : 'false'; ?>">Gestion des Artistes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $activeTab == 'genres' ? 'active' : ''; ?>" id="genres-tab" data-bs-toggle="tab" data-bs-target="#genres" type="button" role="tab" aria-controls="genres" aria-selected="<?php echo $activeTab == 'genres' ? 'true' : 'false'; ?>">Gestion des Genres</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $activeTab == 'association' ? 'active' : ''; ?>" id="association-tab" data-bs-toggle="tab" data-bs-target="#association" type="button" role="tab" aria-controls="association" aria-selected="<?php echo $activeTab == 'association' ? 'true' : 'false'; ?>">Associations Album-Genre</button>
            </li>
        </ul>
        
        <!-- Contenu des onglets -->
        <div class="tab-content" id="adminTabsContent">
            <!-- Onglet Albums -->
            <div class="tab-pane fade <?php echo $activeTab == 'albums' ? 'show active' : ''; ?>" id="albums" role="tabpanel" aria-labelledby="albums-tab">
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card admin-card">
                            <div class="card-header">
                                <h3 class="card-title h5">Ajouter un nouvel album</h3>
                            </div>
                            <div class="card-body">
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label for="titre" class="form-label">Titre *</label>
                                        <input type="text" id="titre" name="titre" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="prix" class="form-label">Prix *</label>
                                        <input type="number" id="prix" name="prix" step="0.01" min="0.01" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="photo" class="form-label">Photo *</label>
                                        <input type="text" id="photo" name="photo" class="form-control" required>
                                        <small class="text-muted">Nom du fichier dans le dossier img (ex: album.jpg)</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="anneeSortie" class="form-label">Année de sortie *</label>
                                        <input type="number" id="anneeSortie" name="anneeSortie" min="1900" max="<?php echo date('Y'); ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="id_artiste" class="form-label">Artiste *</label>
                                        <select id="id_artiste" name="id_artiste" class="form-select" required>
                                            <option value="">Sélectionnez un artiste</option>
                                            <?php foreach ($artistes as $artiste): ?>
                                                <option value="<?php echo $artiste['idArtiste']; ?>"><?php echo htmlspecialchars($artiste['nomArt']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" name="add_album" class="btn btn-primary">Ajouter l'album</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card admin-card">
                            <div class="card-header">
                                <h3 class="card-title h5">Liste des albums</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Image</th>
                                                <th>Titre</th>
                                                <th>Artiste</th>
                                                <th>Prix</th>
                                                <th>Année</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($albums as $album): ?>
                                                <tr>
                                                    <td><?php echo $album['idAlbum']; ?></td>
                                                    <td>
                                                        <img src="../img/<?php echo $album['photo']; ?>" alt="<?php echo htmlspecialchars($album['titreAlb']); ?>" class="img-thumbnail" style="max-width: 50px;">
                                                    </td>
                                                    <td><?php echo htmlspecialchars($album['titreAlb']); ?></td>
                                                    <td><?php echo htmlspecialchars($album['nomArt']); ?></td>
                                                    <td><?php echo number_format($album['prix'], 2); ?> €</td>
                                                    <td><?php echo $album['anneeSortie']; ?></td>
                                                    <td>
                                                        <form method="post" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet album ?');">
                                                            <input type="hidden" name="id_album" value="<?php echo $album['idAlbum']; ?>">
                                                            <button type="submit" name="delete_album" class="btn btn-sm btn-danger">Supprimer</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Onglet Artistes -->
            <div class="tab-pane fade <?php echo $activeTab == 'artistes' ? 'show active' : ''; ?>" id="artistes" role="tabpanel" aria-labelledby="artistes-tab">
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card admin-card">
                            <div class="card-header">
                                <h3 class="card-title h5">Ajouter un nouvel artiste</h3>
                            </div>
                            <div class="card-body">
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label for="nomArt" class="form-label">Nom de l'artiste *</label>
                                        <input type="text" id="nomArt" name="nomArt" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="paysArt" class="form-label">Pays</label>
                                        <input type="text" id="paysArt" name="paysArt" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="metier" class="form-label">Métier</label>
                                        <input type="text" id="metier" name="metier" class="form-control">
                                        <small class="text-muted">Ex: Chanteur, Musicien, Groupe, etc.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="photoArt" class="form-label">Photo</label>
                                        <input type="text" id="photoArt" name="photoArt" class="form-control">
                                        <small class="text-muted">Nom du fichier dans le dossier img (ex: artiste.jpg)</small>
                                    </div>
                                    <button type="submit" name="add_artiste" class="btn btn-primary">Ajouter l'artiste</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card admin-card">
                            <div class="card-header">
                                <h3 class="card-title h5">Liste des artistes</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nom</th>
                                                <th>Pays</th>
                                                <th>Métier</th>
                                                <th>Albums</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($artistes as $artiste): ?>
                                                <?php $albumCount = getAlbumCountByArtist($artiste['idArtiste']); ?>
                                                <tr>
                                                    <td><?php echo $artiste['idArtiste']; ?></td>
                                                    <td><?php echo htmlspecialchars($artiste['nomArt']); ?></td>
                                                    <td><?php echo htmlspecialchars($artiste['paysArt'] ?? 'Non spécifié'); ?></td>
                                                    <td><?php echo htmlspecialchars($artiste['metier'] ?? 'Non spécifié'); ?></td>
                                                    <td><?php echo $albumCount; ?></td>
                                                    <td>
                                                        <?php if ($albumCount == 0): ?>
                                                            <form method="post" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet artiste ?');">
                                                                <input type="hidden" name="id_artiste" value="<?php echo $artiste['idArtiste']; ?>">
                                                                <button type="submit" name="delete_artiste" class="btn btn-sm btn-danger">Supprimer</button>
                                                            </form>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-secondary" disabled title="Cet artiste possède des albums et ne peut pas être supprimé">
                                                                Supprimer
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Onglet Genres -->
            <div class="tab-pane fade <?php echo $activeTab == 'genres' ? 'show active' : ''; ?>" id="genres" role="tabpanel" aria-labelledby="genres-tab">
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card admin-card">
                            <div class="card-header">
                                <h3 class="card-title h5">Ajouter un nouveau genre</h3>
                            </div>
                            <div class="card-body">
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label for="nomGenre" class="form-label">Nom du genre *</label>
                                        <input type="text" id="nomGenre" name="nomGenre" class="form-control" required>
                                    </div>
                                    <button type="submit" name="add_genre" class="btn btn-primary">Ajouter le genre</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card admin-card">
                            <div class="card-header">
                                <h3 class="card-title h5">Liste des genres</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nom</th>
                                                <th>Albums associés</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($genres as $genre): ?>
                                                <tr>
                                                    <td><?php echo $genre['idGenre']; ?></td>
                                                    <td><?php echo htmlspecialchars($genre['nomGenre']); ?></td>
                                                    <td><?php echo $genre['nbAlbums']; ?></td>
                                                    <td>
                                                        <?php if ($genre['nbAlbums'] == 0): ?>
                                                            <form method="post" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce genre ?');">
                                                                <input type="hidden" name="id_genre" value="<?php echo $genre['idGenre']; ?>">
                                                                <button type="submit" name="delete_genre" class="btn btn-sm btn-danger">Supprimer</button>
                                                            </form>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-secondary" disabled title="Ce genre est associé à des albums et ne peut pas être supprimé">
                                                                Supprimer
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Onglet Associations Album-Genre -->
            <div class="tab-pane fade <?php echo $activeTab == 'association' ? 'show active' : ''; ?>" id="association" role="tabpanel" aria-labelledby="association-tab">
                <div class="row mt-4">
                    <div class="col-md-6 mx-auto">
                        <div class="card admin-card">
                            <div class="card-header">
                                <h3 class="card-title h5">Associer un genre à un album</h3>
                            </div>
                            <div class="card-body">
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label for="id_album_assoc" class="form-label">Album *</label>
                                        <select id="id_album_assoc" name="id_album" class="form-select" required>
                                            <option value="">Sélectionnez un album</option>
                                            <?php foreach ($albums as $album): ?>
                                                <option value="<?php echo $album['idAlbum']; ?>"><?php echo htmlspecialchars($album['titreAlb']); ?> (<?php echo htmlspecialchars($album['nomArt']); ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="id_genre_assoc" class="form-label">Genre *</label>
                                        <select id="id_genre_assoc" name="id_genre" class="form-select" required>
                                            <option value="">Sélectionnez un genre</option>
                                            <?php foreach ($genres as $genre): ?>
                                                <option value="<?php echo $genre['idGenre']; ?>"><?php echo htmlspecialchars($genre['nomGenre']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" name="associate_genre" class="btn btn-primary">Associer</button>
                                </form>
                            </div>
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
                        <li><a href="../vue/mentions.html">Mentions Légales</a></li>
                        <li><a href="../vue/CGU.html">CGU</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activer l'onglet correct au chargement de la page
        var activeTab = "<?php echo $activeTab; ?>";
        var tab = new bootstrap.Tab(document.getElementById(activeTab + '-tab'));
        tab.show();
    });
    </script>
</body>
</html>