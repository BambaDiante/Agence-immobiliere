<?php
require_once "../configuration/connexion.php";
session_start();

if(isset($_POST['name'], $_POST['mail'], $_POST['date'], $_POST['adresse'], $_POST['password'],$_POST['tel'],$_POST['lien'])){
    // Hachage du mot de passe avec l'algorithme sécurisé par défaut (BCRYPT)
    $passwordHache = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $insertion = "INSERT INTO users(type, nom, date, adresse, mail, password, numero,lien_marchand,is_activated) 
                  VALUES (:type, :nom, :date, :adresse, :mail, :password, :numero, :lien_marchand, :is_activated)";
    
    $insert = $pdo->prepare($insertion);
    $insert->execute([
        ":type" => "Commercial",
        ":nom" => $_POST['name'],
        ":date" => $_POST['date'],
        ":adresse" => $_POST['adresse'],
        ":mail" => $_POST['mail'],
        ":password" => $passwordHache, // On enregistre le hash
        ':numero'=>$_POST['num'],
        ':lien_marchand'=>$_POST['lien'],
        ":is_activated" => true
    ]);

    $_SESSION['connected'] = true;
    $_SESSION['nom'] = $_POST['name'];
    $_SESSION['id'] = $pdo->lastInsertId();
    header("Location: acceuil.php");
    exit;
}
else{
    echo "Probleme avec les champs";
}
?>