<?php
// Inclusion du fichier de connexion à la base de données
include_once "back/connect_bdd.php";

// Vérifier si la connexion à la base de données est établie
if (!$connexion) {
    echo "La connexion à la base de données a échoué.";
    exit; // Arrêter l'exécution du script en cas d'échec de la connexion
}

// Récupération des services depuis la base de données
$sql = "SELECT service.*, image.image_id, image.image_data, image.image_type FROM service LEFT JOIN image ON service.image_id = image.image_id";
$result = $connexion->query($sql);

// Définir une variable pour savoir si c'est le premier élément du carousel
$first = true;
?>
<body>
    <div class="info-container container-fluid"> <!-- Utilisation de container-fluid pour permettre le plein largeur -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3"> <!-- Utilisation de différentes colonnes en fonction de la largeur de l'écran -->
            <?php
            // Vérifier si des services ont été trouvés
            if ($result->num_rows > 0) {
                // Parcourir les résultats de la requête
                while ($row = $result->fetch_assoc()) {
            ?>
                    <div class="col">
                        <div class="card mx-2">
                            <!-- Affichage de l'image du service, avec une image par défaut si aucune image n'est disponible -->
                            <img src="<?php echo (!empty($row['image_data'])) ? 'data:' . $row['image_type'] . ';base64,' . base64_encode($row['image_data']) : 'front/img/default.jpg'; ?>" class="card-img-top" alt="Image du service">
                            <div class="card-body">
                                <!-- Affichage du nom du service -->
                                <h5 class="card-title text-black"><?php echo $row['nom']; ?></h5>
                                <!-- Affichage de la description du service -->
                                <p class="card-text text-black"><?php echo $row['description']; ?></p>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                // Si aucun service trouvé, afficher un message
                echo "<p>Aucun service trouvé.</p>";
            }
            ?>
        </div>
    </div>
</body>
<?php
// Fermeture de la connexion à la base de données
$connexion->close();
?>
