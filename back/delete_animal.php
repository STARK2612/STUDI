<?php
include 'connect_bdd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $animal_id = $_POST['animal_id'];

    // Préparer la requête DELETE avec un paramètre
    $sql = "DELETE FROM animal WHERE animal_id = ?";
    $stmt = $connexion->prepare($sql);

    // Liaison du paramètre
    $stmt->bind_param("i", $animal_id);

    // Exécuter la requête préparée
    if ($stmt->execute()) {
        echo "Animal supprimé avec succès.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $connexion->error;
    }

    // Fermer la requête préparée
    $stmt->close();
}

// Fermer la connexion
$connexion->close();
?>
