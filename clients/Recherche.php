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

// Fonction pour vérifier les favoris (pour garder la cohérence avec l'accueil)
function isFavori($connexion, $idBien){
    if(!isset($_SESSION['IdUser'])) return false;
    $sql = "SELECT * FROM favoris WHERE idBien=? AND idUser=?";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$idBien, $_SESSION['IdUser']]);
    return $stmt->rowCount() > 0;
}

// RECHERCHE
if(isset($_GET['q']) && !empty($_GET['q'])){
    $q = $_GET['q'];
    $sql = "SELECT * FROM bien_imm WHERE Type LIKE ? OR Adresse LIKE ?";
    $stmt = $connexion->prepare($sql);
    $stmt->execute(["%$q%", "%$q%"]);
    $resultats = $stmt->fetchAll();
} else {
    $resultats = [];
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche - Agence Immobilière</title>
    <link rel="icon" href="../configuration/images/logoagence.jpeg">
    <link rel="stylesheet" href="../configuration/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        /* Copie des styles de l'accueil pour la cohérence */
        .reveal { opacity: 0; transform: translateY(40px); transition: all 0.9s ease-out; }
        .reveal.active { opacity: 1; transform: translateY(0); }
        .card { transition: transform 0.3s ease, box-shadow 0.3s ease; border: none; border-radius: 15px; overflow: hidden; }
        .card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.2) !important; }
        header.navbar { z-index: 1000; }
        body { background-color: #f8f9fa; }
    </style>
</head>

<body>

    <!-- HEADER (Identique à accueil.php) -->
    <header class="navbar bg-white shadow-sm px-4 sticky-top">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div>
                <a href="acceuil.php"><img src="imageL/logoAgence.png" style="height:80px;" alt="Logo"></a>
            </div>

            <form class="d-flex w-50" action="recherche.php" method="GET">
                <input class="form-control me-2" type="search" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Rechercher un bien...">
                <button class="btn btn-dark"><i class="fa fa-search"></i></button>
            </form>

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fa fa-bars fs-4"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="favoris.php"><i class="fa fa-heart text-danger"></i> Favoris</a></li>
                    <li><a class="dropdown-item" href="historique.php"><i class="fa fa-clock"></i> Historique</a></li>
                    <li><a class="dropdown-item" href="mesReservations.php"><i class="fa fa-calendar"></i> Réservations</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <?php if (isset($_SESSION['IdUser'])): ?>
                        <li><a class="dropdown-item text-danger" href="deconnexion.php"><i class="fa fa-sign-out-alt"></i> Déconnexion</a></li>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="authentification.php"><i class="fa fa-sign-in-alt"></i> Se connecter</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>

    <div class="container mt-5" style="min-height: 70vh;">
        <h2 class="mb-4">Résultats pour : <span class="text-muted">"<?= htmlspecialchars($q ?? '') ?>"</span></h2>

        <?php if(empty($resultats)): ?>
            <div class="text-center mt-5">
                <i class="fa fa-search-minus fs-1 text-muted mb-3"></i>
                <p class="fs-4">Aucun résultat trouvé pour votre recherche 😢</p>
                <a href="acceuil.php" class="btn btn-outline-dark">Retour à l'accueil</a>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php foreach($resultats as $bien): ?>
                <div class="col-md-4 mb-4 reveal">
                    <div class="card shadow h-100">
                        <!-- IMAGE -->
                        <?php $img = getImage($connexion, $bien['IdBien']); ?>
                        <?php if($img): ?>
                            <img src="../commerciaux/<?= htmlspecialchars($img['url']) ?>" style="width:100%; height:220px; object-fit:cover;" alt="Bien">
                        <?php else: ?>
                            <img src="imageL/default.jpg" style="width:100%; height:220px; object-fit:cover;" alt="Défaut">
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <h5 class="fw-bold"><?= ($bien['Type']=='app') ? 'Appartement':'Villa'; ?></h5>
                            <p class="text-muted">
                                <i class="fa fa-map-marker-alt text-danger"></i> <?= htmlspecialchars($bien['Adresse']) ?><br>
                                <i class="fa fa-tag text-success"></i> <strong><?= number_format($bien['Prix_jour'], 0, ',', ' ') ?> FCFA</strong> / jour
                            </p>
                            
                            <div class="mt-auto">
                                <form action="details.php" method="POST">
                                    <input type="hidden" name="idBien" value="<?= $bien['IdBien'] ?>">
                                    <button type="submit" class="btn btn-dark w-100">Voir les détails</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- FOOTER (Identique à accueil.php) -->
    <footer class="bg-dark text-white text-center p-4 mt-5">
        <p class="mb-0">© 2026 Agence Immobilière - Tous droits réservés</p>
    </footer>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation Reveal au Scroll
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                if (elementTop < windowHeight - 100) {
                    reveals[i].classList.add("active");
                }
            }
        }
        window.addEventListener("scroll", reveal);
        reveal(); // Init
    </script>
</body>
</html>