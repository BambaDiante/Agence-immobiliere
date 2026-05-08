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
    <link rel="stylesheet" href="../configuration/css/bootstrap.min.css">

    <link rel="icon"  href="../configuration/images/logoagence.jpeg">

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
            display:flex;
            flex-direction:column;
            min-height:100vh;
            width:100%;
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
            padding:28px 24px;
            background:#ffffff;
            width:min(92%, 620px);
            margin:18px auto 28px;
            border-radius:18px;
            gap:14px;
            box-shadow:0 12px 30px rgba(0,0,0,0.08);
        }
        input,select,textarea{
            border:1px solid #eeeeee;
            background:#eeeeee;
            border-radius:10px;
            padding:12px 14px;
            width:100%;
            outline:none;
        }
        label{
            width:100%;
            text-align:left;
            font-weight:600;
            color:#333;
            margin-top:4px;
        }
        input[type="submit"]{
            background:#512da8;
            padding:12px 20px;
            color:white;
            width:100%;
            border:none;
            cursor:pointer;
            font-weight:600;
            transition:0.3s;

        }
        input[type="submit"]:hover{
            background:#311b92;
            transform:translateY(-1px);

        }
        textarea{
            min-height:120px;
            max-height:260px;
            resize:vertical;
        }
        footer {
            margin-top: auto;
            width: 100%;
        }

        @media (max-width: 576px) {
            h2 {
                font-size: 1.2rem;
                text-align: center;
                padding: 0 12px;
            }

            form {
                width: calc(100% - 24px);
                padding: 22px 16px;
                gap: 12px;
            }

            input, select, textarea, input[type="submit"] {
                font-size: 0.95rem;
            }
        }
        

    </style>
</head>
<body>
   
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
    <footer class="bg-dark text-white text-center p-3">
        <p>© 2026 Agence Immobilière - Tous droits réservés</p>
    </footer>
    

</body>
</html>