<?php 
$servername = "localhost";
$username = "root";
$password = "" ;

try {
    $bdd = new PDO("mysql:host=$servername;dbname=SING", $username, $password); 
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur : ".$e->getMessage();
    die(); 
}
?>