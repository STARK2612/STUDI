<?php
// Connexion à la base de données
$serveur = "localhost"; // Adresse du serveur MySQL
$utilisateur_db = "root"; // Nom d'utilisateur de la base de données
$mot_de_passe_db = "root"; // Mot de passe de la base de données
$nom_db = "zooarcadia"; // Nom de la base de données

// Connexion à la base de données
try {
    $connexion = new mysqli($serveur, $utilisateur_db, $mot_de_passe_db, $nom_db);
    
    // Vérifier la connexion
    if ($connexion->connect_error) {
        throw new Exception("La connexion a échoué : " . $connexion->connect_error);
    }
} catch (Exception $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
