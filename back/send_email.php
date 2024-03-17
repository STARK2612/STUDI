<<<<<<< HEAD
<?php
// Récupérer les données du formulaire
$title = $_POST['title'];
$email = $_POST['email'];
$description = $_POST['description'];

// Adresse e-mail de destination
$to = 'nofx126609@gmail.com';

// Sujet de l'e-mail
$subject = 'Nouveau message depuis le formulaire de contact';

// Corps de l'e-mail
$message = "Titre: $title\n\n";
$message .= "Email: $email\n\n";
$message .= "Description:\n$description";

// En-têtes de l'e-mail
$headers = "From: $email\r\nReply-To: $email\r\n";

// Envoyer l'e-mail
mail($to, $subject, $message, $headers);

// Redirection après l'envoi du formulaire (optionnel)
header('Location: contact.php');
?>
=======
<?php
// Récupérer les données du formulaire
$title = $_POST['title'];
$email = $_POST['email'];
$description = $_POST['description'];

// Adresse e-mail de destination
$to = 'nofx126609@gmail.com';

// Sujet de l'e-mail
$subject = 'Nouveau message depuis le formulaire de contact';

// Corps de l'e-mail
$message = "Titre: $title\n\n";
$message .= "Email: $email\n\n";
$message .= "Description:\n$description";

// En-têtes de l'e-mail
$headers = "From: $email\r\nReply-To: $email\r\n";

// Envoyer l'e-mail
mail($to, $subject, $message, $headers);

// Redirection après l'envoi du formulaire (optionnel)
header('Location: contact.php');
?>
>>>>>>> 54d25e1ccebbdf612c1ee9a6ad64fbe4b3b867e4
