<?php
   session_start();
   require_once "../configuration/connexion.php";
   $id=$_SESSION['id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon"  href="../configuration/images/logoagence.jpeg">
    <link rel="stylesheet" href="style.css">
    <title>Gestion des locations de mes biens</title>
</head>
<body>
    <h1>Valider les locations</h1>
    <?php
       $loc="SELECT L* FROM (SELECT idUser,url FROM bien_imm NATURAL JOIN photos) B JOIN (SELECT idBien,nom FROM users U JOIN location l ON u.IdUser=l.idUser) L on B.IdBien=L.idBien WHERE B.idUser=:idUser";
       $location=$pdo->prepare($loc);
       $location->execute([
        ":idUser"=>$id
       ]);
       $locs=$location->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table>
        <tr>
            
        </tr>
    </table>


    
</body>
</html>