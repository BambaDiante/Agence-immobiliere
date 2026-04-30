<?php
    session_start();
    require("bd.php");
    if(isset($_POST['submit']))
    { 
        $mail=$_POST['mail'];
        $password=$_POST['password'];
        $sql="SELECT*FROM users WHERE mail=? AND password=?";
        $rsql=$connexion->prepare($sql);
        $rsql->execute(array($mail,$password));
        $user = $rsql->fetch();

        if($user){
            // ✅ ON STOCKE EN SESSION
            $_SESSION['idUser'] = $user['idUser']; // adapte au nom de ta colonne
            $_SESSION['nom'] = $user['nom'];
    
            // redirection correcte
            header("Location: accueil.php");
            exit;
        } else {
            echo "Email ou mot de passe incorrect";
        }
       
    }    
?>