<?php

include_once "../modele/db.php";

session_start();
$_SESSION = array();
session_destroy();
header("Location: ../vue/vueAuthentification.php");

?>