<?php
// Informations de configuration de la boîte email
$adresseMail = 'zarca@alwaysdata.net';
$motDePasse = 'Aub1w@n3Ken0b1Master';
$serveurIMAP = 'imap-zarca.alwaysdata.net';
$serveurPOP = 'pop-zarca.alwaysdata.net';
$serveurSMTP = 'smtp-zarca.alwaysdata.net';

// Paramètres de configuration pour l'envoi d'email via PHP
ini_set('SMTP', $serveurSMTP);
ini_set('smtp_port', 587);
ini_set('sendmail_from', $adresseMail);

// Définition des en-têtes pour l'email
$headers = 'From: ' . $adresseMail . "\r\n" .
    'Reply-To: ' . $adresseMail . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

// Définition des variables et initialisation avec des valeurs vides
$titre = $email = $message = "";
$titreErr = $emailErr = $messageErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nettoyage des données entrées
    $titre = test_input($_POST["titre"]);
    $email = test_input($_POST["email"]);
    $message = test_input($_POST["message"]);

    // Vérifie si le champ titre est vide
    if (empty($titre)) {
        $titreErr = "Le titre est requis";
    }

    // Vérifie si le champ email est vide et s'il est au bon format
    if (empty($email)) {
        $emailErr = "L'email est requis";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Format d'email invalide";
    }

    // Vérifie si le champ message est vide
    if (empty($message)) {
        $messageErr = "Le message est requis";
    }

    // Si tous les champs sont remplis, envoie l'email
    if ($titreErr == "" && $emailErr == "" && $messageErr == "") {
        $destinataire = "zarca@alwaysdata.net";
        $sujet = $titre;
        
        // En-têtes pour l'email
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Contenu de l'email au format HTML
        $corps = "Nouveau message de contact zooarcadia\n";
        $corps .= "Titre: $titre\n";
        $corps .= "Email: $email\n";
        $corps .= "Message:\n" . wordwrap($message, 50, "\n", true);

        // Envoie l'email
        if (mail($destinataire, $sujet, $corps, $headers)) {
            echo "<script>alert('Message envoyé avec succès');</script>";
        } else {
            echo "<script>alert(\"Erreur lors de l'envoi du message\");</script>";
        }
    }
}

// Fonction pour sécuriser les données du formulaire
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<div class="container mt-4" id="background-color" style="border-radius: 10px; border: 3px solid white;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <br><br>
            <h2 class="mb-4">Formulaire de contact</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre:</label>
                    <input type="text" class="form-control" id="titre" name="titre">
                    <span class="text-danger"><?php echo $titreErr;?></span>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="text" class="form-control" id="email" name="email">
                    <span class="text-danger"><?php echo $emailErr;?></span>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message:</label>
                    <textarea class="form-control" id="message" name="message" rows="5"></textarea>
                    <span class="text-danger"><?php echo $messageErr;?></span>
                </div>
                <button type="submit" class="btn btn-warning">Envoyer</button>
                <br><br>
            </form>
        </div>
    </div>
</div>
