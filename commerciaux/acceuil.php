<?php
    session_start();

    if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true) {
        header("Location:authentification.php");
        exit;
    }
    require_once("../configuration/connexion.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=search" />
    <link rel="stylesheet" href="../configuration/css/bootstrap.min.css">
    <link rel="icon"  href="../configuration/images/logoagence.jpeg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=logout" />
    <link rel="stylesheet" href="style.css">
    <title>Page d'acceuil</title>
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
            display:flex;
            flex-direction:column;
        }
        .hero{
            position: relative;
            width: 95%; 
            margin: 20px auto;
        }

        #fond{
            width: 95%;
            height: 90vh;
            border-radius: 15px;
        }
        #mess{
            position: absolute;
            top: 20%;
            left: 50%;
            font-weight:bold;
            transform: translate(-50%, -50%);  
            color: white;
            font-size: 2.5rem;
            padding: 10px 20px;
            color:black;
            border-radius: 10px;
            white-space: nowrap;
            overflow: hidden;
            max-width: 0;
            animation: typing 2s steps(30) forwards;
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

        /* Ajoutez une marge au corps de la page pour ne pas que le contenu passe sous le header fixe */
        body {
            padding-top: 70px; 
        }
        @keyframes typing{
            from {
                max-width: 0;
            }
            to {
                max-width: 100%;
            }
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
        
        .container{
            background:#fff;
            display:inline-block;
            align-items:center;
            justify-content:center;
            border-radius:15px;
            width:min(90%, 900px);
            min-height:500px;
            margin:24px auto;
            padding:20px;
            box-shadow:0 10px 30px rgba(0,0,0,0.12);
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
        .bloc{
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            flex:1;
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
        #disconnect{
            margin-top:100%;
            background:#311b92;
            padding:10px;
            border-radius:15px;
            color:white;
        }
        .deconnect{
            color:white;
        }
        .card{
            margin:2px;
        }
        .card-img-top{
            margin-top:10px;
        }
        .list-group-item,.list-group{
            border:none;
        }
        .card-body,.card-text{
            /* border-bottom:none;
            border-top:none; */
            border:none;
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
        /* Force les images à avoir la même hauteur et à remplir l'espace proprement */
        .card-img-top {
            height: 200px; /* Ajustez cette valeur selon votre préférence */
            object-fit: cover; /* Recadre l'image pour remplir le cadre sans la déformer */
        }

        /* Assure que toutes les cartes d'une même ligne s'étirent sur toute la hauteur */
        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .card {
            display: flex;
            flex-direction: column;
            height: 100%; /* Force la carte à prendre 100% de la hauteur du parent .col */
        }

        /* Pousse le bouton vers le bas si le texte est plus court sur certaines cartes */
        .card-body:last-child {
            margin-top: auto;
        }
        /* --- Nouvelles Animations --- */

/* 1. Animation Hero Zoom au chargement */
.hero {
    overflow: hidden; /* Important pour que le zoom ne dépasse pas */
}

#fond {
    width: 95%;
    height: 90vh;
    border-radius: 15px;
    object-fit: cover;
    /* Animation de zoom */
    animation: zoomInHero 1.5s ease-out;
}

@keyframes zoomInHero {
    from { transform: scale(1.1); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* 2. Scroll Reveal (Apparition au défilement) */
.reveal {
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.9s ease-out;
}

.reveal.active {
    opacity: 1;
    transform: translateY(0);
}

/* 3. Effet de survol sur les cartes */
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease !important;
    border: none;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2) !important;
}

/* Limitation description à 2 lignes */
.description-preview {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
    font-size: 0.95rem;
}

        .empty-state {
            width: min(100%, 760px);
            margin: 30px auto;
            padding: 36px 28px;
            border-radius: 22px;
            background: linear-gradient(145deg, rgba(255,255,255,0.98), rgba(243,239,255,0.95));
            border: 1px solid rgba(81, 45, 168, 0.16);
            box-shadow: 0 20px 45px rgba(17, 12, 46, 0.14);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .empty-state::before {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            top: -70px;
            right: -60px;
            background: radial-gradient(circle, rgba(81, 45, 168, 0.18), rgba(81, 45, 168, 0));
        }

        .empty-state::after {
            content: "";
            position: absolute;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            bottom: -70px;
            left: -60px;
            background: radial-gradient(circle, rgba(25, 135, 84, 0.18), rgba(25, 135, 84, 0));
        }

        .empty-state-icon {
            width: 78px;
            height: 78px;
            margin: 0 auto 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            font-weight: 700;
            color: #311b92;
            background: rgba(81, 45, 168, 0.12);
            border: 1px solid rgba(81, 45, 168, 0.25);
            position: relative;
            z-index: 1;
        }

        .empty-state h2 {
            margin: 0 0 10px;
            font-size: clamp(1.4rem, 3vw, 2rem);
            color: #1f1f2e;
            position: relative;
            z-index: 1;
        }

        .empty-state p {
            margin: 0 auto 24px;
            width: min(100%, 560px);
            color: #4c4f69;
            font-size: 1rem;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .empty-state .btn-add {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 12px;
            background: linear-gradient(135deg, #512da8, #311b92);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 10px 24px rgba(49, 27, 146, 0.32);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            z-index: 1;
        }

        .empty-state .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(49, 27, 146, 0.4);
            color: #fff;
        }

        @media (max-width: 576px) {
            .empty-state {
                padding: 28px 18px;
                border-radius: 16px;
            }

            .empty-state-icon {
                width: 66px;
                height: 66px;
                font-size: 28px;
            }
        }
        
        
    </style>
</head>
<body>

    <header>
        <a href="acceuil.php"><img src="../configuration/images/logoagence.jpeg" id="logo" alt="logo"></a>
        
        <form action="racceuil.php" method="POST" id="search-bar" class="search-container">
            <input type="search" name="barre" placeholder="Rechercher une villa, un appartement..." aria-label="Search">
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
    <div class="bloc">
        <div class="hero">
            <img src="../configuration/images/villa.jpg" id="fond" alt="Image de fonds">
            <?php
                echo "<h1 id='mess'>Bienvenue ".$_SESSION['nom']."</h1>";
            ?>
        </div>
        <div class="container">
        <?php
           $select="SELECT B.*, (SELECT url FROM photos WHERE idBien = B.IdBien LIMIT 1) as url FROM bien_imm B WHERE B.idUser=:idUser";
           $recup=$pdo->prepare($select);
           $recup->execute([
            ":idUser"=>$_SESSION['id'],
           ]);
           $bieninfo=$recup->fetchALL(PDO::FETCH_ASSOC);
           if(!empty($bieninfo)){
            echo "<div class='row'>";
            foreach($bieninfo as $info){
                // On utilise col-lg-4 col-md-6 pour un meilleur rendu responsive
                echo "<div class='col-lg-4 col-md-6 mb-4 d-flex align-items-stretch reveal'>";
                    echo "<div class='card h-100 w-100'>"; // h-100 force la hauteur égale
                        echo "<img src='".$info['url']."' class='card-img-top' alt='Image du bien'>";         
                        echo "<div class='card-body d-flex flex-column'>"; // flex-column pour aligner le bouton en bas
                            echo '<h5 class="card-title">'.$info['titre'].'</h5>';
                            echo '<p class="card-text description-preview">'.$info['Description'].'</p>';
                            
                            echo '<ul class="list-group list-group-flush mt-auto">'; // mt-auto pousse la liste vers le bas
                                echo '<li class="list-group-item"><strong>Adresse:</strong> '.$info['Adresse'].'</li>';
                                echo '<li class="list-group-item text-primary"><strong>Prix:</strong> '.$info['Prix_jour'].' FCFA</li>';
                            echo '</ul>';
                            
                            echo '<div class="mt-3">';
                            echo "<form method='POST' action='detailsbien.php'>";
                                echo "<input type='hidden' name='IdBien' value='".$info['IdBien']."'>";
                                echo "<input type='submit' class='btn btn-primary w-100' value='Voir les détails'>";
                            echo "</form>";
                            echo '</div>';
                        echo '</div>';
                    echo "</div>";
                echo "</div>";
            }
            echo '</div>';   

           }
           else{
            echo '<div class="empty-state">';
            echo '  <div class="empty-state-icon">+</div>';
            echo '  <h2>Vous n\'avez pas encore de bien</h2>';
            echo '  <p>Commencez par ajouter votre premier bien pour le mettre en location et le gérer facilement depuis votre espace commercial.</p>';
            echo '  <a href="ajout.php" class="btn-add">Ajouter mon premier bien</a>';
            echo '</div>';
           }


        ?>

        </div>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2026 Agence Immobilière - Tous droits réservés</p>
    </footer>
    
</body>
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
        // Fonction pour détecter le défilement et révéler les éléments
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 100; // Distance avant que l'élément n'apparaisse

                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        }

        // Écouteur d'événement sur le scroll
        window.addEventListener("scroll", reveal);

        // Lancer une fois au chargement pour afficher les éléments déjà visibles
        document.addEventListener("DOMContentLoaded", reveal);
</script>
</html>