<?php
// Inclure le fichier de connexion à la base de données
require_once('connect_bdd.php');

// Vérifier si le formulaire de modification a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $username = $_POST['username'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $role = $_POST['role'];

    // Convertir le rôle en ID correspondant
    switch ($role) {
        case "Employé":
            $role_id = 2;
            break;
        case "Vétérinaire":
            $role_id = 3;
            break;
        default:
            // Gérer le cas où le rôle est inconnu
            echo "Erreur : rôle inconnu.";
            exit;
    }

    // Préparer la requête de mise à jour
    $update_query = "UPDATE utilisateur SET nom = ?, prenom = ?, role_id = ? WHERE username = ?";
    
    // Préparer et exécuter la requête de mise à jour
    $stmt = $connexion->prepare($update_query);
    $stmt->bind_param("ssis", $nom, $prenom, $role_id, $username);
    
    if ($stmt->execute()) {
        // Rediriger vers la page compte.php avec un paramètre GET 'success'
        header("Location: ../compte.php?success=1");
        exit;
    } else {
        // Gérer les erreurs de mise à jour
        echo "Erreur lors de la modification du compte utilisateur.";
    }
} else {
    // Si le formulaire n'a pas été soumis via POST, rediriger vers la page compte
    header("Location: ../compte.php");
    exit;
}
?>
