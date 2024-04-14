<?php
require_once('back/connect_bdd.php');

// Vérifier la connexion à la base de données
if ($connexion->connect_error) {
    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
}

// Récupérer les horaires d'ouverture et de fermeture pour l'id spécifié (id = 1)
$idHoraire = 1;
$sqlHoraire = "SELECT * FROM horaire WHERE id = ?";
$stmtHoraire = $connexion->prepare($sqlHoraire);
$stmtHoraire->bind_param("i", $idHoraire);
$stmtHoraire->execute();
$resultHoraire = $stmtHoraire->get_result();

// Vérifier si la requête s'est exécutée correctement
if ($resultHoraire->num_rows > 0) {
    $rowHoraire = $resultHoraire->fetch_assoc();
    $heure_ouverture = $rowHoraire['debut'];
    $heure_fermeture = $rowHoraire['fin'];
} else {
    $heure_ouverture = null;
    $heure_fermeture = null;
}

// Récupérer les avis visibles
$sqlAvis = "SELECT * FROM avis WHERE isVisible = ?";
$stmtAvis = $connexion->prepare($sqlAvis);
$isVisible = 1;
$stmtAvis->bind_param("i", $isVisible);
$stmtAvis->execute();
$resultAvis = $stmtAvis->get_result();

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
$sqlAvisPage = "SELECT * FROM avis WHERE isVisible = ? LIMIT ?, ?";
$stmtAvisPage = $connexion->prepare($sqlAvisPage);
$stmtAvisPage->bind_param("iii", $isVisible, $indiceDepart, $avisParPage);
$stmtAvisPage->execute();
$resultAvisPage = $stmtAvisPage->get_result();

?>

<div class="container" id="background-color">
    <div class="row">
        <div class="column left custom-form col-md-3" style='border-radius: 20px; border: 2px solid white;'>
            <br>
            <h2 class='text-center'>ZOO Arcadia</h2>
            <p class='text-justify text-center' style='background-color:white; border-radius: 10px; border: 1px solid black;'>
                Arcadia est un zoo situé en France, près de la célèbre forêt de Brocéliande, en Bretagne. Fondé en 1960, ce zoo offre une expérience unique aux visiteurs, les plongeant au cœur de la nature et leur permettant de découvrir une grande diversité d'animaux fascinants.
                Arcadia abrite une variété d'habitats soigneusement aménagés pour offrir aux animaux des conditions de vie proches de leur environnement naturel. Parmi les habitats remarquables, on trouve la savane africaine, la jungle tropicale, les marais côtiers et bien d'autres. Chaque espace est conçu avec soin pour garantir le bien-être des animaux tout en offrant aux visiteurs une expérience immersive.
                ZOO Arcadia offre une expérience inoubliable pour les amoureux de la nature de tous âges, combinant divertissement, éducation et conservation dans un cadre magnifique et préservé.
            </p>
            <div class='container'>
                <div class="text-center">
                    <br>
                    <br>
                    <?php if ($heure_ouverture && $heure_fermeture): ?>
                        <p class="font-weight-bold" style="font-size: 20px; color: white; border: 2px solid white; padding: 20px; border-radius: 20px;">Heure d'Ouverture du ZOO:<br><?php echo date("H:i", strtotime($heure_ouverture)); ?></p>
                        <p class="font-weight-bold" style="font-size: 20px; color: white; border: 2px solid white; padding: 20px; border-radius: 20px;">Heure de Fermeture du ZOO:<br><?php echo date("H:i", strtotime($heure_fermeture)); ?></p>
                    <?php else: ?>
                        <p class="font-weight-bold" style="font-size: 20px; color: white; border: 2px solid white; padding: 20px; border-radius: 20px;">Aucun horaire trouvé dans la base de données.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
        <div class="col-md-6" style='border: 2px solid white; border-radius: 20px;'>
            <!-- Affichage des avis -->
            <?php if ($resultAvisPage->num_rows > 0) : ?>
                <br>
                <h3 class='text-justify'>Avis des visiteurs</h3>
                <div class="table-responsive overflow-auto" id="avisTable">
                    <table id="table">
                        <thead>
                            <tr>
                                <th class="table text-justify" style="width: 150px;">Pseudo</th>
                                <th class="table text-justify" style="width: 500px;">Commentaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $resultAvisPage->fetch_assoc()) : ?>
                                <tr>
                                    <td class="description description-cell text-center" style='background-color:white; border: 1px solid black;'><?php echo $row['pseudo']; ?></td>
                                    <td class="description description-cell2 text-center" style='background-color:white; border: 1px solid black;'><?php echo $row['commentaire']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <br>
            <?php endif; ?>
            <nav aria-label="Page navigation" id="paginationTable">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <div class="form-container">
                <!-- Formulaire pour soumettre un avis -->
                <br>
                <h3 class='text-justify'>Formulaire pour envoyer ton avis</h3>
                <form id="avisForm" method="post" action="back/save_avis.php" class='text-justify'>
                    <div class="form-group col-md-12">
                        <label for="pseudo">Pseudo :</label>
                        <input type="text" class="form-control" id="pseudo" name="pseudo" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="commentaire">Votre avis :</label>
                        <textarea class="form-control" id="commentaire" name="commentaire" rows="3" required></textarea>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-warning">Soumettre</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Carousel -->
<br>
<div id="carouselExampleIndicators" class="carousel slide rounded-carousel custom-carousel text-center" data-bs-ride="carousel" style='border-radius: 22px; border: 3px solid white;'>
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

<?php $connexion->close(); ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var paginationLinks = document.querySelectorAll("#paginationTable a.page-link");

        paginationLinks.forEach(function(link) {
            link.addEventListener("click", function(event) {
                event.preventDefault(); // Empêche le comportement par défaut du lien

                var targetPage = this.getAttribute("href").split("=")[1];

                setTimeout(function() {
                    window.location.href = "?page=" + targetPage;
                }, 50);
            });
        });
    });
</script>
