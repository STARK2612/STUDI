<div class="container">  
    <div class="row mt-5">
        <div class="col-md-6 mx-auto">
            <form action="send_email.php" method="post" enctype="text/plain">
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
