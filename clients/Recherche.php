<?php 
session_start();
require("bd.php");

// fonction image
function getImage($connexion, $id){
    $sql = "SELECT * FROM photos WHERE idBien = ? LIMIT 1";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// RECHERCHE
if(isset($_GET['q']) && !empty($_GET['q'])){
    $q = $_GET['q'];

    $sql = "SELECT * FROM bien_imm 
            WHERE Type LIKE ? OR Adresse LIKE ?";
    $stmt = $connexion->prepare($sql);
    $stmt->execute(["%$q%", "%$q%"]);
    $resultats = $stmt->fetchAll();

} else {
    $resultats = [];
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Résultats de recherche</h2>

    <?php if(empty($resultats)): ?>
        <p class="text-center">Aucun résultat trouvé 😢</p>
    <?php endif; ?>

    <div class="row">

        <?php foreach($resultats as $bien): ?>
            <div class="col-md-4 mb-4">

                <div class="card shadow">

                    <!-- IMAGE -->
                    <?php $img = getImage($connexion, $bien['IdBien']); ?>

                    <?php if($img): ?>
                        <img src="../commerciaux/<?= htmlspecialchars($img['url']) ?>"
                             style="width:100%; height:200px; object-fit:cover;">
                    <?php else: ?>
                        <img src="imageL/default.jpg"
                             style="width:100%; height:200px; object-fit:cover;">
                    <?php endif; ?>

                    <div class="card-body">

                        <h5><?= htmlspecialchars($bien['Type']) ?></h5>

                        <p>
                            📍 <?= htmlspecialchars($bien['Adresse']) ?><br>
                            💰 <?= htmlspecialchars($bien['Prix_jour']) ?> FCFA<br>
                            🏠 <?= htmlspecialchars($bien['nbre_pieces']) ?> pièces
                        </p>
                        <div class="card-body text-center">
                                        <form action="details.php" method="POST">
                                            <input type="hidden" name="idBien" value="<?= $bien['IdBien'] ?>">
                                            <button type="submit" class="btn btn-dark">Voir détails</button>
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