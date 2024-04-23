<?php
// Inclusion du fichier de connexion à la base de données
include_once "back/connect_bdd.php";

// Vérification de la connexion à la base de données
if (!$connexion) {
    echo "La connexion à la base de données a échoué.";
    exit;
}

// Vérification de la présence de l'identifiant de l'habitat dans l'URL
if (isset($_GET['habitat_id']) && !empty($_GET['habitat_id'])) {
    // Récupération de l'identifiant de l'habitat depuis l'URL
    $habitat_id = $_GET['habitat_id'];
    // Appel de la fonction pour obtenir les détails de l'habitat
    getHabitatDetails($habitat_id);
} else {
    // Message d'erreur si aucun habitat n'est sélectionné
    echo "Aucun habitat sélectionné.";
}

// Fonction pour obtenir les détails de l'habitat
function getHabitatDetails($habitat_id) {
    global $connexion;
    
    // Requête pour récupérer les détails de l'habitat ainsi que l'image associée
    $habitat_query = "SELECT habitat.*, image.image_data, image.image_type FROM habitat LEFT JOIN image ON habitat.image_id = image.image_id WHERE habitat.habitat_id = $habitat_id";
    // Exécution de la requête
    $habitat_result = $connexion->query($habitat_query);
    // Récupération de la première ligne de résultat
    $habitat_row = $habitat_result->fetch_assoc();

    // Affichage des détails de l'habitat
    echo '<br>';
    echo '<div class="container custom-container" id="background-color">';
    echo '<br>';
    echo "<h2 class='text-center'>" . $habitat_row['nom'] . "</h2>";
    // Affichage de l'image de l'habitat avec une ombre
    if (!empty($habitat_row['image_data'])) {
        echo "<img src='data:image/" . $habitat_row['image_type'] . ";base64," . base64_encode($habitat_row['image_data']) . "' alt='" . $habitat_row['nom'] . "' class='text-center img-fluid habitat-image' style='width: 800px; border: 3px solid white; height: auto; border-radius: 20px;'>";
    } else {
        echo "<img src='front/img/default.jpg' alt='Image par défaut' class='text-center img-fluid habitat-image' style='width: 800px; border: 3px solid white; height: auto; border-radius: 20px;'>";
    }
    echo "<p class='lead text-center2'>";
    echo "<span class='d-none d-sm-block'><u>Description :</u></strong></span>"; // Ne pas afficher sur les smartphones
    echo "<span class='d-sm-none'><u><b>Desc. :</b></u></span>"; // Afficher uniquement sur les smartphones
    echo "<pre class='d-inline d-sm-none habitat-description shadow' style='background-color: white; border: 3px solid white; display: inline; padding: 10px; border-radius: 20px; text-align: center;'>" . wordwrap($habitat_row['description'], 22, "<br>", true) . "</pre>"; // Texte pour smartphones
    echo "<pre class='d-none d-sm-inline habitat-description shadow' style='background-color: white; border: 3px solid white; display: block; padding: 10px; border-radius: 20px; text-align: center; width: 570px; margin: 0 auto;'>" . wordwrap($habitat_row['description'], 50, "<br>", true) . "</pre>"; // Texte pour tablettes et PC


    echo "</p>";

    // Requête pour récupérer les animaux associés à cet habitat
    $animaux_query = "SELECT animal.*, race.label AS race_label, image.image_data, image.image_type FROM animal INNER JOIN race ON animal.race_id = race.race_id LEFT JOIN image ON animal.image_id = image.image_id WHERE animal.habitat_id = $habitat_id";
    // Exécution de la requête
    $animaux_result = $connexion->query($animaux_query);

    // Affichage des animaux associés à cet habitat
    echo "<h3 class='text-center'>Animaux :</h3>";
    echo "<div class='btn-group d-flex flex-wrap justify-content-center align-items-start'>";
    while ($animal_row = $animaux_result->fetch_assoc()) {
        // Lien vers une page pour chaque animal avec une image et un appel à une fonction JavaScript onclick
        echo "<a href='les_habitats_3.php?animal_id=" . $animal_row['animal_id'] . "' class='m-2' onclick='handleAnimalClick(" . $animal_row['animal_id'] . ")'>";
        echo "<div style='width: 150px; height: 150px; border: 3px solid white; border-radius: 50%; overflow: hidden; position: relative;'>";
        if (!empty($animal_row['image_data'])) {
            echo "<img src='data:image/" . $animal_row['image_type'] . ";base64," . base64_encode($animal_row['image_data']) . "' alt='" . $animal_row['prenom'] . "' style='width: 100%; height: 100%; object-fit: cover;'>";
        } else {
            echo "<img src='front/img/default_animal.jpg' alt='Image par défaut' style='width: 100%; height: auto;'>";
        }
        echo "<div style='position: absolute; bottom: 0; width: 100%; background-color: rgba(0, 0, 0, 0.7); color: white; text-align: center; padding: 5px;'>";
        echo $animal_row['prenom'] . "<br>" . $animal_row['race_label'];
        echo "</div>";
        echo "</div>";
        echo "</a>";
    }
    echo "</div>";
    echo '<a href="les_habitats.php" class="btn btn-secondary btn-block custom-width" style="margin: 0 auto;">Retour</a>';
    echo '<br>';
    echo '</div>';
}
?>

<!-- Script JavaScript pour gérer le clic sur un animal -->
<script>
    function handleAnimalClick(animal_id) {
        // Création d'une requête HTTP GET pour incrémenter un compteur
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'back/increment_counter.php?animal_id=' + animal_id, true);
        xhr.send();
        
        // Redirection vers une autre page après le clic
        window.location.href = 'animal.php?animal_id=' + animal_id;
    }
</script>
