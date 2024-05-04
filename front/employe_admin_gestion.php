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

// Initialisation du tableau pour stocker la liste des animaux
$liste_animaux = array();

// Requête SQL pour récupérer les informations des animaux
$sql_animaux = "SELECT a.prenom, r.label as race_label, a.date_nour, a.heure_nour FROM animal a JOIN race r ON a.race_id = r.race_id";

// Exécution de la requête SQL
$resultat_animaux = mysqli_query($connexion, $sql_animaux);

// Vérification s'il y a des résultats
if (mysqli_num_rows($resultat_animaux) > 0) {
    // Parcours des résultats et stockage dans le tableau $liste_animaux
    while ($row = mysqli_fetch_assoc($resultat_animaux)) {
        $liste_animaux[$row['prenom']] = $row;
    }
} else {
    // Affichage d'un message si aucun animal n'est trouvé dans la base de données
    echo "Aucun animal trouvé dans la base de données.";
}

// Traitement du formulaire lorsqu'il est soumis en méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des valeurs du formulaire
    $animal = isset($_POST['animal']) ? $_POST['animal'] : "";
    $nour = isset($_POST['nourriture_proposee']) ? $_POST['nourriture_proposee'] : "";
    $qte_nour = isset($_POST['quantite_nourriture']) ? $_POST['quantite_nourriture'] : "";
    $date_nour = isset($_POST['date_passage']) ? $_POST['date_passage'] : "";
    $heure_nour = isset($_POST['heure_passage']) ? $_POST['heure_passage'] : "";

    // Requête SQL pour mettre à jour les données de l'animal
    $sql_animal = "UPDATE animal SET nour = '$nour', qte_nour = '$qte_nour', date_nour = '$date_nour', heure_nour = '$heure_nour' WHERE prenom = '$animal'";
    // Exécution de la requête SQL
    if (mysqli_query($connexion, $sql_animal)) {
        // Ne rien afficher ici
    } else {
        // Affichage d'un message en cas d'erreur lors de la mise à jour des données de l'animal
        echo "Erreur lors de la mise à jour des données de l'animal: " . mysqli_error($connexion);
    }
}

// Fermeture de la connexion à la base de données
mysqli_close($connexion);
?>
<div class="container my-4" id="background-color" style="border-radius: 10px; border: 3px solid white;">
    <br>
    <div class="text-center">
        <h2 class="mb-4">Alimentation quotidienne</h2>
    </div>
    <div class="mx-auto" style="max-width: 500px;">
        <!-- Formulaire de mise à jour des données de l'animal -->
        <form id="updateForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- Sélection de l'animal à nourrir -->
            <div class="form-group">
                <label for="animal">Animal:</label>
                <select class="form-control" id="animal" name="animal">
                    <option value="" disabled selected>Choisir l'animal à nourrir</option>
                    <?php
                    // Boucle pour afficher les options de sélection des animaux
                    foreach ($liste_animaux as $prenom => $animal) {
                        echo "<option value='$prenom'>" . $animal['prenom'] . " - Race: " . $animal['race_label'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- Sélection de la nourriture proposée -->
            <div class="form-group">
                <label for="nourriture_proposee">Nourriture proposée:</label>
                <select class="form-control" id="nourriture_proposee" name="nourriture_proposee">
                    <option value="" disabled selected>Choisir une nourriture</option>
                    <option value="Fruits frais">Fruits frais</option>
                    <option value="Légumes frais">Légumes frais</option>
                    <option value="Viande crue">Viande crue</option>
                    <option value="Poissons">Poissons</option>
                    <option value="Insectes vivants">Insectes vivants</option>
                    <option value="Foin">Foin</option>
                </select>
            </div>
            <!-- Entrée de la quantité de nourriture proposée -->
            <div class="form-group">
                <label for="quantite_nourriture">Quantité nourriture proposée:</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="quantite_nourriture" name="quantite_nourriture" placeholder="Quantité nourriture proposée" min="0" step="1">
                    <div class="input-group-append">
                        <span class="input-group-text">gramme</span>
                    </div>
                </div>
            </div>
            <!-- Sélection de la date de passage -->
            <div class="form-group">
                <label for="date_passage">Date de passage:</label>
                <input type="date" class="form-control" id="date_passage" name="date_passage">
            </div>
            <!-- Sélection de l'heure de passage -->
            <div class="form-group">
                <label for="heure_passage">Heure de passage:</label>
                <input type="time" class="form-control" id="heure_passage" name="heure_passage">
            </div>
            <br>
            <!-- Boutons de soumission et de retour -->
            <div class="text-center">
                <button type="submit" class="btn btn-warning" onclick="validateForm(event)">Enregistrer</button>
                <a href="<?php
                // Redirection en fonction du rôle de l'utilisateur
                if ($_SESSION['role'] == 'Administrateur') {
                    echo "admin.php";
                } elseif ($_SESSION['role'] == 'Employé') {
                    echo "employe.php";
                } elseif ($_SESSION['role'] == 'Vétérinaire') {
                    echo "veterinaire.php";
                }
                ?>" class="btn btn-secondary btn-block">Retour</a>
            </div>
            <br>
        </form>
    </div>
</div>
<script>
    // Validation du formulaire côté client
    function validateForm(event) {
        var animal = document.getElementById("animal").value;
        var nourriture = document.getElementById("nourriture_proposee").value;
        var quantite = document.getElementById("quantite_nourriture").value;
        var date = document.getElementById("date_passage").value;
        var heure = document.getElementById("heure_passage").value;

        // Vérification si tous les champs sont remplis avant de soumettre le formulaire
        if (animal === "" || nourriture === "" || quantite === "" || date === "" || heure === "") {
            alert("Veuillez remplir tous les champs avant d'enregistrer.");
            event.preventDefault();
        } else {
            showSuccessMessage();
        }
    }

    // Affichage d'un message de succès
    function showSuccessMessage() {
        alert("Données de l'animal mises à jour avec succès.");
    }
</script>
<script>
    function deconnexionAutomatique() {
    var idleTimer;
    function resetTimer() {
        clearTimeout(idleTimer);
        idleTimer = setTimeout(logout, 30000); // 30 secondes
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
