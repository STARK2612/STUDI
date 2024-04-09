<?php
// Inclusion du fichier de connexion à la base de données
include_once "back/connect_bdd.php";

// Vérifier si la connexion à la base de données est établie
if (!$connexion) {
    echo "La connexion à la base de données a échoué.";
    exit; // Arrêter l'exécution du script en cas d'échec de la connexion
}

// Traitement de l'upload d'image
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    // Vérifier si un fichier est envoyé
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']); // Lecture du fichier
        $imageType = $_FILES['image']['type']; // Type de fichier

        // Vérification du type de fichier
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if(in_array($imageType, $allowedTypes)) {
            // Insertion des données de l'image dans la table image
            $insertImage = $connexion->prepare("INSERT INTO image (image_data, image_type) VALUES (?, ?)");
            $insertImage->bind_param("ss", $imageData, $imageType);
            if ($insertImage->execute()) {
                $imageId = $insertImage->insert_id; // Récupération de l'ID de l'image insérée
            } else {
                echo "Erreur lors de l'insertion de l'image : " . $insertImage->error;
                exit;
            }
        } else {
            echo "Type de fichier non supporté.";
            exit;
        }
    }

    // Récupération des données du formulaire
    $prenom = $_POST['prenom'];
    $race = $_POST['race'];
    $habitat = $_POST['habitat'];

    // Vérification de l'existence de la race
    $checkRace = $connexion->prepare("SELECT race_id FROM race WHERE label = ?");
    $checkRace->bind_param("s", $race);
    $checkRace->execute();
    $resultRace = $checkRace->get_result();

    // Si la race n'existe pas, l'ajouter dans la table race
    if ($resultRace->num_rows === 0) {
        $insertRace = $connexion->prepare("INSERT INTO race (label) VALUES (?)");
        $insertRace->bind_param("s", $race);
        $insertRace->execute();
        $raceId = $insertRace->insert_id;
        $insertRace->close();
    } else {
        // Récupérer l'ID de la race existante
        $raceData = $resultRace->fetch_assoc();
        $raceId = $raceData['race_id'];
    }
    $checkRace->close();

    // Insertion des données de l'animal dans la table animal
    $insertAnimal = $connexion->prepare("INSERT INTO animal (prenom, race_id, habitat_id, image_id) VALUES (?, ?, ?, ?)");
    $insertAnimal->bind_param("siii", $prenom, $raceId, $habitat, $imageId);
    if($insertAnimal->execute()) {
        echo "<script>alert('Nouvel animal ajouté avec succès');</script>";
    } else {
        echo "Erreur lors de l'insertion de l'animal : " . $insertAnimal->error;
    }
}

// Modification d'un animal
if (isset($_POST["modifier"])) {
    // Récupération des données du formulaire
    $animal_id = $_POST['animal_id'];
    $prenom = $_POST['prenom'];
    $race = $_POST['race'];
    $habitat = $_POST['habitat'];

    // Vérifier si un fichier est envoyé
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']); // Lecture du fichier
        $imageType = $_FILES['image']['type']; // Type de fichier

        // Vérification du type de fichier
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if(in_array($imageType, $allowedTypes)) {
            // Insertion des données de l'image dans la table image
            $insertImage = $connexion->prepare("INSERT INTO image (image_data, image_type) VALUES (?, ?)");
            $insertImage->bind_param("ss", $imageData, $imageType);
            if ($insertImage->execute()) {
                $imageId = $insertImage->insert_id; // Récupération de l'ID de l'image insérée
            } else {
                echo "Erreur lors de l'insertion de l'image : " . $insertImage->error;
                exit;
            }
        } else {
            echo "Type de fichier non supporté.";
            exit;
        }
    }

    // Requête SQL pour mettre à jour l'animal dans la base de données
    $requete = "UPDATE animal SET prenom=?, habitat_id=?, image_id=? WHERE animal_id=?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param("siii", $prenom, $habitat, $imageId, $animal_id);
    if ($stmt->execute()) {
        // Modifier le label de la race dans la table des races
        $updateRace = $connexion->prepare("UPDATE race SET label = ? WHERE race_id = (SELECT race_id FROM animal WHERE animal_id = ?)");
        $updateRace->bind_param("si", $race, $animal_id);
        if ($updateRace->execute()) {
            echo "<script>alert('L\\'animal a été modifié avec succès.');</script>";
        } else {
            echo "Erreur lors de la modification de l'animal : " . $connexion->error;
        }
    }
}

// Suppression d'un animal
if (isset($_POST['supprimer'])) {
    $animal_id = $_POST['animal_id'];

    // Supprimer les statistiques liées à l'animal
    $sql_delete_stat = "DELETE FROM stat WHERE animal_id=?";
    $stmt_delete_stat = $connexion->prepare($sql_delete_stat);
    $stmt_delete_stat->bind_param("i", $animal_id);
    if ($stmt_delete_stat->execute()) {
        // Supprimer l'animal
        $sql_delete_animal = "DELETE FROM animal WHERE animal_id=?";
        $stmt_delete_animal = $connexion->prepare($sql_delete_animal);
        $stmt_delete_animal->bind_param("i", $animal_id);
        if ($stmt_delete_animal->execute()) {
            echo "<script>alert('Animal supprimé avec succès');</script>"; // Affichage du message dans une fenêtre popup
        } else {
            echo "Erreur lors de la suppression de l'animal : " . $stmt_delete_animal->error;
        }
    } else {
        echo "Erreur lors de la suppression des statistiques de l'animal : " . $stmt_delete_stat->error;
    }
}

// Récupération de la liste des habitats
$sql_habitats = "SELECT habitat_id, nom FROM habitat";
$result_habitats = $connexion->query($sql_habitats);

// Récupération des races d'animaux
$sql_races = "SELECT race_id, label FROM race";
$result_races = $connexion->query($sql_races);

$animauxParPage = 1; // Nombre d'animaux à afficher par page

// Vérifier si la page est définie, sinon, la définir sur 1
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculer le numéro du premier animal pour la requête SQL
$premierAnimal = ($page - 1) * $animauxParPage;

// Récupération des animaux pour la page actuelle
$sql_animaux = "SELECT animal.animal_id, animal.prenom, race.label AS race, habitat.nom AS habitat, image.image_data, image.image_type 
                FROM animal
                LEFT JOIN race ON animal.race_id = race.race_id
                LEFT JOIN habitat ON animal.habitat_id = habitat.habitat_id
                LEFT JOIN image ON animal.image_id = image.image_id 
                LIMIT ?, ?";
$stmt = $connexion->prepare($sql_animaux);
$stmt->bind_param("ii", $premierAnimal, $animauxParPage);
$stmt->execute();
$result_animaux = $stmt->get_result();
?>

<div class="container" id="background2">
    <div class="row">
        <div class="col-md-4">
            <br>
            <form method="post" class="custom-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onsubmit="return checkFileSize()">
            <h3>Ajouter un Nouvel Animal</h3>
                <div class="form-group">
                    <label for="prenom">Prénom de l'Animal:</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>
                <div class="form-group">
                    <label for="race">Race de l'Animal:</label>
                    <input type="text" class="form-control" id="race" name="race" required>
                </div>
                <br>
                <div class="form-group">
                    <label for="image">Photo de l'Animal:</label>
                    <input type="file" class="form-control-file" id="image" name="image" accept="image/jpeg, image/jpg, image/png" required>
                    <div id="fileSizeError" style="color: red;"></div> <!-- Div pour afficher le message d'erreur -->
                </div>
                <br>
                <div class="form-group">
                    <label for="habitat">Habitat de l'Animal:</label>
                    <select class="form-control" id="habitat" name="habitat">
                        <option value="" disabled selected>Choisir un habitacle</option>
                        <?php
                        // Récupération de la liste des habitats
                        $sql_habitats = "SELECT habitat_id, nom FROM habitat";
                        $result_habitats = $connexion->query($sql_habitats);
                        while ($row = $result_habitats->fetch_assoc()) {
                            echo "<option value='" . $row['habitat_id'] . "'>" . $row['nom'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <br>
                <button type="submit" class="btn btn-primary" name="ajouter">Ajouter</button>
                <br><br>
            <a href="admin.php" class="btn btn-secondary btn-block">Retour</a>
            <br><br>
            </form>
        </div>
        <div class="col-md-6">
        <div class="table-responsive overflow-auto">
            <br>
            <h3>Modifier/Supprimer un Animal</h3>
            <table class="table">
                <thead>
                <tr>
                    <th class='hidden'>ID de l'Animal</th>
                    <th>Prénom</th>
                    <th>Photo</th>
                    <th>Race</th>
                    <th>Habitat</th>
                    <th>Modifier/Supprimer</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // Boucle à travers chaque animal et afficher les détails dans le tableau
                while ($row = $result_animaux->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='animal_id hidden'>" . $row['animal_id'] . "</td>";
                    echo "<td class='prenom description-cell'>" . $row['prenom'] . "</td>";
                    echo "<td><img src='data:" . $row['image_type'] . ";base64," . base64_encode($row['image_data']) . "' width='auto' height='50' /></td>";
                    echo "<td class='race-animal description-cell'>" . $row['race'] . "</td>";
                    echo "<td class='habitat-animal'>" . $row['habitat'] . "</td>";
                    // Ajouter des boutons pour modifier et supprimer chaque animal
                    echo "<td>";
                    echo "<div class='btn-group' role='group'>";
                    echo "<button class='btn btn-primary btn-sm edit-button'>Modifier</button>";
                    echo "</div>";
                    echo "<div style='margin-top: 5px;'></div>"; // Espace de 5px entre les boutons
                    // Formulaire pour la suppression de l'animal
                    echo "<form class='delete-form' method='post' action='" . $_SERVER['PHP_SELF'] . "' onsubmit='return confirmDelete(" . $row['animal_id'] . ")'>";
                    echo "<input type='hidden' name='animal_id' value='" . $row['animal_id'] . "'>";
                    echo "<button type='submit' class='btn btn-danger btn-sm delete-button' name='supprimer' id='delete-button-" . $row['animal_id'] . "'>Supprimer</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                <?php
                    // Nombre total de pages
                    $sql_total_animaux = "SELECT COUNT(*) AS total FROM animal";
                    $result_total_animaux = $connexion->query($sql_total_animaux);
                    $row_total_animaux = $result_total_animaux->fetch_assoc();
                    $totalPages = ceil($row_total_animaux['total'] / $animauxParPage);

                    // Affichage des liens de pagination
                    for ($i = 1; $i <= $totalPages; $i++) {
                        // Vérifier si la page actuelle correspond à la page en cours de boucle
                        $activeClass = ($i == $page) ? 'active' : '';
                        echo "<li class='page-item $activeClass'><a class='page-link' href='?page=" . $i . "'>" . $i . "</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>
        </div>
    </div>
</div>

<script>
    // Confirmation avant de supprimer un animal
    function confirmDelete(animal_id) {
        return confirm("Êtes-vous sûr de vouloir supprimer cet animal ?");
    }

    // Vérifier la taille du fichier avant de l'envoyer
    function checkFileSize() {
        var fileInput = document.getElementById('image');
        var fileSize = fileInput.files[0].size; // Taille du fichier en octets
        var maxSize = 2 * 1024 * 1024; // Taille maximale autorisée (2 Mo)

        // Vérifier si la taille du fichier dépasse la taille maximale autorisée
        if (fileSize > maxSize) {
            document.getElementById('fileSizeError').innerText = "La taille du fichier ne doit pas dépasser 2 Mo.";
            return false; // Empêcher l'envoi du formulaire
        } else {
            return true; // Autoriser l'envoi du formulaire
        }
    }

    // Modifier les données de l'animal sélectionné
    $('.edit-button').click(function () {
        var row = $(this).closest('tr'); // Récupérer la ligne du tableau
        var animal_id = row.find('.animal_id').text(); // ID de l'animal
        var prenom = row.find('.prenom').text(); // Prénom de l'animal
        var race = row.find('.race-animal').text(); // Race de l'animal
        var habitat = row.find('.habitat-animal').text(); // Habitat de l'animal

        // Pré-remplir le formulaire de modification avec les données de l'animal sélectionné
        $('#animal_id').val(animal_id);
        $('#prenom').val(prenom);
        $('#race').val(race);
        $('#habitat').val(habitat);

        // Faire défiler jusqu'au formulaire de modification
        $('html, body').animate({
            scrollTop: $("#background2").offset().top
        }, 1000);
    });
</script>

<?php
// Fermer les requêtes préparées et la connexion à la base de données
$stmt->close();
$connexion->close();
?>
