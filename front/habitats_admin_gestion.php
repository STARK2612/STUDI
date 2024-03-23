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

                // Insertion des données de l'habitat dans la table habitat avec l'ID de l'image
                $insertHabitat = $connexion->prepare("INSERT INTO habitat (image_id, nom, description) VALUES (?, ?, ?)");
                $insertHabitat->bind_param("iss", $imageId, $nom, $description);
                if($insertHabitat->execute()) {
                    $_SESSION['success_message'] = "Nouvel habitat ajouté avec succès"; // Stocke le message dans une variable de session
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

// Modification d'habitat
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $habitat_id = $_POST['habitat_id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    $sql = "UPDATE habitat SET nom='$nom', description='$description' WHERE habitat_id=$habitat_id";

    if ($connexion->query($sql) === TRUE) {
        echo "Habitat mis à jour avec succès";
    } else {
        echo "Erreur : " . $sql . "<br>" . $connexion->error;
    }
}

// Suppression d'habitat
if (isset($_POST['supprimer'])) {
    $habitat_id = $_POST['habitat_id'];

    // Récupérer l'ID de l'image associée à l'habitat
    $sql_select_image_id = "SELECT image_id FROM habitat WHERE habitat_id=$habitat_id";
    $result_select_image_id = $connexion->query($sql_select_image_id);

    if ($result_select_image_id->num_rows > 0) {
        $row = $result_select_image_id->fetch_assoc();
        $image_id = $row['image_id'];

        // Supprimer l'habitat
        $sql_delete_habitat = "DELETE FROM habitat WHERE habitat_id=$habitat_id";
        if ($connexion->query($sql_delete_habitat) === TRUE) {
            // Supprimer l'image associée si elle existe
            $sql_delete_image = "DELETE FROM image WHERE image_id=$image_id";
            if ($connexion->query($sql_delete_image) === TRUE) {
                echo "<script>alert('Habitat et image associée supprimés avec succès');</script>"; // Affichage du message dans une fenêtre popup
            } else {
                echo "Erreur lors de la suppression de l'image : " . $connexion->error;
            }
        } else {
            echo "Erreur lors de la suppression de l'habitat : " . $connexion->error;
        }
    } else {
        echo "ID d'habitat non trouvé.";
    }
}

$servicesParPage = 1; // Vous pouvez ajuster ce nombre selon vos besoins

// Vérifier si la page est définie, sinon, la définir sur 1
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculer le numéro du premier habitat pour la requête SQL
$premierHabitat = ($page - 1) * $servicesParPage;

// Récupération des habitats pour la page actuelle
$sql = "SELECT * FROM habitat LIMIT $premierHabitat, $servicesParPage";
$result = $connexion->query($sql);

// Calculer le nombre total de pages
$sql_count = "SELECT COUNT(*) AS totalHabitats FROM habitat";
$result_count = $connexion->query($sql_count);
$row_count = $result_count->fetch_assoc();
$totalHabitats = $row_count['totalHabitats'];
$totalPages = ceil($totalHabitats / $servicesParPage);
?>

<div class="container" id="background2">
    <div class="row">
        <div class="col-md-6">
            <br>
            <form method="post" class="custom-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                <h3>Ajouter un Nouvel Habitat</h3>
                <div class="form-group">
                    <label for="nom">Nom de l'Habitat:</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="description">Description de l'Habitat:</label>
                    <input type="text" class="form-control" id="description" name="description" required>
                </div>
                <div class="form-group">
                    <br>
                    <label for="image">Image:</label>
                    <input type="file" class="form-control-file" id="image" name="image" accept="image/jpeg, image/jpg, image/png" required>
                </div>
                <br>
                <button type="submit" class="btn btn-primary" name="ajouter">Ajouter</button>
                <br><br>
                <a href="admin.php" class="btn btn-secondary btn-block">Retour</a>
            </form>
            <br>
        </div>
        <div class="col-md-6">
            <br>
            <h3>Modifier/Supprimer un Habitat</h3>
            <!-- Tableau pour afficher les habitats -->
            <table class="table">
                <thead>
                    <tr>
                        <th class='hidden'>ID de l'Habitat</th>
                        <th>Nom de l'Habitat</th>
                        <th>Description de l'Habitat</th>
                        <th>Commentaire de l'Habitat</th>
                        <th>Modifier/Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Boucle à travers chaque habitat et afficher les détails dans le tableau
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='habitat_id hidden'>" . $row['habitat_id'] . "</td>";
                        echo "<td class='nom'>" . $row['nom'] . "</td>";
                        echo "<td class='description'>" . $row['description'] . "</td>";
                        // Vérifier si la clé 'commentaire_habitat' existe dans le tableau $row avant de l'utiliser
                        echo "<td class='commentaire'>" . (isset($row['commentaire_habitat']) ? $row['commentaire_habitat'] : "") . "</td>";
                        // Ajouter des boutons pour modifier et supprimer chaque habitat
                        echo "<td>";
                        echo "<div class='btn-group' role='group'>";
                        echo "<button class='btn btn-primary btn-sm edit-button'>Modifier</button>";
                        echo "</div>";
                        echo "<div style='margin-top: 5px;'></div>"; // Espace de 5px entre les boutons
                        // Formulaire pour la suppression de l'habitat
                        echo "<form class='delete-form' method='post' action='" . $_SERVER['PHP_SELF'] . "' onsubmit='return confirmDelete(" . $row['habitat_id'] . ")'>";
                        echo "<input type='hidden' name='habitat_id' value='" . $row['habitat_id'] . "'>";
                        echo "<button type='submit' class='btn btn-danger btn-sm delete-button' name='supprimer' id='delete-button-" . $row['habitat_id'] . "'>Supprimer</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <!-- Pagination -->
            <?php
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