<?php
    $host="localhost";
    $user="root";
    $password="";
    $bdd="agence_imm";
    try{
        $pdo= new PDO("mysql:host=$host;dbname=$bdd;charset=utf8", $user,$password);
    }
    catch(Exception $e)
    {
         die("Erreur de connexion : ".$e->getMessage());
    }
?>