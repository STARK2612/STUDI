<?php
// Inclusion du fichier de connexion à la base de données
include_once "back/connect_bdd.php";

// Vérification de la connexion à la base de données
if (!$connexion) {
    echo "La connexion à la base de données a échoué.";
    exit;
}

// Vérification d'erreur de connexion
if ($connexion->connect_error) {
    die("La connexion a échoué : " . $connexion->connect_error);
}

// Traitement du formulaire d'ajout d'un nouvel habitat
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    // Vérification de l'upload du fichier
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageType = $_FILES['image']['type'];

        // Types de fichiers autorisés
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if(in_array($imageType, $allowedTypes)) {
            // Préparation de la requête pour l'insertion de l'image
            $insertImage = $connexion->prepare("INSERT INTO image (image_data, image_type) VALUES (?, ?)");
            $insertImage->bind_param("ss", $imageData, $imageType);
            if ($insertImage->execute()) {
                $imageId = $insertImage->insert_id;

                // Préparation de la requête pour l'insertion de l'habitat
                $insertHabitat = $connexion->prepare("INSERT INTO habitat (image_id, nom, description) VALUES (?, ?, ?)");
                $insertHabitat->bind_param("iss", $imageId, $nom, $description);
                if($insertHabitat->execute()) {
                    $_SESSION['success_message'] = "Nouvel habitat ajouté avec succès";
                } else {
                    echo "Erreur lors de l'insertion de l'habitat : " . $insertHabitat->error;
                }
                $insertHabitat->close();
            } else {
                echo "Erreur lors de l'insertion de l'image : " . $insertImage->error;
            }
            $insertImage->close();
        } else {
            echo "Le fichier doit être au format JPEG, JPG ou PNG.";
        }
    } else {
        echo "Erreur lors de l'upload du fichier.";
    }
}

// Traitement du formulaire de modification d'un habitat
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $habitat_id = isset($_POST['habitat_id']) ? $_POST['habitat_id'] : null;
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    // Vérification de l'existence de l'habitat_id
    if ($habitat_id !== null) {
        // Requête SQL pour la mise à jour de l'habitat
        $sql = "UPDATE habitat SET nom=?, description=?";
        $bindParams = array("ss", $nom, $description);

        if ($_FILES['nouvelle_image']['error'] === UPLOAD_ERR_OK) {
            // Traitement de l'image
            $imageData = file_get_contents($_FILES['nouvelle_image']['tmp_name']);
            $imageType = $_FILES['nouvelle_image']['type'];

            // Types de fichiers autorisés
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($imageType, $allowedTypes)) {
                echo "Le fichier doit être au format JPEG, JPG ou PNG.";
                exit;
            }

            // Préparation de la requête pour l'insertion de l'image
            $sqlInsertImage = "INSERT INTO image (image_data, image_type) VALUES (?, ?)";
            $insertImage = $connexion->prepare($sqlInsertImage);
            $insertImage->bind_param("ss", $imageData, $imageType);
            if (!$insertImage->execute()) {
                echo "Erreur lors de l'insertion de l'image : " . $insertImage->error;
                exit;
            }

            // Récupération de l'ID de l'image insérée
            $imageId = $insertImage->insert_id;

            // Ajout de l'ID de l'image dans la requête de mise à jour
            $sql .= ", image_id=?";
            $bindParams[0] .= "i";
            $bindParams[] = $imageId;
        }

        $sql .= " WHERE habitat_id=?";
        $bindParams[0] .= "i";
        $bindParams[] = $habitat_id;

        // Préparation de la requête
        $updateStatement = $connexion->prepare($sql);

        if (!$updateStatement) {
            echo "Erreur de préparation de la requête : " . $connexion->error;
            exit;
        }

        // Liaison des paramètres
        if (!$updateStatement->bind_param(...$bindParams)) {
            echo "Erreur de liaison des paramètres : " . $updateStatement->error;
            exit;
        }

        // Exécution de la requête
        if ($updateStatement->execute()) {
            echo "Habitat mis à jour avec succès";
        } else {
            echo "Erreur lors de l'exécution de la requête : " . $updateStatement->error;
        }

        // Fermeture de la déclaration
        $updateStatement->close();
    } else {
        echo "ID d'habitat non spécifié.";
    }
}

// Traitement de la suppression d'un habitat
if (isset($_POST['supprimer'])) {
    $habitat_id = isset($_POST['habitat_id']) ? $_POST['habitat_id'] : null;

    // Vérification de l'existence de l'habitat_id
    if ($habitat_id !== null) {
        // Requête pour récupérer l'ID de l'image associée à l'habitat
        $sql_select_image_id = "SELECT image_id FROM habitat WHERE habitat_id=$habitat_id";
        $result_select_image_id = $connexion->query($sql_select_image_id);

        if ($result_select_image_id->num_rows > 0) {
            $row = $result_select_image_id->fetch_assoc();
            $image_id = $row['image_id'];

            // Requête pour supprimer l'habitat
            $sql_delete_habitat = "DELETE FROM habitat WHERE habitat_id=$habitat_id";
            if ($connexion->query($sql_delete_habitat) === TRUE) {
                // Requête pour supprimer l'image associée
                $sql_delete_image = "DELETE FROM image WHERE image_id=$image_id";
                if ($connexion->query($sql_delete_image) === TRUE) {
                    echo "<script>alert('Habitat et image associée supprimés avec succès');</script>";
                } else {
                    echo "Erreur lors de la suppression de l'image : " . $connexion->error;
                }
            } else {
                echo "Erreur lors de la suppression de l'habitat : " . $connexion->error;
            }
        } else {
            echo "ID d'habitat non trouvé.";
        }
    } else {
        echo "ID d'habitat non spécifié.";
    }
}

// Pagination
$servicesParPage = 1;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$premierHabitat = ($page - 1) * $servicesParPage;

// Requête pour sélectionner les habitats avec pagination
$sql = "SELECT habitat.*, image.image_type, image.image_data FROM habitat INNER JOIN image ON habitat.image_id = image.image_id LIMIT $premierHabitat, $servicesParPage";
$result = $connexion->query($sql);

// Requête pour compter le nombre total d'habitats
$sql_count = "SELECT COUNT(*) AS totalHabitats FROM habitat";
$result_count = $connexion->query($sql_count);
$row_count = $result_count->fetch_assoc();
$totalHabitats = $row_count['totalHabitats'];
$totalPages = ceil($totalHabitats / $servicesParPage);

?>
<div class="container" id="background2">
    <div class="row">
        <!-- Formulaire d'ajout d'un nouvel habitat -->
        <div class="col-md-4">
            <br>
            <form method="post" class="custom-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                <h3>Ajouter un Nouvel Habitat</h3>
                <div class="form-group">
                    <label for="nom">Nom de l'Habitat:</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="description">Description de l'Habitat:</label>
                    <textarea type="text" class="form-control" id="description" name="description" rows="17" required></textarea>
                </div>
                <div class="form-group">
                    <br>
                    <label for="image">Image:</label>
                    <input type="file" class="form-control-file" id="image" name="image" accept="image/jpeg, image/jpg, image/png" required>
                </div>
                <br>
                <button type="submit" class="btn btn-primary" name="ajouter">Ajouter</button>
                <br><br>
                <!-- Bouton de retour en fonction du rôle de l'utilisateur -->
                <a href="<?php
                    if ($_SESSION['role'] == 'Administrateur') {
                        echo "admin.php";
                    } elseif ($_SESSION['role'] == 'Employé') {
                        echo "employe.php";
                    } elseif ($_SESSION['role'] == 'Vétérinaire') {
                        echo "veterinaire.php";
                    }
                ?>" class="btn btn-secondary btn-block">Retour</a>
            </form>
            <br>
        </div>
        <!-- Tableau pour afficher les habitats -->
        <div class="col-md-7">
            <br>
            <h3>Modifier/Supprimer un Habitat</h3>
            <div class="table-responsive overflow-auto">
            <table class="table">
            <thead class="thead-dark">
                    <tr>
                        <th class='hidden'>ID de l'Habitat</th>
                        <th>Nom de l'Habitat</th>
                        <th>Description de l'Habitat</th>
                        <th>Commentaire de l'Habitat</th>
                        <th>Image</th>
                        <th>Modifier/Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='habitat_id hidden'>" . ($row['habitat_id'] ?? '') . "</td>";
                        echo "<td class='nom'>" . $row['nom'] . "</td>";
                        echo "<td class='description description-cell2'>" . $row['description'] . "</td>";
                        echo "<td class='commentaire description-cell'>" . (isset($row['commentaire_habitat']) ? $row['commentaire_habitat'] : "") . "</td>";
                        echo "<td>";
                        echo isset($row['image_type']) ? "<img src='data:" . $row['image_type'] . ";base64," . base64_encode($row['image_data']) . "' width='auto' height='50' />" : "<img src='front/img/defaultsmall.jpg' alt='Image par défaut'>";
                        echo "</td>";
                        echo "<td>";
                        echo "<div class='btn-group' role='group'>";
                        echo "<button class='btn btn-primary btn-sm edit-button'>Modifier</button>";
                        echo "</div>";
                        echo "<div style='margin-top: 5px;'></div>";
                        echo "<form class='delete-form' method='post' action='" . $_SERVER['PHP_SELF'] . "' onsubmit='return confirmDelete(" . ($row['habitat_id'] ?? '') . ")'>";
                        echo "<input type='hidden' name='habitat_id' value='" . ($row['habitat_id'] ?? '') . "'>";
                        echo "<button type='submit' class='btn btn-danger btn-sm delete-button' name='supprimer' id='delete-button-" . ($row['habitat_id'] ?? '') . "'>Supprimer</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            </div>
            <!-- Pagination -->
            <?php
            echo "<ul class='pagination'>";
            for ($i = 1; $i <= $totalPages; $i++) {
                $activeClass = ($page == $i) ? "active" : "";
                echo "<li class='page-item $activeClass'><a class='page-link' href='?page=$i'>$i</a></li>";
            }
            echo "</ul>";
            ?>
        </div>
    </div>
</div>
<!-- Modal pour la modification d'un habitat -->
<div id="myModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier Habitat</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" class="custom-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                    <input type="hidden" id="habitat_id" name="habitat_id">
                    <div class="form-group">
                        <label for="habitat_id">ID de l'habitat:</label>
                        <input type="text" class="form-control" id="habitat_id2" name="habitat_id" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nom2">Nom de l'Habitat:</label>
                        <input type="text" class="form-control" id="nom2" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="description2">Description de l'Habitat:</label>
                        <textarea type="text" class="form-control" id="description2" name="description" rows="17" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="nouvelle_image">Nouvelle Image:</label>
                        <input type="file" class="form-control-file" id="nouvelle_image" name="nouvelle_image" accept="image/jpeg, image/jpg, image/png">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary" name="modifier" onclick="showSuccessMessage()">Modifier</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script JavaScript -->
<script>
    // Gestion des boutons de modification
    var editButtons = document.querySelectorAll('.edit-button');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            var form = button.closest('tr');
            var habitat_id = form.querySelector('.habitat_id') ? form.querySelector('.habitat_id').innerText : null;
            var nom = form.querySelector('.nom').innerText;
            var description = form.querySelector('.description').innerText;
            var modal = document.getElementById('myModal');
            modal.style.display = "block";
            document.getElementById('habitat_id2').value = habitat_id;
            document.getElementById('nom2').value = nom;
            document.getElementById('description2').value = description;
        });
    });

    // Gestion de la fermeture du modal
    var closeBtn = document.querySelector('.close');
    closeBtn.addEventListener('click', function() {
        var modal = document.getElementById('myModal');
        modal.style.display = "none";
    });

    // Gestion de la fermeture du modal en cliquant en dehors de celui-ci
    window.onclick = function(event) {
        var modal = document.getElementById('myModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Fonction de confirmation pour la suppression d'un habitat
    function confirmDelete(habitat_id) {
        return confirm("Êtes-vous sûr de vouloir supprimer cet habitat ?");
    }
</script>

<!-- Affichage du message de succès -->
<script>
    <?php if(isset($_SESSION['success_message'])): ?>
        alert("<?php echo $_SESSION['success_message']; ?>");
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
</script>

<!-- Vérification de la taille du fichier avant l'upload -->
<script>
    function checkFileSize() {
        var input = document.getElementById('image');
        if (input.files.length > 0) {
            var fileSize = input.files[0].size;
            var maxSize = 1048576;

            if (fileSize > maxSize) {
                document.getElementById('fileSizeError').innerHTML = 'Fichier trop lourd, 1 Mo maximum.';
                return false;
            } else {
                document.getElementById('fileSizeError').innerHTML = '';
                return true;
            }
        }
        return true;
    }
</script>

<!-- Affichage du message de succès pour la modification -->
<script>
    function showSuccessMessage() {
        alert("Habitat mis à jour avec succès");
        var modal = document.getElementById('myModal');
        modal.style.display = "none";
    }
</script>
