<?php
// Inclure le fichier de connexion à la base de données
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

// Traitement pour l'ajout d'un nouveau service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    // Vérifier si un fichier image est uploadé et s'il n'y a pas d'erreur
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageType = $_FILES['image']['type'];

        // Types de fichiers image autorisés
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if(in_array($imageType, $allowedTypes)) {
            // Préparer et exécuter la requête d'insertion de l'image
            $insertImage = $connexion->prepare("INSERT INTO image (image_data, image_type) VALUES (?, ?)");
            $insertImage->bind_param("ss", $imageData, $imageType);
            if ($insertImage->execute()) {
                $imageId = $insertImage->insert_id;

                // Préparer et exécuter la requête d'insertion du service
                $insertService = $connexion->prepare("INSERT INTO service (nom, description, image_id) VALUES (?, ?, ?)");
                $insertService->bind_param("ssi", $nom, $description, $imageId);
                if($insertService->execute()) {
                    $_SESSION['success_message'] = "Nouveau service ajouté avec succès";
                } else {
                    echo "Erreur lors de l'insertion du service : " . $insertService->error;
                }
                $insertService->close();
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

// Traitement pour la modification d'un service existant
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $service_id = $_POST['service_id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    // Requête de mise à jour du service
    $sql = "UPDATE service SET nom='$nom', description='$description' WHERE service_id=$service_id";

    if ($connexion->query($sql) === TRUE) {
        echo "Service mis à jour avec succès";
    } else {
        echo "Erreur : " . $sql . "<br>" . $connexion->error;
    }
}

// Traitement pour la suppression d'un service
if (isset($_POST['supprimer'])) {
    $service_id = $_POST['service_id'];

    // Sélectionner l'ID de l'image associée au service
    $sql_select_image_id = "SELECT image_id FROM service WHERE service_id=$service_id";
    $result_select_image_id = $connexion->query($sql_select_image_id);

    if ($result_select_image_id->num_rows > 0) {
        $row = $result_select_image_id->fetch_assoc();
        $image_id = $row['image_id'];

        // Supprimer le service
        $sql_delete_service = "DELETE FROM service WHERE service_id=$service_id";
        if ($connexion->query($sql_delete_service) === TRUE) {
            // Supprimer l'image associée
            $sql_delete_image = "DELETE FROM image WHERE image_id=$image_id";
            if ($connexion->query($sql_delete_image) === TRUE) {
                echo "<script>alert('Service et image associée supprimés avec succès');</script>";
            } else {
                echo "Erreur lors de la suppression de l'image : " . $connexion->error;
            }
        } else {
            echo "Erreur lors de la suppression du service : " . $connexion->error;
        }
    } else {
        echo "ID de service non trouvé.";
    }
}

// Pagination des services
$servicesParPage = 1;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$premierService = ($page - 1) * $servicesParPage;
$sql = "SELECT * FROM service LIMIT $premierService, $servicesParPage";
$result = $connexion->query($sql);
?>

<!-- Début du contenu HTML -->
<div class="container" id="background2">
    <div class="row">
        <!-- Formulaire d'ajout d'un nouveau service -->
        <div class="col-md-4">
            <br>
            <form method="post" class="custom-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onsubmit="return checkFileSize()">
                <h3>Ajouter un Nouveau Service</h3>
                <div class="form-group">
                    <label for="nom">Nom du Service:</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea type="text" class="form-control" id="description" name="description" rows="17" required></textarea>
                </div>
                <div class="form-group">
                    <br>
                    <label for="image">Image:</label>
                    <input type="file" class="form-control-file" id="image" name="image" accept="image/jpeg, image/jpg, image/png" required>
                    <div id="fileSizeError" style="color: red;"></div>
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
        <!-- Affichage des services avec possibilité de modification et suppression -->
        <div class="col-md-8">
            <br>
            <h3>Modifier/Supprimer un Service</h3>
            <div class="table-responsive overflow-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class='hidden'>ID du Service</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Modifier/Supprimer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Affichage des services
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='service_id hidden'>" . $row['service_id'] . "</td>";
                            echo "<td class='nom'>" . $row['nom'] . "</td>";
                            echo "<td class='description description-cell2'>" . $row['description'] . "</td>";
                            echo "<td>";
                            echo "<div class='btn-group' role='group'>";
                            echo "<button class='btn btn-primary btn-sm edit-button'>Modifier</button>";
                            echo "</div>";
                            echo "<div style='margin-top: 5px;'></div>";
                            echo "<form class='delete-form' method='post' action='" . $_SERVER['PHP_SELF'] . "' onsubmit='return confirmDelete(" . $row['service_id'] . ")'>";
                            echo "<input type='hidden' name='service_id' value='" . $row['service_id'] . "'>";
                            echo "<button type='submit' class='btn btn-danger btn-sm delete-button' name='supprimer' id='delete-button-" . $row['service_id'] . "'>Supprimer</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
            // Pagination
            $sql = "SELECT COUNT(*) AS totalServices FROM service";
            $result = $connexion->query($sql);
            $row = $result->fetch_assoc();
            $totalServices = $row['totalServices'];
            $totalPages = ceil($totalServices / $servicesParPage);

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

<!-- Modèle de fenêtre modale pour la modification de service -->
<div id="myModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier Service</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="back/modifier_service.php">
                    <div class="form-group">
                        <label for="service_id">ID du Service:</label>
                        <input type="text" class="form-control" id="service_id2" name="service_id" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom du Service:</label>
                        <input type="text" class="form-control" id="nom2" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description2" name="description" rows="15" required></textarea>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary" onclick="showSuccessMessage()">Modifier</button>
                </form>
            </div>
            <!-- Affichage du message de succès après la modification -->
            <div id="successMessage" class="modal-footer" style="display: none;">
            </div>
        </div>
    </div>
</div>

<!-- Script JavaScript pour la gestion des événements -->
<script>
    // Gestion des boutons de modification
    var editButtons = document.querySelectorAll('.edit-button');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            var form = button.closest('tr');
            var service_id = form.querySelector('.service_id').innerText;
            var nom = form.querySelector('.nom').innerText;
            var description = form.querySelector('.description').innerText;
            var modal = document.getElementById('myModal');
            modal.style.display = "block";
            document.getElementById('service_id2').value = service_id;
            document.getElementById('nom2').value = nom;
            document.getElementById('description2').value = description;
        });
    });

    // Gestion du bouton de fermeture de la fenêtre modale
    var closeBtn = document.querySelector('.close');
    closeBtn.addEventListener('click', function() {
        var modal = document.getElementById('myModal');
        modal.style.display = "none";
    });

    // Fermer la fenêtre modale lors du clic en dehors de celle-ci
    window.onclick = function(event) {
        var modal = document.getElementById('myModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Fonction de confirmation de suppression d'un service
    function confirmDelete(service_id) {
        return confirm("Êtes-vous sûr de vouloir supprimer ce service ?");
    }
</script>

<!-- Script JavaScript pour afficher le message de succès après la modification -->
<script>
    <?php if(isset($_SESSION['success_message'])): ?>
        alert("<?php echo $_SESSION['success_message']; ?>");
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
</script>

<!-- Script JavaScript pour vérifier la taille du fichier uploadé -->
<script>
    function checkFileSize() {
        var input = document.getElementById('image');
        if (input.files.length > 0) {
            var fileSize = input.files[0].size;
            var maxSize = 1048576; // Taille maximale : 1 Mo

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

<!-- Script JavaScript pour afficher le message de succès après la modification d'un service -->
<script>
    function showSuccessMessage() {
        alert("Service mis à jour avec succès");
        var modal = document.getElementById('myModal');
        modal.style.display = "none";
    }
</script>
