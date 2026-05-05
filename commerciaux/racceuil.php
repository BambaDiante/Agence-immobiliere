<?php
    session_start();

    // Protection de la page
    if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true) {
        header("Location:authentification.php");
        exit;
    }

    require_once("../configuration/connexion.php");

    // Traitement de la recherche
    $resultats = [];
    $recherche = "";

    if (isset($_POST['barre'])) {
        $recherche = $_POST['barre'];
        $select = "SELECT B.*, (SELECT url FROM photos WHERE idBien = B.IdBien LIMIT 1) as url 
                   FROM bien_imm B 
                   WHERE B.idUser = :idUser 
                   AND (B.titre LIKE :search OR B.Description LIKE :search OR B.Adresse LIKE :search)";
        
        $result = $pdo->prepare($select);
        $result->execute([
            ":idUser" => $_SESSION['id'],
            ":search" => "%" . $recherche . "%"
        ]);
        $resultats = $result->fetchAll(PDO::FETCH_ASSOC);
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- On réutilise tous tes liens CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=search,logout" />
    <link rel="stylesheet" href="../configuration/css/bootstrap.min.css">
    <link rel="icon" href="../configuration/images/logoagence.jpeg">
    <link rel="stylesheet" href="style.css">
    <title>Résultats de recherche - Agence</title>
    
    <!-- On injecte ton style personnalisé (tu peux aussi le mettre dans style.css pour éviter la répétition) -->
    <style>
        /* [Copie ici le CSS de ta page accueil pour garder l'aspect identique] */
        /* Note : Pour gagner de la place, j'ai omis le bloc CSS identique, 
           mais il est crucial de garder les styles du header, menu et container. */
        *{ 
            margin: 0;
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Montserrat', 
            sans-serif; 
        }
        body{ 
            background: linear-gradient(to right, #e2e2e2, #c9d6ff); 
            min-height: 100vh; padding-top: 80px; 
        }
        header { 
            display: flex; 
            align-items: center; 
            width: 100%; 
            position: fixed; 
            top: 0; 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(5px); 
            z-index: 3000; 
            padding: 10px 0; 
        }
        #logo { 
            height:50px; 
            width:50px; 
            border-radius:50%; 
            margin-left:15px; 
        }
        .search-container { 
            margin: 0 auto; 
            display: flex; 
            background: #f8f9fa; 
            border: 1px solid #ddd; 
            border-radius: 50px; 
            padding: 5px 15px; 
            width: 100%; 
            max-width: 500px; 
        }
        .search-container input { 
            border: none; 
            background: transparent; 
            outline: none; 
            padding: 10px; 
            width: 100%; 
        }
        #search-button { 
            background: #512da8; 
            color: white; 
            border: none; 
            border-radius: 50%; 
            width: 38px; 
            height: 38px; 
            cursor: pointer; 
        }
        .container{ 
            background:#fff; 
            border-radius:15px; 
            width:min(95%, 1100px); 
            margin:20px auto; 
            padding:30px; 
            box-shadow:0 10px 30px rgba(0,0,0,0.1); 
        }
        .card { 
            transition: transform 0.3s; 
            margin-bottom: 20px; 
            border: none; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.05); 
        }
        .card:hover { 
            transform: translateY(-5px); 
        }
        .card-img-top { 
            height: 200px; 
            object-fit: cover; 
            border-radius: 10px 10px 0 0; 
        }
        /* Style du menu latéral identique à l'accueil */
        nav#contenu { 
            position:fixed; 
            top:0; right:-280px; 
            width:280px; 
            height:100%; 
            background:#fff; 
            z-index:1001; 
            transition:right 0.3s; 
            padding-top:60px; 
            box-shadow:-2px 0 8px rgba(0,0,0,0.1); 
        }
        .menu { 
            cursor: pointer; 
            margin-right: 20px; 
            z-index: 2000; 
            width: 35px; 
        }
        .menu span { 
            display: block; 
            height: 4px; 
            background:#333; 
            margin: 6px 0; 
            border-radius:2px; 
        }
        .menu-overlay { 
            display: none; 
            position: fixed; 
            inset: 0; 
            background: rgba(0,0,0,0.7); z-index: 999; 
        }
        #disconnect-btn { 
            background: #311b92; 
            color: white !important; 
            padding: 12px; 
            border-radius: 10px; 
            text-decoration: none; 
            font-weight: bold; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }
    </style>
</head>
<body>

    <header>
        <a href="acceuil.php"><img src="../configuration/images/logoagence.jpeg" id="logo" alt="logo"></a>
        <form action="racceuil.php" method="POST" class="search-container">
            <input type="search" name="barre" value="<?= htmlspecialchars($recherche) ?>" placeholder="Rechercher..." aria-label="Search">
            <button type="submit" id="search-button">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
        <div class="menu" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </div>
    </header>

    <!-- Menu latéral (identique à l'accueil) -->
    <nav id="contenu">
        <h2 class="p-3">Menu</h2>
        <ul class="list-unstyled">
            <li class="p-3 border-bottom"><a href="ajout.php" class="text-decoration-none text-dark">Ajouter un bien</a></li>
            <li class="p-3 border-bottom"><a href="consult.php" class="text-decoration-none text-dark">Gestion de mes biens</a></li>
            <li class="p-3 border-bottom"><a href="gestionclient.php" class="text-decoration-none text-dark">Gestion des clients</a></li>
            <li class="p-3 border-bottom"><a href="gestionloc.php" class="text-decoration-none text-dark">Gestion des locations</a></li>
            <li class="p-3" style="margin-top: 50%;">
                <a href="disconnect.php" id="disconnect-btn">
                    <span class="material-symbols-outlined me-2">logout</span> Se déconnecter
                </a>
            </li>
        </ul>
    </nav>
    <div class="menu-overlay" id="overlay" onclick="closeMenu()"></div>

    <div class="container">
        <h2 class="mb-4">Résultats pour : "<?= htmlspecialchars($recherche) ?>"</h2>
        
        <?php if (!empty($resultats)): ?>
            <div class="row">
                <?php foreach ($resultats as $info): ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <img src="<?= $info['url'] ? $info['url'] : '../configuration/images/default.jpg' ?>" class="card-img-top" alt="Bien immobilier">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($info['titre']) ?></h5>
                                <p class="card-text text-muted"><?= substr(htmlspecialchars($info['Description']), 0, 80) ?>...</p>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item small"><b>Adresse :</b> <?= htmlspecialchars($info['Adresse']) ?></li>
                                <li class="list-group-item text-primary"><b>Prix :</b> <?= number_format($info['Prix_jour'], 0, ',', ' ') ?> CFA</li>
                            </ul>
                            <div class="card-body">
                                <form method='POST' action='detailsbien.php'>
                                    <input type='hidden' name='IdBien' value='<?= $info['IdBien'] ?>'>
                                    <input type='submit' class='btn btn-primary w-100' value='Voir les détails'>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <span class="material-symbols-outlined" style="font-size: 64px; color: #ccc;">search_off</span>
                <h3 class="mt-3 text-muted">Aucun résultat trouvé pour votre recherche.</h3>
                <a href="acceuil.php" class="btn btn-outline-primary mt-3">Retour à l'accueil</a>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white text-center p-3 mt-auto">
        <p>© 2026 Agence Immobilière - Tous droits réservés</p>
    </footer>

    <!-- Scripts pour le menu -->
    <script>
        function toggleMenu() {
            document.getElementById("contenu").style.right = "0px";
            document.getElementById("overlay").style.display = "block";
            document.querySelector(".menu").classList.add("active");
        }
        function closeMenu() {
            document.getElementById("contenu").style.right = "-280px";
            document.getElementById("overlay").style.display = "none";
            document.querySelector(".menu").classList.remove("active");
        }
    </script>
</body>
</html>