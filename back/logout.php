<<<<<<< HEAD
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
=======
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
>>>>>>> 54d25e1ccebbdf612c1ee9a6ad64fbe4b3b867e4
