<h1 class='text-center'>Détails de l'animal</h1>
<br>
<?php
// Inclusion du fichier de connexion à la base de données
include_once "back/connect_bdd.php";

// Vérifier si la connexion à la base de données est établie
if (!$connexion) {
    echo "La connexion à la base de données a échoué.";
    exit; // Arrêter l'exécution du script en cas d'échec de la connexion
}

// Vérification si un animal_id est passé en paramètre
if (isset($_GET['animal_id']) && !empty($_GET['animal_id'])) {
    $animal_id = $_GET['animal_id'];

    // Appel de la fonction pour récupérer les détails de l'animal
    getAnimalDetails($animal_id);
} else {
    echo "Aucun animal sélectionné.";
}

// Fonction pour récupérer les détails d'un animal avec l'avis du vétérinaire
function getAnimalDetails($animal_id) {
    global $connexion;

    // Récupérer les détails de l'animal
    $animal_query = "SELECT * FROM animal WHERE animal_id = ?";
    $animal_stmt = $connexion->prepare($animal_query);
    $animal_stmt->bind_param("i", $animal_id);
    $animal_stmt->execute();
    $animal_result = $animal_stmt->get_result();
    $animal_row = $animal_result->fetch_assoc();

    // Récupérer le nom de la race de l'animal
    $race_query = "SELECT label FROM race WHERE race_id = ?";
    $race_stmt = $connexion->prepare($race_query);
    $race_stmt->bind_param("i", $animal_row['race_id']);
    $race_stmt->execute();
    $race_result = $race_stmt->get_result();
    $race_row = $race_result->fetch_assoc();

    // Récupérer le nom de l'habitat de l'animal
    $habitat_query = "SELECT nom FROM habitat WHERE habitat_id = ?";
    $habitat_stmt = $connexion->prepare($habitat_query);
    $habitat_stmt->bind_param("i", $animal_row['habitat_id']);
    $habitat_stmt->execute();
    $habitat_result = $habitat_stmt->get_result();
    $habitat_row = $habitat_result->fetch_assoc();

    // Récupérer les données de l'image de l'animal
    $image_query = "SELECT image_data, image_type FROM image WHERE image_id = ?";
    $image_stmt = $connexion->prepare($image_query);
    $image_stmt->bind_param("i", $animal_row['image_id']);
    $image_stmt->execute();
    $image_result = $image_stmt->get_result();
    $image_row = $image_result->fetch_assoc();

    // Vérifier si des données d'image ont été récupérées
    if ($image_result && $image_result->num_rows > 0) {
        // Afficher l'image
        $image_data = $image_row['image_data'];
        $image_type = $image_row['image_type'];
        $base64_image = 'data:image/' . $image_type . ';base64,' . base64_encode($image_data);
    }

    // Afficher les détails de l'animal
    echo '<div class="container custom-container" id="background2">';
    echo '<br>';
    echo '<a href="les_habitats.php" class="btn btn-secondary btn-block">Retour</a>';
    echo '<img src="' . $base64_image . '" alt="Image de l\'animal" width="100" height="100" class="text-center rounded">';
    echo "<h2 class='text-center'>" . $animal_row['prenom'] . "</h2>";
    if (!empty($race_row['label'])) {
        echo "<p class='text-center'>Race : " . $race_row['label'] . "</p>";
    }
    if (!empty($habitat_row['nom'])) {
        echo "<p class='text-center'>Habitat : " . $habitat_row['nom'] . "</p>";
    }
    echo "<p class='text-center'>État : " . $animal_row['etat'] . "</p>";
    echo "<p class='text-center'>Nourriture proposée : " . $animal_row['nour'] . "</p>";

    // Ajouter l'unité de mesure "gramme" après le grammage de la nourriture
    echo "<p class='text-center'>Grammage de la nourriture : " . $animal_row['qte_nour'] . " gramme(s)</p>";

    // Formater et afficher la date de passage au format "dd/mm/yyyy"
    $date_nour = date('d/m/Y', strtotime($animal_row['date_nour']));
    echo "<p class='text-center'>Date de passage : " . $date_nour . "</p>";

    // Récupérer l'avis du vétérinaire s'il existe
    $rapport_query = "SELECT detail FROM rapport_veterinaire WHERE rapport_veterinaire_id = ?";
    $rapport_stmt = $connexion->prepare($rapport_query);
    $rapport_stmt->bind_param("i", $animal_row['rapport_veterinaire_id']);
    $rapport_stmt->execute();
    $rapport_result = $rapport_stmt->get_result();
    if ($rapport_result && $rapport_result->num_rows > 0) {
        $rapport_row = $rapport_result->fetch_assoc();
        echo "<p class='text-center'>Avis du vétérinaire :<br>" . wordwrap($rapport_row['detail'], 31, "<br>", true) . "</p>";

    }
    echo '<br>';
    echo '</div>';
}

// Fermeture de la connexion
$connexion->close();
?>
