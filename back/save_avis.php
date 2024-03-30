<?php
require_once('connect_bdd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les données du formulaire sont bien définies
    if (isset($_POST['pseudo']) && isset($_POST['commentaire'])) {
        // Récupérer les données du formulaire
        $pseudo = $_POST['pseudo'];
        $commentaire = $_POST['commentaire'];

        // Préparation de la requête SQL
        $sql = "INSERT INTO `avis` (pseudo, commentaire, isVisible) VALUES (?, ?, false)";

        // Préparation de la déclaration
        $stmt = $connexion->prepare($sql);

        // Liaison des paramètres
        $stmt->bind_param("ss", $pseudo, $commentaire);

        // Exécution de la déclaration
        if ($stmt->execute()) {
            // Retourner un message de succès
            echo "<script>alert('Votre avis a été soumis pour validation!'); window.location.href = '../index.php';</script>";
        } else {
            // Retourner un message d'erreur
            echo "<script>alert('Une erreur s\'est produite. Veuillez réessayer plus tard.');</script>";
        }

        // Fermer la déclaration
        $stmt->close();
    } else {
        // Retourner un message d'erreur si les données sont manquantes
        echo "<script>alert('Erreur : Tous les champs doivent être remplis.');</script>";
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['approve']) && isset($_GET['id'])) {
    // Approbation ou rejet d'un avis
    $id = $_GET['id'];
    $approve = $_GET['approve'];

    // Mettre à jour isVisible en fonction de l'approbation ou du rejet
    if ($approve == 1) {
        // Approbation
        $sql = "UPDATE avis SET isVisible = 1 WHERE avis_id = ?";
    } else {
        // Rejet
        $sql = "UPDATE avis SET isVisible = 2 WHERE avis_id = ?";
    }

    // Préparation de la déclaration
    $stmt = $connexion->prepare($sql);

    // Liaison du paramètre
    $stmt->bind_param("i", $id);

    // Exécution de la déclaration
    if ($stmt->execute()) {
        if ($approve == 1) {
            // Retourner un message de succès pour l'approbation
            echo "<script>alert('L\'avis a été approuvé avec succès!'); window.location.href = '../avis_gestion.php';</script>";
        } else {
            // Retourner un message de succès pour le rejet
            echo "<script>alert('L\'avis a été rejeté avec succès!'); window.location.href = '../avis_gestion.php';</script>";
        }
    } else {
        // Retourner un message d'erreur
        echo "<script>alert('Une erreur s\'est produite. Veuillez réessayer plus tard.');</script>";
    }

    // Fermer la déclaration
    $stmt->close();
} else {
    // Rediriger vers index.php si la page est accédée directement sans soumission du formulaire
    header("Location: ../index.php");
    exit();
}

// Fermer la connexion
$connexion->close();
?>
