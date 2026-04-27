<?php
   require_once "../configuration/connexion.php";
   require_once "../configuration/verification.php";
   session_start();
   $ext_ok = ['gif', 'jpg', 'jpeg', 'png'];
   if(isset($_POST['type'],$_POST['sup'],$_POST['addr'],$_POST['nbre'],$_POST['price'],$_POST['desc'])){
       $insertion="INSERT INTO bien_imm (Type,Superficie,Adresse,Description,Prix_jour,nbre_pieces,idUser) VALUES (:Type,:Superficie,:Adresse,:Description,:Prix_jour,:nbre_pieces,:idUser)";
       $insert=$pdo->prepare($insertion);
       $insert->execute(array(
         ":Type"=>$_POST['type'],
         ":Superficie"=>$_POST['sup'],
         ":Adresse"=>$_POST['addr'],
         ":Description"=>$_POST['desc'],
         ":Prix_jour"=>$_POST['price'],
         ":nbre_pieces"=>$_POST['nbre'],
         ":idUser"=>$_SESSION['id']
       ));
       if(isset($_FILES['images'])){
       $idproduit=$pdo->lastInsertId();
       if(!is_dir('photos')) {
            mkdir('photos', 0777, true);
        }
        for($i=0;$i<count($_FILES['images']['name']);$i++){
            $chemin = null;
            if($_FILES['images']['name'][$i]){
                
                if(valid_extension($_FILES['images']['name'][$i],$ext_ok)){
                    
                    $chemin=move_file($_FILES['images']['tmp_name'][$i],"photos",$_FILES['images']['name'][$i]);
                }
                else{
                    echo "Probleme lors de l'insertion de la photo";
                }
            }
            if($chemin){
                $insert2="INSERT INTO photos(url,idBien) VALUES (:url,:idBien)";
                $insertion2=$pdo->prepare($insert2);
                $insertion2->execute([
                    ":url"=>$chemin,
                    ":idBien"=>$idproduit
                ]);
            }
            
        }
        echo "Ajout reussi";
       }
       else{
          echo "Echec de l'ajout de l'image";
       }
   }
   else{
       echo "Echec de l'ajout";

   }
?>