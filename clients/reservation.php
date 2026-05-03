<?php
session_start();
require("bd.php");

$message = "";
$bien = null;

// Vérifier utilisateur connecté
if(!isset($_SESSION['IdUser'])){
    header("Location: authentification.php?redirect=reservation.php");
    exit();
}

// Récupérer idBien (GET ou POST)
$idBien = $_POST['idBien'] ?? $_GET['id'] ?? null;

if(!$idBien){
    die("Bien introuvable");
}

$idBien = (int)$idBien;

// Récupérer le bien
$sql = "SELECT * FROM bien_imm WHERE IdBien=?";
$stmt = $connexion->prepare($sql);
$stmt->execute([$idBien]);
$bien = $stmt->fetch();

if(!$bien){
    die("Bien introuvable en base");
}

// TRAITEMENT FORMULAIRE
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $dateDebut = $_POST['date_debut'] ?? null;
    $duree = (int) ($_POST['duree'] ?? 0);

    // VALIDATION
    if(empty($dateDebut) || $duree <= 0){

        $message = "Veuillez remplir correctement les champs ❌";

    } else {

        // Calcul prix
        $prix = $bien['Prix_jour'] * $duree;

        // Vérifier chevauchement
        $sqlCheck = "SELECT * FROM location  
                     WHERE idBien = ?  
                     AND is_validated = 0
                     AND dateDebut <= ? 
                     AND DATE_ADD(dateDebut, INTERVAL duree DAY) >= ?";

        $stmtCheck = $connexion->prepare($sqlCheck);
        $stmtCheck->execute([$idBien, $dateDebut, $dateDebut]);

        $existe = $stmtCheck->fetch();

        if($existe){

            $message = "Ce bien est déjà réservé ❌";

        } else {

            // INSERTION
            $sql3 = "INSERT INTO location (idBien, idUser, duree, dateDebut, prix, is_validated)
                     VALUES (?, ?, ?, ?, ?, 0)";

            $stmt3 = $connexion->prepare($sql3);
            $stmt3->execute([
                $idBien,
                $_SESSION['IdUser'],
                $duree,
                $dateDebut,
                $prix
            ]);

            $message = "Réservation effectuée avec succès ✅";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2>
        Réserver : <?= htmlspecialchars($bien['Type']) ?> 
        à <?= htmlspecialchars($bien['Adresse']) ?>
    </h2>

    <!-- MESSAGE -->
    <?php if(!empty($message)): ?>
        <div class="alert alert-info mt-3">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <!-- FORMULAIRE -->
    <form method="POST">

        <input type="hidden" name="idBien" value="<?= $idBien ?>">

        <div class="mb-3">
            <label>Date début</label>
            <input type="date" name="date_debut" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Durée (jours)</label>
            <input type="number" id="duree" name="duree" min="1" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">
            Confirmer réservation
        </button>

    </form>

    <!-- PRIX -->
    <p id="prix" class="mt-3 fw-bold"></p>

</div>

<script>
const prixJour = <?= (int)$bien['Prix_jour'] ?>;

document.getElementById('duree').addEventListener('input', function(){
    let jours = this.value;

    if(jours > 0){
        document.getElementById('prix').textContent =
            "Prix total : " + (jours * prixJour) + " FCFA";
    } else {
        document.getElementById('prix').textContent = "";
    }
});
</script>

</body>
</html>