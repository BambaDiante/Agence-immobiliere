<?php
     session_start();
     require("../configuration/connexion.php");
     $sql = "SELECT * FROM bien_imm";
     $stmt = $pdo->prepare($sql);
     $stmt->execute();
     $biens = $stmt->fetchAll();
     function getImage($pdo, $id){
        $sql = "SELECT * FROM photos WHERE idBien = ? LIMIT 6";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }
    function isFavori($pdo,  $idBien){
        $sql = "SELECT * FROM favoris WHERE idBien=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([ $idBien]);
        return $stmt->rowCount() > 0;
    }
    
?>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>Acceuil agence immobiliere </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
            <style>
                .hero {
                    height: 700px;
                    background-image: url("imageL/immobilier5.jpg"); /* ton image */
                    background-size: cover;
                    background-position: center;
                    position: relative;
                }

                .overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 100;
                    background: rgba(0,0,0,0.5);

                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;

                    color: white;
                    text-align: center;
                }

                .overlay p {
                    font-size: 40px;
                    font-weight: bold;
                }

                .overlay p {
                    
                }
                .hero span {
                    font-size: 3rem;
                    font-weight: bold;
                }
                .hero p {
                    font-size: 2.5rem;
                }
                p{
                    backdrop-filter:blur(12px);
            
                }
        </style>
    </head>
    <body>
        <header class="navbar bg-white shadow-sm px-4">
                
            <div class="container-fluid d-flex align-items-center justify-content-between">

                        <!-- LOGO -->
                <div>
                     <img src="imageL/logoAgence.png" style="height:130px;">
                </div>

                        <!-- RECHERCHE -->
                <form class="d-flex w-50">
                    <input class="form-control me-2" type="search" placeholder="Rechercher un bien...">
                    <button class="btn btn-dark">
                         <i class="fa fa-search"></i>
                     </button>
                </form>
                        <!-- MENU DROITE -->
                <div class="dropdown">
                    <button class="btn btn-light" data-bs-toggle="dropdown">
                        <i class="fa fa-bars fs-4"></i>
                    </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                            <a class="dropdown-item" href="favoris.php">
                                <i class="fa fa-heart text-danger"></i> Favoris
                            </a>
                        </li>
                            <li>
                                <a class="dropdown-item" href="historique.php">
                                    <i class="fa fa-clock"></i> Historique
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                            <a class="dropdown-item text-danger" href="deconnexion.php">
                            <i class="fa fa-sign-out-alt"></i> Déconnexion
                            </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="authentification.php">
                                    <i class="fa fa-sign-in-alt"></i> Connexion
                                </a>
                            </li>
                        </ul>
                </div>
        </header>    
       
    
        <div class="bg-light text-center p-5  hero" >
            <div class="overlay">
                <p>
                    <span>Bienvenue sur notre agence immobilière <br></span>
                    Trouvez votre appartement ou villa idéale
               </p>
            </div>    
        </div>
               
        <div class="container mt-5">
           <h2 class="text-center mb-4">Nos biens disponibles</h2>
           <div class="row">
           <?php foreach($biens as $bien): ?>
            <div class="col-md-4 mb-4">
            <div class="card shadow position-relative">
                      <!-- BOUTON FAVORIS ❤️ -->
                <div class="position-absolute top-0 end-0 m-2">      
                    <?php $isFav = isFavori($pdo,  $bien['IdBien']); ?>
                    <a href="favoris_action.php?id=<?= $bien['IdBien'] ?>" 
                         class="btn btn-light rounded-circle shadow">
                        <i class="fa fa-heart <?= $isFav ? 'text-danger' : 'text-secondary' ?>"></i>
                    </a>
                    <a href="authentification.php" 
                        class="btn btn-light rounded-circle shadow">
                          <i class="fa fa-heart text-secondary"></i>
                    </a>
                </div>
                             <!-- IMAGE --> 
                             <?php $images = getImage($pdo, $bien['IdBien']); ?>

<?php if (!empty($images)): ?>

<div id="carousel<?= $bien['IdBien'] ?>" class="carousel slide" data-bs-ride="carousel">

    <div class="carousel-inner">
        <?php $first = true; ?>

        <?php foreach($images as $image): ?>
            <div class="carousel-item <?= $first ? 'active' : '' ?>">
                <img src="../COMMERCIAUX/imagesBiens/<?= $image['url'] ?>"
                     class="d-block w-100"
                     style="height:200px; object-fit:cover;">
            </div>
            <?php $first = false; ?>
        <?php endforeach; ?>
    </div>

   <!-- <button class="carousel-control-prev" type="button"
            data-bs-target="#carousel<?= $bien['IdBien'] ?>" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button"
            data-bs-target="#carousel<?= $bien['IdBien'] ?>" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>-->

</div>

<?php else: ?>
    <img src="imageL/immobilier5.jpg" style="width:100%; height:200px; object-fit:cover;">
<?php endif; ?>

                    </div>
                    <div class="card-body info">
                        <!-- TYPE -->
                        <h5 class="card-title">
                            <?= $bien['Type'] ?>
                        </h5>
                       <!-- <div class="d-flex gap-2">
                        <?php //foreach($images as $image): ?>
                            <img src="../COMMERCIAUX/imagesBiens/<?//= $image['url'] ?>"
                                style="width:70px; height:70px; object-fit:cover;">
                        <?php// endforeach; ?>
                        </div>-->
                        <!-- INFOS -->
                        <p classe="info">
                            📍 <?= $bien['Adresse'] ?><br>
                            💰 <?= $bien['Prix_jour'] ?> FCFA / jour<br>
                            📐 <?= $bien['Superficie'] ?> m²<br>
                            🏠 <?= $bien['nbre_pieces'] ?> pièces
                        </p>

                        <!-- BOUTONS -->
                        <a href="details.php?id=<?= $bien['IdBien'] ?>" class="btn btn-primary btn-sm">
                            Détails
                        </a>
                        <!--<a href="reservation.php?id=<?= $bien['IdBien'] ?>" class="btn btn-success btn-sm">
                                Réserver
                        </a> -->
                        <div class="text-center mt-4">
                            <a href="appartement.php" class="btn btn-dark btn-lg">
                            Voir plus de biens
                            </a>
                        </div>
                    </div>
                </div>
            </div>
              <?php endforeach; ?>
           </div>
     </div>

     <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2026 Agence Immobilière - Tous droits réservés</p>
     </footer>

  </body>
     
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
