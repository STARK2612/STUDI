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
        <nav class="navbar navbar-dark bg-bark" style="width: 80px;">
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
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'les_habitats_2.php') {
                require 'front/habitat.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'les_habitats_3.php') {
                require 'front/animal.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'contact.php') {
                require 'front/contact_admin.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'connexion.php') {
                require 'front/connexion_admin.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'admin.php') {
                require 'front/admin_space.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'veterinaire.php') {
                require 'front/veterinaire_admin.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'employe.php') {
                require 'front/employe_admin.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'compte.php') {
                require 'front/compte_admin.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'animal_gestion.php') {
                require 'front/animal_admin_gestion.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'les_services_gestion.php') {
                require 'front/services_admin_gestion.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'avis_gestion.php') {
                require 'front/avis_admin_gestion.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'veterinaire_gestion.php') {
                require 'front/veterinaire_admin_gestion.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'employe_gestion.php') {
                require 'front/employe_admin_gestion.php'; // Inclure le contenu de habitats_admin.php  
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'les_habitats_gestion.php') {
                require 'front/habitats_admin_gestion.php'; // Inclure le contenu de habitats_admin.php
            } elseif (basename($_SERVER['SCRIPT_FILENAME']) == 'stat_dashboard.php') {
                require 'front/dashboard.php'; // Inclure le contenu de habitats_admin.php
            } else {
                require 'front/article_accueil.php'; // Sinon, inclure le contenu par défaut
            }
            ?>
        </div>        
        
    </div>
</header>
