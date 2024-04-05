<?php
session_start();
require_once('connect_bdd.php');

// Vérifier si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si le jeton CSRF est présent dans la requête
    if (isset($_POST['csrf_token']) && isset($_SESSION['csrf_token'])) {
        // Vérifier si le jeton CSRF est valide
        if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {
            // Gérer la soumission du formulaire de connexion
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                // Requête préparée pour récupérer les données de l'utilisateur
                $requete = "SELECT utilisateur.*, role.label as role_label FROM utilisateur JOIN role ON utilisateur.role_id = role.role_id WHERE username = ?";
                $stmt = $connexion->prepare($requete);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $resultat = $stmt->get_result();

                if ($resultat->num_rows == 1) {
                    $utilisateur = $resultat->fetch_assoc();
                    // Vérifier si le mot de passe correspond
                    if (password_verify($password, $utilisateur['password'])) {
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
        } else {
            // Le jeton CSRF est invalide
            echo "Tentative d'attaque CSRF détectée.";
        }
    } else {
        // Le jeton CSRF est absent
        echo "Tentative d'attaque CSRF détectée.";
    }
} else {
    // Générer et stocker le jeton CSRF dans la session
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
