<?php
session_start();
require_once('connect_bdd.php');

// Gérer la soumission du formulaire de connexion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Requête pour récupérer les données de l'utilisateur
    $requete = "SELECT utilisateur.*, role.label as role_label FROM utilisateur JOIN role ON utilisateur.role_id = role.role_id WHERE username = '$username'";
    $resultat = $connexion->query($requete);

    if ($resultat->num_rows == 1) {
        $utilisateur = $resultat->fetch_assoc();
        if ($utilisateur['password'] == $password) {
            // Authentification réussie
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $utilisateur['role_label'];
            
            if ($utilisateur['role_label'] == 'Employé') {
                // Redirection vers l'interface appropriée
                header("Location: employe.php");
                exit();
            } elseif ($utilisateur['role_label'] == 'Vétérinaire') {
                // Redirection vers l'interface d'administrateur
                header("Location: veterinaire.php");
                exit();
            } elseif ($utilisateur['role_label'] == 'Administrateur') {
                // Redirection vers l'interface d'administrateur
                header("Location: admin.php");
                exit();
            } else {
                // Redirection vers une page d'erreur ou message d'erreur
                echo "Erreur: Rôle non reconnu";
            }
        } else {
            // Mot de passe incorrect
            $message_erreur = "Mot de passe incorrect";
        }
    } else {
        // Utilisateur non trouvé
        $message_erreur = "Nom d'utilisateur invalide";
    }
}
?>
