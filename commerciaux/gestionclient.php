<?php
   session_start();
   require_once "../configuration/connexion.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=search" />
    <title>Gestion des clients</title>
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
            display: flex; /* Active Flexbox sur le corps de la page */
            flex-direction: column;
        }
        header{
            display:flex;
            flex-direction:row;
            align-items:center;
            width:100%;

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
        #search{
            border-radius:15px;
            margin-right:5px;
            width:200px;
            outline:none;
            border:none;
            padding:5px;

        }
        #search-button{
            height:30px;
            border-radius:50%;
            border:none;
            width:30px;
            
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
        .bloc{
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
        }
        .menu {
            width:28px;
            padding:8px;
            margin-right:30px;
            z-index: 1200;
        }
        .menu.active span:nth-child(1){
            transform: translateY(9px) rotate(45deg);
        }
        .menu.active span:nth-child(2){
            opacity:0;
        }
        .menu.active span:nth-child(3){
            transform: translateY(-9px) rotate(-45deg);
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
        footer {
            margin-top: auto;
            width: 100%;
            padding: 1.5rem;
            background:#2c2d2d;
            backdrop-filter: blur(10px); /* Effet de flou */
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            color:white; /* Utilisation de la couleur de vos liens au survol */
            font-size: 0.9rem;
            font-weight: 500;
        }
        table{
            border-collapse:collapse;
            margin:auto;
            border:1px solid black;
            font-weight:300;
            max-width:98%;
                      
        }
        tr,th{
            border:1px solid black;
            font-weight:300;
            margin:15px;
            padding:15px;
            border-radius:15px;  
        }
    </style>
</head>
<body>
     <header>
        <a href="acceuil.php"><img src="../configuration/images/logoagence.jpeg" id="logo" alt="logo"></a>
        <form action="" method="POST" id="search-bar">
            <input type="search" id="search" name="barre" placeholder="Rechercher un bien immobilier">
            <button type="submit" id="search-button">
                <span class="material-symbols-outlined">
                    search
                </span>
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
            <button type="submit" id="disconnect"><a href="disconnect.php" class="deconnect">Se deconnecter</a></button>
        </ul>
    </nav>
    <div class="menu-overlay" id="overlay" onclick="closeMenu()"></div>
    
    <div class="container">
        <h1>Page de gestion des clients</h1>
        <?php
           $clients="SELECT * FROM users WHERE type <> 'Commercial'";
           $client=$pdo->prepare($clients);
           $client->execute();
           $users=$client->fetchAll(PDO::FETCH_ASSOC)
        ?>
        <table>
            <tr>
                <th>Nom</th>
                <th>Date de Naissance</th>
                <th>Adresse</th>
                <th>Mail</th>
                <th>Status</th>
            </tr>
            <?php
               foreach($users as $us){
                    echo "<tr>";
                    echo "<th>".$us['nom']."</th>";
                    echo "<th>".$us['date']."</th>";
                    echo "<th>".$us['adresse']."</th>";
                    echo "<th>".$us['mail']."</th>";
                    if($us['is_activated']==1){
                        echo "<th>Active</th>";
                    }
                    else{
                        echo "<th>Desactive</th>";
                    }
                    echo "<tr>";
               }
            ?>
        </table>
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

</script>
</html>