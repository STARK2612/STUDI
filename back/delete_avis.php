<?php
require_once('connect_bdd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Récupérer l'ID de l'avis à supprimer
    $id = $_POST['id'];

    // Préparer la requête de suppression de l'avis
    $sql = "DELETE FROM avis WHERE avis_id = ?";
    $stmt = $connexion->prepare($sql);

    // Liaison du paramètre
    $stmt->bind_param("i", $id);

    // Exécuter la requête préparée
    if ($stmt->execute()) {
        // Redirection vers la page d'administration après la suppression
        header("Location: ../avis_gestion.php");
        exit();
    } else {
        // En cas d'erreur, afficher un message d'erreur
        echo "Erreur lors de la suppression de l'avis : " . $connexion->error;
    }

    // Fermer la requête préparée
    $stmt->close();
} else {
    // Si la requête n'est pas de type POST ou si l'ID n'est pas défini, rediriger vers la page d'accueil
    header("Location: ../admin.php");
    exit();
}

// Fermer la connexion à la base de données
$connexion->close();
?>
