<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
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
            background-size:cover;
            background-attachment:fixed;
            min-width:100vw;
            min-height: 100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            flex-direction:column;
            gap:20px;
        }
        .container{
            
            max-width:80vw;
            min-width:80vw;
            min-height:600px;
            border-radius:15px;
            border:1px solid #ffffff;
            background:#ffffff;
        }
       

        form{
            margin-top:20px;
            border:1px solid transparent;
            border-radius:15px;
            background:#fff;
            height:50px;
            padding:15px;
        }

    </style>
</head>
<body>
    <form class="search" action="" method="POST">
        <input type="search" class="search-input" name="rech" placeholder="Rechercher un client">
        <button type="submit" name="barre" class='barre'>rechercher</button>
    </form>
    <div class="container">

    </div>
    
</body>
</html>