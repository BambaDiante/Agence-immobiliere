<?php

   require("bd.php");
    $sql = "SELECT * FROM bien_imm";
    $stmt = $connexion->prepare($sql);
    $stmt->execute();
    $biens = $stmt->fetchAll();
?>
<html>
    <head>
        <meta charset="utf-8"/>
    </head>
    <body>
        <?php $images = getImage($connexion, $bien['IdBien']); ?>
        <div id="carousel<?= $bien['IdBien'] ?>" class="carousel slide">
            <div class="carousel-inner">
                <?php $first = true; ?>
                <?php foreach($images as $img): ?>
                    <div class="carousel-item <?= $first ? 'active' : '' ?>">
                        <img src="../COMMERCIAUX/IMAGES/<?= $img['image'] ?>" 
                            class="d-block w-100"
                            style="height:200px; object-fit:cover;">
                    </div>
                    <?php $first = false; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </body> 
</html>