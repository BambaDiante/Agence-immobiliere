<?php
session_start();
require("bd.php");

if (!isset($_SESSION['IdUser'])) {
    header("Location: authentification.php");
    exit;
}

function getImage($connexion, $idBien) {
    $sql = "SELECT * FROM photos WHERE idBien = ? LIMIT 6";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$idBien]);
    return $stmt->fetchAll();
}

$sql = "SELECT b.*
        FROM favoris f
        JOIN bien_imm b ON b.IdBien = f.idBien
        WHERE f.idUser = ?
        ";
$stmt = $connexion->prepare($sql);
$stmt->execute([$_SESSION['IdUser']]);
$favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes favoris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php if (empty($favoris)): ?>
        <div class="d-flex align-items-center justify-content-center" style="min-height: 80vh; background: linear-gradient(to right, #e2e2e2, #c9d6ff);">
            <div class="text-center">
                <i class="fa fa-heart text-muted" style="font-size: 5rem; opacity: 0.5;"></i>
                <h2 class="mt-4 fw-bold text-dark">Aucun favori pour le moment</h2>
                <p class="text-muted fs-5 mb-4">Explorez nos biens et ajoutez vos préférés à vos favoris</p>
                <a href="acceuil.php" class="btn btn-primary btn-lg shadow-sm">
                    <i class="fa fa-home"></i> Retour à l'accueil
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="container mt-5">
            <h2 class="text-center mb-5">Mes biens favoris</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($favoris as $bien): ?>
                    <div class="col">
                        <div class="card shadow h-100 position-relative border-0">
                            <?php $images = getImage($connexion, $bien['IdBien']); ?>
                            <?php if (!empty($images)): ?>
                                <img src="../commerciaux/<?= htmlspecialchars($images[0]['url']) ?>" class="d-block w-100" style="height:220px; object-fit:cover;" alt="Bien">
                            <?php else: ?>
                                <img src="imageL/default.jpg" class="d-block w-100" style="height:220px; object-fit:cover;" alt="Par défaut">
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-center fw-bold"><?= strtolower(trim($bien['Type'])) === 'app' ? 'Appartement' : 'Villa' ?></h5>
                                <p class="card-text text-muted mb-4">
                                    <i class="fa fa-map-marker-alt text-danger"></i> <?= htmlspecialchars($bien['Adresse']) ?><br>
                                    <i class="fa fa-tag text-success"></i> <strong><?= number_format($bien['Prix_jour'], 0, ',', ' ') ?> FCFA</strong> / jour
                                </p>
                                <a href="details.php" onclick="event.preventDefault(); this.nextElementSibling.submit();" class="btn btn-dark mt-auto">Voir détails</a>
                                <form action="details.php" method="POST" class="d-none">
                                    <input type="hidden" name="idBien" value="<?= $bien['IdBien'] ?>">
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>