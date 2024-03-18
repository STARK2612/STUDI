<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrateur') {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté en tant qu'administrateur
    header("Location: ../index.php");
    exit;
}

require_once('connect_bdd.php');

// Vérifier si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $role = $_POST['role'];

    // Vérifier si le nom d'utilisateur existe déjà dans la base de données
    $query = "SELECT COUNT(*) AS count FROM utilisateur WHERE username = ?";
    $stmt = $connexion->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Vérifier si le nom d'utilisateur existe déjà
    if ($row['count'] > 0) {
        echo "<script>
                alert('Le nom d\'utilisateur existe déjà.');
                window.location.href = '../compte.php'; // Redirection vers la page compte.php
                document.getElementById('inscription-form').reset(); // Réinitialisation des champs du formulaire
            </script>";
        exit;
    } else {
        // Associer role_id selon la valeur sélectionnée
        if ($role == 'Employé') {
            $role_id = 2;
        } elseif ($role == 'Vétérinaire') {
            $role_id = 3;
        } else {
            // Gérer le cas où aucun rôle n'est sélectionné correctement
            echo "<script>
                    alert('Erreur: Rôle non valide sélectionné.');
                  </script>";
            exit;
        }

        // Préparer la requête d'insertion
        $sql = "INSERT INTO utilisateur (username, password, nom, prenom, role_id) VALUES (?, ?, ?, ?, ?)";

        // Préparer et exécuter la requête
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("ssssi", $username, $password, $nom, $prenom, $role_id);
        if ($stmt->execute()) {
            // Afficher un message de succès dans une fenêtre popup JavaScript
            echo "<script>
                    alert('Compte créé avec succès');
                    window.location.href = '../compte.php'; // Redirection vers la page compte.php
                  </script>";
            exit;
        } else {
            // Gérer les erreurs d'exécution de la requête
            echo "Erreur lors de l'insertion des données: " . $connexion->error;
        }

        // Fermer la requête
        $stmt->close();
    }
} else {
    // Si ce n'est pas une requête POST, rediriger vers une page d'erreur ou simplement ignorer
    header('Location: ../compte.php');
    exit;
}
?>
