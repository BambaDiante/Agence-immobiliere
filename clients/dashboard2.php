<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Menu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f7f7f7;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar-airbnb {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 10px 30px;
        }

        .btn-airbnb {
            border-radius: 20px;
            padding: 6px 15px;
            font-weight: 500;
        }

        .btn-airbnb:hover {
            background-color: #f2f2f2;
        }

        .btn-logout {
            border-radius: 20px;
            padding: 6px 15px;
        }
    </style>
</head>

<body>

<nav class="navbar-airbnb d-flex justify-content-end align-items-center">

    <div class="d-flex gap-2">

        <a href="dashboard.php" class="btn btn-light btn-airbnb">
            Espace Client
        </a>

        <a href="logout.php" class="btn btn-outline-danger btn-logout">
            Déconnexion
        </a>

    </div>

</nav>

<footer class="bg-dark text-white text-center p-3 mt-auto">
    <p>© 2026 Agence Immobilière - Tous droits réservés</p>
</footer>

</body>
</html>