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
    <header>
        <div class="container">
            <div class="navbar-top">
                <div class="search-bar">
                    <input type="text" placeholder="Rechercher...">
                </div>
                <div class="logo">
                    <a href="../vue/artistes.html.php"><img src="../img/logo.png" alt="" width="200px"></a> 
                </div>
                <div class="icons">
                    <a href="../vue/login.html.php"><img src="../img/login.webp" alt="login" width="30px"></a>
                    <a href="#"><img src="../img/heart.svg" alt="like" width="30px"></a>
                </div>
            </div>
        </div>
        
    
  <div class="wrapper">
    <div class="container main">
        <div class="row">
            <div class="col-md-6 side-image">
                
                <div class="text">
                    <p>Ecoutez vos artistes préférés et chantez avec eux !</br> <i>Inscrivez vous pour avoir accès à un catalogue plus vaste</i></p>
                </div>
                
            </div>

            <div class="col-md-6 right">
                
                <div class="input-box">
                   
                   <header>Création d'un compte</header>
                   <div class="input-field">
                        <input type="text" class="input" id="pseudo" required="" autocomplete="off">
                        <label for="pseudo">Pseudo</label> 
                    </div> 
                   <div class="input-field">
                    <div class="input-field">
                        <input type="text" class="input" id="email" required="" autocomplete="on">
                        <label for="email">Email</label> 
                    </div> 
                   <div class="input-field">
                        <input type="password" class="input" id="pass" required="">
                        <label for="pass">Mot de Passe</label>
                    </div> 
                   <div class="input-field">
                        
                        <input type="submit" class="submit" value="Inscription">
                   </div> 
                   <div class="signin">
                    <span>Vous avez déjà un compte ?<a href="../vue/signin.html.php"> Connectez-vous ici</a></span>
                   </div>
                </div>  
            </div>
        </div>
    </div>
</div>
</body>
</html>