<?php
    session_start();
    require_once "../configuration/connexion.php";

    // Protection de session (optionnel mais recommandé)
    if (!isset($_SESSION['id'])) {
        header("Location: authentification.php");
        exit;
    }

    $resultats = [];
    $recherche = "";

    // Traitement de la recherche
    if (isset($_POST['barre'])) {
        $recherche = trim($_POST['barre']);
        
        // Recherche par nom ou par mail pour les utilisateurs qui ne sont pas des commerciaux
        $sql = "SELECT * FROM users 
                where (nom LIKE :search OR mail LIKE :search OR adresse LIKE :search)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":search" => "%" . $recherche . "%"
        ]);
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../configuration/images/logoagence.jpeg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=logout,search" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats Recherche Clients</title>
    <style>
        /* Reprise exacte de vos styles pour la cohérence visuelle */
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
            margin: 0 auto; display: flex; 
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
            top:0; right:-280px; 
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
            flex: 1; 
            margin-top: 100px; 
            width: 100%; 
        }
        table { 
            border-collapse: collapse; 
            margin: 20px auto; 
            border: 1px solid black; 
            width: 98%; 
            background: white; 
        }
        th, td { 
            border: 1px solid black; 
            padding: 15px; 
            font-weight: 300; 
        }
        
        .bouton { 
            padding:10px; 
            border-radius:10px; 
            color:white; 
            width:95%; 
            border:1px solid black; 
            cursor: pointer; 
            margin-bottom: 5px; 
        }
        .bouton-danger { 
            background:#bb2d3b; 
        }
        .bouton-success { 
            background:#157347; 
        }
        .histo { 
            padding:10px; 
            border-radius:15px; 
            color:white; 
            font-size:14px; 
            background:#311b92; 
            border:none; 
            cursor: pointer; 
            width: 100%; 
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
        <form action="rclient.php" method="POST" class="search-container">
            <input type="search" name="barre" placeholder="Rechercher un client..." value="<?= htmlspecialchars($recherche) ?>">
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
            <li style="padding:15px 20px; border-bottom:1px solid #eee;"><a href="ajout.php" style="text-decoration:none; color:#333;">Ajouter un bien</a></li>
            <li style="padding:15px 20px; border-bottom:1px solid #eee;"><a href="consult.php" style="text-decoration:none; color:#333;">Gestion de mes biens</a></li>
            <li style="padding:15px 20px; border-bottom:1px solid #eee;"><a href="gestionclient.php" style="text-decoration:none; color:#333;">Gestion des clients</a></li>
            <li style="padding:15px 20px; border-bottom:1px solid #eee;"><a href="gestionloc.php" style="text-decoration:none; color:#333;">Gestion des locations</a></li>
            <li>
                <a href="disconnect.php" id="disconnect-btn">
                    <span class="material-symbols-outlined" style="margin-right: 8px;">logout</span> Se déconnecter
                </a>
            </li>
        </ul>
    </nav>
    <div class="menu-overlay" id="overlay" onclick="closeMenu()"></div>

    <div class="container">
        <h1>Résultats pour : "<?= htmlspecialchars($recherche) ?>"</h1>

        <?php if (!empty($resultats)): ?>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Date de Naissance</th>
                    <th>Adresse</th>
                    <th>Mail</th>
                    <th>Status</th>
                    <th>Actions</th>
                    <th>Consulter l'historique</th>
                </tr>
                <?php foreach($resultats as $us): ?>
                    <tr>
                        <td><?= htmlspecialchars($us['nom']) ?></td>
                        <td><?= $us['date'] ?></td>
                        <td><?= htmlspecialchars($us['adresse']) ?></td>
                        <td><?= htmlspecialchars($us['mail']) ?></td>
                        <td><?= ($us['is_activated'] == 1) ? "Active" : "Désactivé" ?></td>
                        <td>
                            <?php if($us['is_activated'] == 1): ?>
                                <form action='deluser.php' method='POST'>
                                    <input type='hidden' name='idUser' value='<?= $us['IdUser'] ?>'>
                                    <input type='submit' class='bouton bouton-danger' value='Désactiver'>
                                </form>
                            <?php else: ?>
                                <form action='actuser.php' method='POST'>
                                    <input type='hidden' name='idusers' value='<?= $us['IdUser'] ?>'>
                                    <input type='submit' class='bouton bouton-success' value='Activer'>
                                </form>
                            <?php endif; ?>

                            <form action='deleteuser.php' method='POST'>
                                <input type='hidden' name='idusers' value='<?= $us['IdUser'] ?>'>
                                <input type='submit' class='bouton bouton-danger' value="Supprimer l'utilisateur" onclick="return confirm('Supprimer définitivement ce client ?');">
                            </form>
                        </td>
                        <td>
                            <form action='consulthisto.php' method='POST'>
                                <input type='hidden' name='idusers' value='<?= $us['IdUser'] ?>'>
                                <input type='hidden' name='nom' value='<?= htmlspecialchars($us['nom']) ?>'>
                                <input type='submit' class='histo' value="Consulter son historique">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div style="margin-top: 50px;">
                <h3 style="color: #666;">Aucun client trouvé.</h3>
                <br>
                <a href="gestionclient.php" style="color: #311b92; font-weight: bold;">Retour à la liste complète</a>
            </div>
        <?php endif; ?>
    </div>

    <footer style="background:#2c2d2d; color:white; padding:1.5rem; width:100%; margin-top: auto;">
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