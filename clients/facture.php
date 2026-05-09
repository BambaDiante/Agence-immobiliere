<?php
session_start();
require("bd.php");

$idBien = $_GET['idBien'] ?? null;
$prix = $_GET['prix'] ?? 0;
$idLoc = $_GET['idLoc'] ?? null;

if(!$idBien || !$idLoc){
    die("Informations introuvables");
}

// récupérer le bien + propriétaire
$sql = "
SELECT b.*, u.lien_marchand
FROM bien_imm b
JOIN users u ON b.idUser = u.IdUser
WHERE b.IdBien = ?
";

$stmt = $connexion->prepare($sql);
$stmt->execute([$idBien]);

$bien = $stmt->fetch();
$lienMarchand = trim((string)($bien['lien_marchand'] ?? ''));

if(!$bien){
    die("Bien introuvable");
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Facture</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
        body{
            background:#f5f5f5;
            font-family:Arial, sans-serif;
        }
        .facture-container{
            max-width:700px;
            margin:60px auto;
            background:white;
            border-radius:18px;
            overflow:hidden;
            box-shadow:0 10px 30px rgba(0,0,0,0.1);
        }
        .facture-header{
            background:linear-gradient(135deg,#000,#1f1f1f);
            color:white;
            padding:30px;
            text-align:center;
        }
        .facture-header h1{
            font-size:32px;
            margin-bottom:10px;
            color:#d4af37;
        }
        .facture-header p{
            margin:0;
            opacity:0.9;
        }
        .facture-body{
            padding:35px;
        }
        .info-box{
            background:#fafafa;
            border-radius:12px;
            padding:20px;
            margin-bottom:25px;
            border-left:5px solid #d4af37;
        }
        .info-box h4{
            color:#d4af37;
            margin-bottom:15px;
        }
        .info-row{
            display:flex;
            justify-content:space-between;
            margin-bottom:12px;
            font-size:17px;
        }
        .info-row span:first-child{
            font-weight:bold;
            color:#555;
        }
        .total-box{
            background:#d4af37;
            color:black;
            padding:20px;
            border-radius:12px;
            text-align:center;
            margin-top:20px;
        }
        .total-box h2{
            margin:0;
            font-size:35px;
            font-weight:bold;
        }
        .buttons{
            display:flex;
            justify-content:center;
            gap:20px;
            margin-top:35px;
            flex-wrap:wrap;
        }
        .btn-wave{
            background:#28a745;
            color:white;
            border:none;
            padding:14px 30px;
            border-radius:10px;
            font-weight:bold;
            font-size:16px;
            transition:0.3s;
        }
        .btn-wave:hover{
            background:#218838;
            transform:translateY(-3px);
        }
        .btn-cash{
            background:#000;
            color:white;
            border:none;
            padding:14px 30px;
            border-radius:10px;
            font-weight:bold;
            font-size:16px;
            transition:0.3s;
        }
        .btn-cash:hover{
            background:#222;
            transform:translateY(-3px);
        }
        .footer-note{
            text-align:center;
            padding:20px;
            color:#777;
            font-size:14px;
        }
        </style>
    </head>
    <body>
        <div class="facture-container">
            <div class="facture-header">
                <h1>FACTURE</h1>
                <p>
                    Confirmation de votre réservation
                </p>
            </div>
            <div class="facture-body">
                <div class="info-box">
                    <h4>Détails du bien</h4>
                    <div class="info-row">
                        <span>Type :</span>
                        <span>
                            <?= htmlspecialchars($bien['titre']) ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span>Adresse :</span>
                        <span>
                            <?= htmlspecialchars($bien['Adresse']) ?>
                        </span>
                    </div>
                </div>
                <div class="total-box">
                    <p>Montant total à payer</p>
                    <h2>
                        <?= number_format($prix,0,',',' ') ?> FCFA
                    </h2>
                </div>
                <div class="buttons">
                    <button class="btn-wave" onclick='payer("Wave", <?= json_encode($lienMarchand) ?>)'> Payer avec Wave </button>
                    <button class="btn-cash" onclick='payer("Especes", "")'> Paiement en especes </button>
                </div>
            </div>
            <div class="text-center">
                        <a href="acceuil.php" class="text-muted">
                            Annuler
                        </a>
            </div>
            <div class="footer-note">
                Merci pour votre confiance 🏡
            </div>
        </div>
        <script>
        function payer(mode,lien){
            if(mode === "Wave"){
                if(lien.trim() === ""){
                    alert("Aucun lien marchand disponible");
                    return;
                }
                window.open(lien,"_blank");
            }
            window.location.href = "ConfirmationP.php?mode=" + encodeURIComponent(mode) + "&idLoc=<?= urlencode((string)$idLoc) ?>";
        }
        </script>
    </body>
</html>