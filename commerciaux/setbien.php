<?php
   session_start();

   if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true) {
       header("Location:authentification.php");
       exit;
   }
   require_once "../configuration/connexion.php";
   require_once "../configuration/verification.php";
   
   $ext_ok = ['gif', 'jpg', 'jpeg', 'png'];
   $bien = [];
   $message = "";
   
   if (isset($_POST['id'])) {
       $recup = $pdo->prepare("SELECT * FROM bien_imm WHERE IdBien = :id");
       $recup->execute([
           ":id" => $_POST['id']
       ]);
       $bien = $recup->fetch(PDO::FETCH_ASSOC);
   }

   if (isset($_POST['confirm'], $_POST['id'], $_POST['title'], $_POST['type'], $_POST['sup'], $_POST['addr'], $_POST['nbre'], $_POST['price'], $_POST['desc'])) {
       $update = $pdo->prepare("UPDATE bien_imm SET titre = :titre, Type = :Type, Superficie = :Superficie, Adresse = :Adresse, Description = :Description, Prix_jour = :Prix_jour, nbre_pieces = :nbre_pieces WHERE IdBien = :IdBien");
       $result = $update->execute([
           ":titre" => $_POST['title'],
           ":Type" => $_POST['type'],
           ":Superficie" => $_POST['sup'],
           ":Adresse" => $_POST['addr'],
           ":Description" => $_POST['desc'],
           ":Prix_jour" => $_POST['price'],
           ":nbre_pieces" => $_POST['nbre'],
           ":IdBien" => $_POST['id']
       ]);

       if ($result) {
           // Traitement des images si elles sont uploadées
           if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
               if (!is_dir('photos')) {
                   mkdir('photos', 0777, true);
               }
               for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                   if ($_FILES['images']['name'][$i]) {
                       if (valid_extension($_FILES['images']['name'][$i], $ext_ok)) {
                           $chemin = move_file($_FILES['images']['tmp_name'][$i], "photos", $_FILES['images']['name'][$i]);
                           if ($chemin) {
                               $insert_photo = $pdo->prepare("INSERT INTO photos(url,idBien) VALUES (:url,:idBien)");
                               $insert_photo->execute([
                                   ":url" => $chemin,
                                   ":idBien" => $_POST['id']
                               ]);
                           }
                       }
                   }
               }
           }
           header("Location: consult.php");
           exit;
       } else {
           $message = "Erreur lors de la modification.";
       }
   }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de bien immobilier</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        body{
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            min-height: 100vh;
            padding: 20px;
        }
        h1, h2{
            text-align: center;
            margin: 10px 0;
        }
        form{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 15px;
            background: #ffffff;
            width: 50%;
            margin: 15px auto;
            border-radius: 15px;
            gap: 20px;
        }
        input, select, textarea{
            border: 1px solid #eeeeee;
            background: #eeeeee;
            border-radius: 5px;
            padding: 8px;
            width: 40%;
            outline: none;
        }
        input[type="submit"]{
            background: #512da8;
            color: white;
            width: 30%;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        textarea{
            min-width: 40%;
            max-width: 70%;
            min-height: 120px;
        }
        .message{
            width: 50%;
            margin: 10px auto;
            padding: 12px;
            background: #ffdede;
            border-left: 4px solid #d32f2f;
            border-radius: 8px;
            color: #7a1c1c;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Modification de bien immobilier</h1>
    <h2>Modifier les informations du bien</h2>

    <?php if ($message !== "") { echo "<div class='message'>" . $message . "</div>"; } ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $bien['IdBien'] ?? ($_POST['id'] ?? ''); ?>">

        <label for="type">Type de bien immobilier:</label>
        <select name="type" id="type">
            <option value="app" <?php echo (($bien['Type'] ?? ($_POST['type'] ?? '')) === 'app') ? 'selected' : ''; ?>>Appartement</option>
            <option value="vil" <?php echo (($bien['Type'] ?? ($_POST['type'] ?? '')) === 'vil') ? 'selected' : ''; ?>>Villa</option>
        </select>

        <input type="text" name="title" placeholder="Titre" value="<?php echo htmlspecialchars($bien['titre'] ?? ($_POST['title'] ?? '')); ?>">
        <input type="number" name="sup" placeholder="Superficie(en m²)" value="<?php echo htmlspecialchars($bien['Superficie'] ?? ($_POST['sup'] ?? '')); ?>">
        <input type="text" name="addr" placeholder="Adresse" value="<?php echo htmlspecialchars($bien['Adresse'] ?? ($_POST['addr'] ?? '')); ?>">
        <input type="number" name="nbre" placeholder="Nombre de pieces" value="<?php echo htmlspecialchars($bien['nbre_pieces'] ?? ($_POST['nbre'] ?? '')); ?>">
        <input type="number" name="price" placeholder="Prix journalier" value="<?php echo htmlspecialchars($bien['Prix_jour'] ?? ($_POST['price'] ?? '')); ?>">
        <textarea name="desc" placeholder="Description de votre Bien immobilier"><?php echo htmlspecialchars($bien['Description'] ?? ($_POST['desc'] ?? '')); ?></textarea>
        <input type="file" name="images[]" accept="image/gif, image/jpeg , image/png" multiple>
        <input type="submit" name="confirm" value="Valider la modification">
    </form>
</body>
</html>