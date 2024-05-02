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
<div class="container" id="background-color" style="border-radius: 10px; border: 3px solid white;">
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
                    <div id="fileSizeError" style="color: red;"></div>
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
                <button type="submit" class="btn btn-warning" name="ajouter">Ajouter</button>
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
                    <th class='text-center'>Prénom</th>
                    <th class='text-center'>Photo</th>
                    <th class='text-center'>Race</th>
                    <th class='text-center'>Habitat</th>
                    <th class='text-center'>Modifier/Supprimer</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // Boucle à travers chaque animal et afficher les détails dans le tableau
                while ($row = $result_animaux->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='animal_id hidden text-center'>" . $row['animal_id'] . "</td>";
                    echo "<td class='prenom description-cell text-center'style='background-color:white; border: 1px solid black;'>" . $row['prenom'] . "</td>";
                    echo "<td class='text-center' style='background-color:white; border: 1px solid black;'><img src='data:" . $row['image_type'] . ";base64," . base64_encode($row['image_data']) . "'border= 2px solid black width='auto' height='50'/></td>";
                    echo "<td class='race-animal description-cell text-center' style='background-color:white; border: 1px solid black;'>" . $row['race'] . "</td>";
                    echo "<td class='habitat-animal text-center' style='background-color:white; border: 1px solid black;'>" . $row['habitat'] . "</td>";
                    // Ajouter des boutons pour modifier et supprimer chaque animal
                    echo "<td class='text-center' style='background-color:white; border: 1px solid black;'>";
                    echo "<div class='btn-group text-center' role='group'>";
                    echo "<button class='btn btn-primary btn-sm edit-button text-center'>Modifier</button>";
                    echo "</div>";
                    echo "<div style='margin-top: 5px;'></div>"; // Espace de 5px entre les boutons
                    // Formulaire pour la suppression de l'animal
                    echo "<form class='delete-form text-center' method='post' action='" . $_SERVER['PHP_SELF'] . "' onsubmit='return confirmDelete(" . $row['animal_id'] . ")'>";
                    echo "<input type='hidden' name='animal_id' value='" . $row['animal_id'] . "'>";
                    echo "<button type='submit' class='btn btn-danger btn-sm delete-button text-center' name='supprimer' id='delete-button-" . $row['animal_id'] . "'>Supprimer</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
            <!-- Pagination -->
            <nav aria-label="Page navigation example" class="pagination-container">
                <ul class="pagination justify-content-center">
                    <?php
                    // Nombre total de pages
                    $sql_total_animaux = "SELECT COUNT(*) AS total FROM animal";
                    $result_total_animaux = $connexion->query($sql_total_animaux);
                    $row_total_animaux = $result_total_animaux->fetch_assoc();
                    $totalPages = ceil($row_total_animaux['total'] / $animauxParPage);
                    // Affichage des liens de pagination
                    echo "<li class='page-item'><a class='page-link' href='?page=1'>1</a></li>"; // Toujours afficher le lien vers la première page
                    for ($i = 0; $i <= $totalPages; $i++) {
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

<!-- Modal de modification d'animal -->
<div id="myModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier Animal</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Formulaire de modification d'animal -->
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="animal_id">ID de l'Animal:</label>
                        <input type="text" class="form-control" id="animal_id2" name="animal_id" readonly>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom de l'Animal:</label>
                        <input type="text" class="form-control" id="prenom2" name="prenom" required>
                    </div>
                    <div class="form-group">
                        <label for="race2">Race de l'Animal:</label>
                        <input type="text" class="form-control" id="race2" name="race" required>
                    </div>
                    <div class="form-group">
                        <label for="habitat2">Habitat de l'Animal:</label>
                        <select class="form-control" id="habitat2" name="habitat" required>
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
                    <div class="form-group">
                        <label for="image2">Photo de l'Animal:</label>
                        <input type="file" class="form-control-file" id="image2" name="image" accept="image/jpeg, image/jpg, image/png">
                        <div id="fileSizeErrorModal" style="color: red;"></div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-warning" name="modifier" onclick="return checkFileSizeModal() && showSuccessMessage()">Modifier</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Inclure la bibliothèque jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Inclure la bibliothèque Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Ajouter un gestionnaire d'événements pour les boutons "Modifier"
    var editButtons = document.querySelectorAll('.edit-button');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            // Empêcher le comportement par défaut du formulaire
            event.preventDefault();
            // Récupérer le formulaire parent
            var form = button.closest('tr');
            // Récupérer les valeurs des champs
            var animal_id = form.querySelector('.animal_id').innerText;
            var prenom = form.querySelector('.prenom').innerText;
            var race = form.querySelector('.race-animal').innerText;
            var habitat = form.querySelector('.habitat-animal').innerText;
            // Afficher la fenêtre modale de modification avec les champs préremplis
            var modal = document.getElementById('myModal');
            modal.style.display = "block";
            // Remplir les champs de la fenêtre modale avec les valeurs récupérées
            document.getElementById('animal_id2').value = animal_id;
            document.getElementById('prenom2').value = prenom;
            document.getElementById('race2').value = race;
            document.getElementById('habitat2').value = habitat;
        });
    });

    // Fermer la fenêtre modale lorsque l'utilisateur clique sur la croix
    var closeBtn = document.querySelector('.close');
    closeBtn.addEventListener('click', function() {
        var modal = document.getElementById('myModal');
        modal.style.display = "none";
    });

    // Fermer la fenêtre modale lorsque l'utilisateur clique en dehors de celle-ci
    window.onclick = function(event) {
        var modal = document.getElementById('myModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    // Fonction pour confirmer la suppression d'un animal
    // Confirmation avant de supprimer un animal
    function confirmDelete(animal_id) {
        return confirm("Êtes-vous sûr de vouloir supprimer cet animal ?");
    }
    </script>
    <script>
        function checkFileSize() {
        var input = document.getElementById('image');
        if (input.files.length > 0) {
            var fileSize = input.files[0].size;
            var maxSize = 10485760; // Taille maximale : 10 Mo (10 * 1024 * 1024)

            if (fileSize > maxSize) {
                document.getElementById('fileSizeError').innerHTML = 'Fichier trop lourd, 10 Mo maximum.';
                return false;
            } else {
                document.getElementById('fileSizeError').innerHTML = '';
                return true;
            }
        }
        return true;
    }
    function checkFileSizeModal() {
    var input = document.getElementById('image2');
    if (input.files.length > 0) {
        var fileSize = input.files[0].size;
        var maxSize = 10485760; // Taille maximale : 10 Mo (10 * 1024 * 1024)

        if (fileSize > maxSize) {
            // Afficher le message d'erreur dans la modal
            document.getElementById('fileSizeErrorModal').innerHTML = 'Fichier trop lourd, 10 Mo maximum.';
            return false;
        } else {
            // Réinitialiser le message d'erreur s'il n'y a pas d'erreur
            document.getElementById('fileSizeErrorModal').innerHTML = '';
            return true;
        }
    }
    return true;
}
    </script>
    <script>
        function showSuccessMessage() {
        alert("Animal mis à jour avec succès");
        var modal = document.getElementById('myModal');
        modal.style.display = "none";
    }
    </script>
    <script>
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