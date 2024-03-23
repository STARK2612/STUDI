<?php
// Inclusion du fichier de connexion à la base de données
include_once "connect_bdd.php";

// Vérification si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification si les paramètres requis sont définis dans la requête
    if (isset($_POST['commentaire_habitat']) && isset($_POST['habitat_id'])) {
        // Récupérer l'identifiant de l'habitat de l'animal
        $habitat_id = $_POST['habitat_id'];

        // Récupérer le nouveau commentaire sur l'habitat depuis le formulaire
        $commentaire_habitat = $_POST['commentaire_habitat'];

        // Requête SQL pour mettre à jour le commentaire sur l'habitat de l'animal dans la table "habitat"
        $sql_update_habitat = "UPDATE habitat SET commentaire_habitat = ? WHERE habitat_id = ?";
        $stmt_update_habitat = mysqli_prepare($connexion, $sql_update_habitat);

        // Vérification si la préparation de la déclaration a réussi
        if ($stmt_update_habitat === false) {
            echo "Erreur de préparation de la requête : " . mysqli_error($connexion);
            exit;
        }

        // Liaison des paramètres à la déclaration SQL
        mysqli_stmt_bind_param($stmt_update_habitat, "si", $commentaire_habitat, $habitat_id);

        // Exécution de la requête
        if (mysqli_stmt_execute($stmt_update_habitat)) {
            echo "Le commentaire sur l'habitat a été mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour du commentaire sur l'habitat : " . mysqli_error($connexion);
        }

        // Fermeture de la déclaration
        mysqli_stmt_close($stmt_update_habitat);
    } else {
        // Si les paramètres requis ne sont pas spécifiés dans la requête POST
        echo "Erreur: Paramètres requis non spécifiés.";
    }
} else {
    // Si la méthode de requête n'est pas POST
    echo "Erreur: Méthode de requête non autorisée.";
}

// Fermeture de la connexion à la base de données
mysqli_close($connexion);
?>
