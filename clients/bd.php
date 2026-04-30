<?php
  $dsn = 'mysql:host=localhost;port=8889;dbname=agence__imm';
  $user = 'root';
  $password = 'root';
    try{
        $connexion=new PDO($dsn,$user,$password);
        //echo"Connexion établie !";
    } catch(PDOException $e){
        echo"Erreur :" . $e->getMessage();
    }
?>