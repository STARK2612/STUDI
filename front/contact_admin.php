<?php
// Démarrage de la session
session_start();

// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification du jeton CSRF
    if (isset($_POST['csrf_token']) && isset($_SESSION['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        // Récupération des données du formulaire
        $titre = htmlspecialchars($_POST['titre']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);
        
        // Validation des données (ajouter plus de validation selon les besoins)
        if (!empty($titre) && !empty($email) && !empty($message)) {
            // Envoi du message par e-mail
            $to = 'zarca@alwaysdata.net';
            $subject = $titre;
            $headers = 'From: ' . $email . "\r\n" .
                'Reply-To: ' . $email . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            
            // Envoi du message
            mail($to, $subject, $message, $headers);
            echo 'Message envoyé avec succès.';
        } else {
            echo 'Veuillez remplir tous les champs du formulaire.';
        }
    } else {
        echo 'Erreur CSRF : Token invalide.';
    }
    
    // Réinitialisation du jeton CSRF
    unset($_SESSION['csrf_token']);
    session_destroy();
    exit();
}

// Génération du jeton CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>

<div class="container" id="background2">  
    <div class="row mt-5">
        <div class="col-md-6 mx-auto">
            <h2>Formulaire de Contact</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <label for="titre">Titre :</label><br>
                <input type="text" id="titre" name="titre" required><br>
                <label for="email">Email :</label><br>
                <input type="email" id="email" name="email" required><br>
                <label for="message">Message :</label><br>
                <textarea id="message" name="message" rows="4" required></textarea><br>
                <input type="submit" value="Envoyer">
            </form>
        </div>
    </div>
</div>
