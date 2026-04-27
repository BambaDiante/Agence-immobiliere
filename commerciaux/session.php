<?php
    session_start();
    if(isset($_SESSION['connected'])){
        if($_SESSION['connected']==true){
            echo "<h1>Bonjour ".$_SESSION['nom']." </h1>";
        }
        else{
            header("Location:authentification.php");
        }
    }
    else{
        header("Location:authentification.php");
    }
?>