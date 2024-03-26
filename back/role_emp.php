<?php
session_start();

// Vérifier si l'utilisateur est connecté et possède le bon rôle
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit();
}

// Vérifier le rôle de l'utilisateur pour accéder à cette page
$allowed_roles = ['Employé'];
if (!in_array($_SESSION['role'], $allowed_roles)) {
    // Redirection vers une page d'erreur si le rôle n'est pas autorisé
    echo "Accès refusé : Vous n'avez pas les permissions nécessaires pour accéder à cette page.";
    exit();
}
?>