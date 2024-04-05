<?php
// Générer et stocker le jeton CSRF dans la session
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card connexion-form"> 
                <div class="card-header black-label">
                    Connexion
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <!-- Ajout du champ CSRF -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                        <div class="form-group">
                            <label for="username" class="black-label">Nom d'utilisateur :</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="black-label">Mot de passe :</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                        </div>
                        <br>
                        <br>
                        <button type="submit" class="btn btn-primary bg-custom">Se connecter</button>
                        <br>
                    </form>
                    <?php if (isset($message_erreur)) : ?>
                        <p><?php echo $message_erreur; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
