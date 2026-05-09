<?php
session_start();

if(!isset($_SESSION['IdUser'])){
    header("Location: authentificationA.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte Bloqué</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Georgia', serif;
            background-attachment:fixed;
        }
        
        .container-blocked {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 50px 40px;
            max-width: 550px;
            text-align: center;
        }
        
        .icon-blocked {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 25px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        .message-text {
            color: #666;
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        
        .alert-info {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
            text-align: left;
        }
        
        .alert-info strong {
            color: #0c5aa0;
        }
        
        .btn-return {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        
        .btn-return:hover {
            background-color: #764ba2;
            text-decoration: none;
            color: white;
        }
        
        .contact-info {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #ddd;
            font-size: 14px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container-blocked">
        <div class="icon-blocked">
            🔒
        </div>
        
        <h1>Votre Compte a été Bloqué</h1>
        
        <div class="message-text">
            <p>
                Nous regrettons de vous informer que votre compte a été <strong>bloqué par notre équipe</strong>.
            </p>
            <p>
                <strong>Vous ne pouvez pas effectuer de réservation</strong> tant que votre compte n'aura pas été débloqué par un de nos commerciaux.
            </p>
        </div>
        
        
        
        <div class="alert-info" style="background-color: #fff3cd; border-left-color: #ffc107;">
            <strong style="color: #856404;">📞 Pour plus d'informations :</strong>
            <p style="margin-top: 10px;">
                Veuillez contacter notre équipe commerciale pour connaître les raisons du blocage et les conditions de déblocage de votre compte.
            </p>
        </div>
        
        <a href="acceuil.php" class="btn-return">Retourner à l'accueil</a>
        
        <div class="contact-info">
            <p>Si vous pensez qu'il y a une erreur, veuillez nous contacter.</p>
        </div>
    </div>
</body>
</html>