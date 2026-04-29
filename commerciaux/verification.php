<?php
   require_once "../configuration/connexion.php";
   session_start();
?>
<?php
   if(isset($_POST['mail'],$_POST['password'])){
     $recher="SELECT * FROM users WHERE mail=:mail";
     $parcourir=$pdo->prepare($recher);
     $parcourir->execute([
        ":mail"=>$_POST['mail']
     ]);
     $resultat=$parcourir->fetch(PDO::FETCH_ASSOC);
   if(!empty($resultat)){
        if($resultat['password']==$_POST['password']){
            $_SESSION['connected']=true;
            $_SESSION['nom']=$resultat['nom'];
            $_SESSION['id']=$resultat['IdUser'];
            header("Location:acceuil.php");
            exit;
        }
        else{
           echo "Mot de passe incorrect"; 
        }
     }
     else{
        echo "Utilisateur introuvable";
     }
   }
?>