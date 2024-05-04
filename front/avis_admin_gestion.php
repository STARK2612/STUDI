<?php
require_once('back/connect_bdd.php');

// Vérifier la connexion à la base de données
if ($connexion->connect_error) {
    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
}

// Récupérer les avis en attente de validation
$sqlAttenteValidation = "SELECT * FROM avis WHERE isVisible = 0";
$resultAttenteValidation = $connexion->query($sqlAttenteValidation);

// Pagination pour les avis en attente de validation
$avisParPageAttenteValidation = 1;
$totalAvisAttenteValidation = $resultAttenteValidation->num_rows;
$totalPagesAttenteValidation = ceil($totalAvisAttenteValidation / $avisParPageAttenteValidation);
$pageAttenteValidation = isset($_GET['page_attente_validation']) ? $_GET['page_attente_validation'] : 1;
$indiceDepartAttenteValidation = ($pageAttenteValidation - 1) * $avisParPageAttenteValidation;
$sqlAttenteValidation .= " LIMIT $indiceDepartAttenteValidation, $avisParPageAttenteValidation";
$resultAttenteValidation = $connexion->query($sqlAttenteValidation);

// Récupérer les avis validés et rejetés
$sqlAvisValidesEtRejetes = "SELECT * FROM avis";
$resultAvisValidesEtRejetes = $connexion->query($sqlAvisValidesEtRejetes);

// Pagination pour les avis validés et rejetés
$avisParPageValidesEtRejetes = 1;
$totalAvisValidesEtRejetes = $resultAvisValidesEtRejetes->num_rows;
$totalPagesValidesEtRejetes = ceil($totalAvisValidesEtRejetes / $avisParPageValidesEtRejetes);
$pageValidesEtRejetes = isset($_GET['page_valides_et_rejetes']) ? $_GET['page_valides_et_rejetes'] : 1;
$indiceDepartValidesEtRejetes = ($pageValidesEtRejetes - 1) * $avisParPageValidesEtRejetes;
$sqlAvisValidesEtRejetes .= " LIMIT $indiceDepartValidesEtRejetes, $avisParPageValidesEtRejetes";
$resultAvisValidesEtRejetes = $connexion->query($sqlAvisValidesEtRejetes);
?>

    <div class="container" id="background-color" style="border-radius: 10px; border: 3px solid white;">
        <div class="row">
            <div class="column left">
                <br>
                <h2>Avis en attente de validation</h2>
                <?php if ($resultAttenteValidation->num_rows > 0) : ?>
                    <div class="table-responsive overflow-auto">
                        <table class="table" class="custom-form">
                            <thead>
                                <tr>
                                    <th>Pseudo</th>
                                    <th>Commentaire</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultAttenteValidation->fetch_assoc()) : ?>
                                    <tr>
                                        <td class='text-center' style='background-color:white; border: 1px solid black;'><?php echo $row['pseudo']; ?></td>
                                        <td class="commentaire text-center" style='background-color:white; border: 1px solid black;'><?php echo $row['commentaire']; ?></td>
                                        <td class='text-center' style='background-color:white; border: 1px solid black;'>
                                            <form action="back/save_avis.php" method="GET">
                                                <input type="hidden" name="id" value="<?php echo $row['avis_id']; ?>">
                                                <button type="submit" class="btn btn-success" name="approve" value="1">Approuver</button>
                                            </form>
                                            <br>
                                            <form action="back/save_avis.php" method="GET">
                                                <input type="hidden" name="id" value="<?php echo $row['avis_id']; ?>">
                                                <button type="submit" class="btn btn-danger" name="approve" value="0">Rejeter</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination pour les avis en attente de validation -->
                    <?php if ($totalPagesAttenteValidation > 1) : ?>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <?php for ($i = 1; $i <= $totalPagesAttenteValidation; $i++) : ?>
                                    <li class="page-item <?php if ($i == $pageAttenteValidation) echo 'active'; ?>"><a class="page-link" href="?page_attente_validation=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php else : ?>
                    <p class='text-center' style='background-color:white; border: 1px solid black;'>Aucun avis en attente de validation.</p>
                <?php endif; ?>
            </div>
            <div class="column2">
                <div class="form-container2">
                    <!-- Affichage des avis validés paginés -->
                    <?php if ($resultAvisValidesEtRejetes->num_rows > 0) : ?>
                        <h3>Avis des visiteurs</h3>
                        <div class="table-responsive overflow-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pseudo</th>
                                    <th>Commentaire</th>
                                    <th>État</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultAvisValidesEtRejetes->fetch_assoc()) : ?>
                                    <tr>
                                        <td class='text-center' style='background-color:white; border: 1px solid black;'><?php echo $row['pseudo']; ?></td>
                                        <td class="commentaire" style='background-color:white; border: 1px solid black;'><?php echo $row['commentaire']; ?></td>
                                        <td class='text-center' style='background-color:white; border: 1px solid black;'><?php echo ($row['isVisible'] == 1) ? 'Approuvé' : 'Rejeté'; ?></td>
                                        <td class='text-center' style='background-color:white; border: 1px solid black;'>
                                            <!-- Formulaire pour supprimer l'avis -->
                                            <form action="back/delete_avis.php" method="POST">
                                                <input type="hidden" name="id" value="<?php echo $row['avis_id']; ?>">
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis?')">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        </div>
                        <!-- Pagination pour les avis validés et rejetés -->
                        <?php if ($totalPagesValidesEtRejetes > 1) : ?>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <?php for ($i = 1; $i <= $totalPagesValidesEtRejetes; $i++) : ?>
                                        <li class="page-item <?php if ($i == $pageValidesEtRejetes) echo 'active'; ?>"><a class="page-link" href="?page_valides_et_rejetes=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <a href="<?php
    if ($_SESSION['role'] == 'Administrateur') {
        echo "admin.php";
    } elseif ($_SESSION['role'] == 'Employé') {
        echo "employe.php";
    } elseif ($_SESSION['role'] == 'Vétérinaire') {
        echo "veterinaire.php";
    }
?>" class="btn btn-secondary btn-block">Retour</a><br><br>
            </div>
    </div>
    <script>
    function deconnexionAutomatique() {
    var idleTimer;
    function resetTimer() {
        clearTimeout(idleTimer);
        idleTimer = setTimeout(logout, 10000); // 30 secondes
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
<?php
// Fermer la connexion à la base de données
$connexion->close();
?>