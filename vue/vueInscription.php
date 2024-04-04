<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Inscription ⎸ SingLouder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../style/stylelo.css" type="text/css" rel="stylesheet">
</head>
<body>
    <div class="col-md-6 right">
        <form method="POST" action="../controleur/traitement.php">
            <div class="input-box">
                <header>Création d'un compte</header>
                <div class="input-field">
                    <input type="text" class="input" id="pseudoU" name="pseudoU" required="" autocomplete="off">
                    <label for="pseudo">Pseudo</label>
                </div>
                <div class="input-field">
                    <input type="text" class="input" id="mailU" name="mailU" required="" autocomplete="on">
                    <label for="email">Email</label>
                </div>
                <div class="input-field">
                    <input type="password" class="input" id="mdpU" name="mdpU" required="">
                    <label for="pass">Mot de Passe</label>
                </div>
                <div class="input-field">
                    <input type="submit" class="submit" value="Inscription">
                </div>
                <div class="signin">
                    <span>Vous avez déjà un compte ?<a href="../vue/vueSignin.php"> Connectez-vous ici</a></span>
                </div>
            </div>
        </form>
    </div>
</body>

</html>