    <h1>Détails de l'habitat</h1>
    
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
        echo '<div class="container" id="background2">';
        echo '<br>';
        echo "<h2>" . $habitat_row['nom'] . "</h2>";
        // Afficher l'image avec la bonne taille
        echo "<img src='data:image/" . $habitat_row['image_type'] . ";base64," . base64_encode($habitat_row['image_data']) . "' alt='" . $habitat_row['nom'] . "' class='img-fluid'>";
        echo "<p class='lead'>Description : " . $habitat_row['description'] . "</p>";

        
        // Récupérer les animaux associés à cet habitat
        $animaux_query = "SELECT * FROM animal WHERE habitat_id = $habitat_id";
        $animaux_result = $connexion->query($animaux_query);
        
        // Afficher les animaux associés avec un style Bootstrap
        echo "<h3>Animaux :</h3>";
        echo "<ul class='list-unstyled'>";
        while ($animal_row = $animaux_result->fetch_assoc()) {
            echo "<li><a href='les_habitats_3.php?animal_id=" . $animal_row['animal_id'] . "'>" . $animal_row['prenom'] . "</a></li>";
        }
        echo "</ul>";
        echo '<br>';
        echo '</div>';
    }
    
    // Fermeture de la connexion
    $connexion->close();
    ?>