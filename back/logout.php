<?php
session_start(); // Démarrer la session

// Déconnexion en détruisant toutes les variables de session
$_SESSION = array();

// Destruction de la session
session_destroy();

// Redirection vers la page de connexion ou une autre page selon votre besoin
header("Location: ../connexion.php");
exit();
?>
