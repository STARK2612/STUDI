<?php
// Inclure le fichier de connexion à la base de données
require_once('back/connect_bdd.php');

// Fonction pour échapper les caractères spéciaux
function escape_string($connexion, $value) {
    return $connexion->real_escape_string($value);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les champs sont définis et non vides
    if (isset($_POST['animal']) && isset($_POST['avis_veterinaire']) && isset($_POST['detail_etat']) && isset($_POST['date_visite_veto'])) {
        // Récupérer les valeurs des champs
        $prenom = $connexion->real_escape_string($_POST['animal']);
        $avis_veterinaire = $connexion->real_escape_string($_POST['avis_veterinaire']);
        $detail_etat = $connexion->real_escape_string($_POST['detail_etat']);
        $date_visite_veto = $connexion->real_escape_string($_POST['date_visite_veto']);

        // Requête SQL pour mettre à jour les données dans la table "habitat"
        $update_habitat_query = "UPDATE habitat INNER JOIN animal ON habitat.habitat_id = animal.habitat_id SET habitat.commentaire_habitat = '$avis_veterinaire' WHERE animal.prenom = '$prenom'";

        // Exécuter la requête SQL pour mettre à jour l'avis vétérinaire sur l'habitat
        if ($connexion->query($update_habitat_query) === TRUE) {
            // Requête SQL pour mettre à jour les données dans la table "rapport_veterinaire"
            $update_rapport_query = "UPDATE rapport_veterinaire INNER JOIN animal ON rapport_veterinaire.rapport_veterinaire_id = animal.rapport_veterinaire_id SET rapport_veterinaire.detail = '$detail_etat', rapport_veterinaire.date = STR_TO_DATE('$date_visite_veto', '%d/%m/%Y') WHERE animal.prenom = '$prenom'";

            // Exécuter la requête SQL pour mettre à jour le détail de l'état de l'animal
            if ($connexion->query($update_rapport_query) === TRUE) {
                if ($connexion->affected_rows === 0) {
                    // Aucune ligne mise à jour, cela signifie qu'aucun rapport n'existe encore pour cet animal
                    // Nous devons insérer un nouveau rapport vétérinaire

                    // Récupérer le nom de l'animal sélectionné dans le formulaire
                    $prenom = $connexion->real_escape_string($_POST['animal']);

                    // Requête SQL pour récupérer l'ID de rapport vétérinaire de l'animal sélectionné
                    $select_rapport_id_query = "SELECT rapport_veterinaire_id FROM animal WHERE prenom = '$prenom'";
                    $result_rapport_id = $connexion->query($select_rapport_id_query);

                    if ($result_rapport_id->num_rows > 0) {
                        $row_rapport_id = $result_rapport_id->fetch_assoc();
                        $rapport_veterinaire_id = $row_rapport_id['rapport_veterinaire_id'];

                        // Requête SQL pour insérer une nouvelle ligne dans la table rapport_veterinaire et récupérer l'ID généré automatiquement
$insert_rapport_query = "INSERT INTO rapport_veterinaire (detail, date) VALUES ('$detail_etat', STR_TO_DATE('$date_visite_veto', '%d/%m/%Y'))";
if ($connexion->query($insert_rapport_query) === TRUE) {
    // Récupérer l'ID généré automatiquement
    $rapport_veterinaire_id = $connexion->insert_id;

    // Mise à jour de la colonne rapport_veterinaire_id dans la table animal avec l'ID généré automatiquement
    $update_animal_query = "UPDATE animal SET rapport_veterinaire_id = '$rapport_veterinaire_id' WHERE prenom = '$prenom'";
    if ($connexion->query($update_animal_query) === TRUE) {
        // Mise à jour réussie
        echo '<script>alert("Les modifications ont été enregistrées avec succès.");</script>';
    } else {
        echo "Erreur lors de la mise à jour de la colonne rapport_veterinaire_id dans la table animal : " . $connexion->error;
    }
} else {
    echo "Erreur lors de l'insertion d'un nouveau rapport vétérinaire : " . $connexion->error;
}
                    }}}}}}

// Requête pour récupérer les noms des animaux, leur race, le nom de l'habitat, l'avis vétérinaire sur l'habitat et le détail de l'état de l'animal
$sql = "SELECT animal.prenom, animal.etat, DATE_FORMAT(animal.date_nour, '%d/%m/%Y') AS formatted_date, TIME_FORMAT(animal.heure_nour, '%H:%i') AS formatted_hour, animal.nour, animal.qte_nour, habitat.nom AS habitat_nom, habitat.commentaire_habitat, rapport_veterinaire.detail AS rapport_detail, rapport_veterinaire.date AS rapport_date, race.label
        FROM animal
        INNER JOIN race ON animal.race_id = race.race_id
        LEFT JOIN habitat ON animal.habitat_id = habitat.habitat_id
        LEFT JOIN rapport_veterinaire ON animal.rapport_veterinaire_id = rapport_veterinaire.rapport_veterinaire_id";

$result = $connexion->query($sql);

if ($result->num_rows > 0) {
    // Construction du menu déroulant
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

    // Stockage des données des animaux dans un tableau associatif pour un accès facile en JavaScript
    $animalData = array();

    while($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['prenom'] . '">' . $row['prenom'] . ' - ' . $row['label'] . '</option>';
        
        // Stocker les données de l'animal dans le tableau associatif
       
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
            <br>
        <input type="submit" class="btn btn-primary" value="Enregistrer">
        <br><br>
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
        <div class="col-md-8">
            <div class="form-group">
                <br><br><br>
                <label for="date_visite_veto">Date de la visite vétérérinaire:</label> 
                <input type="text" class="form-control" id="date_visite_veto" name="date_visite_veto" pattern="\d{1,2}/\d{1,2}/\d{4}" placeholder="jj/mm/aaaa" title="Format attendu : jj/mm/aaaa" required><br>
            </div>
            <div class="form-group">
                <label for="avis_veterinaire">Avis vétérinaire sur l'habitat:</label>
                <textarea id="avis_veterinaire" class="form-control" name="avis_veterinaire" rows="10"></textarea><br>
            </div>
            <div class="form-group">
                <label for="detail_etat">Détail de l’état de l’animal:</label>
                <textarea id="detail_etat" class="form-control" name="detail_etat" rows="10"></textarea><br>
            </div>
        </div>
        </form>
            </div>
            </div>
        

<script>
    var animalData = <?php echo json_encode($animalData); ?>;

function updateAnimalInfo(prenom) {
    document.getElementById("etat").value = animalData[prenom].etat;
    document.getElementById("date_passage").value = animalData[prenom].date_nour;
    document.getElementById("heure_passage").value = animalData[prenom].heure_nour;
    document.getElementById("nourriture").value = animalData[prenom].nour;
    document.getElementById("grammage").value = animalData[prenom].qte_nour + " g"; // Ajouter " g" pour l'unité de mesure
    document.getElementById("habitat").value = animalData[prenom].habitat_nom;
    document.getElementById("avis_veterinaire").value = animalData[prenom].commentaire_habitat;
    document.getElementById("detail_etat").value = animalData[prenom].rapport_detail;
    document.getElementById("avis_veterinaire").readOnly = false; // Rendre le champ modifiable
    document.getElementById("detail_etat").readOnly = false; // Rendre le champ modifiable
}
</script>