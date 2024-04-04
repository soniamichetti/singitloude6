<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/stylelo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Connexion ⎸ SingLouder</title>
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
                <a href="../vue/vueInscription.php"><img src="../img/inscription.webp" alt="inscription" width="30px"></a>
                <a href="../vue/vueInscription.php"><img src="../img/heart.svg" alt="like" width="30px"></a>
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
                   
                   <header>Connexion à un compte</header>

                   <div class="input-field">
                        <input type="text" class="input" id="email" required="" autocomplete="off">
                        <label for="email">Email</label> 
                    </div> 
                   <div class="input-field">
                        <input type="password" class="input" id="pass" required="">
                        <label for="pass">Mot de Passe</label>
                    </div> 
                   <div class="input-field">
                        
                        <input type="submit" class="submit" value="Connexion">
                   </div> 
                   <div class="signin">
                    <span>Vous n'avez pas de compte ? <a href="../vue/vueInscription.html.php">Inscrivez-vous ici</a></span>
                   </div>
                </div>  
            </div>
        </div>
    </div>
</div>
</body>
</html>