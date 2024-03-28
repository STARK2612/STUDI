<?php
// Inclusion du fichier de connexion à la base de données
include_once "connect_bdd.php";

// Vérification si l'identifiant de l'animal est passé en paramètre
if (isset($_GET['animal_id']) && !empty($_GET['animal_id'])) {
    $animal_id = $_GET['animal_id'];
    
    // Vérification de l'identifiant de l'animal
    echo "Animal ID: " . $animal_id . "<br>";

    // Mettre à jour le compteur de l'animal sélectionné
    $update_query = "UPDATE animal SET counter = counter + 1 WHERE animal_id = $animal_id";
    
    // Exécution de la requête de mise à jour
    if ($connexion->query($update_query) === TRUE) {
        echo "Compteur mis à jour avec succès";
    } else {
        echo "Erreur lors de la mise à jour du compteur : " . $connexion->error;
    }
} else {
    echo "ID de l'animal non valide.";
}

?>