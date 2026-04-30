<?php
session_start();
require("bd.php");
require("favoris.php");

$idUser = $_SESSION['IdUser'];
$idBien = $_GET['id'];

// vérifier si existe
$sql = "SELECT * FROM favoris WHERE idUser=? AND idBien=?";
$stmt = $connexion->prepare($sql);
$stmt->execute([$idUser, $idBien]);

if($stmt->rowCount() > 0){
    // supprimer (déjà en favori)
    $sql = "DELETE FROM favoris WHERE idUser=? AND idBien=?";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$idUser, $idBien]);
} else {
    // ajouter
    $sql = "INSERT INTO favoris(idUser, idBien) VALUES (?, ?)";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$idUser, $idBien]);
}

// retour accueil
header("Location: accueil.php");
exit;
?>