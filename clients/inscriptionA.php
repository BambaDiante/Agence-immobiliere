<?php
     session_start();
     require("bd.php");
     if(isset($_POST['inscription']))
     {
        $name = $_POST['name'];
        $mail = $_POST['mail'];
        $adresse = $_POST['adresse'];
        $password = $_POST['password'];
        $date = $_POST['date'];
        $type = "client";
        $activation = 1;
        $sql = "INSERT INTO users(nom, mail, adresse, date, password, type, is_activated)VALUES (?, ?, ?, ?, ?, ?, ?)";
        $rsql = $connexion->prepare($sql);
        $rsql->execute([$name, $mail, $adresse, $date, $password, $type, $activation]);
        header("Location: acceuil.php");
        exit;
     }
?>