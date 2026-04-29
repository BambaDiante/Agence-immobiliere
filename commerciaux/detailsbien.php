<?php
    require_once("../configuration/connexion.php");

    $images = [];
    $bien_infos = [];

  if(isset($_POST['IdBien'])){
    $id=$_POST['IdBien'];
    $recupimages="SELECT * FROM photos WHERE idBien=:idBien";
    $recuperer=$pdo->prepare($recupimages);
    $recuperer->execute([
        ":idBien"=>$id
    ]);
    $images=$recuperer->fetchALL(PDO::FETCH_ASSOC);
    $recupinfos="SELECT * FROM bien_imm WHERE IdBien=:IdBien";
    $recuperation=$pdo->prepare($recupinfos);
    $recuperation->execute([
        ":IdBien"=>$id
    ]);
    $bien_infos=$recuperation->fetch(PDO::FETCH_ASSOC);

  }
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../configuration/css/bootstrap.min.css">
    <title>Details Produit</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            display: flex;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            background-attachment:fixed;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            gap: 20px;
        }
        #carouselExampleIndicators {
            width: 100%;
            display:block;
            position: relative;
        }
        .carousel-inner {
            height: 100%;
        }
        .carousel-item {
            height: 100%;
        }
        .carousel-item img {
            height: 100%;
            object-fit: cover;
            width: 100%;
        }
        .carousel-control-prev,
        .carousel-control-next {
            width: 50px;
            height: 50px;
            top: 50%;
            transform: translateY(-50%);
        }
        .card{
            display:flex;
            align-items:center;
            justify-content:center;
            width:80%;
        }
        .card-title,.card-text{
            margin:10px auto;
            text-align: center;
        }
    </style>
</head>
<body>

    <div id="carouselExampleIndicators" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <?php
                
                for($i = 0; $i < count($images); $i++) {
                    echo '<div class="carousel-item' . ($i === 0 ? ' active' : '') . '">';
                    echo '<img src="' . $images[$i]["url"] . '" class="d-block w-100" alt="...">';
                    echo '</div>';
                }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <!--Apres le caroussel l'affichage des informations du bien -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo $bien_infos["titre"]; ?></h5>
            <p class="card-text"><?php echo $bien_infos["Description"]; ?></p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Nombre de pieces: <?php echo $bien_infos["nbre_pieces"] ?? ""; ?></li>
            <li class="list-group-item">Superficie: <?php echo $bien_infos["Superficie"] ?? ""; ?></li>
            <li class="list-group-item">Adresse: <?php echo $bien_infos["Adresse"] ?? ""; ?></li>
            <li class="list-group-item">Statut: <?php echo $bien_infos["statut"] ?? ""; ?></li>
        </ul>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>