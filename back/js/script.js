<<<<<<< HEAD
// Fonction pour masquer le conteneur d'informations après un certain délai
function hideInfoContainer() {
    var infoContainer = document.querySelector('.info-container');
    infoContainer.style.display = 'none';
    setTimeout(function() {
        infoContainer.style.display = 'block';
    }, 10000); // Réapparition après 10 secondes (10000 millisecondes)
=======
// Fonction pour masquer le conteneur d'informations après un certain délai
function hideInfoContainer() {
    var infoContainer = document.querySelector('.info-container');
    infoContainer.style.display = 'none';
    setTimeout(function() {
        infoContainer.style.display = 'block';
    }, 10000); // Réapparition après 10 secondes (10000 millisecondes)
>>>>>>> 54d25e1ccebbdf612c1ee9a6ad64fbe4b3b867e4
}