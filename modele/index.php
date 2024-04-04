<?php

$servername = "localhost";
$username = "root";
$password = "" ;

try {
    $bdd = new PDO("mysql:host=$servername;dbname=SING", $login, $mdp, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')); 
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $bdd;
} catch (PDOException $e) {
    echo "Erreur : ".$e->getMessage();
}



?>