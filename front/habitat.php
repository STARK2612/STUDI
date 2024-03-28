    <h1 class='text-center'>Détails de l'habitat</h1>
    
    <?php
    // Inclusion du fichier de connexion à la base de données
    include_once "back/connect_bdd.php";

    // Vérifier si la connexion à la base de données est établie
    if (!$connexion) {
        echo "La connexion à la base de données a échoué.";
        exit; // Arrêter l'exécution du script en cas d'échec de la connexion
    }
    
    // Vérification si un habitat_id est passé en paramètre
    if (isset($_GET['habitat_id']) && !empty($_GET['habitat_id'])) {
        $habitat_id = $_GET['habitat_id'];
        
        // Appel de la fonction pour récupérer les détails de l'habitat avec ses animaux associés
        getHabitatDetails($habitat_id);
    } else {
        echo "Aucun habitat sélectionné.";
    }
    
    // Fonction pour récupérer les détails d'un habitat avec ses animaux associés
function getHabitatDetails($habitat_id) {
    global $connexion;
    
    // Récupérer les détails de l'habitat avec les informations sur l'image
    $habitat_query = "SELECT habitat.*, image.image_data, image.image_type FROM habitat INNER JOIN image ON habitat.image_id = image.image_id WHERE habitat.habitat_id = $habitat_id";
    $habitat_result = $connexion->query($habitat_query);
    $habitat_row = $habitat_result->fetch_assoc();

    // Afficher les détails de l'habitat avec un style Bootstrap
    echo '<br>';
    echo '<div class="container custom-container" id="background2">';
    echo '<br>';
    echo "<h2 class='text-center'>" . $habitat_row['nom'] . "</h2>";
    // Afficher l'image avec la bonne taille
    echo "<img src='data:image/" . $habitat_row['image_type'] . ";base64," . base64_encode($habitat_row['image_data']) . "' alt='" . $habitat_row['nom'] . "' class='text-center img-fluid rounded' style='width: 600px; height: auto;'>";
    echo "<p class='lead text-center'>Description : " . $habitat_row['description'] . "</p>";

    
    // Récupérer les animaux associés à cet habitat avec leur race
    $animaux_query = "SELECT animal.*, race.label AS race_label FROM animal INNER JOIN race ON animal.race_id = race.race_id WHERE animal.habitat_id = $habitat_id";
    $animaux_result = $connexion->query($animaux_query);

    // Afficher les animaux associés avec leur race dans un style Bootstrap
    echo "<h3 class='text-center'>Animaux :</h3>";
    echo "<div class='btn-group d-flex flex-wrap justify-content-center'>";
    while ($animal_row = $animaux_result->fetch_assoc()) {
        echo "<a href='les_habitats_3.php?animal_id=" . $animal_row['animal_id'] . "' class='btn btn-primary m-2' onclick='incrementCounter(" . $animal_row['animal_id'] . ")'>" . $animal_row['prenom'] . " (" . $animal_row['race_label'] . ")</a>";
    }
    echo "</div>";
    echo '<br>';
    echo '</div>';
}

// Vérifier si l'identifiant de l'animal est passé en paramètre
if (isset($_GET['animal_id']) && !empty($_GET['animal_id'])) {
    $animal_id = $_GET['animal_id'];

    // Mettre à jour le compteur de l'animal sélectionné
    $update_query = "UPDATE animal SET counter = counter + 1 WHERE animal_id = $animal_id";
    if ($connexion->query($update_query) === TRUE) {
        echo "Compteur mis à jour avec succès";
    } else {
        echo "Erreur lors de la mise à jour du compteur : " . $connexion->error;
    }
}
?>

<script>
    function incrementCounter(animal_id) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'back/increment_counter.php?animal_id=' + animal_id, true);
        xhr.send();
        // Rafraîchir la page après la mise à jour du compteur
        xhr.onload = function() {
            if (xhr.status === 20) {
                location.reload();
            }
        };
    }
</script>