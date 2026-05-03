<?php
session_start();
require("bd.php");

// sécurité
if(!isset($_SESSION['IdUser'])){
    header("Location: authentification.php");
    exit();
}

$idUser = $_SESSION['IdUser'];
$idLoc = $_GET['id'] ?? null;

if(!$idLoc){
    die("Réservation invalide");
}

// récupérer la réservation
$sql = "SELECT * FROM location WHERE idLoc=? AND idUser=?";
$stmt = $connexion->prepare($sql);
$stmt->execute([$idLoc, $idUser]);
$res = $stmt->fetch();

if(!$res){
    die("Réservation introuvable");
}

$message = "";

// traitement modification
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $dateDebut = $_POST['date_debut'] ?? null;
    $duree = (int) ($_POST['duree'] ?? 0);

    if(empty($dateDebut) || $duree <= 0){
        $message = "Veuillez remplir correctement les champs ❌";
    } else {

        // recalcul prix
        $sqlPrix = "SELECT Prix_jour FROM bien_imm WHERE IdBien=?";
        $stmtPrix = $connexion->prepare($sqlPrix);
        $stmtPrix->execute([$res['idBien']]);
        $bien = $stmtPrix->fetch();

        $prix = $bien['Prix_jour'] * $duree;

        // vérifier chevauchement
        $sqlCheck = "SELECT * FROM location
                     WHERE idBien = ?
                     AND idLoc != ?
                     AND dateDebut <= ?
                     AND DATE_ADD(dateDebut, INTERVAL duree DAY) >= ?";

        $stmtCheck = $connexion->prepare($sqlCheck);
        $stmtCheck->execute([
            $res['idBien'],
            $idLoc,
            $dateDebut,
            $dateDebut
        ]);

        if($stmtCheck->fetch()){
            $message = "Dates indisponibles ❌";
        } else {

            // update
            $sqlUpdate = "UPDATE location 
                          SET dateDebut=?, duree=?, prix=? 
                          WHERE idLoc=?";

            $stmtUpdate = $connexion->prepare($sqlUpdate);
            $stmtUpdate->execute([
                $dateDebut,
                $duree,
                $prix,
                $idLoc
            ]);

            header("Location: mesReservations.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">
    <h2>Modifier réservation</h2>

    <?php if(!empty($message)): ?>
        <div class="alert alert-warning"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label>Date début</label>
            <input type="date" name="date_debut" class="form-control"
                   value="<?= $res['dateDebut'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Durée (jours)</label>
            <input type="number" name="duree" class="form-control"
                   value="<?= $res['duree'] ?>" min="1" required>
        </div>

        <button class="btn btn-success">Enregistrer</button>
        <a href="mesReservations.php" class="btn btn-secondary">Retour</a>

    </form>
</div>

</body>
</html>