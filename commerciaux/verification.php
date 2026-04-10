<?php
   require_once "../configuration/connexion.php";
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
          header("Location:acceuil.php");
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