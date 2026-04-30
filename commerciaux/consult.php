<?php
   session_start();
   require_once "../configuration/connexion.php";
   $id=$_SESSION['id'];
   $delete_message = "";
   $delete_error = false;

   // Traitement de la suppression
   if (isset($_POST['delete_idbien'])) {
       // Vérifier d'abord le statut du bien
       $check = $pdo->prepare("SELECT statut FROM bien_imm WHERE IdBien = :IdBien");
       $check->execute([":IdBien" => $_POST['delete_idbien']]);
       $bien = $check->fetch(PDO::FETCH_ASSOC);
       
       if ($bien && $bien['statut'] === 'libre') {
           // Le bien est libre, on peut le supprimer
           // Supprimer d'abord les photos associées au bien
           $delete_photos = $pdo->prepare("DELETE FROM photos WHERE idBien = :idBien");
           $delete_photos->execute([":idBien" => $_POST['delete_idbien']]);
           
           // Ensuite supprimer le bien lui-même
           $delete_bien = $pdo->prepare("DELETE FROM bien_imm WHERE IdBien = :IdBien");
           $delete_bien->execute([":IdBien" => $_POST['delete_idbien']]);
           
           $delete_message = "Bien supprimé avec succès !";
       } else if ($bien && $bien['statut'] !== 'libre') {
           // Le bien est réservé ou en location
           $delete_message = "❌ Impossible de supprimer ce bien car il est actuellement en réservation ou en location.";
           $delete_error = true;
       } else {
           $delete_message = "❌ Bien introuvable.";
           $delete_error = true;
       }
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon"  href="../configuration/images/logoagence.jpeg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=search" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=logout" />
    <link rel="stylesheet" href="../configuration/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Gestion de mes biens</title>
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
        .bloc{
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
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
        table{
            border-collapse:collapse;
            margin:40px auto;
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
        #boutons{
            display:flex;
            border:none;
        }
        input[type="submit"]{
            margin:5px;
            padding:15px;
            border:1px solid black;
            border-radius:15px;
            color:white;
        }
        #modif{
            background:#157347;
        }
        #dele{
            background:#bb2d3b;
        }
        .container{
            margin-top:100px;
            flex:1;
        }

        .delete-message {
            margin: 20px auto;
            padding: 15px;
            border-radius: 8px;
            max-width: 98%;
            font-weight: 500;
            animation: slideIn 0.3s ease;
        }

        .delete-message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .delete-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        footer {
            margin-top: auto;
            width: 100%;
            bottom:0;
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
    </style>
</head>
<body>
    <header>
        <a href="acceuil.php"><img src="../configuration/images/logoagence.jpeg" id="logo" alt="logo"></a>
        
        <form action="" method="POST" id="search-bar" class="search-container">
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

    <?php
        $select="SELECT B.*, (SELECT url FROM photos WHERE idBien = B.IdBien LIMIT 1) as url FROM bien_imm B WHERE B.idUser=:idUser";
        $recup=$pdo->prepare($select);
        $recup->execute([
        ":idUser"=>$_SESSION['id'],
        ]);
        $bieninfo=$recup->fetchALL(PDO::FETCH_ASSOC);
    ?>
    <div class="container">
        <h1>Gestion de mes biens</h1>
        
        <?php if ($delete_message): ?>
            <div class="delete-message <?php echo $delete_error ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($delete_message); ?>
            </div>
        <?php endif; ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Titre</th>
                <th>Superficie(m²)</th>
                <th>Adresse</th>
                <th>Prix journalier</th>
                <th>Nombre de pieces</th>
                <th>Action</th>
            </tr>
            <?php
                foreach($bieninfo as $info){
                    echo "<tr>";
                    echo "<th>".$info['IdBien']."</th>";
                    if($info['IdBien']=="app"){
                        echo "<th>Appartement</th>";
                    }
                    else{
                        echo "<th>Villa</th>";
                    }
                    echo "<th>".$info['titre']."</th>";
                    echo "<th>".$info['Superficie']."</th>";
                    echo "<th>".$info['Adresse']."</th>";
                    echo "<th>".$info['Prix_jour']."</th>";
                    echo "<th>".$info['nbre_pieces']."</th>";
                    echo "<th id='boutons'>";
                        echo "<form method='POST' action='setbien.php'>";
                        echo "<input type='hidden' name='id' value=".$info['IdBien'].">";
                        echo "<input type='hidden' name='titre' value=".$info['titre'].">";
                        echo "<input type='hidden' name='sup' value=".$info['Superficie'].">";
                        echo "<input type='hidden' name='adr' value=".$info['Adresse'].">";
                        echo "<input type='hidden' name='prix' value=".$info['Prix_jour'].">";
                        echo "<input type='hidden' name='nbre' value=".$info['nbre_pieces'].">";  
                        echo "<input type='submit' id='modif' value='Modifer'>";
                        echo "</form>";

                        echo "<form method='POST' action=''>";
                        echo "<input type='hidden' name='delete_idbien' value=".$info['IdBien'].">";
                        echo "<input type='submit' id='dele' value='Supprimer' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce bien ?\");'>";
                        echo "</form>";
                    echo "</th>";
                    echo "</tr>";          
                    

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