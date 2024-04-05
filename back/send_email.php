<?php
session_start();

// Vérifier si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si le jeton CSRF est présent dans la requête
    if (isset($_POST['csrf_token'])) {
        // Vérifier si le jeton CSRF est valide
        if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {
            // Récupérer les données du formulaire après nettoyage
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

            // Vérifier si les données ont été correctement envoyées
            if ($title && $email && $description) {
                // Adresse e-mail de destination
                $to = 'votreadressemail@example.com';

                // Sujet de l'e-mail
                $subject = 'Nouveau message depuis le formulaire de contact';

                // Corps de l'e-mail
                $message = "Titre: $title\n\n";
                $message .= "Email: $email\n\n";
                $message .= "Description:\n$description";

                // En-têtes de l'e-mail
                $headers = "From: $email\r\nReply-To: $email\r\n";

                // Envoyer l'e-mail
                if (mail($to, $subject, $message, $headers)) {
                    // Redirection après l'envoi du formulaire (optionnel)
                    header('Location: contact.php?success=true');
                    exit();
                } else {
                    // Gérer les erreurs d'envoi d'e-mail
                    echo "Une erreur s'est produite lors de l'envoi du message.";
                }
            } else {
                // Gérer les erreurs de données manquantes ou invalides
                echo "Veuillez remplir tous les champs du formulaire.";
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
    // Redirection si le formulaire n'a pas été soumis via POST
    header('Location: contact.php');
    exit();
}
?>
