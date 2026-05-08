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

if(!$idBien){
    die("Bien introuvable");
}

$idBien = (int)$idBien;

// récupérer le bien
$sql = "SELECT * FROM bien_imm WHERE IdBien = ?";
$stmt = $connexion->prepare($sql);
$stmt->execute([$idBien]);

$bien = $stmt->fetch();

if(!$bien){
    die("Bien introuvable en base");
}

// récupération des images
function getImage($connexion, $id){

    $sql = "SELECT * FROM photos WHERE idBien = ?";

    $stmt = $connexion->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetchAll();
}

$images = getImage($connexion, $idBien);


// réservation
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer'])){

    $dateDebut = $_POST['date_debut'] ?? null;
    $duree = (int)($_POST['duree'] ?? 0);

    if(empty($dateDebut) || $duree <= 0){

        $message = "Veuillez remplir correctement les champs ❌";

    } else {

        $prix = $bien['Prix_jour'] * $duree;

        // vérifier disponibilité
        $sqlCheck = "
        SELECT * FROM location
        WHERE idBien = ?
        AND is_validated = 0
        AND (
            (? BETWEEN dateDebut
            AND DATE_ADD(dateDebut, INTERVAL duree DAY))

            OR

            (DATE_ADD(?, INTERVAL ? DAY)
            BETWEEN dateDebut
            AND DATE_ADD(dateDebut, INTERVAL duree DAY))
        )
        ";

        $stmtCheck = $connexion->prepare($sqlCheck);

        $stmtCheck->execute([
            $idBien,
            $dateDebut,
            $dateDebut,
            $duree
        ]);

        $existe = $stmtCheck->fetch();

        if($existe){

            $message = "Ce bien est déjà réservé pour ces dates ❌";

        } else {

            // insertion réservation
            $sql3 = "
            INSERT INTO location(
                idBien,
                idUser,
                duree,
                dateDebut,
                prix,
                is_validated
            )
            VALUES (?, ?, ?, ?, ?, 0)
            ";

            $stmt3 = $connexion->prepare($sql3);

            $stmt3->execute([
                $idBien,
                $_SESSION['IdUser'],
                $duree,
                $dateDebut,
                $prix
            ]);

            // récupérer id réservation
            $idLoc = $connexion->lastInsertId();

            // redirection vers facture
            header("Location: facture.php?idBien=$idBien&prix=$prix&idLoc=$idLoc");
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
        body{
            background:#fdf6e3;
            font-family:'Georgia', serif;
            color:#333;
        }
        .form-container{
            max-width:700px;
            margin:50px auto;
            padding:30px;
            background:#fffaf0;
            border-radius:15px;
            box-shadow:0 6px 15px rgba(0,0,0,0.1);
        }
        .form-container h2{
            text-align:center;
            color:#d4af37;
            margin-bottom:25px;
        }
        .form-control{
            border-radius:8px;
            padding:10px;
        }
        .details-main-img{
            width:100%;
            max-height:400px;
            object-fit:contain;
            border-radius:10px;
            margin-top:20px;
        }
        .details-thumbs{
            display:flex;
            gap:10px;
            overflow-x:auto;
            margin-top:15px;
        }
        .details-thumbs img{
            width:90px;
            height:90px;
            object-fit:cover;
            border-radius:8px;
            cursor:pointer;
            transition:0.3s;
            border:2px solid transparent;
        }
        .details-thumbs img:hover{
            transform:scale(1.05);
            border:2px solid #d4af37;
        }
        #prix-total{
            font-size:20px;
            font-weight:bold;
            color:#28a745;
            text-align:center;
            margin-top:20px;
        }
        .btn-success{
            display:block;
            width:250px;
            margin:25px auto;
            padding:12px;
            border:none;
            border-radius:10px;
            background:#28a745;
            color:white;
            font-weight:bold;
        }
        .btn-success:hover{
            background:#218838;
        }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="form-container">
                <h2>
                    Réserver :
                    <?= htmlspecialchars($bien['titre']) ?>
                    <br>
                    <small style="font-size:0.6em;color:#666;">
                        à <?= htmlspecialchars($bien['Adresse']) ?>
                    </small>
                </h2>
                <?php if(!empty($message)): ?>
                    <div class="alert alert-danger">
                        <?= $message ?>
                    </div>
                <?php endif; ?>
                <form id="reservationForm" method="POST">
                    <input type="hidden" name="idBien" value="<?= $idBien ?>">
                    <div class="mb-3">
                        <label class="form-label"> Date de début </label>
                        <input type="date" name="date_debut" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"> Durée (jours) </label>
                        <input type="number" id="duree" name="duree" min="1" class="form-control" required>
                    </div>
                    <!-- images -->
                    <?php if(!empty($images)): ?>
                        <img id="mainImg<?= $bien['IdBien'] ?>" src="../commerciaux/<?= htmlspecialchars($images[0]['url']) ?>" class="details-main-img">
                        <div class="details-thumbs">
                            <?php foreach($images as $img): ?>
                                <img src="../commerciaux/<?= htmlspecialchars($img['url']) ?>" onclick="
                                    document.getElementById('mainImg<?= $bien['IdBien'] ?>' ).src=this.src " >
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center mt-3">
                            Aucune image disponible
                        </p>
                    <?php endif; ?>
                    <p id="prix-total"></p>
                    <button type="submit" name="confirmer" class="btn btn-success">
                        Confirmer la réservation
                    </button>
                    <div class="text-center">
                        <a href="acceuil.php" class="text-muted">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <script>
            const prixJour = <?= (int)$bien['Prix_jour'] ?>;
            const displayPrix =
            document.getElementById('prix-total');
            document.getElementById('duree')
            .addEventListener('input', function(){
                let jours = this.value;
                if(jours > 0){
                    displayPrix.textContent =
                    "Prix total : " +
                    (jours * prixJour).toLocaleString()
                    + " FCFA";
                } 
                else 
                {
                    displayPrix.textContent = "";
                }
            });
        </script>

    </body>
</html>