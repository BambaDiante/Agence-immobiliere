<?php
    session_start();
    require("bd.php");

    // vérifier utilisateur
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE mail=?";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$mail]);
    $user = $stmt->fetch();
    //var_dump($user);
    //exit;
    if($user && $password== $user['password']){
        
        $_SESSION['IdUser'] = $user['IdUser'];
        // REDIRECT IMPORTANT
        if(!empty($_POST['redirect'])){
            header("Location: " . $_POST['redirect']);
        } else {
            header("Location: acceuil.php");
        }
        exit();
       
    } else {
        echo "Email ou mot de passe incorrect";
    }
?>    