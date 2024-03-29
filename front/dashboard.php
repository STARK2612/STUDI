<?php
// Connexion à la base de données
require_once("back/connect_bdd.php");

// Initialisation de $result_stats
$result_stats = null;

// Récupérer les données des animaux avec leur race
$query_animaux = "SELECT a.animal_id, a.prenom, r.label AS race_label
                  FROM animal a
                  INNER JOIN race r ON a.race_id = r.race_id";
$result_animaux = $connexion->query($query_animaux);

// Construction de la requête SQL pour les statistiques
$query_stats = "SELECT s.animal_id, a.prenom, r.label AS race_label, DATE_FORMAT(s.date, '%Y-%m') AS mois_annee, SUM(s.counter) AS total_cliques
                FROM stat s 
                INNER JOIN animal a ON s.animal_id = a.animal_id
                INNER JOIN race r ON a.race_id = r.race_id";

// Filtrage par animal
if(isset($_GET['animal']) && !empty($_GET['animal'])) {
    $animal_id = $_GET['animal'];
    $query_stats .= " WHERE s.animal_id = $animal_id";
}

// Filtrage par année
if(isset($_GET['annee']) && !empty($_GET['annee'])) {
    $annee = $_GET['annee'];
    $query_stats .= " AND YEAR(s.date) = $annee";
}

// Filtrage par mois
if(isset($_GET['mois']) && !empty($_GET['mois']) && $_GET['mois'] !== 'aucun') {
    $mois = $_GET['mois'];
    $query_stats .= " AND MONTH(s.date) = $mois";
}

$query_stats .= " GROUP BY s.animal_id";
$query_stats .= " ORDER BY s.animal_id";

// Exécution de la requête SQL pour les statistiques
$result_stats = $connexion->query($query_stats);

// Tableaux pour les étiquettes et les données du graphique
$labels = array();
$data = array();

// Remplir les tableaux avec les données de PHP
if($result_stats !== null) {
    while($row_stats = $result_stats->fetch_assoc()) {
        // Concaténer le prénom avec le label de la race
        $prenom_race = $row_stats['prenom'] . ' (' . $row_stats['race_label'] . ')';
        $labels[] = $prenom_race;
        $data[] = $row_stats['total_cliques'];
    }
}

// Déterminer les en-têtes du tableau en fonction des filtres
$entetes_tableau = array('Animal (prénom)', 'Nombre de clics');
if(!(isset($_GET['annee']) && !empty($_GET['annee']))) {
    $entetes_tableau[] = 'Mois';
}

?>

<div class="container" id="background2">
    <div class="row">
        <div class="col-md-6">
            <br>
            <a href="admin.php" class="btn btn-secondary btn-block">Retour</a>
            <br><br>
            <h1 class="text-center">Dashboard Statistiques</h1>
            <br>
            <form action="" method="GET" class="mb-3">
                <div class="form-group">
                    <label for="animal">Filtrer par Animal :</label>
                    <select name="animal" id="animal" class="form-control">
                        <option value="">Tous</option>
                        <?php while($row_animal = $result_animaux->fetch_assoc()): ?>
                            <option value="<?php echo $row_animal['animal_id']; ?>"><?php echo $row_animal['prenom'] . ' (' . $row_animal['race_label'] . ')'; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <br>
                <div class="form-group">
                    <label for="annee">Filtrer par Année :</label>
                    <select name="annee" id="annee" class="form-control">
                        <option value="">Toutes</option>
                        <?php 
                        $query_annees = "SELECT DISTINCT YEAR(date) AS annee FROM stat ORDER BY annee DESC";
                        $result_annees = $connexion->query($query_annees);
                        while($row_annee = $result_annees->fetch_assoc()): ?>
                            <option value="<?php echo $row_annee['annee']; ?>"><?php echo $row_annee['annee']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <br>
                <div class="form-group">
                    <label for="mois">Filtrer par Mois :</label>
                    <select name="mois" id="mois" class="form-control">
                        <option value="aucun">Aucun mois</option>
                        <?php 
                        $mois_labels = array(
                            1 => 'Janvier',
                            2 => 'Février',
                            3 => 'Mars',
                            4 => 'Avril',
                            5 => 'Mai',
                            6 => 'Juin',
                            7 => 'Juillet',
                            8 => 'Août',
                            9 => 'Septembre',
                            10 => 'Octobre',
                            11 => 'Novembre',
                            12 => 'Décembre'
                        );
                        foreach ($mois_labels as $mois_num => $mois_label): ?>
                            <option value="<?php echo $mois_num; ?>"><?php echo $mois_label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <button type="submit" class="btn btn-primary btn-block">Filtrer</button>
            </form>

            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <?php foreach ($entetes_tableau as $entete): ?>
                            <th class="align-middle"><?php echo $entete; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Vérifier si $result_stats est défini et non null avant de l'utiliser
                    if($result_stats !== null) {
                        // Réinitialiser le pointeur de résultat
                        mysqli_data_seek($result_stats, 0);
                        while($row_stats = $result_stats->fetch_assoc()): ?>
                            <tr>
                                <td class="align-middle"><?php echo $row_stats['prenom'] . ' (' . $row_stats['race_label'] . ')'; ?></td>
                                <td class="align-middle"><?php echo $row_stats['total_cliques']; ?></td>
                                <?php if(!(isset($_GET['annee']) && !empty($_GET['annee']))): ?>
                                    <td class="align-middle"><?php echo substr($row_stats['mois_annee'], 5); ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile;
                    }
                    ?>
                </tbody>
            </table>
            <br>
        </div>
        <div class="col-md-6">
            <canvas id="pieChart" width="400" height="400"></canvas>
        </div>
    </div> 
    <br>   
</div>

<script>
    // Dessiner le graphique
    var ctx = document.getElementById('pieChart').getContext('2d');
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Nombre de clics par animal',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'right'
            }
        }
    });
</script>

<?php
// Fermer la connexion à la base de données
$connexion->close();
?>
