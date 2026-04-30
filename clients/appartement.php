<?php

   require("bd.php");
    $sql = "SELECT * FROM bien_imm";
    $stmt = $connexion->prepare($sql);
    $stmt->execute();
    $biens = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            html, body {
                height: 100%;
            }
            body {
                display: flex;
                flex-direction: column;
            }
        </style>
    </head>
    <body>
        <?php $images = getImage($connexion, $bien['IdBien']); ?>
        <div id="carousel<?= $bien['IdBien'] ?>" class="carousel slide">
            <div class="carousel-inner">
                <?php $first = true; ?>
                <?php foreach($images as $img): ?>
                    <div class="carousel-item <?= $first ? 'active' : '' ?>">
                        <img src="COMMERCIAUX/IMAGES/<?= $img['image'] ?>" 
                            class="d-block w-100"
                            style="height:200px; object-fit:cover;">
                    </div>
                    <?php $first = false; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </body>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2026 Agence Immobilière - Tous droits réservés</p>
    </footer>
</html>