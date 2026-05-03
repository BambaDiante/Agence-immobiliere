<?php
    session_start();
    require_once "../configuration/connexion.php";

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        $idLoc = $_POST['id'];
        // On vérifie si l'utilisateur a cliqué sur 'Valider'
        if (isset($_POST['val'])) {
            $stmt = $pdo->prepare("UPDATE location SET is_validated = '1' WHERE idLoc = :idLoc");
            $stmt->execute([':idLoc' => $idLoc]);
        } 
        // On vérifie si l'utilisateur a cliqué sur 'Annuler' (le bouton 'anu' dans votre HTML)
        elseif (isset($_POST['anu'])) {
            $stmt = $pdo->prepare("UPDATE location SET is_validated = '0' WHERE idLoc = :idLoc");
            $stmt->execute([':idLoc' => $idLoc]);
        }
    }
    // Redirection vers la page de gestion
    header("Location: gestionloc.php");
    exit;
?>