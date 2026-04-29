<?php
    session_start();

    if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true) {
        header("Location:authentification.php");
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accceuil</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body{
            background-color: #c9d6ff;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            width:100%;
            min-height: 100vh;
        }
        h1,h2{
            display:flex;
            align-items:center;
            justify-content:center;
            margin:10px;
        }
        form{
            display:flex;
            align-items:center;
            justify-content:center;
            flex-direction:column;
            padding:15px;
            background:#ffffff;
            width:50%;
            height:500px;
            margin:15px auto;
            border-radius:15px;
            gap:20px;
        }
        input,select,textarea{
            border:1px solid #eeeeee;
            background:#eeeeee;
            border-radius:5px;
            padding:5px;
            width:40%;
            outline:none;
        }
        input[type="submit"]{
            background:#512da8;
            padding-left:20px;
            padding-right:20px;
            padding-top:10px;
            padding-bottom:10px;
            color:white;
            width:30%;

        }
        textarea{
            min-width:40%;
            max-width:70%;
            max-height:400px;        
        }
        

    </style>
</head>
<body>
    <?php
        echo "<h1>".$_SESSION['nom']."</h1>";
    ?>
    <h2>Formulaire d'ajout de bien immobilier</h2>
    <form action="ajoutbien.php" method="POST" enctype="multipart/form-data">

        <label for="type">Type de bien immobilier:</label>
        <select name="type">
                <option value="app">Appartement</option>
                <option value="vil">Villa</option>
        </select>
        <input type="text" name="title" placeholder="Titre">
        <input type="number" name="sup" placeholder="Superficie(en m²)">
        <input type="text" name="addr" placeholder="Adresse">
        <input type="number" name="nbre" placeholder="Nombre de pieces">
        <input type="number" name="price" placeholder="Prix journalier">
        <textarea name="desc" placeholder="Description de votre Bien immobilier"></textarea>
        <input type="file" name="images[]" accept="image/gif, image/jpeg , image/png" multiple>
        <input type="submit" value="Ajouter">
    </form>
</body>
</html>