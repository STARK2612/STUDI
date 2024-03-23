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

// Exécuter la requête SQL pour récupérer les animaux
$sql = "SELECT animal.prenom, race.label AS race_label
        FROM animal
        INNER JOIN race ON animal.race_id = race.race_id";
$result = $connexion->query($sql);

?>

<div class="container my-4" id="background2">
    <br>
    <div class="text-center">
        <h2 class="mb-4">Bilan vétérinaire</h2>
    </div>
    <div class="mx-auto" style="max-width: 750px;">
        <form id="updateForm" action="javascript:void(0);" onsubmit="updateHabitatComment(); return false;" method="post">
            <div class="form-group">
                <label for="animal">Animal:</label>
                <select class="form-control" id="animal" name="animal" onchange="getAnimalInfo(this.value)">
                    <option value="" disabled selected>Choisir l'animal visité</option>
                    <?php
                    // Parcourir les résultats de la requête et générer les options du menu déroulant
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['prenom'] . "'>" . $row['prenom'] . " - Race: " . $row['race_label'] . "</option>";
                        }
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
                        <input type="text" class="form-control"id="nourriture_proposee" name="nourriture_proposee" readonly>
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
                    <div class="form-group">
                        <label for="habitat_animal">Habitat de l'animal:</label>
                        <input type="text" class="form-control" id="habitat_animal" name="habitat_animal" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date_bilan">Date du bilan:</label>
                        <input type="date" class="form-control" id="date_bilan" name="date_bilan">
                    </div>
                    <div class="form-group">
                        <label for="rapport_veterinaire">Rapport vétérinaire:</label>
                        <textarea class="form-control" id="rapport_veterinaire" name="rapport_veterinaire" rows="8"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="commentaire_habitat">Commentaire sur l'habitat:</label>
                        <textarea class="form-control" id="commentaire_habitat" name="commentaire_habitat" rows="8"></textarea>
                    </div>
                </div>
            <div class="text-center">
                <br>
                <button type="submit" class="btn btn-primary" onclick="showSuccessMessage()">Enregistrer</button>
                <a href="admin.php" class="btn btn-secondary ml-2">Retour</a>
            </div>
        </form>
        <br><br><br>
    </div>
</div>

<script>
    function getAnimalInfo(prenom) {
        // Effectuer une requête AJAX pour récupérer les informations sur l'animal
        $.ajax({
            type: 'POST',
            url: 'back/get_animal_info.php', // Chemin vers le script PHP pour récupérer les informations sur l'animal
            data: { prenom: prenom },
            success: function(data) {
                // Log des données récupérées depuis le script PHP
                console.log("Données récupérées depuis le script PHP :", data);

                // Mettre à jour les champs du formulaire avec les données récupérées
                $('#etat_animal').val(data.etat);
                $('#nourriture_proposee').val(data.nour);
                $('#quantite_nourriture').val(data.qte_nour);
                $('#date_passage').val(data.date_nour);
                $('#heure_passage').val(data.heure_nour);
                $('#habitat_animal').val(data.nom_habitat);
                $('#commentaire_habitat').val(data.commentaire_habitat);
            }
        });
    }
</script>


<?php
// Fermer la connexion à la base de données
$connexion->close();
?>