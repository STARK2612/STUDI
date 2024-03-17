<?php
// Inclusion du fichier de connexion à la base de données
include_once "connect_bdd.php";

// Vérifier si la connexion à la base de données est établie
if (!$connexion) {
    echo "La connexion à la base de données a échoué.";
    exit; // Arrêter l'exécution du script en cas d'échec de la connexion
}

// Vérification de la connexion
if ($connexion->connect_error) {
    die("La connexion a échoué : " . $connexion->connect_error);
}

// Vérifier si la requête POST est utilisée pour la modification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['service_id'])) {
    // Récupérer les données du formulaire de modification
    $service_id = $_POST['service_id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    // Préparer la requête de mise à jour du service
    $sql = "UPDATE service SET nom=?, description=? WHERE service_id=?";

    // Préparer et exécuter la requête de mise à jour
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("ssi", $nom, $description, $service_id);

    if ($stmt->execute()) {
        // Rediriger vers la page de gestion des services après la modification
        header("Location: ../les_services_gestion.php?success=1");
        exit;
    } else {
        // Gérer les erreurs de modification
        echo "Erreur lors de la modification du service : " . $connexion->error;
    }
} else {
    // Redirection en cas d'accès direct à ce script
    header("Location: ../services_admin_gestion.php");
    exit;
}

// Fermeture de la connexion
$connexion->close();
?>
