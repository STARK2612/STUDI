<<<<<<< HEAD
<div class="container">
    <div id="carouselExampleIndicators2" class="carousel slide carousel-fade info-container">
        <div class="carousel-inner video-container">
            <div class="carousel-item active video-container1 object-fit-cover border rounded" onclick="afficherDetails.call(this, 'jungle')">
                <h3 class="video-title1">La jungle: <img class="cliquez_ici" src="front/img/cliquez-ici.gif" alt="Cliquez ici"><br><br><p>La jungle est un habitat dense et humide, rempli de végétation luxuriante et d'une grande diversité d'animaux.</p></h3>
                <div id="habitat-details1"></div>
                <div id="animal-details1"></div>
                <img class="video-image" src="front/img/jungle.jpg" alt="Jungle Image">
            </div>
            <div class="carousel-item video-container2 object-fit-cover border rounded" onclick="afficherDetails.call(this, 'savane')">
                <h3 class="video-title2">La savane: <img class="cliquez_ici" src="front/img/cliquez-ici.gif" alt="Cliquez ici"><br><br><p>La savane est un vaste paysage ouvert, caractérisé par des herbes hautes et des arbres dispersés.</p></h3>
                <div id="habitat-details2"></div>
                <div id="animal-details2"></div>
                <img class="video-image" src="front/img/savane.jpg" alt="Savane Image">
            </div>
            <div class="carousel-item video-container3 object-fit-cover border rounded" onclick="afficherDetails.call(this, 'marais')">
                <h3 class="video-title3">Les marais: <img class="cliquez_ici" src="front/img/cliquez-ici.gif" alt="Cliquez ici"><br><br><p>Les marais sont des zones humides avec une végétation aquatique dense, souvent habitée par une variété d'animaux.</p></h3>
                <div id="habitat-details3"></div>
                <div id="animal-details3"></div>
                <img class="video-image" src="front/img/marais.jpg" alt="Marais Image">
            </div>
        </div>
        <div class="carousel-controls">
            <a class="carousel-control-prev" href="#carouselExampleIndicators2" role="button" data-slide="prev">
                <img class="img_pre" src="front/img/precedent.gif" alt="precedent">
                <span class="sr-only"></span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button" data-slide="next">
                <img class="img_sui" src="front/img/suivant.gif" alt="Suivant"><br><br><br>
                <span class="sr-only"></span>
            </a>
        </div>
    </div>
</div>
<!-- Inclure la bibliothèque jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Inclure la bibliothèque Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<!-- Inclure la bibliothèque Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Fonction pour afficher les détails de l'animal
function afficherDetailsAnimal(animal, habitat) {
    // Récupérer l'élément contenant les détails de l'animal
    var animalDetails;
    if (habitat === 'jungle') {
        animalDetails = document.getElementById('animal-details1');
    } else if (habitat === 'savane') {
        animalDetails = document.getElementById('animal-details2');
    } else if (habitat === 'marais') {
        animalDetails = document.getElementById('animal-details3');
    }
    
    // Effacer le contenu précédent des détails de l'animal
    animalDetails.innerHTML = '';

    // Afficher le titre de l'animal
    var animalTitle = document.createElement('h3');
    animalTitle.textContent = animal.name;
    animalTitle.classList.add('white-text'); // Ajouter la classe pour le texte en noir
    animalDetails.appendChild(animalTitle);

    // Afficher l'image de l'animal
    var animalImage = document.createElement('img');
    animalImage.src = animal.image; // Assurez-vous que votre objet animal contient une propriété 'image' contenant l'URL de l'image de l'animal
    animalImage.alt = animal.name; // Texte alternatif pour l'image
    animalImage.classList.add('animal-image'); // Ajouter une classe à l'image pour le stylage CSS
    animalDetails.appendChild(animalImage);

    // Afficher la race de l'animal
    var animalRace = document.createElement('p');
    animalRace.textContent = "Race : " + animal.race;
    animalRace.classList.add('white-text'); // Ajouter la classe pour le texte en noir
    animalDetails.appendChild(animalRace);

// Afficher l'état de l'animal
var animalState = document.createElement('p');
        animalState.textContent = "État de l'animal : " + animal.state;
        animalState.classList.add('white-text'); // Ajouter la classe pour le texte en noir
        animalDetails.appendChild(animalState);

        // Afficher le détail de l'état de l'animal (information facultative)
        if (animal.stateDetail) {
            var stateDetail = document.createElement('p');
            stateDetail.textContent = "Détail de l'état : " + animal.stateDetail;
            stateDetail.classList.add('white-text'); // Ajouter la classe pour le texte en noir
            animalDetails.appendChild(stateDetail);
        }

        // Afficher la nourriture proposée
        var foodOffered = document.createElement('p');
        foodOffered.textContent = "Nourriture proposée : " + animal.foodOffered;
        foodOffered.classList.add('white-text'); // Ajouter la classe pour le texte en noir
        animalDetails.appendChild(foodOffered);

        // Afficher le grammage de la nourriture
        var foodGrams = document.createElement('p');
        foodGrams.textContent = "Grammage de la nourriture : " + animal.foodGrams + " g";
        foodGrams.classList.add('white-text'); // Ajouter la classe pour le texte en noir
        animalDetails.appendChild(foodGrams);

        // Afficher la date de passage
        var passageDate = document.createElement('p');
        passageDate.textContent = "Date de passage : " + animal.passageDate;
        passageDate.classList.add('white-text'); // Ajouter la classe pour le texte en noir
        animalDetails.appendChild(passageDate);

        // Afficher l'habitat où il est affecté
        var assignedHabitat = document.createElement('p');
        assignedHabitat.textContent = "Habitat affecté : " + animal.assignedHabitat;
        assignedHabitat.classList.add('white-text'); // Ajouter la classe pour le texte en noir
        animalDetails.appendChild(assignedHabitat);

    // Afficher l'avis du vétérinaire sur l'animal
    var veterinaryOpinion = document.createElement('p');
    veterinaryOpinion.textContent = "Avis du vétérinaire : " + animal.veterinaryOpinion;
    veterinaryOpinion.classList.add('white-text'); // Ajouter la classe pour le texte en noir
    animalDetails.appendChild(veterinaryOpinion);
}

// Fonction pour afficher les détails de l'habitat
function afficherDetails(habitat) {
    // Récupérer l'élément contenant les détails de l'habitat
    var habitatDetails;
    if (habitat === 'jungle') {
        habitatDetails = document.getElementById('habitat-details1');
    } else if (habitat === 'savane') {
        habitatDetails = document.getElementById('habitat-details2');
    } else if (habitat === 'marais') {
        habitatDetails = document.getElementById('habitat-details3');
    }

    // Récupérer l'élément contenant les détails de l'animal
    var animalDetails;
    if (habitat === 'jungle') {
        animalDetails = document.getElementById('animal-details1');
    } else if (habitat === 'savane') {
        animalDetails = document.getElementById('animal-details2');
    } else if (habitat === 'marais') {
        animalDetails = document.getElementById('animal-details3');
    }

    // Ajouter la classe pour réduire la largeur des détails des habitats et des animaux
    habitatDetails.classList.add('details-container');
    animalDetails.classList.add('details-container');

    // Masquer le titre de la vidéo lorsqu'un conteneur vidéo est cliqué
    var videoTitle = this.querySelector('h3');
    if (videoTitle.style.display !== 'none') {
        videoTitle.style.display = 'none';
    } else {
        videoTitle.style.display = 'block';
    }

    // Effacer le contenu précédent des détails de l'habitat
    habitatDetails.innerHTML = '';

    // Récupérer toutes les vidéos
    var videos = document.querySelectorAll('.video-container1 video, .video-container2 video, .video-container3 video');

    // Parcourir toutes les vidéos pour désactiver les autres
    videos.forEach(function(video) {
        video.classList.remove('clicked'); // Supprimer la classe 'clicked' de toutes les vidéos
    });

    // Si la vidéo est déjà active, retirer les détails de l'habitat et de l'animal
    if (this.classList.contains('clicked')) {
        habitatDetails.innerHTML = '';
        animalDetails.innerHTML = '';
        this.classList.remove('clicked');
        return; // Arrêter l'exécution de la fonction
    }

    // Ajouter la classe 'clicked' uniquement à la vidéo cliquée
    this.classList.add('clicked');

    // Ajouter la classe pour remplir le fond du conteneur de vidéo avec du blanc
    this.classList.add('white-background');

    // Afficher le titre de l'habitat
    var habitatTitle = document.createElement('h2');
    habitatTitle.textContent = habitat;
    habitatTitle.classList.add('white-text'); // Ajouter la classe pour le texte en noir
    habitatDetails.appendChild(habitatTitle);
    habitatTitle.style.display = 'none';

    // Récupérer les détails de l'habitat à partir d'une source de données (par exemple, une base de données)
    var habitatDetailsData = getHabitatDetails(habitat); // Cette fonction devrait récupérer les détails de l'habitat depuis une source de données

    // Afficher la description de l'habitat
    var habitatDescription = document.createElement('p');
    habitatDescription.textContent = habitatDetailsData.description;
    habitatDescription.classList.add('white-text'); // Ajouter la classe pour le texte en noir
    habitatDetails.appendChild(habitatDescription);

    // Afficher la liste des animaux de l'habitat
    var animalsList = document.createElement('ul');
    habitatDetailsData.animals.forEach(function(animal) {
        var animalItem = document.createElement('li');
        var animalButton = document.createElement('button');
        animalButton.textContent = animal.name;
        animalButton.classList.add('btn', 'btn-primary', 'mr-2', 'mb-2');
        animalButton.style.backgroundColor = '#6098ea'; // Changer la couleur du fond du bouton
        animalButton.style.borderColor = '#6098ea'; // Changer la couleur de la bordure du bouton
        animalButton.onclick = function(event) {
            event.stopPropagation(); // Empêcher la propagation de l'événement au conteneur vidéo
            afficherDetailsAnimal(animal, habitat); // Passer l'habitat en paramètre
        };
        animalItem.appendChild(animalButton);
        animalsList.appendChild(animalItem);
    });
    habitatDetails.appendChild(animalsList);

    // Masquer les détails de l'animal lorsqu'on clique à nouveau sur la vidéo
    this.addEventListener('click', function() {
        animalDetails.innerHTML = '';
    });
}

    // Fonction factice pour récupérer les détails de l'habitat depuis une source de données (par exemple, une base de données)
    function getHabitatDetails(habitat) {
    // Simulation des données
    var habitats = {
        jungle: {
            description: "La jungle est un habitat dense et humide, rempli de végétation luxuriante et d'une grande diversité d'animaux.",
            animals: [
                { name: "Tigre", race: "Bengal", veterinaryOpinion: "L'animal est en bonne santé.", image: "front/img/tigre.jpg", state: "Actif", stateDetail: "Aucun", foodOffered: "Viande", foodGrams: 500, passageDate: "2024-03-13", assignedHabitat: "Jungle" },
                { name: "Gorille", race: "Gorille des montagnes", veterinaryOpinion: "L'animal présente quelques signes de stress.", image: "front/img/gorille.jpg", state: "Inactif", stateDetail: "Repos", foodOffered: "Fruits et légumes", foodGrams: 800, passageDate: "2024-03-12", assignedHabitat: "Jungle" },
                { name: "Panthère", race: "Panthère noire", veterinaryOpinion: "L'animal a besoin de plus d'exercice.", image: "front/img/panthere.jpg", state: "Actif", stateDetail: "Chasse", foodOffered: "Viande", foodGrams: 600, passageDate: "2024-03-11", assignedHabitat: "Jungle" }
            ]
        },
        savane: {
            description: "La savane est un vaste paysage ouvert, caractérisé par des herbes hautes et des arbres dispersés.",
            animals: [
                { name: "Lion", race: "Lion d'Afrique", veterinaryOpinion: "L'animal est en pleine forme.", image: "front/img/lion.jpg", state: "Actif", stateDetail: "Chasse", foodOffered: "Viande", foodGrams: 700, passageDate: "2024-03-10", assignedHabitat: "Savane" },
                { name: "Éléphant", race: "Éléphant d'Afrique", veterinaryOpinion: "L'animal nécessite une attention particulière à sa santé dentaire.", image: "front/img/elephant.jpg", state: "Inactif", stateDetail: "Repos", foodOffered: "Herbe", foodGrams: 1000, passageDate: "2024-03-09", assignedHabitat: "Savane" },
                { name: "Girafe", race: "Girafe réticulée", veterinaryOpinion: "L'animal présente des signes de déshydratation.", image: "front/img/girafe.jpg", state: "Actif", stateDetail: "Recherche de nourriture", foodOffered: "Feuilles", foodGrams: 800, passageDate: "2024-03-08", assignedHabitat: "Savane" }
            ]
        },
        marais: {
            description: "Les marais sont des zones humides avec une végétation aquatique dense, souvent habitées par une variété d'animaux.",
            animals: [
                { name: "Crocodile", race: "Crocodile du Nil", veterinaryOpinion: "L'animal est en période de mue.", image: "front/img/crocodile.jpg", state: "Inactif", stateDetail: "Repos", foodOffered: "Viande", foodGrams: 600, passageDate: "2024-03-07", assignedHabitat: "Marais" },
                { name: "Hippopotame", race: "Hippopotame amphibie", veterinaryOpinion: "L'animal nécessite un régime alimentaire plus équilibré.", image: "front/img/hippopotame.jpg", state: "Actif", stateDetail: "Baignade", foodOffered: "Herbe", foodGrams: 900, passageDate: "2024-03-06", assignedHabitat: "Marais" },
                { name: "Flamant rose", race: "Flamant rose", veterinaryOpinion: "L'animal est en bonne santé.", image: "front/img/flamant.jpg", state: "Actif", stateDetail: "Recherche de nourriture", foodOffered: "Crustacés", foodGrams: 300, passageDate: "2024-03-05", assignedHabitat: "Marais" }
            ]
        }
    };

    return habitats[habitat];
}
</script>
=======
<div class="container">
    <div class="card bg-custom text-white top-container d-flex justify-content-center align-items-center">  
        <div class="card-body">
            <h2 class="text-center custom-title">Les habitats</h2>
        </div>
    </div>
    <div id="carouselExampleIndicators2" class="carousel slide carousel-fade">
    <div class="carousel-inner video-container">
            <div class="carousel-item active video-container1 object-fit-cover border rounded" onclick="afficherDetails.call(this, 'jungle')">
                <h3 class="video-title1">La jungle: <img class="cliquez_ici" src="front/img/cliquez-ici.gif" alt="Cliquez ici"><br><br><p>La jungle est un habitat dense et humide, rempli de végétation luxuriante et d'une grande diversité d'animaux.</p></h3>
                <div id="habitat-details1"></div>
                <div id="animal-details1"></div>
                <video autoplay muted loop>
                    <source src="front/img/jungle.mp4" type="video/mp4">
                    Votre navigateur ne prend pas en charge la balise vidéo.
                </video>
            </div>
            <div class="carousel-item video-container2 object-fit-cover border rounded" onclick="afficherDetails.call(this, 'savane')">
                <h3 class="video-title2">La savane: <img class="cliquez_ici" src="front/img/cliquez-ici.gif" alt="Cliquez ici"><br><br><p>La savane est un vaste paysage ouvert, caractérisé par des herbes hautes et des arbres dispersés.</p></h3>
                <div id="habitat-details2"></div>
                <div id="animal-details2"></div>
                <video autoplay muted loop>
                    <source src="front/img/savane.mp4" type="video/mp4">
                    Votre navigateur ne prend pas en charge la balise vidéo.
                </video>
            </div>
            <div class="carousel-item video-container3 object-fit-cover border rounded" onclick="afficherDetails.call(this, 'marais')">
                <h3 class="video-title3">Les marais: <img class="cliquez_ici" src="front/img/cliquez-ici.gif" alt="Cliquez ici"><br><br><p>Les marais sont des zones humides avec une végétation aquatique dense, souvent habitée par une variété d'animaux.</p></h3>
                <div id="habitat-details3"></div>
                <div id="animal-details3"></div>
                <video autoplay muted loop>
                    <source src="front/img/marais.mp4" type="video/mp4">
                    Votre navigateur ne prend pas en charge la balise vidéo.
                </video>
            </div>
        </div>
        <div class="carousel-controls">
            <a class="carousel-control-prev" href="#carouselExampleIndicators2" role="button" data-slide="prev">
                <img class="img_pre" src="front/img/precedent.gif" alt="precedent">
                <span class="sr-only"></span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button" data-slide="next">
                <img class="img_sui" src="front/img/suivant.gif" alt="Suivant"><br><br><br>
                <span class="sr-only"></span>
            </a>
        </div>
    </div>
</div>
<!-- Inclure la bibliothèque jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Inclure la bibliothèque Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<!-- Inclure la bibliothèque Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Fonction pour afficher les détails de l'animal
function afficherDetailsAnimal(animal, habitat) {
    // Récupérer l'élément contenant les détails de l'animal
    var animalDetails;
    if (habitat === 'jungle') {
        animalDetails = document.getElementById('animal-details1');
    } else if (habitat === 'savane') {
        animalDetails = document.getElementById('animal-details2');
    } else if (habitat === 'marais') {
        animalDetails = document.getElementById('animal-details3');
    }
    
    // Effacer le contenu précédent des détails de l'animal
    animalDetails.innerHTML = '';

    // Afficher le titre de l'animal
    var animalTitle = document.createElement('h3');
    animalTitle.textContent = animal.name;
    animalTitle.classList.add('white-text'); // Ajouter la classe pour le texte en noir
    animalDetails.appendChild(animalTitle);

    // Afficher l'image de l'animal
    var animalImage = document.createElement('img');
    animalImage.src = animal.image; // Assurez-vous que votre objet animal contient une propriété 'image' contenant l'URL de l'image de l'animal
    animalImage.alt = animal.name; // Texte alternatif pour l'image
    animalImage.classList.add('animal-image'); // Ajouter une classe à l'image pour le stylage CSS
    animalDetails.appendChild(animalImage);

    // Afficher la race de l'animal
    var animalRace = document.createElement('p');
    animalRace.textContent = "Race : " + animal.race;
    animalRace.classList.add('white-text'); // Ajouter la classe pour le texte en noir
    animalDetails.appendChild(animalRace);

    // Afficher d'autres détails de l'animal (images, etc.) si nécessaire

    // Afficher l'avis du vétérinaire sur l'animal
    var veterinaryOpinion = document.createElement('p');
    veterinaryOpinion.textContent = "Avis du vétérinaire : " + animal.veterinaryOpinion;
    veterinaryOpinion.classList.add('white-text'); // Ajouter la classe pour le texte en noir
    animalDetails.appendChild(veterinaryOpinion);
}

// Fonction pour afficher les détails de l'habitat
function afficherDetails(habitat) {
    // Récupérer l'élément contenant les détails de l'habitat
    var habitatDetails;
    if (habitat === 'jungle') {
        habitatDetails = document.getElementById('habitat-details1');
    } else if (habitat === 'savane') {
        habitatDetails = document.getElementById('habitat-details2');
    } else if (habitat === 'marais') {
        habitatDetails = document.getElementById('habitat-details3');
    }

    // Récupérer l'élément contenant les détails de l'animal
    var animalDetails;
    if (habitat === 'jungle') {
        animalDetails = document.getElementById('animal-details1');
    } else if (habitat === 'savane') {
        animalDetails = document.getElementById('animal-details2');
    } else if (habitat === 'marais') {
        animalDetails = document.getElementById('animal-details3');
    }

    // Ajouter la classe pour réduire la largeur des détails des habitats et des animaux
    habitatDetails.classList.add('details-container');
    animalDetails.classList.add('details-container');

    // Masquer le titre de la vidéo lorsqu'un conteneur vidéo est cliqué
    var videoTitle = this.querySelector('h3');
    if (videoTitle.style.display !== 'none') {
        videoTitle.style.display = 'none';
    } else {
        videoTitle.style.display = 'block';
    }

    // Effacer le contenu précédent des détails de l'habitat
    habitatDetails.innerHTML = '';

    // Récupérer toutes les vidéos
    var videos = document.querySelectorAll('.video-container1 video, .video-container2 video, .video-container3 video');

    // Parcourir toutes les vidéos pour désactiver les autres
    videos.forEach(function(video) {
        video.classList.remove('clicked'); // Supprimer la classe 'clicked' de toutes les vidéos
    });

    // Si la vidéo est déjà active, retirer les détails de l'habitat et de l'animal
    if (this.classList.contains('clicked')) {
        habitatDetails.innerHTML = '';
        animalDetails.innerHTML = '';
        this.classList.remove('clicked');
        return; // Arrêter l'exécution de la fonction
    }

    // Ajouter la classe 'clicked' uniquement à la vidéo cliquée
    this.classList.add('clicked');

    // Ajouter la classe pour remplir le fond du conteneur de vidéo avec du blanc
    this.classList.add('white-background');

    // Afficher le titre de l'habitat
    var habitatTitle = document.createElement('h2');
    habitatTitle.textContent = habitat;
    habitatTitle.classList.add('white-text'); // Ajouter la classe pour le texte en noir
    habitatDetails.appendChild(habitatTitle);
    habitatTitle.style.display = 'none';

    // Récupérer les détails de l'habitat à partir d'une source de données (par exemple, une base de données)
    var habitatDetailsData = getHabitatDetails(habitat); // Cette fonction devrait récupérer les détails de l'habitat depuis une source de données

    // Afficher la description de l'habitat
    var habitatDescription = document.createElement('p');
    habitatDescription.textContent = habitatDetailsData.description;
    habitatDescription.classList.add('white-text'); // Ajouter la classe pour le texte en noir
    habitatDetails.appendChild(habitatDescription);

    // Afficher la liste des animaux de l'habitat
    var animalsList = document.createElement('ul');
    habitatDetailsData.animals.forEach(function(animal) {
        var animalItem = document.createElement('li');
        var animalButton = document.createElement('button');
        animalButton.textContent = animal.name;
        animalButton.classList.add('btn', 'btn-primary', 'mr-2', 'mb-2');
        animalButton.style.backgroundColor = '#6098ea'; // Changer la couleur du fond du bouton
        animalButton.style.borderColor = '#6098ea'; // Changer la couleur de la bordure du bouton
        animalButton.onclick = function(event) {
            event.stopPropagation(); // Empêcher la propagation de l'événement au conteneur vidéo
            afficherDetailsAnimal(animal, habitat); // Passer l'habitat en paramètre
        };
        animalItem.appendChild(animalButton);
        animalsList.appendChild(animalItem);
    });
    habitatDetails.appendChild(animalsList);

    // Masquer les détails de l'animal lorsqu'on clique à nouveau sur la vidéo
    this.addEventListener('click', function() {
        animalDetails.innerHTML = '';
    });
}

    // Fonction factice pour récupérer les détails de l'habitat depuis une source de données (par exemple, une base de données)
    function getHabitatDetails(habitat) {
        // Simulation des données
        var habitats = {
            jungle: {
                description: "",
                animals: [
                    { name: "Tigre", race: "Bengal", veterinaryOpinion: "L'animal est en bonne santé.", image: "front/img/tigre.jpg" },
                    { name: "Gorille", race: "Gorille des montagnes", veterinaryOpinion: "L'animal présente quelques signes de stress.", image: "front/img/tigre.jpg" },
                    { name: "Panthère", race: "Panthère noire", veterinaryOpinion: "L'animal a besoin de plus d'exercice.", image: "front/img/tigre.jpg" }
                ]
            },
            savane: {
                description: "",
                animals: [
                    { name: "Lion", race: "Lion d'Afrique", veterinaryOpinion: "L'animal est en pleine forme.", image: "front/img/tigre.jpg" },
                    { name: "Éléphant", race: "Éléphant d'Afrique", veterinaryOpinion: "L'animal nécessite une attention particulière à sa santé dentaire.", image: "front/img/tigre.jpg" },
                    { name: "Girafe", race: "Girafe réticulée", veterinaryOpinion: "L'animal présente des signes de déshydratation.", image: "front/img/tigre.jpg" }
                ]
            },
            marais: {
                description: "",
                animals: [
                    { name: "Crocodile", race: "Crocodile du Nil", veterinaryOpinion: "L'animal est en période de mue.", image: "front/img/tigre.jpg" },
                    { name: "Hippopotame", race: "Hippopotame amphibie", veterinaryOpinion: "L'animal nécessite un régime alimentaire plus équilibré.", image: "front/img/tigre.jpg" },
                    { name: "Flamant rose", race: "Flamant rose", veterinaryOpinion: "L'animal est en bonne santé.", image: "front/img/tigre.jpg" }
                ]
            }
        };

        return habitats[habitat];
    }
</script>

>>>>>>> 54d25e1ccebbdf612c1ee9a6ad64fbe4b3b867e4
