<?php

        // Récupérer les données du formulaire après nettoyage
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

        // Vérifier si les données ont été correctement envoyées
        if ($title && $email && $description) {
            // Adresse e-mail de destination
            $to = 'zarca@alwaysdata.net';

            // Sujet de l'e-mail
            $subject = 'Nouveau message depuis le formulaire de contact';

            // Corps de l'e-mail
            $message = "Titre: $title\n\n";
            $message .= "Email: $email\n\n";
            $message .= "Description:\n$description";

            // En-têtes de l'e-mail
            $headers = "From: $email\r\nReply-To: $email\r\n";

            // Configuration des paramètres SMTP
            $smtpServer = 'smtp-zarca.alwaysdata.net';
            $smtpPort = 587; // Le port SMTP à utiliser (peut être différent selon le fournisseur)
            $smtpUsername = 'zarca@alwaysdata.net'; // Votre adresse e-mail
            $smtpPassword = 'Aub1w@n3Ken0b1Master'; // Votre mot de passe e-mail

            // Configuration additionnelle pour l'envoi via SMTP
            ini_set('SMTP', $smtpServer);
            ini_set('smtp_port', $smtpPort);
            ini_set('sendmail_from', $smtpUsername);

            // Envoyer l'e-mail
            if (mail($to, $subject, $message, $headers)) {
                // Redirection après l'envoi du formulaire (optionnel)
                header('Location: ./contact.php?success=true');
                exit();
            } else {
                // Gérer les erreurs d'envoi d'e-mail
                echo "Une erreur s'est produite lors de l'envoi du message.";
            }
        } else {
            // Gérer les erreurs de données manquantes ou invalides
            echo "Veuillez remplir tous les champs du formulaire.";
        }
?>
