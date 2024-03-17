<<<<<<< HEAD
<?php
require_once('back/connect_bdd.php');

// Vérifier la connexion à la base de données
if ($connexion->connect_error) {
    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
}

// Récupérer les avis visibles
$sql = "SELECT * FROM avis WHERE isVisible = 1";
$result = $connexion->query($sql);

// Définir le nombre d'avis par page
$avisParPage = 1;

// Obtenir le nombre total d'avis
$sqlTotalAvis = "SELECT COUNT(*) AS total FROM avis WHERE isVisible = 1";
$resultTotalAvis = $connexion->query($sqlTotalAvis);
$rowTotalAvis = $resultTotalAvis->fetch_assoc();
$totalAvis = $rowTotalAvis['total'];

// Calculer le nombre total de pages
$totalPages = ceil($totalAvis / $avisParPage);

// Obtenir le numéro de page actuel
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculer l'indice de départ pour l'extraction des avis
$indiceDepart = ($page - 1) * $avisParPage;

// Récupérer les avis pour la page actuelle
$sql = "SELECT * FROM avis WHERE isVisible = 1 LIMIT $indiceDepart, $avisParPage";
$result = $connexion->query($sql);

// Vérifier si la requête s'est exécutée correctement
if (!$result) {
    die("Erreur lors de l'exécution de la requête SQL : " . $connexion->error);
}
?>
<body>
    <div class="container">
        <div class="column left">
            <h2>ZOO Arcadia</h2>
            <p>
                Arcadia est un zoo situé en France, près de la célèbre forêt de Brocéliande, en Bretagne. Fondé en 1960, ce zoo offre une expérience unique aux visiteurs, les plongeant au cœur de la nature et leur permettant de découvrir une grande diversité d'animaux fascinants.
                Arcadia abrite une variété d'habitats soigneusement aménagés pour offrir aux animaux des conditions de vie proches de leur environnement naturel. Parmi les habitats remarquables, on trouve la savane africaine, la jungle tropicale, les marais côtiers et bien d'autres. Chaque espace est conçu avec soin pour garantir le bien-être des animaux tout en offrant aux visiteurs une expérience immersive.
                ZOO Arcadia offre une expérience inoubliable pour les amoureux de la nature de tous âges, combinant divertissement, éducation et conservation dans un cadre magnifique et préservé.
            </p>
        </div>
        <div class="column right">
            <div class="form-container">
                <!-- Formulaire pour soumettre un avis -->
                <form id="avisForm" method="post" action="back/save_avis.php">
                    <div class="form-group">
                        <label for="pseudo">Pseudo :</label>
                        <input type="text" class="form-control" id="pseudo" name="pseudo" required>
                    </div>
                    <div class="form-group">
                        <label for="commentaire">Votre avis :</label>
                        <textarea class="form-control" id="commentaire" name="commentaire" rows="3" required></textarea>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                </form>
            </div>
            <br>
            <!-- Affichage des avis -->
            <?php if ($result->num_rows > 0) : ?>
                <h3>Avis des visiteurs</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pseudo</th>
                            <th>Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['pseudo']; ?></td>
                                <td class="description description-cell"><?php echo $row['commentaire']; ?></td> <!-- Ajout de la classe pour le style -->
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>   
<!-- Carousel -->
<div id="carouselExampleIndicators" class="carousel slide rounded-carousel" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="front/img/zoop.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="front/img/felin.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="front/img/pero.jpg" class="d-block w-100" alt="...">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Précédent</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Suivant</span>
                </button>
            </div>  
</body>

<?php $connexion->close(); ?>
=======
<h2>ZOO Arcadia</h2>
<div class="photos-button">
    <button class="btn btn-success bg-custom" onclick="hideInfoContainer()">Pour regarder les photos, cliquez ici</button>
</div>
<p>
    Arcadia est un zoo situé en France, près de la célèbre forêt de Brocéliande, en Bretagne. Fondé en 1960, ce zoo offre une expérience unique aux visiteurs, les plongeant au cœur de la nature et leur permettant de découvrir une grande diversité d'animaux fascinants.
    Arcadia abrite une variété d'habitats soigneusement aménagés pour offrir aux animaux des conditions de vie proches de leur environnement naturel. Parmi les habitats remarquables, on trouve la savane africaine, la jungle tropicale, les marais côtiers et bien d'autres. Chaque espace est conçu avec soin pour garantir le bien-être des animaux tout en offrant aux visiteurs une expérience immersive.
    Arcadia propose une gamme complète de services pour ses visiteurs, garantissant une expérience agréable et instructive pour tous. Des visites guidées passionnantes sont disponibles pour découvrir les coulisses du zoo et en apprendre davantage sur les animaux qui y vivent. Des aires de pique-nique sont également disponibles pour profiter d'une journée en plein air en famille ou entre amis. De plus, des programmes éducatifs sont organisés pour sensibiliser le public à la conservation de la faune et de la flore.
    Arcadia abrite une grande variété d'animaux, des plus petits aux plus grands, des plus communs aux plus exotiques. Parmi les résidents du zoo, on trouve des éléphants majestueux se déplaçant dans la savane, des singes espiègles se balançant dans la jungle, des crocodiles imposants se cachant dans les marais, et bien plus encore. Chaque animal est pris en charge par une équipe dévouée de soigneurs et vétérinaires qui veillent à leur bien-être et à leur santé.
    En somme, ZOO Arcadia offre une expérience inoubliable pour les amoureux de la nature de tous âges, combinant divertissement, éducation et conservation dans un cadre magnifique et préservé.
</p>

<!-- Formulaire pour soumettre un avis -->
<form id="avisForm" method="post" action="back/save_avis.php">
    <div class="form-group">
        <label for="pseudo">Pseudo:</label>
        <input type="text" class="form-control" id="pseudo" name="pseudo" required>
    </div>
    <div class="form-group">
        <label for="commentaire">Votre avis:</label>
        <textarea class="form-control" id="commentaire" name="commentaire" rows="3" required></textarea>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Soumettre</button>
</form>
>>>>>>> 54d25e1ccebbdf612c1ee9a6ad64fbe4b3b867e4
