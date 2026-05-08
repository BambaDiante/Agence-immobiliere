<?php
   session_start();
   require_once "../configuration/connexion.php";
   $id=$_SESSION['id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon"  href="../configuration/images/logoagence.jpeg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=search" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=logout" />
    <link rel="stylesheet" href="../configuration/css/bootstrap.min.css">
    <title>Gestion des locations de mes biens</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body{
            background-color: #c9d6ff;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            background-attachment:fixed;
            width:100%;
            min-height: 100vh;
            text-align:center;
            display: flex;
            flex-direction: column;
        }
        header {
            display: flex;
            flex-direction: row;
            align-items: center;
            width: 100%;
            position: fixed; /* Fixe le header en haut */
            top: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.9); /* Optionnel: fond semi-transparent */
            backdrop-filter: blur(5px);           /* Optionnel: effet de flou */
            z-index: 3000;
            padding: 10px 0;
        }
        .menu {
            order: 3; /* Place le menu en dernier */
            cursor: pointer;
        }

        #search-bar {
            order: 2; /* Place la barre de recherche au milieu */
            margin: auto;
            display:flex;
            flex-direction:row;
            
        }
           /* Container de la barre */
        .search-container {
            order: 2;
            margin: 0 auto;
            display: flex;
            align-items: center;
            background: #f8f9fa; /* Gris très clair */
            border: 1px solid #ddd;
            border-radius: 50px; /* Bordure très arrondie */
            padding: 5px 15px;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 500px; /* Largeur max pour ne pas envahir l'écran */
        }

        /* Effet au survol/focus de la barre entière */
        .search-container:focus-within {
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: #512da8; /* Couleur de ton thème */
        }

        /* Le champ de texte */
        .search-container input {
            border: none;
            background: transparent;
            outline: none;
            padding: 10px;
            width: 100%;
            font-size: 0.95rem;
            color: #333;
        }

        /* Le bouton loupe */
        #search-button {
            background: #512da8; /* Couleur violette pro */
            color: white;
            border: none;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.2s ease;
            flex-shrink: 0; /* Empêche le bouton de s'écraser */
        }

        #search-button:hover {
            background: #311b92;
            transform: scale(1.1); /* Petit effet de zoom au survol */
        }

        #search-button .material-symbols-outlined {
            font-size: 20px;
        }

        #logo {
            order: 1; /* Garde le logo au début */
        }
        #logo{
            height:50px;
            width:50px;
            border-radius:50%;
            margin-top:10px;
            margin-left:10px;
        }
        
        .sidebar{
            margin-right:100px;
        }
        nav{
            position:fixed;
            top:0;
            right:-280px;
            width:280px;
            height:100%;
            background:#fff;
            z-index:1001;
            transition:right 0.3s ease;
            padding-top:60px;
            overflow-y:auto;
            box-shadow:-2px 0 8px rgba(0,0,0,0.1);
        }
        nav#contenu h2{
            padding:20px;
            border-bottom:1px solid #eee;
        }
        nav#contenu ul{
            list-style:none;
        }
        nav#contenu li{
            padding:15px 20px;
            border-bottom:1px solid #eee;
        }
        nav#contenu a{
            text-decoration:none;
            color:#333;
            font-weight:500;
        }
        nav#contenu a:hover{
            color:#512da8;
        }
        .menu span {
            display: block;
            height: 4px;
            background:#333;
            margin: 5px 0;
            width:35px;
            transition:0.3s;
            transform-origin:center;
            border-radius:4px;
        }
        .menu {
            order: 3;
            cursor: pointer;
            position: relative;
            z-index: 2000; /* Priorité maximale */
            width: 35px;
            margin-right: 20px;
        }

        .menu span {
            display: block;
            height: 4px;
            width: 100%;
            background: #333;
            margin: 6px 0;
            transition: 0.4s;
            border-radius: 2px;
        }

        /* Transformation en croix */
        .menu.active span:nth-child(1) {
            transform: translateY(10px) rotate(45deg);
        }

        .menu.active span:nth-child(2) {
            opacity: 0;
        }

        .menu.active span:nth-child(3) {
            transform: translateY(-10px) rotate(-45deg);
        }
        .menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 999;
            backdrop-filter: blur(3px);
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .container{
            margin-top:100px;
            flex: 1 0 auto;
        }
        footer {
            margin-top: auto;
            width: 100%;
        }
        /* Pour l'élément de liste */
        .logout-item {
            margin-top: 80%; /* Espace raisonnable après le dernier lien */
            padding: 0 20px;
            border-bottom: none !important; /* Enlever la ligne grise sous le bouton */
        }

        /* Le bouton lui-même */
        #disconnect-btn {
            display: flex;          /* Utilise flexbox pour l'alignement */
            align-items: center;    /* Centre l'icône et le texte verticalement */
            justify-content: center;/* Centre le contenu horizontalement */
            background: #311b92;
            color: white !important;
            padding: 12px;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        /* Optionnel : Ajuster la taille de l'icône spécifiquement dans le bouton */
        #disconnect-btn .material-symbols-outlined {
            font-size: 20px;
        }
        .val{
            background:#157347;
            color:white;
        }
        .anu{
            background:#bb2d3b;
            color:white;
        }
        .locations-container {
            width: 100%;
            max-width: 1300px;
            margin: 25px auto 0;
        }

        .location-col {
            margin-bottom: 1.5rem;
        }

        .location-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .location-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2) !important;
        }

        .image-section {
            width: 100%;
            height: 240px;
            background: #f5f5f5;
            overflow: hidden;
        }

        .carousel,
        .carousel-inner,
        .carousel-item {
            height: 100%;
        }

        .property-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background: #f5f5f5;
        }

        .info-section {
            padding: 1rem;
            text-align: left;
        }

        .top-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.8rem;
            gap: 0.5rem;
        }

        .top-info h2 {
            font-size: 1.1rem;
            margin: 0;
            color: #222;
            font-weight: 700;
        }

        .status {
            padding: 5px 11px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .validated {
            background: #d1e7dd;
            color: #0f5132;
        }

        .client-block p {
            margin: 0.35rem 0;
            color: #444;
            font-size: 0.95rem;
        }

        .action-section {
            margin-top: 1rem;
        }

        .action-btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
        }

        .validate-btn {
            background: #198754;
            color: white;
        }

        .validate-btn:hover {
            background: #157347;
        }

        .cancel-btn {
            background: #dc3545;
            color: white;
        }

        .cancel-btn:hover {
            background: #bb2d3b;
        }

        .empty-locations {
            width: min(100%, 820px);
            margin: 30px auto 10px;
            padding: 34px 24px;
            border-radius: 20px;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.98), rgba(241, 236, 255, 0.95));
            border: 1px solid rgba(81, 45, 168, 0.16);
            box-shadow: 0 16px 36px rgba(17, 12, 46, 0.12);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .empty-locations::before {
            content: "";
            position: absolute;
            width: 170px;
            height: 170px;
            border-radius: 50%;
            top: -70px;
            right: -55px;
            background: radial-gradient(circle, rgba(81, 45, 168, 0.16), rgba(81, 45, 168, 0));
        }

        .empty-locations::after {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            bottom: -65px;
            left: -55px;
            background: radial-gradient(circle, rgba(25, 135, 84, 0.14), rgba(25, 135, 84, 0));
        }

        .empty-icon {
            width: 74px;
            height: 74px;
            margin: 0 auto 14px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 700;
            color: #311b92;
            background: rgba(81, 45, 168, 0.12);
            border: 1px solid rgba(81, 45, 168, 0.26);
            position: relative;
            z-index: 1;
        }

        .empty-locations h2 {
            margin: 0 0 10px;
            color: #1f1f2e;
            font-size: clamp(1.35rem, 2.6vw, 1.95rem);
            position: relative;
            z-index: 1;
        }

        .empty-locations p {
            margin: 0 auto 22px;
            width: min(100%, 580px);
            color: #4c4f69;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .empty-locations .btn-empty {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #512da8, #311b92);
            box-shadow: 0 10px 22px rgba(49, 27, 146, 0.3);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            z-index: 1;
        }

        .empty-locations .btn-empty:hover {
            transform: translateY(-2px);
            color: #fff;
            box-shadow: 0 14px 28px rgba(49, 27, 146, 0.38);
        }

        @media (max-width: 500px) {
            .top-info {
                flex-direction: column;
                align-items: flex-start;
            }

            .empty-locations {
                padding: 28px 16px;
                border-radius: 16px;
            }

            .empty-icon {
                width: 64px;
                height: 64px;
                font-size: 26px;
            }
        }
        
    </style>
</head>
<body>
    <header>
        <a href="acceuil.php"><img src="../configuration/images/logoagence.jpeg" id="logo" alt="logo"></a>
        <form action="rlocation.php" method="POST" id="search-bar" class="search-container">
            <input type="search" name="barre" placeholder="Rechercher une location" aria-label="Search">
            <button type="submit" id="search-button">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
        <div class="menu" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

    <nav id="contenu">
        <h2>Menu</h2>
        <ul>
            <li><a href="ajout.php">Ajouter un bien</a></li>
            <li><a href="consult.php">Gestion de mes biens</a></li>
            <li><a href="gestionclient.php">Gestion des clients</a></li>
            <li><a href="gestionloc.php">Gestion des locations</a></li>
            <li class="logout-item">
                <a href="disconnect.php" id="disconnect-btn">
                    <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px;">
                        logout
                    </span>
                    Se déconnecter
                </a>
            </li>
        </ul>
    </nav>
    <div class="menu-overlay" id="overlay" onclick="closeMenu()"></div>
    <div class="container">
        <h1>Gestion des locations</h1>
        <?php
        $loc="SELECT l.idLoc,
             l.idBien,
             l.duree,
             l.dateDebut,
             l.is_validated,
             l.prix,
             u.nom,
             Paie.mode
      FROM location l
      JOIN users u ON u.idUser = l.idUser
      JOIN bien_imm b ON b.IdBien = l.idBien
      JOIN paiement Paie ON l.idLoc = Paie.idLoc
      WHERE b.idUser = :idUser";
        $location=$pdo->prepare($loc);
        $location->execute([
            ":idUser"=>$id
        ]);
        $locs=$location->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php if (empty($locs)) { ?>
        <div class="empty-locations">
            <div class="empty-icon">0</div>
            <h2>Aucune location a gerer pour le moment</h2>
            <p>
                Vos biens sont bien en place, mais aucune reservation n'a encore ete enregistree.
                Des qu'un client reserve, la location apparaitra ici avec tous les details.
            </p>
            <a href="consult.php" class="btn-empty">Voir mes biens</a>
        </div>
        <?php } else { ?>
        <div class="locations-container row">
    <?php
    foreach($locs as $l){
    ?>
            <?php
$reqPhotos = $pdo->prepare("SELECT url FROM photos WHERE idBien = :idBien");
$reqPhotos->execute([
    ":idBien" => $l['idBien']
]);

$photos = $reqPhotos->fetchAll(PDO::FETCH_ASSOC);
?>
        <div class="col-md-6 col-lg-4 location-col">
            <div class="card shadow h-100 location-card">

            <div class="image-section">

    <div id="carousel<?= $l['idLoc'] ?>" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-inner">

            <?php
            foreach($photos as $index => $photo){
            ?>
                <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                    <img src="<?= $photo['url'] ?>" class="property-image">
                </div>
            <?php
            }
            ?>

        </div>

        <?php
        if(count($photos) > 1){
        ?>
            <button class="carousel-control-prev"
                    type="button"
                    data-bs-target="#carousel<?= $l['idLoc'] ?>"
                    data-bs-slide="prev">

                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next"
                    type="button"
                    data-bs-target="#carousel<?= $l['idLoc'] ?>"
                    data-bs-slide="next">

                <span class="carousel-control-next-icon"></span>
            </button>
        <?php
        }
        ?>

    </div>

</div>

            <div class="info-section card-body">

                <div class="top-info">
                    <h2>Location #<?= $l['idLoc'] ?></h2>

                    <?php
                    if($l['is_validated']==0){
                        echo "<span class='status pending'>En attente</span>";
                    }
                    else{
                        echo "<span class='status validated'>Validée</span>";
                    }
                    ?>
                </div>

                <div class="client-block">
                    <p><strong>Client :</strong> <?= $l['nom'] ?></p>
                    <p><strong>Durée :</strong> <?= $l['duree'] ?> jours</p>
                    <p><strong>Date début :</strong> <?= $l['dateDebut'] ?></p>
                    <p><strong>Prix :</strong> <?= $l['prix'] ?> FCFA</p>
                    <p><strong>Paiement :</strong> <?= $l['mode'] ?></p>
                </div>

                <div class="action-section">

                    <?php
                    if($l['is_validated']==0){
                    ?>
                        <form method="POST" action="validate.php">
                            <input type="hidden" name="id" value="<?= $l['idLoc'] ?>">
                            <button type="submit" class="action-btn validate-btn" name="val">
                                Valider
                            </button>
                        </form>
                    <?php
                    }
                    else{
                    ?>
                        <form method="POST" action="validate.php">
                            <input type="hidden" name="id" value="<?= $l['idLoc'] ?>">
                            <button type="submit" class="action-btn cancel-btn" name="anu">
                                Annuler
                            </button>
                        </form>
                    <?php
                    }
                    ?>

                </div>

            </div>

        </div>
        </div>
    <?php
    }
    ?>
</div>
        <?php } ?>
    </div>
    <footer class="bg-dark text-white text-center p-3">
        <p>© 2026 Agence Immobilière - Tous droits réservés</p>
    </footer>
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
       
        function toggleMenu() {
            const menuPanel = document.getElementById("contenu");
            const overlay = document.getElementById("overlay");
            const burger = document.querySelector(".menu");
            
            if (menuPanel.style.right === "0px") {
                closeMenu();
            } else {
                menuPanel.style.right = "0px";
                overlay.style.display = "block";
                burger.classList.add("active");
            }
        }
        function closeMenu() {
            const menuPanel = document.getElementById("contenu");
            const overlay = document.getElementById("overlay");
            const burger = document.querySelector(".menu");

            menuPanel.style.right = "-280px";
            overlay.style.display = "none";
            burger.classList.remove("active");
        }

        // Fermer le menu en cliquant sur l'overlay
        document.getElementById('overlay').addEventListener('click', closeMenu);

        // Fermer avec la touche Échap
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeMenu();
            }
        });
        // Fermer le menu en cliquant sur un élément (optionnel)
        document.querySelectorAll('#submenu span').forEach(item => {
            item.addEventListener('click', function() {
                // Ici tu pourrais ajouter la logique pour charger le contenu
                // sans fermer le menu
                console.log('Chargement du contenu pour:', this.textContent);
            });
        });
</script>
</html>