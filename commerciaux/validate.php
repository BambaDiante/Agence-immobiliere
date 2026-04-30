<?php
    session_start();
    require_once "../configuration/connexion.php";

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        $update = $pdo->prepare("UPDATE location SET is_validated = '1' WHERE idLoc = :idLoc");
        $update->execute([
            ':idLoc' => $_POST['id']
        ]);
    }

    header("Location: gestionloc.php");
    exit;
?>
