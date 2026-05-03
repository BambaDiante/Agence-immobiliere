<?php
    session_start();
    require("bd.php");
    if(!isset($_SESSION['IdUser'])){
        header("Location: authentification.php");
        exit();
    }
    $idUser = $_SESSION['IdUser'];
    $sql = "SELECT location.*, bien_imm.Type, bien_imm.Adresse FROM location JOIN bien_imm ON location.idBien = bien_imm.IdBien WHERE location.idUser = ? ORDER BY location.dateDebut DESC";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$idUser]);
    $reservations = $stmt->fetchAll();
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
        <title>Historique</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <h2 class="mb-4 text-center">📜 Mon historique</h2>
            <?php if(empty($reservations)): ?>
                <p class="text-center">Aucune réservation pour le moment</p>
            <?php else: ?>
                <div class="row">

        <?php foreach($reservations as $r): 
            $image = getImage($connexion, $r['idBien']);
        ?>

        <div class="col-md-4 mb-4">

        <div class="card shadow">

        <!-- IMAGE -->
        <?php if($image): ?>
            <img src="../commerciaux/<?= $image['url'] ?>" 
                 class="card-img-top"
                 style="height:200px; object-fit:cover;">
        <?php else: ?>
            <img src="imageL/default.jpg" 
                 class="card-img-top"
                 style="height:200px; object-fit:cover;">
        <?php endif; ?>

        <!-- CONTENU -->
        <div class="card-body">

            <h5 class="card-title"><?= ($r['Type']=='app') ? 'Appartement':'Villa' ?></h5>

            <p>
                📍 <?= $r['Adresse'] ?><br>
                📅 Début : <?= $r['dateDebut'] ?><br>
                ⏳ <?= $r['duree'] ?> jours<br>
                💰 <?= $r['prix'] ?> FCFA
            </p>
            <!-- STATUT -->
            <p>
                <?= $r['is_validated'] 
                    ? "<span class='text-success'>✅ Validée</span>" 
                    : "<span class='text-warning'>⏳ En attente</span>" ?>
            </p>
           
        </div>

    </div>

</div>

<?php endforeach; ?>

</div>
            <?php endif; ?>
        </div>
    </body>
</html>