<?php
session_start();
require("bd.php");

/*if(!isset($_SESSION['idUser'])){
    header("Location: authentification.php");
    exit();
}*/

$idUser = $_SESSION['idUser'];

// récupérer réservations
$sql = "SELECT l.*, b.Type, b.Adresse, b.Prix_jour
        FROM location l
        JOIN bien_imm b ON l.idBien = b.IdBien
        WHERE l.idUser = ?";

$stmt = $connexion->prepare($sql);
$stmt->execute([$idUser]);
$reservations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .container {
            flex: 1;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>

<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="accueil.php">Mon Espace</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Déconnexion</a>
    </div>
</nav>

<!-- CONTENU -->
<div class="container mt-5">

    <h2 class="mb-4 text-center">Bienvenue 👋</h2>

    <div class="card p-3 shadow mb-4">
        <h5>Mes réservations</h5>
    </div>

    <div class="row">

        <?php foreach($reservations as $res): ?>

        <div class="col-md-6 mb-3">

            <div class="card shadow p-3">

                <h5><?= $res['Type'] ?></h5>

                <p>
                    📍 <?= $res['Adresse'] ?><br>
                    💰 <?= $res['Prix_jour'] ?> FCFA / jour<br>
                    📅 Du <?= $res['date_debut'] ?> au <?= $res['date_fin'] ?>
                </p>

                <span class="badge bg-info">
                    En cours
                </span>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

</div>

<footer class="bg-dark text-white text-center p-3 mt-5">
    <p>© 2026 Agence Immobilière - Tous droits réservés</p>
</footer>

</body>
</html>