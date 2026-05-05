<?php
session_start();
require("bd.php");

$message = "";
$bien = null;

if(!isset($_SESSION['IdUser'])){
    header("Location: authentificationA.php?redirect=reservation.php");
    exit();
}

$idBien = $_POST['idBien'] ?? $_GET['id'] ?? null;
if(!$idBien){ die("Bien introuvable"); }

$idBien = (int)$idBien;

$sql = "SELECT * FROM bien_imm WHERE IdBien=?";
$stmt = $connexion->prepare($sql);
$stmt->execute([$idBien]);
$bien = $stmt->fetch();

if(!$bien){ die("Bien introuvable en base"); }

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer'])){

    $dateDebut = $_POST['date_debut'] ?? null;
    $duree = (int) ($_POST['duree'] ?? 0);

    if(empty($dateDebut) || $duree <= 0){
        $message = "Veuillez remplir correctement les champs ❌";
    } else {
        $prix = $bien['Prix_jour'] * $duree;

        $sqlCheck = "SELECT * FROM location  
                     WHERE idBien = ?  
                     AND is_validated = 0
                     AND dateDebut <= ? 
                     AND DATE_ADD(dateDebut, INTERVAL duree DAY) >= ?";

        $stmtCheck = $connexion->prepare($sqlCheck);
        $stmtCheck->execute([$idBien, $dateDebut, $dateDebut]);
        $existe = $stmtCheck->fetch();

        if($existe){
            $message = "Ce bien est déjà réservé pour ces dates ❌";
        } else {
            $sql3 = "INSERT INTO location (idBien, idUser, duree, dateDebut, prix, is_validated)
                     VALUES (?, ?, ?, ?, ?, 0)";
            $stmt3 = $connexion->prepare($sql3);
            $stmt3->execute([$idBien, $_SESSION['IdUser'], $duree, $dateDebut, $prix]);

            // Redirection pour éviter de renvoyer le formulaire en actualisant
            header("Location: mesReservations.php?success=1");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fdf6e3; font-family: 'Georgia', serif; color: #333; }
        .form-container { max-width: 500px; margin: 60px auto; padding: 30px; background: #fffaf0; border-radius: 12px; box-shadow: 0 6px 15px rgba(0,0,0,0.1); }
        .form-container h2 { text-align: center; color: #d4af37; margin-bottom: 25px; }
        .form-container .form-control { border-radius: 8px; border: 1px solid #ccc; padding: 10px; }
        .btn-success { display: block; width: 100%; background-color: #28a745; color: #fff; font-weight: bold; border-radius: 8px; padding: 12px; border: none; }
        .btn-success:hover { background-color: #218838; }
        #prix-total { color: #28a745; font-weight: bold; font-size: 1.1rem; }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2>Réserver : <?= htmlspecialchars($bien['Type']) ?><br>
            <small style="font-size: 0.6em; color: #666;">à <?= htmlspecialchars($bien['Adresse']) ?></small>
        </h2>

        <?php if(!empty($message)): ?>
            <div class="alert alert-danger mt-3"><?= $message ?></div>
        <?php endif; ?>

        <!-- FORMULAIRE CORRIGÉ -->
        <form method="POST" action="">
            <input type="hidden" name="idBien" value="<?= $idBien ?>">

            <div class="mb-3">
                <label class="form-label">Date de début</label>
                <input type="date" name="date_debut" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Durée (jours)</label>
                <input type="number" id="duree" name="duree" min="1" class="form-control" required>
            </div>

            <p id="prix-total" class="mt-3"></p>

            <button type="submit" name="confirmer" class="btn btn-success">
                Confirmer la réservation
            </button>
            
            <div class="text-center mt-3">
                <a href="index.php" class="text-muted">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
const prixJour = <?= (int)$bien['Prix_jour'] ?>;
const displayPrix = document.getElementById('prix-total');

document.getElementById('duree').addEventListener('input', function(){
    let jours = this.value;
    if(jours > 0){
        displayPrix.textContent = "Prix total : " + (jours * prixJour).toLocaleString() + " FCFA";
    } else {
        displayPrix.textContent = "";
    }
});
</script>

</body>
</html>