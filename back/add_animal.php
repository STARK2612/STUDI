<?php
include 'connect_bdd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prenom = $_POST['prenom'];
    $race_id = $_POST['race_id'];
    $habitat_id = $_POST['habitat_id'];
    // Ajoutez le code pour manipuler les images si nécessaire

    $sql = "INSERT INTO animal (prenom, race_id, habitat_id) VALUES ('$prenom', '$race_id', '$habitat_id')";
    if ($connexion->query($sql) === TRUE) {
        echo "Animal ajouté avec succès.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $connexion->error;
    }
}
$connexion->close();
?>
