<?php
/**
 * Script à usage unique pour mettre à jour les mots de passe en clair vers des mots de passe hashés
 * À exécuter une seule fois lors de la mise à jour de l'application
 */

// Inclure la connexion à la base de données
include_once "../modele/db.php";

// Fonction pour mettre à jour tous les mots de passe
function updateAllPasswords() {
    global $bdd;
    
    try {
        // Vérifier d'abord que la colonne mdpU peut stocker des hash longs
        $colonneCheck = $bdd->query("SHOW COLUMNS FROM utilisateur LIKE 'mdpU'");
        $colonne = $colonneCheck->fetch(PDO::FETCH_ASSOC);
        
        // Si la colonne est trop petite (généralement varchar(50) à l'origine), la modifier
        if (strpos($colonne['Type'], 'varchar(50)') !== false) {
            $bdd->exec("ALTER TABLE utilisateur MODIFY mdpU VARCHAR(255)");
            echo "<p>La colonne mdpU a été agrandie pour stocker les hash de mot de passe.</p>";
        }
        
        // Récupérer tous les utilisateurs avec leurs mots de passe en clair
        $req = $bdd->query("SELECT mailU, mdpU FROM utilisateur");
        $users = $req->fetchAll(PDO::FETCH_ASSOC);
        
        $count = 0;
        
        foreach ($users as $user) {
            $mailU = $user['mailU'];
            $plainPassword = $user['mdpU'];
            
            // Vérifier si le mot de passe est déjà hashé (commence par $2y$)
            if (strpos($plainPassword, '$2y$') === 0) {
                echo "<p>Le mot de passe pour {$mailU} est déjà hashé.</p>";
                continue; // Passer à l'utilisateur suivant
            }
            
            // Créer un hash du mot de passe
            $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
            
            // Mettre à jour le mot de passe dans la base de données
            $updateReq = $bdd->prepare("UPDATE utilisateur SET mdpU = :mdpU WHERE mailU = :mailU");
            $updateReq->execute([
                ':mdpU' => $hashedPassword,
                ':mailU' => $mailU
            ]);
            
            $count++;
            echo "<p>Mot de passe mis à jour pour : {$mailU}</p>";
        }
        
        return [
            'success' => true,
            'message' => "$count mot(s) de passe mis à jour avec succès."
        ];
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => "Erreur lors de la mise à jour des mots de passe: " . $e->getMessage()
        ];
    }
}

// Interface utilisateur simple pour ce script
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour des mots de passe - SingLouder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Mise à jour des mots de passe - SingLouder</h1>
        
        <div class="card">
            <div class="card-header bg-warning">
                <h2 class="card-title h5 mb-0">⚠️ ATTENTION : Script à usage unique</h2>
            </div>
            <div class="card-body">
                <p>Ce script va convertir tous les mots de passe en clair de la base de données en mots de passe hashés sécurisés.</p>
                <p><strong>N'exécutez ce script qu'une seule fois, puis supprimez-le ou déplacez-le hors du répertoire web.</strong></p>
                
                <?php
                // Exécution du script uniquement si le formulaire est soumis
                if (isset($_POST['update'])) {
                    $result = updateAllPasswords();
                    
                    if ($result['success']) {
                        echo '<div class="alert alert-success">' . $result['message'] . '</div>';
                        echo '<div class="alert alert-warning">Tous les mots de passe ont été mis à jour. Veuillez supprimer ce fichier ou le déplacer hors du répertoire web pour des raisons de sécurité.</div>';
                    } else {
                        echo '<div class="alert alert-danger">' . $result['message'] . '</div>';
                    }
                } else {
                    // Formulaire de confirmation
                    ?>
                    <form method="post" action="">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="confirm" required>
                            <label class="form-check-label" for="confirm">J'ai fait une sauvegarde de la base de données et je comprends que cette opération est irréversible.</label>
                        </div>
                        <button type="submit" name="update" class="btn btn-danger">Mettre à jour les mots de passe</button>
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="../vue/admin.php" class="btn btn-secondary">Retour à l'administration</a>
        </div>
    </div>
</body>
</html>