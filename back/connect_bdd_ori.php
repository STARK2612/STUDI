<?php
// Définir les constantes pour les paramètres de connexion
define('SERVEUR', 'mysql-zarca.alwaysdata.net');
define('UTILISATEUR_DB', 'zarca');
define('MOT_DE_PASSE_DB', 'Aub1w@n3Ken0b1Master$');
define('NOM_DB', 'zarca_bdd');

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