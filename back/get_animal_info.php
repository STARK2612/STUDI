<?php
// Inclusion du fichier de connexion à la base de données
include_once "connect_bdd.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Vérification si le paramètre prénom est défini
    if (isset($_GET['prenom'])) {
        $prenom = $_GET['prenom'];
        
        // Préparation de la requête pour récupérer les informations de l'animal
        $sql = "SELECT etat, nour, qte_nour, DATE_FORMAT(date_nour, '%d/%m/%Y') as date_nour, heure_nour FROM animal WHERE prenom = ?";
        
        // Préparation de la déclaration
        $stmt = mysqli_prepare($connexion, $sql);
        
        // Liaison des paramètres
        mysqli_stmt_bind_param($stmt, "s", $prenom);
        
        // Exécution de la requête
        mysqli_stmt_execute($stmt);
        
        // Récupération des résultats
        mysqli_stmt_bind_result($stmt, $etat, $nour, $qte_nour, $date_nour, $heure_nour);
        
        // Création d'un tableau associatif avec les informations de l'animal
        $animalInfo = array();
        
        if (mysqli_stmt_fetch($stmt)) {
            $animalInfo['etat'] = $etat;
            $animalInfo['nour'] = $nour;
            $animalInfo['qte_nour'] = $qte_nour;
            $animalInfo['date_nour'] = $date_nour;
            $animalInfo['heure_nour'] = $heure_nour;
        }
        
        // Fermeture du statement
        mysqli_stmt_close($stmt);
        
        // Fermeture de la connexion à la base de données
        mysqli_close($connexion);
        
        // Encodage des informations en JSON
        echo json_encode($animalInfo);
    } else {
        echo "Erreur: Paramètre prénom non spécifié.";
    }
} else {
    echo "Erreur: Méthode de requête non autorisée.";
}
?>
