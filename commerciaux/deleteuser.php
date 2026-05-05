<?php
   session_start();
   require_once "../configuration/connexion.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Activation de compte</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 20px;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        input[type="submit"] {
            padding: 12px 30px;
            background-color: #512da8;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        input[type="submit"]:hover {
            background-color: #3f2083;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(81, 45, 168, 0.3);
        }
        
        .bloc {
            background: linear-gradient(135deg, #4caf50, #45a049);
            border-left: 5px solid #2e7d32;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.5s ease-out;
            max-width: 500px;
            text-align: center;
        }
        
        .bloc p {
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <?php
        if(isset($_POST['idusers']) || isset($_POST['confirm'])){
            $id=$_POST['idusers'] ?? $_POST['hidden_id'];
            echo "<form method='POST' id='valider' action=''>";
            echo "<input type='hidden' name='hidden_id' value='".$id."'>";
            echo "<input type='submit'  name='confirm' value='Valider la suppression'>";
            echo "</form>";   
            if(isset($_POST['confirm'])){
                $des="DELETE FROM users WHERE IdUser = :IdUser";
                $desac=$pdo->prepare($des);
                $result=$desac->execute([
                    ":IdUser"=>$id
                ]);
                if($result){
                    echo "<div class='bloc'>";
                    echo "<p>Le compte a ete supprime avec succes</p>";
                    echo "</div>";
                    header("Location: gestionclient.php");
                }
                else{
                    echo "<p>Erreur lors de la suppression</p>";
                }
            }
        }
        
    ?>
    
</body>
</html>