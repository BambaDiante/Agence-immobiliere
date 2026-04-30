    <?php
        //session_start();
        require("bd.php");
        //require("connexionA.php");
        if(!isset($_SESSION['IdUser'])){
        //header("Location: favoris_action.php");
        exit;
        }

        $idUser = $_SESSION['IdUser'];
        $idBien = $_GET['id'];

        // vérifier si déjà en favoris
        $sql = "SELECT * FROM favoris WHERE idUser=? AND idBien=?";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$idUser, $idBien]);

        if($stmt->rowCount() == 0){
        // ajouter
        $sql = "INSERT INTO favoris(idUser, idBien) VALUES (?, ?)";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$idUser, $idBien]);
        }

        // retour accueil
        header("Location: acceuil.php");
        exit;
    ?>