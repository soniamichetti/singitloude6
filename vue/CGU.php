<?php
// Inclure le fichier de sécurité
include_once "../modele/security.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>CGU ⎸ SingLouder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style/stylem.css" type="text/css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar-top">
                <div class="logo">
                    <a href="../vue/artistes.php"><img src="../img/logo.png" alt="" width="200px"></a> 
                </div>
                <div class="icons">
                    <a href="../vue/vueAuthentification.php"><img src="../img/inscription.webp" alt="vueInscription" width="30px"></a>
                    <a href="../vue/cart.php"><img src="../img/shopping.webp" alt="like" width="30px"></a>
                </div>
            </div>
        </div> 
      </header>
      <div class="main-content">
    <nav class="navbar navbar-expand-sm bg-bisque justify-content-center sticky-top">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../vue/artistes.php">Artistes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../vue/albums.php">Albums</a>
            </li>
            <?php if(isset($_SESSION['mailU'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="../vue/favoris.php">Favoris</a>
            </li>
            <?php endif; ?>
            <?php if(isset($_SESSION['userType']) && $_SESSION['userType'] == 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link" href="../vue/admin.php">Administration</a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
       
<main>
    <div class="contenu">
    <h1>Conditions générales d'utilisation</h1>
    <h5>ARTICLE 1 - OBJET</h5>
        <p>Les présentes Conditions Générales d'Utilisation (ci-après "CGU") ont pour objet de définir les modalités et conditions dans lesquelles d'une part, SingLouder, ci-après dénommé l'Éditeur, met à la disposition de ses utilisateurs le site web SingLouder, et les services disponibles sur le site, et d'autre part, la manière par laquelle l'utilisateur accède au site et utilise ses services.</p>    
        <p>Toute connexion au site est subordonnée au respect des présentes CGU. Pour l'utilisateur, le simple accès au site de l'Éditeur implique l'acceptation de l'ensemble des conditions décrites ci-après.</p>
    
    <h5>ARTICLE 2 - DÉFINITIONS</h5>
    <p>La présente clause a pour objet de définir les différents termes essentiels du contrat :</p>
    <p>• <strong>Utilisateur</strong> : toute personne qui utilise le site ou l'un des services proposés par le site.<br>
    • <strong>Contenu utilisateur</strong> : ce sont les données transmises par l'Utilisateur au sein du site.<br>
    • <strong>Membre</strong> : l'Utilisateur devient membre lorsqu'il est identifié sur le site.<br>
    • <strong>Identifiant et mot de passe</strong> : c'est l'ensemble des informations nécessaires à l'identification d'un Utilisateur sur le site. L'identifiant et le mot de passe permettent à l'Utilisateur d'accéder à des services réservés aux membres du site. Le mot de passe est confidentiel.</p>
    
    <h5>ARTICLE 3 - ACCÈS AUX SERVICES</h5>
    <p>Le site permet à l'Utilisateur un accès gratuit aux services suivants :</p>
    <p>• Consultation des albums et artistes disponibles<br>
    • Création d'un compte utilisateur</p>
    
    <p>Le site est accessible gratuitement en tout lieu à tout Utilisateur ayant un accès à Internet. Tous les frais supportés par l'Utilisateur pour accéder au service (matériel informatique, logiciels, connexion Internet, etc.) sont à sa charge.</p>
    
    <p>L'Utilisateur non membre n'a pas accès aux services réservés aux membres. Pour cela, il doit s'identifier à l'aide de son identifiant et de son mot de passe.</p>
    
    <p>Le site met en œuvre tous les moyens mis à sa disposition pour assurer un accès de qualité à ses services. L'obligation étant de moyens, le site ne s'engage pas à atteindre ce résultat.</p>
    
    <p>Tout événement dû à un cas de force majeure ayant pour conséquence un dysfonctionnement du réseau ou du serveur n'engage pas la responsabilité de SingLouder.</p>
    
    <p>L'accès aux services du site peut à tout moment faire l'objet d'une interruption, d'une suspension, d'une modification sans préavis pour une maintenance ou pour tout autre cas. L'Utilisateur s'oblige à ne réclamer aucune indemnisation suite à l'interruption, à la suspension ou à la modification du présent contrat.</p>
    
    <h5>ARTICLE 4 - PROPRIÉTÉ INTELLECTUELLE</h5>
    <p>La structure générale du site SingLouder, ainsi que les textes, graphiques, images, sons et vidéos la composant, sont la propriété de l'Éditeur ou de ses partenaires. Toute représentation et/ou reproduction et/ou exploitation partielle ou totale des contenus et services proposés par le site SingLouder, par quelque procédé que ce soit, sans l'autorisation préalable et par écrit de SingLouder et/ou de ses partenaires est strictement interdite et serait susceptible de constituer une contrefaçon.</p>
    
    <p>Les marques "SingLouder" et "SingLoud", ainsi que les logos correspondants sont des marques déposées par SingLouder SAS. Toute représentation et/ou reproduction et/ou exploitation partielle ou totale de ces marques, de quelque nature que ce soit, est totalement prohibée.</p>
    
    <h5>ARTICLE 5 - RESPONSABILITÉ</h5>
    <p>Les sources des informations diffusées sur le site SingLouder sont réputées fiables mais le site ne garantit pas qu'il soit exempt de défauts, d'erreurs ou d'omissions.</p>
    
    <p>Les informations communiquées sont présentées à titre indicatif et général sans valeur contractuelle. Malgré des mises à jour régulières, le site SingLouder ne peut être tenu responsable de la modification des dispositions administratives et juridiques survenant après la publication. De même, le site ne peut être tenue responsable de l'utilisation et de l'interprétation de l'information contenue dans ce site.</p>
    
    <p>L'Utilisateur s'assure de garder son mot de passe secret. Toute divulgation du mot de passe, quelle que soit sa forme, est interdite. L'Utilisateur assume les risques liés à l'utilisation de son identifiant et mot de passe. Le site décline toute responsabilité.</p>
    
    <h5>ARTICLE 6 - COMPTE MEMBRE</h5>
    <p>Pour accéder à certains services, l'Utilisateur doit créer un compte et fournir des informations personnelles. L'utilisateur s'engage à fournir des informations exactes.</p>
    
    <p>L'utilisateur est responsable de la mise à jour des informations fournies. Il lui est précisé qu'il peut les modifier en se connectant à son compte.</p>
    
    <p>Pour la suppression du compte, l'Utilisateur peut envoyer un email à l'adresse : <a href="mailto:support@singloud.com">support@singloud.com</a>.</p>
    
    <h5>ARTICLE 7 - DROIT APPLICABLE ET JURIDICTION COMPÉTENTE</h5>
    <p>La législation française s'applique au présent contrat. En cas d'absence de résolution amiable d'un litige né entre les parties, les tribunaux français seront seuls compétents pour en connaître.</p>
    
    <p>Pour toute question relative à l'application des présentes CGU, vous pouvez joindre l'éditeur aux coordonnées inscrites dans les mentions légales.</p>
    
    <p style="text-align: center; margin-top: 40px;"><em>Dernière mise à jour : 24 mars 2025</em></p>
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
</body> 
</html>