<?php
require_once('back/connect_bdd.php');

// Vérifier la connexion à la base de données
if ($connexion->connect_error) {
    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
}

// Récupérer les avis visibles
$sql = "SELECT * FROM avis WHERE isVisible = ?";
$stmt = $connexion->prepare($sql);
$isVisible = 1;
$stmt->bind_param("i", $isVisible);
$stmt->execute();
$result = $stmt->get_result();

// Définir le nombre d'avis par page
$avisParPage = 1;

// Obtenir le nombre total d'avis
$sqlTotalAvis = "SELECT COUNT(*) AS total FROM avis WHERE isVisible = ?";
$stmtTotalAvis = $connexion->prepare($sqlTotalAvis);
$stmtTotalAvis->bind_param("i", $isVisible);
$stmtTotalAvis->execute();
$resultTotalAvis = $stmtTotalAvis->get_result();
$rowTotalAvis = $resultTotalAvis->fetch_assoc();
$totalAvis = $rowTotalAvis['total'];

// Calculer le nombre total de pages
$totalPages = ceil($totalAvis / $avisParPage);

// Obtenir le numéro de page actuel
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculer l'indice de départ pour l'extraction des avis
$indiceDepart = ($page - 1) * $avisParPage;

// Récupérer les avis pour la page actuelle
$sql = "SELECT * FROM avis WHERE isVisible = ? LIMIT ?, ?";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("iii", $isVisible, $indiceDepart, $avisParPage);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si la requête s'est exécutée correctement
if (!$result) {
    die("Erreur lors de l'exécution de la requête SQL : " . $connexion->error);
}
?>
<body>
    <div class="container" id="background2">
        <div class="row">
            <div class="column left custom-form col-md-4">
                <br>
                <h2 class='text-center'>ZOO Arcadia</h2>
                <p class='text-justify'>
                    Arcadia est un zoo situé en France, près de la célèbre forêt de Brocéliande, en Bretagne. Fondé en 1960, ce zoo offre une expérience unique aux visiteurs, les plongeant au cœur de la nature et leur permettant de découvrir une grande diversité d'animaux fascinants.
                    Arcadia abrite une variété d'habitats soigneusement aménagés pour offrir aux animaux des conditions de vie proches de leur environnement naturel. Parmi les habitats remarquables, on trouve la savane africaine, la jungle tropicale, les marais côtiers et bien d'autres. Chaque espace est conçu avec soin pour garantir le bien-être des animaux tout en offrant aux visiteurs une expérience immersive.
                    ZOO Arcadia offre une expérience inoubliable pour les amoureux de la nature de tous âges, combinant divertissement, éducation et conservation dans un cadre magnifique et préservé.
                </p>
            </div>
            <div class="col-md-6">
                <div class="form-container">
                    <!-- Formulaire pour soumettre un avis -->
                    <br>
                    <h3 class='text-justify'>Formulaire pour envoyer ton avis</h3>
                    <form id="avisForm" method="post" action="back/save_avis.php" class='text-justify'>
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
                    <br>
                    <h3 class='text-justify'>Avis des visiteurs</h3>
                    <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th class="table text-justify">Commentaire</th>
                                <th class="table text-justify">Pseudo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td class="description description-cell"><?php echo $row['commentaire']; ?></td>
                                    <td class="description description-cell2"><?php echo $row['pseudo']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    </div>
                    <br>
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
    </div>   
<!-- Carousel -->
<br>
<div id="carouselExampleIndicators" class="carousel slide rounded-carousel custom-carousel" data-bs-ride="carousel">
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
