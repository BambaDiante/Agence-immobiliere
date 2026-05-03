<?php
session_start();
require("bd.php");

if(!isset($_SESSION['IdUser'])){
    http_response_code(401); // non connecté
    exit;
}

if(!isset($_GET['id']) || empty($_GET['id'])){
    http_response_code(400); // requête invalide
    exit("Aucun bien sélectionné.");
}

$idUser = $_SESSION['IdUser'];
$idBien = (int) $_GET['id']; // cast en entier pour éviter null ou injection

// Vérifier que le bien existe en base
$sqlBien = "SELECT IdBien FROM bien_imm WHERE IdBien=?";
$stmtBien = $connexion->prepare($sqlBien);
$stmtBien->execute([$idBien]);
if(!$stmtBien->fetch()){
    http_response_code(404); // bien inexistant
    exit("Bien introuvable.");
}

// Vérifier si déjà favori
$sql = "SELECT * FROM favoris WHERE idUser=? AND idBien=?";
$stmt = $connexion->prepare($sql);
$stmt->execute([$idUser, $idBien]);

if($stmt->rowCount() > 0){
    // supprimer
    $sql = "DELETE FROM favoris WHERE idUser=? AND idBien=?";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$idUser, $idBien]);
} else {
    // ajouter
    $sql = "INSERT INTO favoris(idUser, idBien) VALUES (?, ?)";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$idUser, $idBien]);
}

http_response_code(200);
exit;
?>