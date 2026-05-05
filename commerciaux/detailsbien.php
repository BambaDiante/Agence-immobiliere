<?php
    require_once("../configuration/connexion.php");

    $images = [];
    $bien_infos = [];

    if(isset($_POST['IdBien'])){
        $id = $_POST['IdBien'];
        
        // Récupération des images
        $recupimages = "SELECT * FROM photos WHERE idBien = :idBien";
        $recuperer = $pdo->prepare($recupimages);
        $recuperer->execute([":idBien" => $id]);
        $images = $recuperer->fetchAll(PDO::FETCH_ASSOC);

        // Récupération des infos du bien
        $recupinfos = "SELECT * FROM bien_imm WHERE IdBien = :IdBien";
        $recuperation = $pdo->prepare($recupinfos);
        $recuperation->execute([":IdBien" => $id]);
        $bien_infos = $recuperation->fetch(PDO::FETCH_ASSOC);
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon"  href="../configuration/images/logoagence.jpeg">

    <link rel="stylesheet" href="../configuration/css/bootstrap.min.css">
    <title>Détails du Bien</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Conteneur du carrousel pour limiter la taille */
        #carouselExampleIndicators {
            width: 100%;
            max-width: 900px; /* Largeur max raisonnable */
            height: 500px;    /* Hauteur fixe pour éviter les images géantes */
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .carousel-inner, .carousel-item {
            height: 100%;
        }

        .carousel-item img {
            height: 100%;
            width: 100%;
            object-fit: cover; /* L'image remplit le cadre sans se déformer */
        }

        .card {
            width: 100%;
            max-width: 900px;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .card-title {
            color: #2c3e50;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .carousel-control-prev,
        .carousel-control-next {
            width: 3.5rem;
            height: 3.5rem;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.85);
            border-radius: 50%;
            opacity: 1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);

        }

        .carousel-control-prev {
            left: 1rem;
        }

        .carousel-control-next {
            right: 1rem;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(1);
        }
    </style>
</head>
<body>

    <?php if (!empty($images)): ?>
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicateurs dynamiques : génère autant de boutons que d'images -->
        <div class="carousel-indicators">
            <?php foreach($images as $index => $img): ?>
                <button type="button" data-bs-target="#carouselExampleIndicators" 
                        data-bs-slide-to="<?php echo $index; ?>" 
                        class="<?php echo ($index === 0) ? 'active' : ''; ?>" 
                        aria-label="Slide <?php echo $index + 1; ?>"></button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner">
            <?php foreach($images as $index => $img): ?>
                <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                    <img src="<?php echo htmlspecialchars($img["url"]); ?>" class="d-block w-100" alt="Photo du bien">
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Contrôles -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
        </button>
    </div>
    <?php else: ?>
        <div class="alert alert-info">Aucune photo disponible pour ce bien.</div>
    <?php endif; ?>

    <!-- Affichage des informations du bien -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title text-center"><?php echo htmlspecialchars($bien_infos["titre"] ?? "Détails"); ?></h5>
            <hr>
            <p class="card-text"><?php echo nl2br(htmlspecialchars($bien_infos["Description"] ?? "Aucune description.")); ?></p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Nombre de pièces :</strong> <?php echo $bien_infos["nbre_pieces"] ?? "N/A"; ?></li>
            <li class="list-group-item"><strong>Superficie :</strong> <?php echo $bien_infos["Superficie"] ?? "N/A"; ?> m²</li>
            <li class="list-group-item"><strong>Prix :</strong> <span class="text-success fw-bold"><?php echo number_format($bien_infos["Prix_jour"], 0, ',', ' '); ?> F CFA / jour</span></li>
            <li class="list-group-item"><strong>Adresse :</strong> <?php echo htmlspecialchars($bien_infos["Adresse"] ?? "N/A"); ?></li>
            <li class="list-group-item"><strong>Statut :</strong> <span class="badge bg-info"><?php echo htmlspecialchars($bien_infos["statut"] ?? "Inconnu"); ?></span></li>
        </ul>
    </div>

    <!-- Script Bootstrap JS (Indispensable pour que le carrousel bouge) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>