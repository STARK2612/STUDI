<?php
include 'connect_bdd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $animal_id = $_POST['animal_id'];
    $prenom = $_POST['prenom'];
    $race_id = $_POST['race_id'];
    $habitat_id = $_POST['habitat_id'];
    // Ajoutez le code pour manipuler les images si nécessaire

    $sql = "UPDATE animal SET prenom='$prenom', race_id='$race_id', habitat_id='$habitat_id' WHERE animal_id='$animal_id'";
    if ($connexion->query($sql) === TRUE) {
        echo "Animal mis à jour avec succès.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $connexion->error;
    }
}
$connexion->close();
?>
