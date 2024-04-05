<?php
// Inclusion du fichier de connexion à la base de données
include_once "connect_bdd.php";

// Vérification si animal_id est passé en paramètre
if (isset($_GET['animal_id']) && !empty($_GET['animal_id'])) {
    $animal_id = $_GET['animal_id'];
    
    // Préparer la requête d'insertion ou de mise à jour du compteur dans la table "stat"
    $update_query_stat = "INSERT INTO stat (animal_id, counter, date) VALUES (?, 1, NOW()) ON DUPLICATE KEY UPDATE counter = counter + 1, date = NOW()";
    $stmt = $connexion->prepare($update_query_stat);
    
    // Liaison des paramètres
    $stmt->bind_param("i", $animal_id);
    
    // Exécuter la requête préparée
    if ($stmt->execute()) {
        // Redirection vers la page "les_habitats_3.php" après la mise à jour du compteur
        header("Location: ../les_habitats_3.php");
        exit();
    } else {
        echo "Erreur lors de la mise à jour du compteur stat : " . $connexion->error;
    }
    
    // Fermer la requête préparée
    $stmt->close();
}
?>
