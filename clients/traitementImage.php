<?php
session_start();
require("bd.php");

$idBien = $_POST['IdBien'];

// récupérer image
$image = $_FILES['image']['name'];
$tmp = $_FILES['image']['tmp_name'];

// dossier de stockage
$folder = "../COMMERCIAUX/imagesBiens/";

// créer nom unique (évite doublons)
$filename = time() . "_" . $image;

// déplacer fichier
move_uploaded_file($tmp, $folder . $filename);

// insertion en base
$sql = "INSERT INTO photos(url, idBien) VALUES (?, ?)";
$stmt = $connexion->prepare($sql);
$stmt->execute([$filename, $idBien]);

echo "Image ajoutée avec succès";
?>