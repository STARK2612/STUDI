<<<<<<< HEAD
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
    <div class="info-container">
        <div class="row row-cols-3 row-cols-md-3 g-3">
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
=======
<div class="container">
    <div class="card bg-custom text-white top-container d-flex justify-content-center align-items-center">  
        <div class="card-body">
            <h2 class="text-center custom-title">Les services</h2>
        </div>
    </div>
    <div id="carouselExampleIndicators2" class="carousel slide carousel-fade">
        <div class="carousel-inner video-container">
            <div class="carousel-item active">
                <div class="card">
                    <img src="front/img/restauration.jpeg" class="card-img-top" alt="Image du service">
                    <div class="card-body">
                        <h5 class="card-title text-black">Restauration</h5>
                        <p class="card-text text-black">Profitez d'une expérience culinaire exceptionnelle avec notre service de restauration. Des plats savoureux préparés avec des ingrédients frais et de qualité, servis dans une ambiance accueillante. Découvrez une cuisine raffinée, un service attentionné et des saveurs qui raviront vos papilles à chaque bouchée.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="card">
                    <img src="front/img/guide.jpg" class="card-img-top" alt="Image du service">
                    <div class="card-body">
                        <h5 class="card-title text-black">Visite des habitats avec un guide.GRATUIT</h5>
                        <p class="card-text text-black">Accompagnés par nos guides expérimentés, plongez-vous dans les trésors naturels et culturels de notre environnement local. Explorez des habitats uniques, apprenez des faits fascinants sur la faune et la flore locales, et laissez-vous inspirer par la beauté de la nature qui nous entoure.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="card">
                    <img src="front/img/train.jpg" class="card-img-top" alt="Image du service">
                    <div class="card-body">
                        <h5 class="card-title text-black">Visite du zoo en petit train.</h5>
                        <p class="card-text text-black">Profitez d'une visite unique du zoo à bord de notre petit train! Parcourez aisément les différents enclos et découvrez une variété d'animaux fascinants, le tout avec le confort et la commodité du transport ferroviaire. Ne manquez pas cette expérience passionnante pour toute la famille!</p>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators2" role="button" data-slide="prev">
            <img class="img_pre" src="front/img/precedent.gif" alt="precedent">
            <span class="sr-only"></span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button" data-slide="next">
            <img class="img_sui" src="front/img/suivant.gif" alt="Suivant">
            <span class="sr-only"></span>
        </a>
    </div>
</div>
<!-- Inclure la bibliothèque jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Inclure la bibliothèque Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<!-- Inclure la bibliothèque Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
>>>>>>> 54d25e1ccebbdf612c1ee9a6ad64fbe4b3b867e4
