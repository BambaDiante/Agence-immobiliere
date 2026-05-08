<?php
        session_start();
        require("bd.php");

        $mode = $_GET['mode'] ?? null;
        $idLoc = $_GET['idLoc'] ?? null;

        if(!$mode || !$idLoc){
            die("Informations manquantes");
        }

        // Normaliser la valeur pour garder un mode cohérent en base.
        $mode = trim((string)$mode);
        $allowedModes = ['Wave', 'Especes', 'Cash'];
        if(!in_array($mode, $allowedModes, true)){
            die("Mode de paiement invalide");
        }

        // Enregistrer le paiement : update si la ligne existe deja, sinon insert.
        $checkStmt = $connexion->prepare("SELECT 1 FROM paiement WHERE idLoc = ? LIMIT 1");
        $checkStmt->execute([$idLoc]);

        if($checkStmt->fetchColumn()){
            $updateStmt = $connexion->prepare("UPDATE paiement SET mode = ? WHERE idLoc = ?");
            $updateStmt->execute([$mode, $idLoc]);
        } else {
            $insertStmt = $connexion->prepare("INSERT INTO paiement(idLoc, mode) VALUES (?, ?)");
            $insertStmt->execute([$idLoc, $mode]);
        }

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Confirmation</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
        body{
            background:#f8f9fa;
            font-family:Arial;
        }

        .box{
            max-width:600px;
            margin:80px auto;
            background:white;
            padding:40px;
            border-radius:15px;
            box-shadow:0 5px 15px rgba(0,0,0,0.1);
            text-align:center;
        }

        .success{
            color:green;
            font-size:24px;
            font-weight:bold;
        }
        </style>
    </head>

    <body>

        <div class="box">

            <p class="success">
                Paiement enregistré avec succès ✅
            </p>

            <p>
                Mode de paiement :
                <strong><?= htmlspecialchars($mode) ?></strong>
            </p>

            <a href="mesReservations.php" class="btn btn-success">
                Voir mes réservations
            </a>

        </div>

    </body>     
</html>