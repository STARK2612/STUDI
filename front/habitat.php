<!-- Affichage du titre de la page -->
<h1 class='text-center'>Détails de l'habitat</h1>

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
    echo '<div class="container custom-container" id="background2">';
    echo '<br>';
    echo "<h2 class='text-center'>" . $habitat_row['nom'] . "</h2>";
    // Affichage de l'image de l'habitat
    if (!empty($habitat_row['image_data'])) {
        echo "<img src='data:image/" . $habitat_row['image_type'] . ";base64," . base64_encode($habitat_row['image_data']) . "' alt='" . $habitat_row['nom'] . "' class='text-center img-fluid rounded' style='max-width: 100%; height: auto;'>";
    } else {
        echo "<img src='front/img/default.jpg' alt='Image par défaut' class='text-center img-fluid rounded' style='max-width: 100%; height: auto;'>";
    }
    echo "<p class='lead text-center'>";
    echo "<span class='d-none d-sm-block'>Description : </span>"; // Ne pas afficher sur les smartphones
    echo "<span class='d-sm-none'>Desc. : </span>"; // Afficher uniquement sur les smartphones
    echo "<span class='d-inline d-sm-none'>" . wordwrap($habitat_row['description'], 22, "<br>", true) . "</span>"; // Texte pour smartphones
    echo "<span class='d-none d-sm-inline'>" . wordwrap($habitat_row['description'], 40, "<br>", true) . "</span>"; // Texte pour tablettes et PC
    echo "</p>";


    // Requête pour récupérer les animaux associés à cet habitat
    $animaux_query = "SELECT animal.*, race.label AS race_label FROM animal INNER JOIN race ON animal.race_id = race.race_id WHERE animal.habitat_id = $habitat_id";
    // Exécution de la requête
    $animaux_result = $connexion->query($animaux_query);

    // Affichage des animaux associés à cet habitat
    echo "<h3 class='text-center'>Animaux :</h3>";
    echo "<div class='btn-group d-flex flex-wrap justify-content-center'>";
    while ($animal_row = $animaux_result->fetch_assoc()) {
        // Lien vers une page pour chaque animal avec un appel à une fonction JavaScript onclick
        echo "<a href='les_habitats_3.php?animal_id=" . $animal_row['animal_id'] . "' class='btn btn-primary m-2' onclick='handleAnimalClick(" . $animal_row['animal_id'] . ")'>" . $animal_row['prenom'] . " (" . $animal_row['race_label'] . ")</a>";
    }
    echo "</div>";
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
        window.location.href = '../les_habitats_3.php';
    }
</script>
