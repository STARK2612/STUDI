<?php
// Inclusion du fichier de connexion à la base de données
include_once "back/connect_bdd.php";

// Vérification de la connexion à la base de données
if (!$connexion) {
    echo "La connexion à la base de données a échoué.";
    exit;
}

// Fonction pour obtenir les détails d'un habitat
function getHabitatDetails($habitat_id) {
    global $connexion;

    // Requête pour obtenir les détails de l'habitat
    $habitat_query = "SELECT * FROM habitat WHERE habitat_id = $habitat_id";
    $habitat_result = $connexion->query($habitat_query);
    $habitat_row = $habitat_result->fetch_assoc();

    // Affichage des détails de l'habitat
    echo '<div class="container custom-container" id="background2">';
    echo '<br>';
    echo "<h2>" . $habitat_row['nom'] . "</h2>";
    echo "<p>Description : " . $habitat_row['description'] . "</p>";
    echo '<br>';
    echo '</div>';
    echo '<br>';

    // Requête pour obtenir les animaux dans cet habitat
    $animaux_query = "SELECT * FROM animal WHERE habitat_id = $habitat_id";
    $animaux_result = $connexion->query($animaux_query);

    // Affichage des animaux dans cet habitat
    echo '<div class="container custom-container" id="background2">';
    echo '<br>';
    echo "<h3>Animaux :</h3>";
    echo "<ul>";
    while ($animal_row = $animaux_result->fetch_assoc()) {
        echo "<li><a href='animal.php?animal_id=" . $animal_row['animal_id'] . "'>" . $animal_row['prenom'] . "</a></li>";
    }
    echo "</ul>";
    echo '<br>';
    echo '</div>';
}

// Fonction pour obtenir les détails d'un animal
function getAnimalDetails($animal_id) {
    global $connexion;

    // Requête pour obtenir les détails de l'animal
    $animal_query = "SELECT * FROM animal WHERE animal_id = $animal_id";
    $animal_result = $connexion->query($animal_query);
    $animal_row = $animal_result->fetch_assoc();

    // Affichage des détails de l'animal
    echo '<div class="container custom-container" id="background2">';
    echo '<br>';
    echo "<h2>" . $animal_row['prenom'] . "</h2>";
    echo "<p>Race : " . $animal_row['race'] . "</p>";
    echo "<p>Habitat : " . $animal_row['habitat'] . "</p>";
    echo "<p>État : " . $animal_row['etat'] . "</p>";
    echo "<p>Nourriture proposée : " . $animal_row['nour'] . "</p>";
    echo "<p>Grammage de la nourriture : " . $animal_row['qte_nour'] . "</p>";
    echo "<p>Date de passage : " . $animal_row['date_nour'] . "</p>";

    // Requête pour obtenir le rapport vétérinaire de l'animal
    $rapport_query = "SELECT * FROM rapport_veterinaire WHERE animal_id = $animal_id";
    $rapport_result = $connexion->query($rapport_query);
    if ($rapport_result->num_rows > 0) {
        $rapport_row = $rapport_result->fetch_assoc();
        echo "<p>Avis du vétérinaire : " . $rapport_row['detail'] . "</p>";
        echo '<br>';
        echo '</div>';
    }
}

// Requête pour obtenir la liste des habitats
$habitats_query = "SELECT * FROM habitat";
$habitats_result = $connexion->query($habitats_query);
$count = 0;
echo '<div class="row">';
while ($habitat_row = $habitats_result->fetch_assoc()) {
    if ($count % 2 == 0) {
        echo '</div><div class="row">';
    }

    echo '<div class="col-md-6">';
    echo '<div class="container custom-container mb-4" id="background2">';
    echo '<br>';
    echo "<div>";
    echo "<h2 class='text-center'>" . $habitat_row['nom'] . "</h2>";
    echo '<br>';

    // Requête pour obtenir l'image de l'habitat
    $image_id = $habitat_row['image_id'];
    
    // Vérifiez si $image_id est vide
    if (empty($image_id)) {
        // Si $image_id est vide, affichez l'image par défaut
        echo "<img src='front/img/default.jpg' alt='Image par défaut' class='img-fluid'>";
    } else {
        // Si $image_id n'est pas vide, exécutez la requête normalement
        $image_query = "SELECT * FROM image WHERE image_id = $image_id";
        $image_result = $connexion->query($image_query);

        // Affichage de l'image de l'habitat
        echo "<div class='text-center'>";
        if ($image_result->num_rows > 0) {
            $image_row = $image_result->fetch_assoc();
            $image_data = $image_row['image_data'];
            $image_type = $image_row['image_type'];
            $image_src = 'data:image/' . $image_type . ';base64,' . base64_encode($image_data);
            echo "<img src='" . $image_src . "' alt='" . $habitat_row['nom'] . "' class='img-fluid rounded'>";
        } else {
            echo "<img src='front/img/default.jpg' alt='Image par défaut' class='img-fluid'>";
        }
        echo "</div>";
    }
    echo '<br>';
    echo '<br>';
    echo "<p class='text-center'><a href='les_habitats_2.php?habitat_id=" . $habitat_row['habitat_id'] . "' class='btn btn-primary'>Voir détails</a></p>";
    echo "</div>";
    echo '<br>';
    echo '</div>';
    echo '<br>';
    echo '</div>';

    $count++;
}
echo '</div>';

// Fermeture de la connexion à la base de données
$connexion->close();
?>
