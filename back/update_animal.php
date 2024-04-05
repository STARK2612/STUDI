<?php
require_once 'connect_bdd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $animal_id = $_POST['animal_id'];
    $prenom = $_POST['prenom'];
    $race_id = $_POST['race_id'];
    $habitat_id = $_POST['habitat_id'];
    // Ajoutez le code pour manipuler les images si nécessaire

    // Préparer la requête d'update avec des paramètres
    $sql = "UPDATE animal SET prenom=?, race_id=?, habitat_id=? WHERE animal_id=?";

    // Préparer et exécuter la requête avec des paramètres
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("siii", $prenom, $race_id, $habitat_id, $animal_id);

    if ($stmt->execute()) {
        echo "Animal mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour de l'animal : " . $connexion->error;
    }

    // Fermer la déclaration
    $stmt->close();
}
$connexion->close();
?>
