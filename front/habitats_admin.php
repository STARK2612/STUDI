<?php
// Inclusion du fichier de connexion à la base de données
include_once "back/connect_bdd.php";

// Vérifier si la connexion à la base de données est établie
if (!$connexion) {
    echo "La connexion à la base de données a échoué.";
    exit; // Arrêter l'exécution du script en cas d'échec de la connexion
}

// Fonction pour récupérer les détails d'un habitat avec ses animaux associés
function getHabitatDetails($habitat_id) {
    global $connexion;
    
    // Récupérer les détails de l'habitat
    $habitat_query = "SELECT * FROM habitat WHERE habitat_id = $habitat_id";
    $habitat_result = $connexion->query($habitat_query);
    $habitat_row = $habitat_result->fetch_assoc();
    
    // Afficher les détails de l'habitat
    echo '<div class="container" id="background2">';
    echo '<br>';
    echo "<h2>" . $habitat_row['nom'] . "</h2>";
    echo "<p>Description : " . $habitat_row['description'] . "</p>";
    echo '<br>';
    echo '</div>';
    echo '<br>';
    
    // Récupérer les animaux associés à cet habitat
    $animaux_query = "SELECT * FROM animal WHERE habitat_id = $habitat_id";
    $animaux_result = $connexion->query($animaux_query);
    
    // Afficher les animaux associés
    echo '<div class="container" id="background2">';
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

// Fonction pour récupérer les détails d'un animal avec l'avis du vétérinaire
function getAnimalDetails($animal_id) {
    global $connexion;
    
    // Récupérer les détails de l'animal
    $animal_query = "SELECT * FROM animal WHERE animal_id = $animal_id";
    $animal_result = $connexion->query($animal_query);
    $animal_row = $animal_result->fetch_assoc();
    
    // Afficher les détails de l'animal
    echo '<div class="container" id="background2">';
    echo '<br>';
    echo "<h2>" . $animal_row['prenom'] . "</h2>";
    echo "<p>Race : " . $animal_row['race'] . "</p>";
    echo "<p>Habitat : " . $animal_row['habitat'] . "</p>";
    echo "<p>État : " . $animal_row['etat'] . "</p>";
    echo "<p>Nourriture proposée : " . $animal_row['nour'] . "</p>";
    echo "<p>Grammage de la nourriture : " . $animal_row['qte_nour'] . "</p>";
    echo "<p>Date de passage : " . $animal_row['date_nour'] . "</p>";
    
    // Récupérer l'avis du vétérinaire
    $rapport_query = "SELECT * FROM rapport_veterinaire WHERE animal_id = $animal_id";
    $rapport_result = $connexion->query($rapport_query);
    if ($rapport_result->num_rows > 0) {
        $rapport_row = $rapport_result->fetch_assoc();
        echo "<p>Avis du vétérinaire : " . $rapport_row['detail'] . "</p>";
        echo '<br>';
        echo '</div>';
    }
}

// Affichage de tous les habitats avec leurs animaux associés
$habitats_query = "SELECT * FROM habitat";
$habitats_result = $connexion->query($habitats_query);
while ($habitat_row = $habitats_result->fetch_assoc()) {
    echo '<div class="container" id="background2">';
    echo '<br>';
    echo "<div>";
    echo "<h2 class='text-center'>" . $habitat_row['nom'] . "</h2>";
    
    // Récupérer les informations de l'image à partir de la table "image"
    $image_id = $habitat_row['image_id'];
    $image_query = "SELECT * FROM image WHERE image_id = $image_id";
    $image_result = $connexion->query($image_query);
    $image_row = $image_result->fetch_assoc();
    
    // Vérifier si une image a été trouvée
    if ($image_result->num_rows > 0) {
        // Récupérer les données de l'image
        $image_data = $image_row['image_data'];
        $image_type = $image_row['image_type'];
        // Définir la taille maximale souhaitée de l'image
        $max_width = 300; // Largeur maximale en pixels
        $max_height = 200; // Hauteur maximale en pixels
        // Récupérer les dimensions originales de l'image
        list($width, $height) = getimagesizefromstring($image_data);
        // Calculer les nouvelles dimensions en conservant le ratio d'aspect
        $ratio = min($max_width / $width, $max_height / $height);
        $new_width = $width * $ratio;
        $new_height = $height * $ratio;
        // Afficher l'image avec les nouvelles dimensions
        $image_src = 'data:image/' . $image_type . ';base64,' . base64_encode($image_data);
        echo "<div class='text-center'>";
        echo "<img src='" . $image_src . "' alt='" . $habitat_row['nom'] . "' width='" . $new_width . "' height='" . $new_height . "'>";
        echo "</div>";
    } else {
        // Afficher une image par défaut si aucune image n'est trouvée
        echo "<img src='images/default.jpg' alt='Image par défaut'>";
    }
    
    echo "<p class='text-center'><a href='les_habitats_2.php?habitat_id=" . $habitat_row['habitat_id'] . "'>Voir détails</a></p>";
    echo "</div>";
    echo '<br>';
    echo '</div>';
    echo '<br>';
}


// Fermeture de la connexion
$connexion->close();
?>
