<?php
// Inclure le fichier de sécurité
include_once "../modele/security.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Mentions ⎸ SingLouder</title>
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
    <h1>Mentions Légales</h1>
    <h5>ÉDITEUR DU SITE</h5>
    <p>Le site web SingLouder est édité par :</p>
    <p>
        SingLouder SAS<br>
        123 Avenue de la Musique<br>
        75008 Paris<br>
        France<br>
        Email : <a href="mailto:contact@singloud.com">contact@singloud.com</a><br>
        Téléphone : +33 1 23 45 67 89
    </p>
    <p>SIRET : 123 456 789 00012<br>
       Capital social : 10 000 €<br>
       Directeur de la publication : Jean Dupont
    </p>
    
    <h5>HÉBERGEUR</h5>
    <p>Le site SingLouder est hébergé par :</p>
    <p>
        Amazon Web Services, Inc.<br>
        410 Terry Avenue North<br>
        Seattle, WA 98109-5210<br>
        USA<br>
        <a href="https://aws.amazon.com" target="_blank">https://aws.amazon.com</a>
    </p>
    
    <h5>PROPRIÉTÉ INTELLECTUELLE</h5>
    <p>L'ensemble des éléments composant le site SingLouder (textes, graphismes, logiciels, photographies, images, sons, plans, logos, marques, etc.) sont la propriété exclusive de SingLouder SAS ou font l'objet d'une autorisation d'utilisation. Ces éléments sont soumis aux lois régissant la propriété intellectuelle.</p>
    <p>Toute reproduction, représentation, modification, publication, adaptation de tout ou partie de ces éléments, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable de SingLouder SAS.</p>
    <p>Les marques et logos reproduits sur le site sont déposés par les sociétés qui en sont propriétaires.</p>
    
    <h5>PROTECTION DES DONNÉES PERSONNELLES</h5>
    <p>Conformément à la loi "Informatique et Libertés" du 6 janvier 1978 modifiée et au Règlement Général sur la Protection des Données (RGPD) 2016/679 du Parlement européen et du Conseil du 27 avril 2016, vous disposez d'un droit d'accès, de rectification, de portabilité et d'effacement de vos données ou encore de limitation du traitement. Vous pouvez également, pour des motifs légitimes, vous opposer au traitement des données vous concernant.</p>
    <p>Pour exercer ces droits ou pour toute question sur le traitement de vos données, vous pouvez contacter notre Délégué à la Protection des Données (DPO) par email à <a href="mailto:dpo@singloud.com">dpo@singloud.com</a>.</p>
    
    <h5>COOKIES</h5>
    <p>Le site SingLouder utilise des cookies pour améliorer l'expérience de navigation des utilisateurs. Les cookies sont de petits fichiers texte stockés sur votre ordinateur qui nous permettent de vous fournir une expérience personnalisée.</p>
    <p>Vous pouvez désactiver l'utilisation de cookies en ajustant les paramètres de votre navigateur.</p>
    
    <h5>LIMITATION DE RESPONSABILITÉ</h5>
    <p>SingLouder SAS ne pourra être tenue responsable des dommages directs et indirects causés au matériel de l'utilisateur, lors de l'accès au site SingLouder, et résultant soit de l'utilisation d'un matériel ne répondant pas aux spécifications indiquées, soit de l'apparition d'un bug ou d'une incompatibilité.</p>
    <p>SingLouder SAS ne pourra également être tenue responsable des dommages indirects consécutifs à l'utilisation du site.</p>
    
    <h5>LOI APPLICABLE ET JURIDICTION</h5>
    <p>Les présentes mentions légales sont soumises au droit français. En cas de litige, les tribunaux français seront compétents.</p>
    
    <h5>CONTACT</h5>
    <p>Pour toute question relative aux présentes mentions légales, vous pouvez nous contacter à l'adresse suivante : <a href="mailto:legal@singloud.com">legal@singloud.com</a>.</p>
    
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