<?php
    require("bd.php");
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8"/>
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
        </style>
    </head>
    <body>
        <div class="container mt-5" style="flex: 1;">
        <form method="POST" action="traitementImage.php" enctype="multipart/form-data">
           <input type="hidden" name="IdBien" value="<?= $_GET['idBien'] ?>">
           <input type="file" name="image" required>
           <button type="submit" class="btn btn-primary">Ajouter image</button>
        </form>
        </div>
        <footer class="bg-dark text-white text-center p-3 mt-5">
            <p>© 2026 Agence Immobilière - Tous droits réservés</p>
        </footer>
    </body>
</html>