<?php
session_start();
require("bd.php");

if(!isset($_SESSION['idUser'])){
    header("Location: authentification.php");
    exit();
}

$idBien = $_GET['id'];
$idUser = $_SESSION['idUser'];

$date_debut = $_POST['date_debut'];
$date_fin = $_POST['date_fin'];

// validation dates
if($date_fin < $date_debut){
    echo "Erreur : dates invalides";
    exit();
}

$sql = "INSERT INTO location (idBien, idUser, date_debut, date_fin)
        VALUES (?, ?, ?, ?)";

$stmt = $connexion->prepare($sql);
$stmt->execute([$idBien, $idUser, $date_debut, $date_fin]);

echo "Réservation effectuée avec succès !";
?>