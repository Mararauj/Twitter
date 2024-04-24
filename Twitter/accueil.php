<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/accueil.css">
    <link rel="icon" href="./images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="./JS/accueil.js"defer></script>
    <script src="./JS/theme_accueil.js" defer></script>
    <title>Accueil</title>
</head>
<body>
    <?php

        session_start();

        if(!isset($_SESSION['id'])){
            header("Location: connexion.php");
            exit;
        }
    ?>

<div class="container">
    <div class="logo">
        <img src="./images/logo.png" alt="twitter">
    </div>
    <div class="list">
        <ul>
            <a href="accueil.php">
                <div id="home">
                    <li><img src="./images/home.png" alt="home">Home</li>
                </div>
            </a>
            <div id="recherche" onclick="recherche()">
                <li><img src="./images/loupe.png" alt="recherche">Recherche</li>
            </div>
            <a href="messagerie.php">
                <div id="message">
                    <li><img src="./images/message.png" alt="message">Message</li>
                </div>
            </a>
            <a href="profil.php">
                <div id="profil">
                    <li><img src="./images/profil.png" alt="profil">Profil</li>
                </div>
            </a>
            <div class="switch-box">
                <i class="fas fa-moon"></i>
            </div>
        </ul>
    </div>
</div>

<div class="container2">

    <div class="search-bar">
    <input id="barre" type="text" placeholder="Recherche">
    <button onclick="recherche()" type="submit">Recherche<i class="fas fa-search"></i></button>
    </div>
</div>

<div class="tweet">

    <div class="post-section">
        <textarea placeholder="Quoi de neuf ?" id="postContent" maxlength="140"></textarea>
        <input type="file" id="photo" accept="image/*">
        <button type="button" id="postButton">Tweeter</button>
        
    </div>

    <div id="all" class="tweetotal">
       
    </div>

</div>


</body>
</html>