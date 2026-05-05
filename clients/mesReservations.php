<?php
session_start();
require("bd.php");

// sécurité
if(!isset($_SESSION['IdUser'])){
    header("Location: authentification.php");
    exit();
}

$idUser = $_SESSION['IdUser'];

// récupérer réservations
$sql = "SELECT r.*, b.Type, b.Adresse, b.Prix_jour 
        FROM location r 
        JOIN bien_imm b ON r.idBien = b.IdBien 
        WHERE r.idUser = ? 
        ORDER BY r.idLoc DESC";

$stmt = $connexion->prepare($sql);
$stmt->execute([$idUser]);
$reservations = $stmt->fetchAll();

// fonction image
function getImage($connexion, $id){
    $sql = "SELECT * FROM photos WHERE idBien = ? LIMIT 1";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mes réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <style>
        body {
            background: #fdf6e3; /* beige clair */
            font-family: 'Georgia', serif;
            color: #333;
        }

        /* Conteneur centré */
        .form-container {
            max-width: 500px; /* largeur réduite */
            margin: 60px auto; /* centré horizontalement et espacé verticalement */
            padding: 30px;
            background: #fffaf0; /* blanc cassé */
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        /* Titre */
        .form-container h2 {
            text-align: center;
            color: #d4af37; /* doré */
            margin-bottom: 25px;
        }

        /* Champs */
        .form-container .form-control {
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 15px;
        }

        /* Bouton */
        .form-container .btn-success {
            display: block;
            width: 100%;
            background-color: #28a745; /* vert */
            color: #fff;
            font-weight: bold;
            border-radius: 8px;
            padding: 12px;
            transition: background-color 0.3s ease;
        }

        .form-container .btn-success:hover {
            background-color: #218838; /* vert plus foncé */
        }


    </style>
</head>

<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Mes réservations</h2>

    <?php if(empty($reservations)): ?>
        <div class="alert alert-warning text-center">
            Aucune réservation pour le moment
        </div>
    <?php endif; ?>

    <div class="row">

    <?php foreach($reservations as $r): ?>

        <?php
        // calcul date fin
        $dateFin = date('Y-m-d', strtotime($r['dateDebut'].' +'.$r['duree'].' days'));
        ?>

        <div class="col-md-4 mb-4">
            <div class="card shadow">

                <!-- IMAGE -->
                <?php $img = getImage($connexion, $r['idBien']); ?>

                <?php if($img): ?>
                    <img src="http://localhost/agenceImm/commerciaux/<?= $img['url'] ?>"
                         style="width:100%; height:200px; object-fit:cover;">
                <?php else: ?>
                    <img src="imageL/default.jpg"
                         style="width:100%; height:200px; object-fit:cover;">
                <?php endif; ?>

                <div class="card-body">

                    <h5><?= ($r['Type']=='app' ? 'Appartement':'Villa') ?></h5>

                    <p>
                        📍 <?= $r['Adresse'] ?><br>
                        📅 Début : <?= $r['dateDebut'] ?><br>
                        📅 Fin : <?= $dateFin ?><br>
                        ⏳ Durée : <?= $r['duree'] ?> jours<br>
                        💰 Prix : <?= $r['prix'] ?> FCFA
                    </p>

                    <!-- STATUT -->
                    <?php if($r['is_validated'] == 1): ?>
                        <span class="badge bg-success">Validée</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">En attente</span>
                    <?php endif; ?>

                    <!-- BOUTONS -->
                    <div class="d-flex gap-2 flex-wrap mt-3">

                        <!-- détail -->
                        <form action="details.php" method="POST" style="display:inline;">
                            <input type="hidden" name="idBien" value="<?= $r['idBien'] ?>">
                            <button type="submit" class="btn btn-dark btn-sm">
                                Détails
                            </button>
                        </form>


                        <!-- modifier -->
                        <a href="modifier_reservation.php?id=<?= $r['idLoc'] ?>" 
                           class="btn btn-warning btn-sm">
                           Modifier
                        </a>

                        <!-- annuler -->
                        <form action="annuler_reservation.php" method="POST"
                              onsubmit="return confirm('Annuler cette réservation ?');">
                            <input type="hidden" name="idLoc" value="<?= $r['idLoc'] ?>">
                            <button class="btn btn-danger btn-sm">
                                Annuler
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        </div>

    <?php endforeach; ?>

    </div>
</div>

</body>
</html>