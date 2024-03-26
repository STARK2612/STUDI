<?php
// Inclusion du fichier de connexion à la base de données
include_once "back/connect_bdd.php";

// Vérifier si la connexion à la base de données est établie
if (!$connexion) {
    echo "La connexion à la base de données a échoué.";
    exit; // Arrêter l'exécution du script en cas d'échec de la connexion
}

// Vérification de la connexion
if ($connexion->connect_error) {
    die("La connexion a échoué : " . $connexion->connect_error);
}

// Traitement de l'upload d'image
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    // Vérification si un fichier est envoyé
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

                // Insertion des données du service dans la table service avec l'ID de l'image
                $insertService = $connexion->prepare("INSERT INTO service (nom, description, image_id) VALUES (?, ?, ?)");
                $insertService->bind_param("ssi", $nom, $description, $imageId);
                if($insertService->execute()) {
                    $_SESSION['success_message'] = "Nouveau service ajouté avec succès"; // Stocke le message dans une variable de session
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

// Modification de service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $service_id = $_POST['service_id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    $sql = "UPDATE service SET nom='$nom', description='$description' WHERE service_id=$service_id";

    if ($connexion->query($sql) === TRUE) {
        echo "Service mis à jour avec succès";
    } else {
        echo "Erreur : " . $sql . "<br>" . $connexion->error;
    }
}

// Suppression de service
if (isset($_POST['supprimer'])) {
    $service_id = $_POST['service_id'];

    // Récupérer l'ID de l'image associée au service
    $sql_select_image_id = "SELECT image_id FROM service WHERE service_id=$service_id";
    $result_select_image_id = $connexion->query($sql_select_image_id);

    if ($result_select_image_id->num_rows > 0) {
        $row = $result_select_image_id->fetch_assoc();
        $image_id = $row['image_id'];

        // Supprimer le service
        $sql_delete_service = "DELETE FROM service WHERE service_id=$service_id";
        if ($connexion->query($sql_delete_service) === TRUE) {
            // Supprimer l'image associée si elle existe
            $sql_delete_image = "DELETE FROM image WHERE image_id=$image_id";
            if ($connexion->query($sql_delete_image) === TRUE) {
                echo "<script>alert('Service et image associée supprimés avec succès');</script>"; // Affichage du message dans une fenêtre popup
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


$servicesParPage = 1; // Nombre de services à afficher par page

// Vérifier si la page est définie, sinon, la définir sur 1
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculer le numéro du premier service pour la requête SQL
$premierService = ($page - 1) * $servicesParPage;

// Récupération des services pour la page actuelle
$sql = "SELECT * FROM service LIMIT $premierService, $servicesParPage";
$result = $connexion->query($sql);
?>

<div class="container" id="background2">
    <div class="row">
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
                    <div id="fileSizeError" style="color: red;"></div> <!-- Div pour afficher le message d'erreur -->
                </div>
                <br>
                <button type="submit" class="btn btn-primary" name="ajouter">Ajouter</button>
                <br><br>
            <a href="admin.php" class="btn btn-secondary btn-block">Retour</a>
            </form>
            <br>
        </div>
        <div class="col-md-8">
            <br>
            <h3>Modifier/Supprimer un Service</h3>
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
                // Boucle à travers chaque service et afficher les détails dans le tableau
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='service_id hidden'>" . $row['service_id'] . "</td>";
                    echo "<td class='nom'>" . $row['nom'] . "</td>";
                    echo "<td class='description description-cell2'>" . $row['description'] . "</td>";
                    // Ajouter des boutons pour modifier et supprimer chaque service
                    echo "<td>";
                    echo "<div class='btn-group' role='group'>";
                    echo "<button class='btn btn-primary btn-sm edit-button'>Modifier</button>";
                    echo "</div>";
                    echo "<div style='margin-top: 5px;'></div>"; // Espace de 5px entre les boutons
                    // Formulaire pour la suppression du service
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
            <?php
            // Affichage de la pagination
            $sql = "SELECT COUNT(*) AS totalServices FROM service";
            $result = $connexion->query($sql);
            $row = $result->fetch_assoc();
            $totalServices = $row['totalServices'];
            $totalPages = ceil($totalServices / $servicesParPage);

            echo "<ul class='pagination'>";
            for ($i = 1; $i <= $totalPages; $i++) {
                // Vérifie si la page actuelle correspond à $i et ajoute la classe "active" si c'est le cas
                $activeClass = ($page == $i) ? "active" : "";
                echo "<li class='page-item $activeClass'><a class='page-link' href='?page=$i'>$i</a></li>";
            }
            echo "</ul>";
            ?>
        </div>
    </div>
    </div>
</div>

<!-- Modal de modification de service -->
<div id="myModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier Service</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Formulaire de modification de service -->
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
            <div id="successMessage" class="modal-footer" style="display: none;">
            </div>
        </div>
    </div>
</div>

<!-- Inclure la bibliothèque jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Inclure la bibliothèque Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
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
            var service_id = form.querySelector('.service_id').innerText;
            var nom = form.querySelector('.nom').innerText;
            var description = form.querySelector('.description').innerText;
            // Afficher la fenêtre modale de modification avec les champs préremplis
            var modal = document.getElementById('myModal');
            modal.style.display = "block";
            // Remplir les champs de la fenêtre modale avec les valeurs récupérées
            document.getElementById('service_id2').value = service_id;
            document.getElementById('nom2').value = nom;
            document.getElementById('description2').value = description;
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
    }

    function confirmDelete(service_id) {
        return confirm("Êtes-vous sûr de vouloir supprimer ce service ?");
    }
</script>
<script>
    <?php if(isset($_SESSION['success_message'])): ?>
        alert("<?php echo $_SESSION['success_message']; ?>");
        <?php unset($_SESSION['success_message']); ?> // Efface le message de la variable de session
    <?php endif; ?>
</script>
<script>
    function checkFileSize() {
        var input = document.getElementById('image');
        if (input.files.length > 0) {
            var fileSize = input.files[0].size; // Taille du fichier en octets
            var maxSize = 1048576; // 1 Mo en octets

            if (fileSize > maxSize) {
                document.getElementById('fileSizeError').innerHTML = 'Fichier trop lourd, 1 Mo maximum.';
                return false; // Empêche l'envoi du formulaire
            } else {
                document.getElementById('fileSizeError').innerHTML = ''; // Efface le message d'erreur s'il existe
                return true; // Autorise l'envoi du formulaire
            }
        }
        return true; // Autorise l'envoi du formulaire si aucun fichier sélectionné
    }
</script>
<script>
    function showSuccessMessage() {
        // Afficher le message de succès dans une fenêtre popup
        alert("Service mis à jour avec succès");

        // Cacher la fenêtre modale après avoir affiché le message
        var modal = document.getElementById('myModal');
        modal.style.display = "none";
    }
</script>