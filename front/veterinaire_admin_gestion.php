<?php
// Inclusion du fichier de connexion à la base de données
require_once('back/connect_bdd.php');

// Fonction pour échapper les caractères spéciaux dans une chaîne
function escape_string($connexion, $value) {
    return $connexion->real_escape_string($value);
}

// Fonction pour mettre à jour les données de l'animal
function updateAnimalData($connexion, $prenom, $avis_veterinaire, $detail_etat, $date_visite_veto) {
    // Échapper les valeurs pour éviter les injections SQL
    $prenom = escape_string($connexion, $prenom);
    $avis_veterinaire = escape_string($connexion, $avis_veterinaire);
    $detail_etat = escape_string($connexion, $detail_etat);
    $date_visite_veto = escape_string($connexion, $date_visite_veto);

    // Requête pour mettre à jour l'avis vétérinaire sur l'habitat
    $update_habitat_query = "UPDATE habitat 
                             INNER JOIN animal ON habitat.habitat_id = animal.habitat_id 
                             SET habitat.commentaire_habitat = '$avis_veterinaire' 
                             WHERE animal.prenom = '$prenom'";

    // Exécution de la requête et gestion des erreurs
    if ($connexion->query($update_habitat_query) === TRUE) {
        // Requête pour mettre à jour le rapport vétérinaire
        $update_rapport_query = "UPDATE rapport_veterinaire 
                                 INNER JOIN animal ON rapport_veterinaire.rapport_veterinaire_id = animal.rapport_veterinaire_id 
                                 SET rapport_veterinaire.detail = '$detail_etat', 
                                     rapport_veterinaire.date = STR_TO_DATE('$date_visite_veto', '%Y-%m-%d') 
                                 WHERE animal.prenom = '$prenom'";

        // Exécution de la requête et gestion des erreurs
        if ($connexion->query($update_rapport_query) === TRUE) {
            // Vérifier si aucune ligne n'a été affectée (aucune mise à jour)
            if ($connexion->affected_rows === 0) {
                // Insérer un nouveau rapport vétérinaire si nécessaire
                $rapport_veterinaire_id = insertNewRapport($connexion, $detail_etat, $date_visite_veto);
                // Mettre à jour l'animal avec l'ID du rapport vétérinaire
                updateAnimalWithRapportID($connexion, $prenom, $rapport_veterinaire_id);
            }
            // Afficher un message de succès
            echo '<script>alert("Les modifications ont été enregistrées avec succès.");</script>';
        } else {
            echo "Erreur lors de la mise à jour du rapport vétérinaire : " . $connexion->error;
        }
    } else {
        echo "Erreur lors de la mise à jour de l'habitat : " . $connexion->error;
    }
}

// Fonction pour insérer un nouveau rapport vétérinaire
function insertNewRapport($connexion, $detail_etat, $date_visite_veto) {
    // Échapper les valeurs pour éviter les injections SQL
    $detail_etat = escape_string($connexion, $detail_etat);
    $date_visite_veto = escape_string($connexion, $date_visite_veto);

    // Requête pour insérer un nouveau rapport vétérinaire
    $insert_rapport_query = "INSERT INTO rapport_veterinaire (detail, date) 
                             VALUES ('$detail_etat', STR_TO_DATE('$date_visite_veto', '%Y-%m-%d'))";

    // Exécution de la requête et gestion des erreurs
    if ($connexion->query($insert_rapport_query) === TRUE) {
        return $connexion->insert_id;
    } else {
        echo "Erreur lors de l'insertion d'un nouveau rapport vétérinaire : " . $connexion->error;
        return null;
    }
}

// Fonction pour mettre à jour l'animal avec l'ID du rapport vétérinaire
function updateAnimalWithRapportID($connexion, $prenom, $rapport_veterinaire_id) {
    // Échapper les valeurs pour éviter les injections SQL
    $prenom = escape_string($connexion, $prenom);

    // Requête pour mettre à jour l'animal avec l'ID du rapport vétérinaire
    $update_animal_query = "UPDATE animal 
                            SET rapport_veterinaire_id = '$rapport_veterinaire_id' 
                            WHERE prenom = '$prenom'";

    // Exécution de la requête et gestion des erreurs
    if ($connexion->query($update_animal_query) !== TRUE) {
        echo "Erreur lors de la mise à jour de l'animal avec l'ID de rapport vétérinaire : " . $connexion->error;
    }
}

// Vérifier si la requête est de type POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les données POST nécessaires sont présentes
    if (isset($_POST['animal']) && isset($_POST['avis_veterinaire']) && isset($_POST['detail_etat']) && isset($_POST['date_visite_veto'])) {
        // Récupérer la date de visite vétérinaire au format "aaaa-mm-jj"
        $date_visite_veto = date('Y-m-d', strtotime($_POST['date_visite_veto']));
        // Appeler la fonction pour mettre à jour les données de l'animal
        updateAnimalData($connexion, $_POST['animal'], $_POST['avis_veterinaire'], $_POST['detail_etat'], $date_visite_veto);
    }
}

// Requête pour récupérer les données des animaux, habitats, rapports vétérinaires, etc.
$sql = "SELECT animal.prenom, animal.etat, DATE_FORMAT(animal.date_nour, '%d/%m/%Y') AS formatted_date, TIME_FORMAT(animal.heure_nour, '%H:%i') AS formatted_hour, animal.nour, animal.qte_nour, habitat.nom AS habitat_nom, habitat.commentaire_habitat, rapport_veterinaire.detail AS rapport_detail, rapport_veterinaire.date AS rapport_date, race.label
        FROM animal
        INNER JOIN race ON animal.race_id = race.race_id
        LEFT JOIN habitat ON animal.habitat_id = habitat.habitat_id
        LEFT JOIN rapport_veterinaire ON animal.rapport_veterinaire_id = rapport_veterinaire.rapport_veterinaire_id";

// Exécution de la requête
$result = $connexion->query($sql);

// Traitement des résultats de la requête
if ($result->num_rows > 0) {
    // Affichage du formulaire et des données des animaux
    echo '<div class="container" id="background2">';
    echo '<br>';
    echo '<div class="row">';
    echo '<div class="col-md-4">';
    echo '<form id="animalForm" method="post">'; 
    echo '<h3>Bilan du vétérinaire</h3>';
    echo '<br>';
    echo '<label for="animal">Choisir l\'animal à visiter:</label>';
    echo '<select name="animal" class="form-control" id="animal" onchange="updateAnimalInfo(this.value)">';
    echo '<option value="">Choisir un animal</option>'; // Option vide avec le texte "Choisir un animal"

    // Tableau associatif pour stocker les données des animaux
    $animalData = array();

    // Boucle à travers les résultats de la requête
    while($row = $result->fetch_assoc()) {
        // Affichage des options dans le menu déroulant
        echo '<option value="' . $row['prenom'] . '">' . $row['prenom'] . ' - ' . $row['label'] . '</option>';
        
        // Stockage des données de l'animal dans le tableau associatif
        $animalData[$row['prenom']] = array(
            'etat' => $row['etat'],
            'date_nour' => $row['formatted_date'],
            'heure_nour' => $row['formatted_hour'],
            'nour' => $row['nour'],
            'qte_nour' => $row['qte_nour'],
            'habitat_nom' => $row['habitat_nom'],
            'commentaire_habitat' => $row['commentaire_habitat'],
            'rapport_detail' => $row['rapport_detail'],
            'rapport_date' => $row['rapport_date']
        );
    }

    echo '</select>';
}
?>
            <br>
            <!-- Affichage des informations de l'animal sélectionné -->
            <div class="form-group">
                <label for="etat">Etat de l'animal:</label>
                <input type="text" class="form-control" id="etat" name="etat" readonly><br>
            </div>
            <div class="form-group">
                <label for="date_passage">Date de passage:</label>
                <input type="text" class="form-control" id="date_passage" name="date_passage" readonly><br>
            </div>
            <div class="form-group">
                <label for="heure_passage">Heure de passage:</label>
                <input type="text" class="form-control" id="heure_passage" name="heure_passage" readonly><br>
            </div>
            <div class="form-group">
                <label for="nourriture">Nourriture proposée:</label>
                <input type="text" class="form-control" id="nourriture" name="nourriture" readonly><br>
            </div>
            <div class="form-group">
                <label for="grammage">Grammage de la nourriture (en grammes):</label>
                <input type="text" class="form-control" id="grammage" name="grammage" readonly><br>
            </div>
            <div class="form-group">
                <label for="habitat">Nom de l'habitat:</label>
                <input type="text" class="form-control" id="habitat" name="habitat" readonly><br>
            </div>
        </div>
        <div class="col-md-8">
            <!-- Formulaire pour saisir les informations de la visite vétérinaire -->
            <div class="form-group">
                <label for="date_visite_veto">Date de la visite vétérérinaire:</label> 
                <input type="date" class="form-control" id="date_visite_veto" name="date_visite_veto" required><br>
            </div>
            <div class="form-group">
                <label for="avis_veterinaire">Avis vétérinaire sur l'habitat:</label>
                <textarea id="avis_veterinaire" class="form-control" name="avis_veterinaire" rows="10"></textarea><br>
            </div>
            <div class="form-group">
                <label for="detail_etat">Détail de l’état de l’animal:</label>
                <textarea id="detail_etat" class="form-control" name="detail_etat" rows="10"></textarea><br>
            </div>
            <br>
            <!-- Bouton pour enregistrer les modifications -->
            <input type="submit" class="btn btn-primary" value="Enregistrer">
            <br><br>
            <!-- Bouton pour retourner à la page appropriée en fonction du rôle de l'utilisateur -->
            <a href="<?php
                if ($_SESSION['role'] == 'Administrateur') {
                    echo "admin.php";
                } elseif ($_SESSION['role'] == 'Employé') {
                    echo "employe.php";
                } elseif ($_SESSION['role'] == 'Vétérinaire') {
                    echo "veterinaire.php";
                }
            ?>" class="btn btn-secondary btn-block">Retour</a>
            <br><br>
        </div>
        </form>
    </div>
</div>

<!-- Script JavaScript pour mettre à jour les informations de l'animal sélectionné -->
<script>
    var animalData = <?php echo json_encode($animalData); ?>;

    function updateAnimalInfo(prenom) {
        // Mettre à jour les champs du formulaire avec les données de l'animal sélectionné
        document.getElementById("etat").value = animalData[prenom].etat;
        document.getElementById("date_passage").value = animalData[prenom].date_nour;
        document.getElementById("heure_passage").value = animalData[prenom].heure_nour;
        document.getElementById("nourriture").value = animalData[prenom].nour;
        document.getElementById("grammage").value = animalData[prenom].qte_nour + " g"; // Ajouter " g" pour l'unité de mesure
        document.getElementById("habitat").value = animalData[prenom].habitat_nom;
        document.getElementById("avis_veterinaire").value = animalData[prenom].commentaire_habitat;
        document.getElementById("detail_etat").value = animalData[prenom].rapport_detail;
        // Rendre les champs modifiables
        document.getElementById("avis_veterinaire").readOnly = false;
        document.getElementById("detail_etat").readOnly = false;
    }
</script>
