<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>user page</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../style/styleC.css">

</head>
<body>
  <div class="logo">
     <a href="../vue/artistes.html"><img src="../img/logo.png" alt="" width="200px"></a> 
  </div>
   
<div class="container">

   <div class="content">
      <h3>Salut, <span>utilisateur</span></h3>
      <h1>bienvenue <span><?php echo $_SESSION['pseudo'] ?></span></h1>
      <p>C'est une page utilisateur</p>
      <a href="../vue/vueAuthentification.php" class="btn">connexion</a>
      <a href="../vue/vueInscription.php" class="btn">inscription</a>
      <a href="../deconnexion.php" class="btn">deconnexion</a>
   </div>

</div>

</body>
</html>