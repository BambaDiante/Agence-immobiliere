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

// récupérer la réservation et les infos du bien associé
$sql = "SELECT l.*, b.Type, b.Adresse, b.Prix_jour 
        FROM location l 
        JOIN bien_imm b ON l.idBien = b.IdBien 
        WHERE l.idLoc=? AND l.idUser=?";
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
        $prix = $res['Prix_jour'] * $duree;

        // vérifier chevauchement (exclure la réservation actuelle)
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
            $message = "Dates indisponibles pour ce bien ❌";
        } else {
            // Mise à jour
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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier ma réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #fdf6e3; /* beige clair */
            font-family: 'Georgia', serif;
            color: #333;
        }

        /* Conteneur centré identique à reservation.php */
        .form-container {
            max-width: 500px;
            margin: 60px auto;
            padding: 30px;
            background: #fffaf0; /* blanc cassé */
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        /* Titre doré */
        .form-container h2 {
            text-align: center;
            color: #d4af37;
            margin-bottom: 25px;
            font-size: 1.5rem;
        }

        .form-container .form-control {
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 15px;
        }

        .btn-success {
            display: block;
            width: 100%;
            background-color: #28a745;
            color: #fff;
            font-weight: bold;
            border-radius: 8px;
            padding: 12px;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-secondary {
            display: block;
            width: 100%;
            margin-top: 10px;
            background-color: #6c757d;
            border-radius: 8px;
            padding: 10px;
            border: none;
        }

        #prix-total {
            color: #28a745;
            font-weight: bold;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2>
            Modifier : <?= htmlspecialchars($res['Type']) ?><br>
            <small style="color: #666; font-size: 0.9rem;">📍 <?= htmlspecialchars($res['Adresse']) ?></small>
        </h2>

        <?php if(!empty($message)): ?>
            <div class="alert alert-warning text-center"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Date de début</label>
                <input type="date" name="date_debut" class="form-control"
                       value="<?= $res['dateDebut'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Durée (jours)</label>
                <input type="number" id="duree" name="duree" class="form-control"
                       value="<?= $res['duree'] ?>" min="1" required>
            </div>

            <p id="prix-total">
                Prix total : <?= number_format($res['prix'], 0, ',', ' ') ?> FCFA
            </p>

            <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
            <a href="mesReservations.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<script>
    // Script de calcul dynamique identique à la page réservation
    const prixParJour = <?= (int)$res['Prix_jour'] ?>;
    const inputDuree = document.getElementById('duree');
    const displayPrix = document.getElementById('prix-total');

    inputDuree.addEventListener('input', function() {
        let jours = this.value;
        if(jours > 0) {
            let total = jours * prixParJour;
            displayPrix.textContent = "Prix total : " + total.toLocaleString() + " FCFA";
        } else {
            displayPrix.textContent = "Prix total : 0 FCFA";
        }
    });
</script>

</body>
</html>