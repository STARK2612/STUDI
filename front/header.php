<div class="container-fluid carousel-container">
    <?php
    // Affichage de la barre de navigation uniquement si l'utilisateur n'est pas connecté et n'est pas sur certaines pages spécifiques
    if (!isset($_SESSION['username']) && !in_array(basename($_SERVER['SCRIPT_FILENAME']), ['admin.php', 'veto.php', 'empl.php'])) {
    echo '
    <nav class="navbar navbar-dark bg-bark" style="width: 80px;">
        <div class="container-fluid">    
            <div class="d-flex align-items-center"> <!-- Ajout de align-items-center pour centrer verticalement les éléments -->
                <button class="navbar-toggler me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation"> <!-- Ajout de la classe me-3 pour un espacement à droite -->
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand ms-auto" href="index.php">
                    <img src="front/img/logo.png" alt="ZOOArcadia Logo" class="logo" width="60" height="60">
                    ZOOArcadia
                </a>
            </div>
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
                    <li class="nav-item" style="width:200px">
                        <a href="connexion.php" class="btn btn-warning connexion-button">
                            <img src="front/img/connexion.png" alt="Connexion" class="connexion-image">
                            <span class="connexion-text">Connexion réservée au personnels du zoo</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>';
    }?>
    <div class="info-container <?php if (basename($_SERVER['SCRIPT_FILENAME']) == 'les_services.php' || basename($_SERVER['SCRIPT_FILENAME']) == 'les_habitats.php' || basename($_SERVER['SCRIPT_FILENAME']) == 'connexion.php') { echo 'transparent-bg'; } ?>">
        <?php
        // Inclusion du contenu en fonction de la page actuelle
        $page = basename($_SERVER['SCRIPT_FILENAME']);
        switch ($page) {
            case 'les_services.php':
                require 'front/services_admin.php';
                break;
            case 'les_habitats.php':
                require 'front/habitats_admin.php';
                break;
            case 'les_habitats_2.php':
                require 'front/habitat.php';
                break;
            case 'les_habitats_3.php':
                require 'front/animal.php';
                break;
            case 'contact.php':
                require 'front/contact_admin.php';
                break;
            case 'connexion.php':
                require 'front/connexion_admin.php';
                break;
            case 'admin.php':
                require 'front/admin_space.php';
                break;
            case 'veterinaire.php':
                require 'front/veterinaire_admin.php';
                break;
            case 'employe.php':
                require 'front/employe_admin.php';
                break;
            case 'compte.php':
                require 'front/compte_admin.php';
                break;
            case 'animal_gestion.php':
                require 'front/animal_admin_gestion.php';
                break;
            case 'les_services_gestion.php':
                require 'front/services_admin_gestion.php';
                break;
            case 'avis_gestion.php':
                require 'front/avis_admin_gestion.php';
                break;
            case 'veterinaire_gestion.php':
                require 'front/veterinaire_admin_gestion.php';
                break;
            case 'employe_gestion.php':
                require 'front/employe_admin_gestion.php';
                break;
            case 'les_habitats_gestion.php':
                require 'front/habitats_admin_gestion.php';
                break;
            case 'stat_dashboard.php':
                require 'front/dashboard.php';
                break;
            default:
                require 'front/article_accueil.php';
                break;
        }
        ?>
    </div>        
</div>
