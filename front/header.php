<<<<<<< HEAD
<?php
// Vérifier si l'utilisateur est connecté en tant qu'Administrateur, Vétérinaire ou Employé
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Déterminer si le bouton de connexion doit être masqué
$masquerBoutonConnexion = ($role == 'Administrateur' || $role == 'Vétérinaire' || $role == 'Employé');
?>
<header>
    <div class="container-fluid carousel-container">
        <!-- Logo -->
       
        <?php
        // Vérifier si l'utilisateur est connecté et n'est pas sur les pages spécifiques
        if (!isset($_SESSION['username']) && !in_array(basename($_SERVER['SCRIPT_FILENAME']), ['admin.php', 'veto.php', 'empl.php'])) {
        echo '
        <nav class="navbar navbar-dark bg-bark" style="width: 80px;"> <!-- Ajustez la hauteur selon vos besoins -->
    <div class="container-fluid">
        <a class="navbar-brand" href="#">ZOOArcadia</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">ZOOArcadia</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="les_services.php">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="les_habitats.php">Habitats</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Contact</a>
          </li>
          <br>
          <li class="nav-item">
          <a href="connexion.php" class="btn btn-danger connexion-button">
              <img src="front/img/connexion.png" alt="Connexion" class="connexion-image">
              <span class="connexion-text">Connexion<br>réservée au<br>personnels du <br>zoo</span>
          </a>

          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>';}?>
        <div class="info-container
        <?php if (basename($_SERVER['SCRIPT_FILENAME']) == 'les_services.php' || basename($_SERVER['SCRIPT_FILENAME']) == 'les_habitats.php' || basename($_SERVER['SCRIPT_FILENAME']) == 'connexion.php') { echo 'transparent-bg'; } ?>">
            <?php
            // Vérifier si la page actuelle est les_services.php
            if (basename($_SERVER['SCRIPT_FILENAME']) == 'les_services.php') {
                require 'front/services_admin.php'; // Inclure le contenu de services_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'les_habitats.php') {
                require 'front/habitats_admin.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'contact.php') {
                require 'front/contact_admin.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'connexion.php') {
                require 'front/connexion_admin.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'admin.php') {
                require 'front/admin_space.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'compte.php') {
                require 'front/compte_admin.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'les_habitats_gestion.php') {
                require 'front/habitats_admin_gestion.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'les_services_gestion.php') {
                require 'front/services_admin_gestion.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'avis_gestion.php') {
                require 'front/avis_admin_gestion.php'; // Inclure le contenu de habitats_admin.php
            } else {
                require 'front/article_accueil.php'; // Sinon, inclure le contenu par défaut
            }
            ?>
        </div>        
        
    </div>
</header>
=======
<?php
// Vérifier si l'utilisateur est connecté en tant qu'Administrateur, Vétérinaire ou Employé
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Déterminer si le bouton de connexion doit être masqué
$masquerBoutonConnexion = ($role == 'Administrateur' || $role == 'Vétérinaire' || $role == 'Employé');
?>
<header>
    <div class="container-fluid carousel-container">
        <!-- Logo -->
        <img src="front/img/logo.png" class="logo" alt="Logo">
        <?php
        // Vérifier si l'utilisateur est connecté et n'est pas sur les pages spécifiques
        if (!isset($_SESSION['username']) && !in_array(basename($_SERVER['SCRIPT_FILENAME']), ['admin.php', 'veto.php', 'empl.php'])) {
        echo '
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <!-- Navbar Toggler Icon -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>  
                <!-- Navbar Items -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                            <a class="nav-link" href="les_services.php">Les services du ZOO</a>
                            <img src="front/img/servir.png" class="service-image" alt="Service du ZOO">
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="les_habitats.php">Les habitats du ZOO</a>
                            <img src="front/img/habitat.png" class="service-image" alt="Habitats du ZOO">
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact</a>
                            <img src="front/img/contact.png" class="service-image" alt="Contact">
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Accueil</a>
                            <img src="front/img/accueil.png" class="service-image" alt="Accueil">
                        </li>
                    </ul>
                </div>
            </div>
        </nav>';}?>
        <!-- Carousel -->
        <div id="carouselExampleIndicators" class="carousel slide rounded-carousel" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="front/img/zoop.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="front/img/felin.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="front/img/pero.jpg" class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Précédent</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Suivant</span>
            </button>
        </div>
        <!-- Conteneur d'informations -->
        <div class="info-container 
        <?php if (basename($_SERVER['SCRIPT_FILENAME']) == 'les_services.php' || basename($_SERVER['SCRIPT_FILENAME']) == 'les_habitats.php' || basename($_SERVER['SCRIPT_FILENAME']) == 'connexion.php') { echo 'transparent-bg'; } ?>">
            <?php
            // Vérifier si la page actuelle est les_services.php
            if (basename($_SERVER['SCRIPT_FILENAME']) == 'les_services.php') {
                require 'front/services_admin.php'; // Inclure le contenu de services_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'les_habitats.php') {
                require 'front/habitats_admin.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'contact.php') {
                require 'front/contact_admin.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'connexion.php') {
                require 'front/connexion_admin.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'admin.php') {
                require 'front/admin_space.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'compte.php') {
                require 'front/compte_admin.php'; // Inclure le contenu de habitats_admin.php
            } else {
                require 'front/article_accueil.php'; // Sinon, inclure le contenu par défaut
            }
            ?>
        </div>        
        <!-- Bouton de connexion -->
        <?php
        // Vérifier si l'utilisateur est connecté et n'est pas sur les pages spécifiques
        if (!isset($_SESSION['username']) && !in_array(basename($_SERVER['SCRIPT_FILENAME']), ['admin.php', 'veto.php', 'empl.php'])) {
            echo '<a href="connexion.php" class="connexion-button">
                    <img src="front/img/connexion.png" alt="Connexion">
                    <span class="connexion-text">Connexion<br>réservée au<br>personnels du <br>zoo</span>
                </a>';
        }
        ?>
    </div>
</header>

>>>>>>> 54d25e1ccebbdf612c1ee9a6ad64fbe4b3b867e4
