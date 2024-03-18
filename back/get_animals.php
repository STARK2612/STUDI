<?php
include 'connect_bdd.php';

$sql = "SELECT animal.animal_id, animal.prenom, race.label AS race, habitat.nom AS habitat
        FROM animal
        JOIN race ON animal.race_id = race.race_id
        JOIN habitat ON animal.habitat_id = habitat.habitat_id";
$result = $connexion->query($sql);

if ($result->num_rows > 0) {
    $rows = array();
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);
} else {
    echo "Aucun animal trouvé.";
}
$connexion->close();
?>
