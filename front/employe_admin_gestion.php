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

// Récupération de la liste des animaux avec leur race depuis la base de données
$liste_animaux = array();
$sql_animaux = "SELECT a.prenom, r.label as race_label, a.date_nour, a.heure_nour FROM animal a JOIN race r ON a.race_id = r.race_id";
$resultat_animaux = mysqli_query($connexion, $sql_animaux);
if (mysqli_num_rows($resultat_animaux) > 0) {
    while ($row = mysqli_fetch_assoc($resultat_animaux)) {
        $liste_animaux[$row['prenom']] = $row;
    }
} else {
    echo "Aucun animal trouvé dans la base de données.";
}

// Mise à jour de la table animal et rapport_veterinaire si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification des données du formulaire avant de les utiliser
    $animal = isset($_POST['animal']) ? $_POST['animal'] : "";
    $etat = isset($_POST['etat_animal']) ? $_POST['etat_animal'] : "";
    $nour = isset($_POST['nourriture_proposee']) ? $_POST['nourriture_proposee'] : "";
    $qte_nour = isset($_POST['quantite_nourriture']) ? $_POST['quantite_nourriture'] : "";
    $date_nour = isset($_POST['date_passage']) ? $_POST['date_passage'] : "";
    $heure_nour = isset($_POST['heure_passage']) ? $_POST['heure_passage'] : "";

    // Mise à jour de la table animal
    $sql_animal = "UPDATE animal SET etat = '$etat', nour = '$nour', qte_nour = '$qte_nour', date_nour = '$date_nour', heure_nour = '$heure_nour' WHERE prenom = '$animal'";
    if (mysqli_query($connexion, $sql_animal)) {
        // Ne rien afficher ici
    } else {
        echo "Erreur lors de la mise à jour des données de l'animal: " . mysqli_error($connexion);
    }
}

// Fermeture de la connexion à la base de données
mysqli_close($connexion);
?>

<div class="container my-4" id="background2">
    <br>
    <div class="text-center">
        <h2 class="mb-4">Alimentation quotidienne</h2>
    </div>
    <div class="mx-auto" style="max-width: 500px;">
        <form id="updateForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="animal">Animal:</label>
                <select class="form-control" id="animal" name="animal">
                <option value="" disabled selected>Choisir l'animal à nourrir</option>
                    <?php
                    foreach ($liste_animaux as $prenom => $animal) {
                        echo "<option value='$prenom'>" . $animal['prenom'] . " - Race: " . $animal['race_label'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="etat_animal">État de l'animal:</label>
                <select class="form-control" id="etat_animal" name="etat_animal">
                    <option value="" disabled selected>Choisir un état</option>
                    <option value="Bonne santé">En bonne santé</option>
                    <option value="Maladie légère">Maladie légère</option>
                    <option value="Blessure mineure">Blessure mineure</option>
                    <option value="Convalescence">Convalescence</option>
                    <option value="Préoccupation diététique">Préoccupation diététique</option>
                    <option value="Trouble comportemental">Trouble comportemental</option>
                </select>
            </div>
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
            <div class="form-group">
                <label for="quantite_nourriture">Quantité nourriture proposée:</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="quantite_nourriture" name="quantite_nourriture" placeholder="Quantité nourriture proposée" min="0" step="1">
                    <div class="input-group-append">
                        <span class="input-group-text">gramme</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="date_passage">Date de passage:</label>
                <input type="date" class="form-control" id="date_passage" name="date_passage">
            </div>
            <div class="form-group">
                <label for="heure_passage">Heure de passage:</label>
                <input type="time" class="form-control" id="heure_passage" name="heure_passage">
            </div>
            <br>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" onclick="showSuccessMessage()">Enregistrer</button>
                <a href="admin.php" class="btn btn-secondary ml-2">Retour</a>
            </div>
            <br>
        </form>
    </div> <!-- Fin de la div avec une largeur maximale de 500px -->
</div>

<script>
    function showSuccessMessage() {
        alert("Données de l'animal mises à jour avec succès.");
    }
    </script>