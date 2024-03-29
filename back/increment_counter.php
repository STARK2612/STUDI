<?php
// Inclusion du fichier de connexion à la base de données
include_once "connect_bdd.php";

// Vérification si animal_id est passé en paramètre
if (isset($_GET['animal_id']) && !empty($_GET['animal_id'])) {
    $animal_id = $_GET['animal_id'];
    
    // Mettre à jour le compteur dans la table "stat" avec la date actuelle
    $update_query_stat = "INSERT INTO stat (animal_id, counter, date) VALUES ($animal_id, 1, NOW()) ON DUPLICATE KEY UPDATE counter = counter + 1, date = NOW()";
    
    if ($connexion->query($update_query_stat) === TRUE) {
        // Redirection vers la page "les_habitats_3.php" après la mise à jour du compteur
        header("Location: ../les_habitats_3.php");
        exit();
    } else {
        echo "Erreur lors de la mise à jour du compteur stat : " . $connexion->error;
    }
}
?>
