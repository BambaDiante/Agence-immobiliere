<?php
   require_once "../configuration/connexion.php";
   session_start();
?>
<?php
   if(isset($_POST['name'],$_POST['mail'],$_POST['date'],$_POST['adresse'],$_POST['password'])){
    $insertion="INSERT INTO users(type,nom,date,adresse,mail,password,is_activated) VALUES (:type,:nom,:date,:adresse,:mail,:password,:is_activated)";
    $insert=$pdo->prepare($insertion);
    $insert->execute([
        ":type"=>"Commercial",
        ":nom"=>$_POST['name'],
        ":date"=>$_POST['date'],
        ":adresse"=>$_POST['adresse'],
        ":mail"=>$_POST['mail'],
        ":password"=>$_POST['password'],
        ":is_activated"=>true
    ]);
    $_SESSION['connected']=true;
    $_SESSION['nom']=$_POST['name'];
    $_SESSION['id']=$pdo->lastInsertId();
    header("Location:acceuil.php");
   }
?>