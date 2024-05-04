<div class="container" id="background-color">
  <br>
  <h1 class="text-center">Espace Vétérinaire <a href="back/logout.php" class="btn btn-danger">Déconnexion</a></h1>
  <br>
  <div class="col-md-4" style="height: 200px; width:500px; margin: 10px;">
    <div class="row justify-content-center">
        <div class="d-flex flex-column align-items-center" style='border-radius: 10px; border: 3px solid black; margin:10px; background-color:white'>
            <div class="col-md-15">
                <div class="d-flex justify-content-center align-items-center">
                    <a href="veterinaire_gestion.php" class="btn btn-warning custom-btn">Bilan Veterinaire</a>
                    <img src="front/img/veterinaire.gif" width="100" height="100" alt="GIF utilisateur">
                </div>
            </div>
            <div class="col-md-8">
                <p class="text-center">Gestion comptes rendus vétérinaires.</p>
            </div>
            <br>
        </div>
    </div>
</div>
</div>
<script>
    function deconnexionAutomatique() {
    var idleTimer;
    function resetTimer() {
        clearTimeout(idleTimer);
        idleTimer = setTimeout(logout, 30000); // 30 secondes
    }
    resetTimer(); // Initialiser le timer

    // Réinitialiser le timer à chaque événement de souris ou de clavier
    document.addEventListener("mousemove", resetTimer);
    document.addEventListener("keypress", resetTimer);
}

// Fonction pour déconnecter l'utilisateur
function logout() {
    window.location.href = 'back/deconnexion.php'; // Page de déconnexion PHP
}

// Appeler la fonction de déconnexion automatique au chargement de la page
window.onload = function() {
    deconnexionAutomatique();
};
</script>