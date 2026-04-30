<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .custom-navbar {
            background: #1f1f1f;
            padding: 12px 20px;
            display: flex;
            justify-content: center;
            flex: 1;
        }

        .nav-box {
            background: #2c2c2c;
            padding: 8px;
            border-radius: 12px;
            display: flex;
            gap: 10px;
        }

        .nav-box a {
            text-decoration: none;
        }
    </style>
</head>

<body>

<nav class="custom-navbar">

    <div class="nav-box">

        <a href="dashboard.php" class="btn btn-success btn-sm">
            Espace Client
        </a>

        <a href="logout.php" class="btn btn-danger btn-sm">
            Déconnexion
        </a>

    </div>

</nav>

<footer class="bg-dark text-white text-center p-3">
    <p>© 2026 Agence Immobilière - Tous droits réservés</p>
</footer>
</body>
</html>