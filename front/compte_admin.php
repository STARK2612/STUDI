<?php
// Inclure le fichier de connexion à la base de données
require_once('back/connect_bdd.php');

// Vérifier si une demande de suppression a été envoyée
if (isset($_POST['delete_username'])) {
    // Récupérer le nom d'utilisateur à supprimer
    $username = $_POST['delete_username'];

    // Préparer la requête de suppression
    $delete_query = "DELETE FROM utilisateur WHERE username = ?";
    
    // Préparer et exécuter la requête de suppression
    $stmt = $connexion->prepare($delete_query);
    $stmt->bind_param("s", $username);
    
    if ($stmt->execute()) {
        // Rediriger vers la page admin après la suppression
        header("Location: compte.php?success2=1");
        exit;
    } else {
        // Gérer les erreurs de suppression
        echo "Erreur lors de la suppression de l'utilisateur.";
    }
}

// Nombre d'utilisateurs à afficher par page
$usersPerPage = 5;

// Déterminer le nombre total d'utilisateurs
$totalUsersQuery = "SELECT COUNT(*) as total FROM utilisateur";
$stmt = $connexion->prepare($totalUsersQuery);
$stmt->execute();
$totalUsersResult = $stmt->get_result();
$totalUsers = $totalUsersResult->fetch_assoc()['total'];

// Calculer le nombre total de pages
$totalPages = ceil($totalUsers / $usersPerPage);

// Déterminer la page actuelle
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculer l'offset pour la requête SQL
$offset = ($current_page - 1) * $usersPerPage;

// Sélectionner les utilisateurs pour la page actuelle
$query = "SELECT * FROM utilisateur LIMIT ?, ?";
$stmt = $connexion->prepare($query);
$stmt->bind_param("ii", $offset, $usersPerPage);
$stmt->execute();
$result = $stmt->get_result();
?>


<div class="container" id="background-color" style="border-radius: 10px; border: 3px solid white;">
    <div class="row">
        <div class="col-md-4 mt-4">
            <!-- Formulaire de création de compte utilisateur -->
            <h2 class="text-center">Création de compte utilisateur</h2>
            <form method="post" action="back/compte_data.php" class="custom-form">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="nom">Nom:</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom:</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>
                <div class="form-group">
                    <label for="role">Rôle:</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="">Sélectionnez un rôle</option>
                        <option value="Employé">Employé</option>
                        <option value="Vétérinaire">Vétérinaire</option>
                    </select>
                </div>
                <br>
                <button type="submit" class="btn btn-warning btn-block">Créer le compte</button>
                <br><br>
            <a href="admin.php" class="btn btn-secondary btn-block">Retour</a>
            <br><br>
            </form>
        </div>
        <div class="col-md-7">
            <br>
            <!-- Tableau des utilisateurs -->
            <h2 class="text-center">Liste des utilisateurs</h2>
            <div class="table-responsive overflow-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Rôle</th>
                        <th>Modifier/Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $firstRow = true; // Pour marquer la première ligne
                // Boucle à travers chaque utilisateur et afficher les détails dans le tableau
                while ($row = $result->fetch_assoc()) {
                    if ($row['role_id'] == 1 && $firstRow) {
                        $firstRow = false;
                        continue; // Saute la première ligne où role_id est égal à 1
                    }
                    echo "<tr>";
                    echo "<td class='username text-center' style='background-color:white; border: 1px solid black;'>" . $row['username'] . "</td>";
                    echo "<td class='nom text-center' style='background-color:white; border: 1px solid black;'>" . $row['nom'] . "</td>";
                    echo "<td class='prenom text-center' style='background-color:white; border: 1px solid black;'>" . $row['prenom'] . "</td>";
                    echo "<td class='role text-center' style='background-color:white; border: 1px solid black;'>";
                    // Mapper les rôles stockés dans la base de données aux libellés correspondants
                    switch ($row['role_id']) {
                        case 1:
                            echo "Administrateur";
                            break;
                        case 2:
                            echo "Employé";
                            break;
                        case 3:
                            echo "Vétérinaire";
                            break;
                        default:
                            echo "Inconnu";
                            break;
                    }
                    echo "</td>";
                    // Ajouter des boutons pour modifier et supprimer chaque ligne
                    echo "<td class='text-center' style='background-color:white; border: 1px solid black;'>";
                    echo "<div class='btn-group' role='group'>";
                    echo "<button class='btn btn-primary btn-sm edit-button'>Modifier</button>";
                    echo "<div style='margin-left: 10px;'></div>"; // Espace de 10px
                    // Formulaire pour la suppression de l'utilisateur
                    echo "<form class='delete-form' method='post' action='' onsubmit='return confirmDelete()'>";
                    echo "<input type='hidden' name='delete_username' value='" . $row['username'] . "'>";
                    echo "<button type='submit' class='btn btn-danger btn-sm delete-button'>Supprimer</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
            </div>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="?page=<?php echo $i; ?>" class="page-item <?php if ($i === $current_page) echo 'active'; ?>">
                        <span class="page-link"><?php echo $i; ?></span>
                    </a>
                <?php endfor; ?>
            </div>
            <br>
        </div>
    </div>
</div>

<div id="myModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier Utilisateur</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="back/modifier_compte.php">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur:</label>
                        <input type="text" class="form-control" id="username2" name="username" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom:</label>
                        <input type="text" class="form-control" id="nom2" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom:</label>
                        <input type="text" class="form-control" id="prenom2" name="prenom" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Rôle:</label>
                        <select class="form-control" id="role2" name="role" required>
                            <option value="">Sélectionnez un rôle</option>
                            <option value="Employé">Employé</option>
                            <option value="Vétérinaire">Vétérinaire</option>
                        </select>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-warning">Modifier</button>
                </form>
            </div>
        </div>
    </div>
</div>


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
            var username = form.querySelector('.username').innerText;
            var nom = form.querySelector('.nom').innerText;
            var prenom = form.querySelector('.prenom').innerText;
            var role = form.querySelector('.role').innerText;
            // Afficher la fenêtre modale de modification avec les champs préremplis
            var modal = document.getElementById('myModal');
            modal.style.display = "block";
            // Remplir les champs de la fenêtre modale avec les valeurs récupérées
            document.getElementById('username2').value = username;
            document.getElementById('nom2').value = nom;
            document.getElementById('prenom2').value = prenom;
            document.getElementById('role2').value = role;
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
</script>
<script>
    // Vérifier si le paramètre GET 'success' est défini et égal à 1
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success') && urlParams.get('success') === '1') {
        // Afficher la fenêtre modale avec le message
        alert("Modification effectuée!");
    }
</script>
<script>
    // Vérifier si le paramètre GET 'success' est défini et égal à 1
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success2') && urlParams.get('success2') === '1') {
        // Afficher la fenêtre modale avec le message
        alert("Compte supprimé avec succès!");
    }
</script>
<script>
    // Fonction pour demander une confirmation avant la suppression
    function confirmDelete() {
        return confirm("Êtes-vous sûr de vouloir supprimer ce compte ?");
    }
</script>
<script>
    function deconnexionAutomatique() {
    var idleTimer;
    function resetTimer() {
        clearTimeout(idleTimer);
        idleTimer = setTimeout(logout, 10000); // 30 secondes
    }
    resetTimer(); // Initialiser le timer

    // Réinitialiser le timer à chaque événement de souris ou de clavier
    document.addEventListener("mousemove", resetTimer);
    document.addEventListener("keypress", resetTimer);
}

// Fonction pour déconnecter l'utilisateur
function logout() {
    window.location.href = 'back/deconnexion.php'; // Page de déconnexion PHP
}

// Appeler la fonction de déconnexion automatique au chargement de la page
window.onload = function() {
    deconnexionAutomatique();
};
</script>

