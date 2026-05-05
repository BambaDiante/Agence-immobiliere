<?php
    session_start();
    require_once "../configuration/connexion.php";

    if (!isset($_SESSION['id'])) {
        header("Location: authentification.php");
        exit;
    }

    $id = $_SESSION['id'];
    $resultats = [];
    $recherche = "";

    // Traitement de la recherche
    if (isset($_POST['barre'])) {
        $recherche = trim($_POST['barre']);
        
        // Requête SQL filtrée par la barre de recherche (recherche sur le nom du client ou le prix)
        $sql = "SELECT l.idLoc, l.idBien, l.duree, l.dateDebut, l.is_validated, l.prix, u.nom, p.url
                FROM location l
                JOIN users u ON u.idUser = l.idUser
                JOIN bien_imm b ON b.IdBien = l.idBien
                JOIN photos p ON p.id = (
                    SELECT MIN(p2.id)
                    FROM photos p2
                    WHERE p2.idBien = b.IdBien
                )
                WHERE b.idUser = :idUser 
                AND (u.nom LIKE :search OR l.prix LIKE :search OR l.dateDebut LIKE :search)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":idUser" => $id,
            ":search" => "%" . $recherche . "%"
        ]);
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../configuration/images/logoagence.jpeg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=search,logout" />
    <link rel="stylesheet" href="../configuration/css/bootstrap.min.css">
    <title>Résultats de recherche - Locations</title>
    <style>
        /* Styles cohérents avec gestionloc.php */
        *{ 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Montserrat', sans-serif; 
        }
        body{ 
            background: linear-gradient(to right, #e2e2e2, #c9d6ff); 
            background-attachment:fixed; 
            min-height: 100vh; 
            text-align:center; 
            display:flex; 
            flex-direction:column; 
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
            margin-left:10px; 
        }
        .search-container { 
            margin: 0 auto; 
            display: flex; 
            align-items: center; 
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
            display: flex; 
            align-items: center; 
            justify-content: center; 
            cursor: pointer; 
        }
        nav#contenu { 
            position:fixed; 
            top:0; 
            right:-280px; 
            width:280px; 
            height:100%; 
            background:#fff; 
            z-index:1001; 
            transition:right 0.3s; 
            padding-top:60px; 
            box-shadow:-2px 0 8px rgba(0,0,0,0.1); 
            text-align: left; 
        }
        .menu { 
            cursor: pointer; 
            margin-right: 20px; 
            z-index: 2000; 
            width: 35px; 
            order: 3; 
        }
        .menu span { 
            display: block; 
            height: 4px; 
            background:#333; 
            margin: 6px 0; 
            border-radius:2px; 
        }
        .container { 
            margin-top: 100px; 
            flex: 1; 
            width: 100%; 
        }
        table { 
            border-collapse: collapse; 
            margin: 40px auto; 
            border: 1px solid black; 
            background: white; 
            width: 98%; 
        }
        th, td { 
            border: 1px solid black; 
            padding: 15px; 
            font-weight: 300; 
        }
        .pic { 
            height: 150px; 
            object-fit: cover; 
            border-radius: 10px; 
        }
        .val { 
            background: lightgreen; 
            border: 1px solid black; 
            padding: 5px 15px; 
            border-radius: 10px; 
            cursor: pointer; 
        }
        .anu { 
            background: #ff4d4d; 
            color: white; 
            border: 1px solid black; 
            padding: 5px 15px; 
            border-radius: 10px; 
            cursor: pointer; 
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
            margin: 20px; 
            margin-top: 80%; 
        }
        .menu-overlay { 
            display: none; 
            position: fixed; 
            inset: 0; 
            background: rgba(0,0,0,0.7); 
            z-index: 999; 
            backdrop-filter: blur(3px); 
        }
    </style>
</head>
<body>
    <header>
        <a href="acceuil.php"><img src="../configuration/images/logoagence.jpeg" id="logo" alt="logo"></a>
        <form action="rlocation.php" method="POST" class="search-container">
            <input type="search" name="barre" placeholder="Rechercher (Client, prix, date...)" value="<?= htmlspecialchars($recherche) ?>">
            <button type="submit" id="search-button">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
        <div class="menu" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </div>
    </header>

    <nav id="contenu">
        <h2 class="p-3">Menu</h2>
        <ul style="list-style:none;">
            <li class="p-3 border-bottom"><a href="ajout.php" class="text-decoration-none text-dark">Ajouter un bien</a></li>
            <li class="p-3 border-bottom"><a href="consult.php" class="text-decoration-none text-dark">Gestion de mes biens</a></li>
            <li class="p-3 border-bottom"><a href="gestionclient.php" class="text-decoration-none text-dark">Gestion des clients</a></li>
            <li class="p-3 border-bottom"><a href="gestionloc.php" class="text-decoration-none text-dark">Gestion des locations</a></li>
            <li>
                <a href="disconnect.php" id="disconnect-btn">
                    <span class="material-symbols-outlined me-2">logout</span> Se déconnecter
                </a>
            </li>
        </ul>
    </nav>
    <div class="menu-overlay" id="overlay" onclick="closeMenu()"></div>

    <div class="container">
        <h1>Résultats de recherche : "<?= htmlspecialchars($recherche) ?>"</h1>

        <?php if (!empty($resultats)): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Image du bien</th>
                    <th>Nom du client</th>
                    <th>Durée (jours)</th>
                    <th>Date de début</th>
                    <th>Prix Total</th>
                    <th>Action</th>
                </tr>
                <?php foreach($resultats as $l): ?>
                    <tr>
                        <td><?= $l['idLoc'] ?></td>
                        <td><img src="<?= $l['url'] ?>" class="pic"></td>
                        <td><?= htmlspecialchars($l['nom']) ?></td>
                        <td><?= $l['duree'] ?></td>
                        <td><?= $l['dateDebut'] ?></td>
                        <td><?= number_format($l['prix'], 0, ',', ' ') ?> FCFA</td>
                        <td>
                            <form method="POST" action="validate.php">
                                <input type="hidden" name="id" value="<?= $l['idLoc'] ?>">
                                <?php if($l['is_validated'] == 0): ?>
                                    <input type="submit" class="val" name="val" value="Valider">
                                <?php else: ?>
                                    <input type="submit" class="anu" name="anu" value="Annuler" onclick="return confirm('Annuler cette location ?');">
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="py-5">
                <h3 class="text-muted">Aucune location trouvée.</h3>
                <a href="gestionloc.php" class="btn btn-primary mt-3">Retour à la liste complète</a>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white text-center p-3 mt-auto">
        <p>© 2026 Agence Immobilière - Tous droits réservés</p>
    </footer>

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