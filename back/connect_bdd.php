<?php
// Définir les constantes pour les paramètres de connexion
define('SERVEUR', 'localhost');
define('UTILISATEUR_DB', 'root');
define('MOT_DE_PASSE_DB', '');
define('NOM_DB', 'zooarcadia');

// Connexion à la base de données
$connexion = new mysqli(SERVEUR, UTILISATEUR_DB, MOT_DE_PASSE_DB, NOM_DB);

// Vérifier la connexion
if ($connexion->connect_error) {
    // Log des erreurs dans un fichier
    error_log("La connexion à la base de données a échoué : " . $connexion->connect_error, 0);
    // Affichage d'un message d'erreur générique pour l'utilisateur
    die("Erreur de connexion à la base de données.");
}
?>
