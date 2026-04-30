<?php
    session_start();
    require("bd.php");
    if(!isset($_SESSION['IdUser'])){
       header("Location: connexion.php");
       exit;
    }
    $idBien = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="fr">  
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        </style>
    </head>
    <body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">

    <div class="card shadow p-4" style="width: 400px; border-radius: 15px;">

        <h3 class="text-center mb-4">Réserver un bien</h3>

        <form method="POST" action="traiterReservation.php?id=<?= $idBien ?>">

            <div class="mb-3">
                <label class="form-label">Date début</label>
                <input type="date" name="date_debut" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Date fin</label>
                <input type="date" name="date_fin" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success w-100">
                Réserver maintenant
            </button>

        </form>

    </div>

</div>

<footer class="bg-dark text-white text-center p-3 mt-5">
    <p>© 2026 Agence Immobilière - Tous droits réservés</p>
</footer>
</body>
</html>