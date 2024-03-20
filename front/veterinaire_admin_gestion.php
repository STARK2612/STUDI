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

// Initialisation des variables pour les données de l'animal sélectionné
$etat_animal = $nourriture_proposee = $quantite_nourriture = $date_passage = $heure_passage = "";

// Vérifier si un animal est sélectionné et récupérer ses informations
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['animal'])) {
    $animal = $_POST['animal'];

    // Récupérer les informations de l'animal depuis la base de données
    $sql_info_animal = "SELECT etat, nour, qte_nour, date_nour, heure_nour FROM animal WHERE prenom = '$animal'";
    $resultat_info_animal = mysqli_query($connexion, $sql_info_animal);
    if ($resultat_info_animal && mysqli_num_rows($resultat_info_animal) > 0) {
        $row = mysqli_fetch_assoc($resultat_info_animal);
        $etat_animal = $row['etat'];
        $nourriture_proposee = $row['nour'];
        $quantite_nourriture = $row['qte_nour'];
        $date_passage = $row['date_nour'];
        $heure_passage = $row['heure_nour'];
    } else {
        echo "Aucune information sur l'animal sélectionné.";
    }
}

// Mise à jour de la table rapport_veterinaire si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date_bilan']) && isset($_POST['rapport_veterinaire'])) {
    // Vérification des données du formulaire avant de les utiliser
    $date_bilan = $_POST['date_bilan'];
    $rapport_veterinaire = $_POST['rapport_veterinaire'];

    // Insérer les données dans la table rapport_veterinaire
    $sql_rapport_veterinaire = "INSERT INTO rapport_veterinaire (date, detail) VALUES ('$date_bilan', '$rapport_veterinaire')";
    if (mysqli_query($connexion, $sql_rapport_veterinaire)) {
        // Succès
        // echo "Les données ont été enregistrées avec succès."; // Supprimer cette ligne
    } else {
        // Erreur lors de l'insertion dans la table rapport_veterinaire
        echo "Erreur lors de l'enregistrement des données dans la table rapport_veterinaire: " . mysqli_error($connexion);
    }
}

// Fermeture de la connexion à la base de données
mysqli_close($connexion);
?>


<div class="container my-4" id="background2">
    <br>
    <div class="text-center">
        <h2 class="mb-4">Bilan vétérinaire</h2>
    </div>
    <div class="mx-auto" style="max-width: 750px;">
        <form id="updateForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="animal">Animal:</label>
                <select class="form-control" id="animal" name="animal" onchange="getAnimalInfo(this.value)">
                    <option value="" disabled selected>Choisir l'animal visité</option>
                    <?php
                    foreach ($liste_animaux as $prenom => $animal) {
                        echo "<option value='$prenom'>" . $animal['prenom'] . " - Race: " . $animal['race_label'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="etat_animal">État de l'animal:</label>
                        <input type="text" class="form-control" id="etat_animal" name="etat_animal" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nourriture_proposee">Nourriture proposée:</label>
                        <input type="text" class="form-control" id="nourriture_proposee" name="nourriture_proposee" readonly>
                    </div>
                    <div class="form-group">
                        <label for="quantite_nourriture">Quantité nourriture proposée:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="quantite_nourriture" name="quantite_nourriture" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">gramme</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_passage">Date de passage:</label>
                        <input type="text" class="form-control" id="date_passage" name="date_passage" readonly>
                    </div>
                    <div class="form-group">
                        <label for="heure_passage">Heure de passage:</label>
                        <input type="text" class="form-control" id="heure_passage" name="heure_passage" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date_bilan">Date du bilan:</label>
                        <input type="date" class="form-control" id="date_bilan" name="date_bilan">
                    </div>
                    <div class="form-group">
                        <label for="rapport_veterinaire">Rapport vétérinaire:</label>
                        <textarea class="form-control" id="rapport_veterinaire" name="rapport_veterinaire" rows="4"></textarea>
                    </div>
                </div>
            <div class="text-center">
                <br>
                <button type="submit" class="btn btn-primary" onclick="showSuccessMessage()">Enregistrer</button>
                <a href="admin.php" class="btn btn-secondary ml-2">Retour</a>
            </div>
        </form>
        <br><br><br>
    </div> <!-- Fin de la div avec une largeur maximale de 500px -->
</div>

<script>
    // Fonction pour récupérer les informations de l'animal sélectionné
    function getAnimalInfo(prenom) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var animalInfo = JSON.parse(this.responseText);
                document.getElementById("etat_animal").value = animalInfo.etat;
                document.getElementById("nourriture_proposee").value = animalInfo.nour;
                document.getElementById("quantite_nourriture").value = animalInfo.qte_nour;
                document.getElementById("date_passage").value = animalInfo.date_nour;
                document.getElementById("heure_passage").value = animalInfo.heure_nour;
            }
        };
        xhttp.open("GET", "back/get_animal_info.php?prenom=" + prenom, true);
        xhttp.send();
    }
</script>
<script>
// Fonction pour afficher un message de succès dans une fenêtre popup
    function showSuccessMessage() {
        alert("Les données ont été enregistrées avec succès.");
    }
</script>
