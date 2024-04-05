<?php
// Inclusion du fichier de connexion à la base de données
include_once "back/connect_bdd.php";

// Vérifier si la connexion à la base de données a échoué
if (!$connexion) {
    echo "La connexion à la base de données a échoué.";
    exit;
}

// Vérifier s'il y a une erreur de connexion
if ($connexion->connect_error) {
    die("La connexion a échoué : " . $connexion->connect_error);
}

// Initialisation des variables
$result_stats = null;

// Requête pour récupérer les animaux avec leurs races associées
$query_animaux = "SELECT a.animal_id, a.prenom, r.label AS race_label
                  FROM animal a
                  INNER JOIN race r ON a.race_id = r.race_id";
$result_animaux = $connexion->query($query_animaux);

// Requête de base pour les statistiques
$query_stats = "SELECT s.animal_id, a.prenom, r.label AS race_label, DATE_FORMAT(s.date, '%Y-%m') AS mois_annee, SUM(s.counter) AS total_cliques
                FROM stat s
                INNER JOIN animal a ON s.animal_id = a.animal_id
                INNER JOIN race r ON a.race_id = r.race_id";

// Filtrage des statistiques selon les paramètres GET
if (isset($_GET['animal']) && !empty(trim($_GET['animal']))) {
    $animal_id = (int) $_GET['animal'];
    $query_stats .= " WHERE s.animal_id = $animal_id";
}

if (isset($_GET['annee']) && !empty(trim($_GET['annee']))) {
    $annee = (int) $_GET['annee'];
    $query_stats .= " AND YEAR(s.date) = $annee";
}

if (isset($_GET['mois']) && !empty(trim($_GET['mois'])) && $_GET['mois'] !== 'aucun') {
    $mois = (int) $_GET['mois'];
    $query_stats .= " AND MONTH(s.date) = $mois";
}

// Groupement des statistiques par animal
$query_stats .= " GROUP BY s.animal_id
                ORDER BY s.animal_id";

// Exécution de la requête pour récupérer les statistiques
$result_stats = $connexion->query($query_stats);

// Initialisation des tableaux pour les données du graphique
$labels = [];
$data = [];

// Traitement des statistiques récupérées
if ($result_stats !== null) {
    while ($row_stats = $result_stats->fetch_assoc()) {
        $prenom_race = $row_stats['prenom'] . ' (' . $row_stats['race_label'] . ')';
        $labels[] = $prenom_race;
        $data[] = $row_stats['total_cliques'];
    }
}

// Entêtes du tableau affichant les statistiques
$entetes_tableau = ['Animal (prénom)', 'Nombre de clics'];
if (isset($_GET['mois']) && !empty(trim($_GET['mois'])) && $_GET['mois'] !== 'aucun') {
    $entetes_tableau[] = 'Mois';
}

// Fermeture de la connexion à la base de données
$connexion->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Statistiques</title>
    <!-- Intégration des styles CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container" id="background2">
    <div class="row">
        <div class="col-md-6">
            <br>
            <!-- Bouton de retour -->
            <a href="admin.php" class="btn btn-secondary btn-block">Retour</a>
            <br><br>
            <!-- Titre du dashboard -->
            <h1 class="text-center">Dashboard Statistiques</h1>
            <br>
            <!-- Formulaire de filtrage -->
            <form action="" method="GET" class="mb-3">
                <div class="form-group">
                    <label for="animal">Filtrer par Animal :</label>
                    <select name="animal" id="animal" class="form-control">
                        <option value="">Tous</option>
                        <?php while ($row_animal = $result_animaux->fetch_assoc()): ?>
                            <option value="<?php echo $row_animal['animal_id']; ?>"><?php echo $row_animal['prenom'] . ' (' . $row_animal['race_label'] . ')'; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <br>
                <!-- Sélecteur pour l'année -->
                <div class="form-group">
                    <label for="annee">Filtrer par Année :</label>
                    <select name="annee" id="annee" class="form-control">
                        <option value="">Toutes</option>
                        <?php
                        $query_annees = "SELECT DISTINCT YEAR(date) AS annee FROM stat ORDER BY annee DESC";
                        $result_annees = $connexion->query($query_annees);
                        while ($row_annee = $result_annees->fetch_assoc()):
                        ?>
                            <option value="<?php echo $row_annee['annee']; ?>"><?php echo $row_annee['annee']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <br>
                <!-- Sélecteur pour le mois -->
                <div class="form-group">
                    <label for="mois">Filtrer par Mois :</label>
                    <select name="mois" id="mois" class="form-control">
                        <option value="aucun">Aucun mois</option>
                        <?php
                        $mois_labels = [
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
                        ];
                        foreach ($mois_labels as $mois_num => $mois_label):
                        ?>
                            <option value="<?php echo $mois_num; ?>"><?php echo $mois_label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <!-- Bouton de soumission du formulaire -->
                <button type="submit" class="btn btn-primary btn-block">Filtrer</button>
            </form>
            <!-- Tableau affichant les statistiques -->
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
                    if ($result_stats !== null) {
                        $result_stats->data_seek(0); // Retour au début des résultats
                        while ($row_stats = $result_stats->fetch_assoc()):
                        ?>
                            <tr>
                                <td class="align-middle"><?php echo $row_stats['prenom'] . ' (' . $row_stats['race_label'] . ')'; ?></td>
                                <td class="align-middle"><?php echo $row_stats['total_cliques']; ?></td>
                                <?php
                                $mois_fr = [
                                    '01' => 'Janvier',
                                    '02' => 'Février',
                                    '03' => 'Mars',
                                    '04' => 'Avril',
                                    '05' => 'Mai',
                                    '06' => 'Juin',
                                    '07' => 'Juillet',
                                    '08' => 'Août',
                                    '09' => 'Septembre',
                                    '10' => 'Octobre',
                                    '11' => 'Novembre',
                                    '12' => 'Décembre'
                                ];
                                ?>
                                <!-- Affichage du mois si le filtre est appliqué -->
                                <?php if (isset($_GET['mois']) && !empty(trim($_GET['mois'])) && $_GET['mois'] !== 'aucun'): ?>
                                    <td class="align-middle"><?php echo $mois_fr[substr($row_stats['mois_annee'], 5)]; ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile;
                    }
                    ?>
                </tbody>
            </table>
            <br>
        </div>
        <!-- Colonne pour afficher le graphique -->
        <div class="col-md-6">
            <canvas id="pieChart" width="400" height="400"></canvas>
        </div>
    </div>
    <br>
</div>

<!-- Intégration de la librairie Chart.js pour créer des graphiques -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

<!-- Script JavaScript -->
<script>
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

<!-- Fin du script JavaScript -->
</body>
</html>
