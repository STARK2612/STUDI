<?php
session_start(); // Démarrer la session

// Déconnexion de l'utilisateur en supprimant toutes les variables de session
session_unset(); 
// Détruire la session
session_destroy(); 

// Redirection vers la page d'accueil
header("Location: ../index.php"); 
exit; // Terminer le script
?>
