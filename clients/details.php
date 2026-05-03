<?php
    session_start();
    require("bd.php");

    if (!isset($_POST['idBien'])) {
        die("Aucun bien sélectionné.");
    }

    $idBien = $_POST['idBien'];

    // Récupérer le bien
    $sql = "SELECT * FROM bien_imm WHERE IdBien = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$idBien]);
    $bien = $stmt->fetch();

    // Récupérer les images
    function getImage($connexion, $id){
        $sql = "SELECT * FROM photos WHERE idBien = ?";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }
    $images = getImage($connexion, $idBien);

    function isFavori($connexion, $idBien){
        if(!isset($_SESSION['IdUser'])) return false;

        $sql = "SELECT * FROM favoris WHERE idBien=? AND idUser=?";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$idBien, $_SESSION['IdUser']]);
        return $stmt->rowCount() > 0;
    }
?>

<html>
    <head>
        <meta charset="utf-"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

        <style>
          body {
                background: #fdf6e3; /* beige clair */
                font-family: 'Georgia', serif;
                color: #333;
            }

            .details-container {
                max-width: 900px;
                margin: 40px auto;
                padding: 20px;
            }

            .details-card {
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 8px 20px rgba(0,0,0,0.15);
                background: #fffaf0; /* blanc cassé */
                border: 2px  #d4af37; /* doré */
                transition: transform 0.3s ease;
            }

            .details-card:hover {
                transform: translateY(-8px);
            }

            .details-main-img {
                width: 100%;
                max-height: 400px;
                object-fit: contain;
                background: #fffaf0; /* blanc cassé  */
                border-bottom: 2px  #d4af37;
            }

            .details-thumbs {
                display: flex;
                gap: 12px;
                overflow-x: auto;
                padding: 15px;
                background: #f8f1dc; /* beige doux */
            }

            .details-thumbs img {
                width: 100px;
                height: 100px;
                object-fit: cover;
                border-radius: 8px;
                cursor: pointer;
                transition: transform 0.2s ease, border 0.2s ease;
                border: 2px  transparent;
            }

            .details-thumbs img:hover {
                transform: scale(1.1);
                border: 2px  #d4af37; /* doré */
            }

            .details-info {
                padding: 20px;
                font-size: 16px;
                line-height: 1.6;
                background: #fffaf0;
                border-top: 2px  #d4af37;
            }

            .details-info strong {
                color: #d4af37; /* doré pour les titres */
            }

            .btn-dark {
                background-color: #000;
                color: #d4af37;
                border: 2px solid #d4af37;
                font-weight: bold;
            }

            .btn-dark:hover {
                background-color: #d4af37;
                color: #000;
            }

            .btn-success {
                background-color: #d4af37;
                color: #000;
                border: none;
                font-weight: bold;
            }

            .btn-success:hover {
                background-color: #b7950b; /* doré plus foncé */
                color: #fff;
            }
            .btn-success {
                background-color: #28a745; /* vert Bootstrap */
                color: #fff;
                border: none;
                font-weight: bold;
                margin:auto;
            }
            .btn-success:hover  {
                background-color: #218838; /* vert plus foncé au survol */
                color: #fff;
            }

        </style>
    </head>
    <body>
        <div class="details-container">
            <div class="card details-card position-relative">

                <!-- Bouton favoris -->
                <div class="position-absolute top-0 end-0 m-2">
                    <!-- ton code favoris ici -->
                    <?php if (isset($_SESSION['IdUser'])): ?>   
                                            <?php $isFav = isFavori($connexion,  $bien['IdBien']); ?>
                                            <a href="favoris_action.php?id=<?= $bien['IdBien'] ?>" 
                                                class="btn btn-light rounded-circle shadow btn-favori" data-id="<?= $bien['IdBien'] ?>">
                                                <i class="fa fa-heart <?= $isFav ? 'text-danger' : 'text-secondary' ?>"></i>
                                            </a>
                                            <?php else: ?>
                                        <a href="authentification.php " 
                                                class="btn btn-light rounded-circle shadow btn-favori" data-id="<?= $bien['IdBien'] ?>">
                                                <i class="fa fa-heart text-secondary"></i>
                                            </a>
                                            <?php endif; ?>
                </div>

                <!-- Image principale -->
                <img id="mainImg<?= $bien['IdBien'] ?>"
                    src="../commerciaux/<?= htmlspecialchars($images[0]['url']) ?>"
                    class="details-main-img">

                <!-- Miniatures -->
                <div class="details-thumbs">
                    <?php foreach($images as $img): ?>
                        <img src="../commerciaux/<?= htmlspecialchars($img['url']) ?>"
                            onclick="document.getElementById('mainImg<?= $bien['IdBien'] ?>').src=this.src">
                    <?php endforeach; ?>
                </div>

                <!-- Infos -->
                <div class="details-info">
                    <p><strong>📍 Adresse :</strong> <?= $bien['Adresse'] ?></p>
                    <p><strong>💰 Prix :</strong> <?= $bien['Prix_jour'] ?> FCFA / jour</p>
                    <p><strong>📐 Superficie :</strong> <?= $bien['Superficie'] ?> m²</p>
                    <p><strong>🏠 Pièces :</strong> <?= $bien['nbre_pieces'] ?></p>
                    <p><strong>Descriptions :</strong><?= $bien['Description'] ?></p>
                </div>
                <form action="reservation.php" method="POST">
                            <input type="hidden" name="idBien" value="<?= $idBien ?>">
                            <button type="submit" class="btn btn-success btn-sm">
                                Réserver
                            </button>
                </form> 
             </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function(){
                $(".btn-favori").on("click", function(e){
                    e.preventDefault();

                    let btn = $(this);
                    let idBien = btn.data("id");

                    $.get("favoris_action.php?id=" + idBien, function(){
                        // Toggle automatique sans attendre de réponse texte
                        let icon = btn.find("i");
                        icon.toggleClass("text-danger text-secondary");
                    }).fail(function(){
                        // Si non connecté, redirection vers login
                        window.location.href = "authentification.php";
                    });
                });
            });
        </script>


    </body>
</html>