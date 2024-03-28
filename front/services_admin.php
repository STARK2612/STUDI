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
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <div class="col">
                        <div class="card mx-2">
                            <img src="<?php echo (!empty($row['image_data'])) ? 'data:' . $row['image_type'] . ';base64,' . base64_encode($row['image_data']) : 'front/img/default.jpg'; ?>" class="card-img-top" alt="Image du service">
                            <div class="card-body">
                                <h5 class="card-title text-black"><?php echo $row['nom']; ?></h5>
                                <p class="card-text text-black"><?php echo $row['description']; ?></p>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>Aucun service trouvé.</p>";
            }
            ?>
        </div>
    </div>
</body>
<?php
// Fermeture de la connexion
$connexion->close();
?>
<!-- Inclure la bibliothèque jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Inclure la bibliothèque Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<!-- Inclure la bibliothèque Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
