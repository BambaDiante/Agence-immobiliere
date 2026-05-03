<?php
    $host="localhost";
    $user="root";
    $password="";
    $bdd="agence_imm";
    try{
        $connexion= new PDO("mysql:host=$host;dbname=$bdd;charset=utf8", $user,$password);
    
    } catch(PDOException $e){
        echo"Erreur :" . $e->getMessage();
    }
?>