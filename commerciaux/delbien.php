<?php
    session_start();

    if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true) {
        header("Location:authentification.php");
        exit;
    }
    require_once "../configuration/connexion.php";
    $message = "";
    $error = false;
    
    if (isset($_POST['idbien'])) {
        // D'abord, vérifier le statut du bien
        $check = $pdo->prepare("SELECT statut FROM bien_imm WHERE IdBien = :IdBien");
        $check->execute([":IdBien" => $_POST['idbien']]);
        $bien = $check->fetch(PDO::FETCH_ASSOC);
        
        if ($bien && $bien['statut'] === 'libre') {
            // Le bien est libre, on peut le supprimer
            // Supprimer d'abord les photos associées au bien
            $delete_photos = $pdo->prepare("DELETE FROM photos WHERE idBien = :idBien");
            $delete_photos->execute([":idBien" => $_POST['idbien']]);
            
            // Ensuite supprimer le bien lui-même
            $delete_bien = $pdo->prepare("DELETE FROM bien_imm WHERE IdBien = :IdBien");
            $result = $delete_bien->execute([":IdBien" => $_POST['idbien']]);
            
            if ($result) {
                header("Location: consult.php");
                exit;
            } else {
                $message = "Erreur lors de la suppression.";
                $error = true;
            }
        } else if ($bien && $bien['statut'] !== 'libre') {
            // Le bien est réservé ou en location
            $message = "Impossible de supprimer ce bien car il est actuellement en réservation ou en location.";
            $error = true;
        } else {
            $message = "Bien introuvable.";
            $error = true;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../configuration/images/logoagence.jpeg">
    <link rel="stylesheet" href="style.css">
    <title>Suppression du bien</title>
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
            background-attachment: fixed;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        header {
            display: flex;
            flex-direction: row;
            align-items: center;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            z-index: 3000;
            padding: 10px 0;
        }

        #logo {
            order: 1;
            height: 50px;
            width: 50px;
            border-radius: 50%;
            margin-top: 10px;
            margin-left: 10px;
        }

        .menu {
            order: 3;
            cursor: pointer;
            position: relative;
            z-index: 2000;
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

        .menu.active span:nth-child(1) {
            transform: translateY(10px) rotate(45deg);
        }

        .menu.active span:nth-child(2) {
            opacity: 0;
        }

        .menu.active span:nth-child(3) {
            transform: translateY(-10px) rotate(-45deg);
        }

        nav {
            position: fixed;
            top: 0;
            right: -280px;
            width: 280px;
            height: 100%;
            background: #fff;
            z-index: 1001;
            transition: right 0.3s ease;
            padding-top: 60px;
            overflow-y: auto;
            box-shadow: -2px 0 8px rgba(0,0,0,0.1);
        }

        nav#contenu h2 {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        nav#contenu ul {
            list-style: none;
        }

        nav#contenu li {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }

        nav#contenu a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        nav#contenu a:hover {
            color: #512da8;
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

        .container {
            margin-top: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
        }

        .confirmation-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
        }

        .confirmation-box p {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .button-group a, .button-group input[type="submit"] {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #5a6268;
        }

        .btn-delete {
            background-color: #bb2d3b;
            color: white;
        }

        .btn-delete:hover {
            background-color: #9a2430;
        }

        .error-message {
            color: #d32f2f;
            margin-top: 20px;
        }

        .success-message {
            color: #4caf50;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <a href="acceuil.php"><img src="../configuration/images/logoagence.jpeg" id="logo" alt="logo"></a>
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
            <button type="submit" id="disconnect"><a href="disconnect.php" class="deconnect">Se deconnecter</a></button>
        </ul>
    </nav>
    <div class="menu-overlay" id="overlay" onclick="closeMenu()"></div>

    <div class="container">
        <h1>Suppression du bien immobilier</h1>
        
        <div class="confirmation-box">
            <p>Êtes-vous sûr de vouloir supprimer ce bien immobilier ?</p>
            <p style="font-size: 14px; color: #999;">Cette action est irréversible et supprimera également toutes les photos associées.</p>
            
            <?php if ($message): ?>
                <p class="<?php echo $error ? 'error-message' : 'success-message'; ?>"><?php echo htmlspecialchars($message); ?></p>
                <?php if ($error): ?>
                    <div class="button-group">
                        <a href="consult.php" class="btn-cancel">Retour</a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="button-group">
                    <a href="consult.php" class="btn-cancel">Annuler</a>
                    <form method="POST" action="" style="margin: 0;">
                        <input type="hidden" name="idbien" value="<?php echo htmlspecialchars($_POST['idbien'] ?? ''); ?>">
                        <input type="submit" value="Confirmer la suppression" class="btn-delete">
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

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

        document.getElementById('overlay').addEventListener('click', closeMenu);

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeMenu();
            }
        });
    </script>
</body>
</html>