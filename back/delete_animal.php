<?php
include 'connect_bdd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $animal_id = $_POST['animal_id'];

    $sql = "DELETE FROM animal WHERE animal_id='$animal_id'";
    if ($connexion->query($sql) === TRUE) {
        echo "Animal supprimé avec succès.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $connexion->error;
    }
}
$connexion->close();
?>
