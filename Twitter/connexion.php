<?php

    require 'db.php'; 
    session_start();

    if (isset($_POST['connect'])) {

        $mail = $_POST['mail'];
        $password = $_POST['mdp'];

        $salt = "vive le projet tweet_academy";
        $password_salted = $salt . $password;
        $hashed_password = hash('ripemd160', $password_salted);

        $sql = $pdo->query("SELECT id, mail, password FROM user WHERE mail = '$mail' AND password = '$hashed_password'");
        if ($sql->rowCount() > 0){
            
            while($s = $sql->fetch()){
                $_SESSION['id'] = $s['id'];
            }
            header("Location: accueil.php");
            exit();
        }

        header("Location: connexion.php");

    }

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./CSS/normalize.css">
        <link rel="stylesheet" href="./CSS/connexion.css">
        <title>Connection</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="six columns">
                   <img src="./images/twitter.png" alt="logo twitter" id="logo">
                </div>
                <div class="six columns">
                    <img src="./images/typotwitter.png" alt="typo twitter" id="typo">
                    <br>
                    <form method="post">
                        <input type="email" id="username" name="mail" placeholder="Adresse mail" autocomplete="off" required>
                        
                        <input type="password" id="password" name="mdp" placeholder="Mot de passe" autocomplete="off" required>
                        
                        <button type="submit" name="connect">Se connecter</button>
                    </form>
                    <p>Vous n'avez pas de compte ? <a href="inscription.php">Inscrivez-vous ici</a></p>
                </div>
            </div>
        </div>
    </body>
</html>