<?php
session_start();
require("bd.php");

// sécurité
if(!isset($_SESSION['IdUser'])){
    exit("Non autorisé");
}

$idUser = $_SESSION['IdUser'];
$idLoc = $_POST['idLoc'] ?? null;

if(!$idLoc){
    header("Location: mesReservations.php");
    exit();
}

// vérifier que la réservation appartient à l'utilisateur
$sql = "SELECT * FROM location WHERE idLoc=? AND idUser=?";
$stmt = $connexion->prepare($sql);
$stmt->execute([$idLoc, $idUser]);
$res = $stmt->fetch();

if(!$res){
    exit("Action interdite");
}

// suppression
$sqlDelete = "DELETE FROM location WHERE idLoc=?";
$stmtDelete = $connexion->prepare($sqlDelete);
$stmtDelete->execute([$idLoc]);

header("Location: mesReservations.php");
exit();