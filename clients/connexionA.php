<?php
    session_start();
    require("bd.php");

    $error_message = "";

    if (isset($_POST['mail'], $_POST['password'])) {
        $mail = trim($_POST['mail']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE mail=?";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$mail]);
        $user = $stmt->fetch();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['IdUser'] = $user['IdUser'];

                // REDIRECT IMPORTANT
                if (!empty($_POST['redirect'])) {
                    header("Location: " . $_POST['redirect']);
                } else {
                    header("Location: acceuil.php");
                }
                exit();
            } else {
                $error_message = "Mot de passe incorrect";
            }
        } else {
            $error_message = "Utilisateur introuvable";
        }
    } else {
        $error_message = "Informations manquantes";
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Erreur d'authentification</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');

        body {
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .error-container {
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            padding: 40px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .icon-box {
            color: #d93025;
            font-size: 50px;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .btn-retry {
            background-color: #512da8;
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
            cursor: pointer;
        }

        .btn-retry:hover {
            background-color: #3e2282;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <div class="error-container">
        <div class="icon-box">
            <i class="fa-solid fa-circle-exclamation"></i>
        </div>
        <h1>Erreur</h1>
        <p><?php echo $error_message; ?>. Veuillez vérifier vos informations et réessayer.</p>
        <a href="authentificationA.php" class="btn-retry">Réessayer la connexion</a>
    </div>

</body>
</html>