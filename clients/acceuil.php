<?php
        session_start();
        require("bd.php");

        $sql = "SELECT * FROM bien_imm";
        $stmt = $connexion->prepare($sql);
        $stmt->execute();
        $biens = $stmt->fetchAll();

        function getImage($connexion, $id){
            $sql = "SELECT * FROM photos WHERE idBien = ? LIMIT 6";
            $stmt = $connexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetchAll();
        }

        function isFavori($connexion, $idBien){
            if(!isset($_SESSION['IdUser'])) return false;
        
            $sql = "SELECT * FROM favoris WHERE idBien=? AND idUser=?";
            $stmt = $connexion->prepare($sql);
            $stmt->execute([$idBien, $_SESSION['IdUser']]);
            return $stmt->rowCount() > 0;
        }
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Accueil agence immobilière</title>
        <link rel="icon"  href="../configuration/images/logoagence.jpeg">
        <link rel="stylesheet" href="../configuration/css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

        <style>
            /* Animation Hero Zoom */
            .hero {
                height: 700px;
                background-image: url("imageL/immobilier5.jpg");
                background-size: cover;
                background-position: center;
                position: relative;
                animation: zoomInHero 1.5s ease-out;
                overflow: hidden;
            }

            @keyframes zoomInHero {
                from { transform: scale(1.1); opacity: 0; }
                to { transform: scale(1); opacity: 1; }
            }

            .overlay {
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5);
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                text-align: center;
            }

            .overlay p span {
                font-size: 3.5rem;
                font-weight: bold;
                display: block;
                margin-bottom: 10px;
            }

            .overlay p {
                font-size: 2rem;
                backdrop-filter: blur(5px);
                padding: 20px;
            }

            /* Animation au défilement (Scroll Reveal) */
            .reveal {
                opacity: 0;
                transform: translateY(40px);
                transition: all 0.9s ease-out;
            }

            .reveal.active {
                opacity: 1;
                transform: translateY(0);
            }

            /* Amélioration des cartes */
            .card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border: none;
                border-radius: 15px;
                overflow: hidden;
            }

            .card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 30px rgba(0,0,0,0.2) !important;
            }

            header.navbar {
                z-index: 1000;
            }

            .btn-favori {
                transition: transform 0.2s ease;
            }

            .btn-favori:active {
                transform: scale(1.3);
            }
        </style>
    </head>

    <body>

        <header class="navbar bg-white shadow-sm px-4 sticky-top">
            <div class="container-fluid d-flex align-items-center justify-content-between">
                <div>
                    <img src="imageL/logoAgence.png" style="height:80px;" alt="Logo">
                </div>

                <form class="d-flex w-50" action="recherche.php" method="GET">
                    <input class="form-control me-2" type="search" name="q" placeholder="Rechercher un bien...">
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
                            <li>
                                <a class="dropdown-item text-danger" href="deconnexion.php">
                                    <i class="fa fa-sign-out-alt"></i> Déconnexion
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a class="dropdown-item" href="authentification.php">
                                    <i class="fa fa-sign-in-alt"></i> Se connecter/S'inscrire
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </header>

        <div class="hero">
            <div class="overlay">
                <p>
                    <span>Bienvenue sur notre agence immobilière</span>
                    Trouvez votre appartement ou villa idéale
                </p>
            </div>
        </div>

        <div class="container mt-5">
            <h2 class="text-center mb-5">Nos biens disponibles</h2>

            <div class="row">
                <?php foreach($biens as $bien): ?>
                    <div class="col-md-4 mb-4 reveal">
                        <div class="card shadow position-relative">
                            
                            <!-- BOUTON FAVORIS ❤️ -->
                            <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                <?php if (isset($_SESSION['IdUser'])): ?>
                                    <?php $isFav = isFavori($connexion, $bien['IdBien']); ?>
                                    <button class="btn btn-light rounded-circle shadow btn-favori" data-id="<?= $bien['IdBien'] ?>">
                                        <i class="fa fa-heart <?= $isFav ? 'text-danger' : 'text-secondary' ?>"></i>
                                    </button>
                                <?php else: ?>
                                    <a href="authentification.php" class="btn btn-light rounded-circle shadow">
                                        <i class="fa fa-heart text-secondary"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Image principale -->
                            <?php $images = getImage($connexion, $bien['IdBien']); ?>
                            <?php if (!empty($images)): ?>
                                <img src="../commerciaux/<?= htmlspecialchars($images[0]['url']) ?>"
                                     style="width:100%; height:240px; object-fit:cover;" alt="Bien">
                            <?php else: ?>
                                <img src="imageL/default.jpg"
                                     style="width:100%; height:240px; object-fit:cover;" alt="Défaut">
                            <?php endif; ?>

                            <!-- Infos + bouton -->
                            <div class="card-body text-center">
                                <h5 class="fw-bold mb-2"><?= $bien['titre'];?></h5>
                                <p class="mb-1 text-muted"><i class="fa fa-map-marker-alt text-danger"></i> <?= htmlspecialchars($bien['Adresse']) ?></p>
                                <p class="mb-3"><i class="fa fa-tag text-success"></i> <strong><?= number_format($bien['Prix_jour'], 0, ',', ' ') ?> FCFA</strong> / jour</p>
                                <form action="details.php" method="POST">
                                    <input type="hidden" name="idBien" value="<?= $bien['IdBien'] ?>">
                                    <button type="submit" class="btn btn-dark w-100">Voir les détails</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <footer class="bg-dark text-white text-center p-4 mt-5">
            <p class="mb-0">© 2026 Agence Immobilière - Tous droits réservés</p>
        </footer>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
            $(document).ready(function(){
                // Gestion AJAX des favoris
                $(".btn-favori").on("click", function(e){
                    e.preventDefault();
                    let btn = $(this);
                    let idBien = btn.data("id");

                    $.get("favoris_action.php?id=" + idBien, function(response){
                        let icon = btn.find("i");
                        icon.toggleClass("text-danger text-secondary");
                    });
                });

                // Fonction Reveal au Scroll
                function reveal() {
                    var reveals = document.querySelectorAll(".reveal");
                    for (var i = 0; i < reveals.length; i++) {
                        var windowHeight = window.innerHeight;
                        var elementTop = reveals[i].getBoundingClientRect().top;
                        var elementVisible = 100;
                        if (elementTop < windowHeight - elementVisible) {
                            reveals[i].classList.add("active");
                        }
                    }
                }

                window.addEventListener("scroll", reveal);
                reveal(); // Lancement initial
            });
        </script>
    </body>
</html>