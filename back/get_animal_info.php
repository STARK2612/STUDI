<?php
// Inclusion du fichier de connexion à la base de données
include_once "connect_bdd.php";

// Vérifier si le prénom de l'animal a été transmis
if(isset($_POST['prenom'])) {
    // Échapper les valeurs pour éviter les injections SQL
    $prenom = $connexion->real_escape_string($_POST['prenom']);

    // Requête SQL pour récupérer les informations sur l'animal
    $sql = "SELECT animal.etat, animal.nour, animal.qte_nour, animal.date_nour, animal.heure_nour, habitat.nom AS nom_habitat, habitat.commentaire_habitat
            FROM animal
            INNER JOIN habitat ON animal.habitat_id = habitat.habitat_id
            WHERE animal.prenom = '$prenom'";

    $result = $connexion->query($sql);

    // Vérifier si la requête a renvoyé des résultats
    if ($result->num_rows > 0) {
        // Récupérer les données de l'animal
        $row = $result->fetch_assoc();
        
        // Créer un tableau associatif avec les données de l'animal
        $animalInfo = array(
            'etat' => $row['etat'],
            'nour' => $row['nour'],
            'qte_nour' => $row['qte_nour'],
            'date_nour' => $row['date_nour'],
            'heure_nour' => $row['heure_nour'],
            'nom_habitat' => $row['nom_habitat'],
            'commentaire_habitat' => $row['commentaire_habitat']
        );

        // Retourner les données au format JSON
        echo json_encode($animalInfo);
    } else {
        echo "Aucune information trouvée pour cet animal.";
    }

    // Fermer la connexion à la base de données
    $connexion->close();
} else {
    echo "Erreur : prénom de l'animal non spécifié.";
}
?>
