<?php
// Générer et stocker le jeton CSRF dans la session
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<div class="container" id="background2">  
    <div class="row mt-5">
        <div class="col-md-6 mx-auto">
        <br>
            <h2 class='text-center'>Contact</h2>
            <form action="send_email.php" method="post" enctype="text/plain">
                <!-- Ajout du champ CSRF -->
                <input type="hidden" name="csrf_token" value="<?php echo uniqid(); ?>">

                <div class="form-group">
                    <label for="title">Titre :</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="description">Description :</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                </div>
                <br>
                <button type="submit" class="btn btn-primary bg-custom">Envoyer</button>
                <br>
                <br>
            </form>
        </div>
    </div>
</div>
