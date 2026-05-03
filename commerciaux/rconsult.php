<?php
    session_start();
    require_once "../configuration/connexion.php";

    // Protection de la page
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
        
        $sql = "SELECT B.*, (SELECT url FROM photos WHERE idBien = B.IdBien LIMIT 1) as url 
                FROM bien_imm B 
                WHERE B.idUser = :idUser 
                AND (B.titre LIKE :search OR B.Adresse LIKE :search OR B.Description LIKE :search)";
        
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=search" />
    <link rel="stylesheet" href="../configuration/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Résultats de gestion - Agence</title>
    <style>
        /* On réutilise exactement ton CSS de consult.php */
        *{ margin: 0; padding: 0; box-sizing: border-box; font-family: 'Montserrat', sans-serif; }
        body{ background: linear-gradient(to right, #e2e2e2, #c9d6ff); background-attachment:fixed; min-height: 100vh; display:flex; flex-direction:column; text-align:center; }
        header { display: flex; align-items: center; width: 100%; position: fixed; top: 0; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(5px); z-index: 3000; padding: 10px 0; }
        #logo { height:50px; width:50px; border-radius:50%; margin-left:10px; }
        .search-container { margin: 0 auto; display: flex; align-items: center; background: #f8f9fa; border: 1px solid #ddd; border-radius: 50px; padding: 5px 15px; width: 100%; max-width: 500px; }
        .search-container input { border: none; background: transparent; outline: none; padding: 10px; width: 100%; }
        #search-button { background: #512da8; color: white; border: none; border-radius: 50%; width: 38px; height: 38px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        nav#contenu { position:fixed; top:0; right:-280px; width:280px; height:100%; background:#fff; z-index:1001; transition:right 0.3s; padding-top:60px; box-shadow:-2px 0 8px rgba(0,0,0,0.1); text-align: left; }
        .menu { cursor: pointer; margin-right: 20px; z-index: 2000; width: 35px; order: 3; }
        .menu span { display: block; height: 4px; background:#333; margin: 6px 0; border-radius:2px; }
        .container { margin-top: 100px; flex: 1; }
        table { border-collapse: collapse; margin: 40px auto; border: 1px solid black; background: white; width: 98%; }
        th, td { border: 1px solid black; padding: 15px; }
        #boutons { display: flex; border: none; justify-content: center; }
        input[type="submit"] { margin: 5px; padding: 10px 15px; border-radius: 10px; color: white; border: none; cursor: pointer; }
        #modif { background: #157347; }
        #dele { background: #bb2d3b; }
        #disconnect-btn { background: #311b92; color: white !important; padding: 12px; border-radius: 10px; text-decoration: none; font-weight: bold; display: flex; align-items: center; justify-content: center; margin: 20px; }
        .menu-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 999; }
    </style>
</head>
<body>
    <header>
        <a href="acceuil.php"><img src="../configuration/images/logoagence.jpeg" id="logo" alt="logo"></a>
        <form action="rconsult.php" method="POST" class="search-container">
            <input type="search" name="barre" placeholder="Rechercher dans mes biens..." value="<?= htmlspecialchars($recherche) ?>">
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
        <ul class="list-unstyled">
            <li class="p-3 border-bottom"><a href="ajout.php" class="text-decoration-none text-dark">Ajouter un bien</a></li>
            <li class="p-3 border-bottom"><a href="consult.php" class="text-decoration-none text-dark">Gestion de mes biens</a></li>
            <li class="p-3 border-bottom"><a href="gestionclient.php" class="text-decoration-none text-dark">Gestion des clients</a></li>
            <li class="p-3 border-bottom"><a href="gestionloc.php" class="text-decoration-none text-dark">Gestion des locations</a></li>
            <li style="margin-top: 80%;">
                <a href="disconnect.php" id="disconnect-btn">
                    <span class="material-symbols-outlined me-2">logout</span> Se déconnecter
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
                    <th>ID</th>
                    <th>Type</th>
                    <th>Titre</th>
                    <th>Superficie(m²)</th>
                    <th>Adresse</th>
                    <th>Prix journalier</th>
                    <th>Nombre de pièces</th>
                    <th>Action</th>
                </tr>
                <?php foreach($resultats as $info): ?>
                    <tr>
                        <td><?= $info['IdBien'] ?></td>
                        <td><?= ($info['Type'] == "app") ? "Appartement" : "Villa" ?></td>
                        <td><?= htmlspecialchars($info['titre']) ?></td>
                        <td><?= $info['Superficie'] ?></td>
                        <td><?= htmlspecialchars($info['Adresse']) ?></td>
                        <td><?= number_format($info['Prix_jour'], 0, ',', ' ') ?></td>
                        <td><?= $info['nbre_pieces'] ?></td>
                        <td id="boutons">
                            <!-- Formulaire Modification -->
                            <form method="POST" action="setbien.php">
                                <input type="hidden" name="id" value="<?= $info['IdBien'] ?>">
                                <input type="hidden" name="titre" value="<?= $info['titre'] ?>">
                                <input type="hidden" name="sup" value="<?= $info['Superficie'] ?>">
                                <input type="hidden" name="adr" value="<?= $info['Adresse'] ?>">
                                <input type="hidden" name="prix" value="<?= $info['Prix_jour'] ?>">
                                <input type="hidden" name="nbre" value="<?= $info['nbre_pieces'] ?>">
                                <input type="submit" id="modif" value="Modifier">
                            </form>

                            <!-- Formulaire Suppression (redirige vers consult.php pour traiter la logique) -->
                            <form method="POST" action="consult.php">
                                <input type="hidden" name="delete_idbien" value="<?= $info['IdBien'] ?>">
                                <input type="submit" id="dele" value="Supprimer" onclick="return confirm('Êtes-vous sûr ?');">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="py-5">
                <h3 class="text-muted">Aucun bien trouvé pour cette recherche.</h3>
                <a href="consult.php" class="btn btn-primary mt-3">Retour à la liste complète</a>
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