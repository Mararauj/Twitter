<?php

    require 'db.php';

    function age($date){
        $date_naissance = new DateTime($date);
        $ajd = new DateTime();
        $diff= $date_naissance->diff($ajd);
        return $diff->format('%Y');
    }

    if(isset($_POST['inscription'])) {

        $arobase = $_POST['arobase'];
        $date_naissance = $_POST['dateNaissance'];
        $mail = $_POST['email'];
        $password = $_POST['motDePasse'];

        if(substr($arobase, 0, 1) != "@"){
            header("location:inscription.php");
            exit();
        }

        if(age($date_naissance) < 13){
            header("location:inscription.php");
            exit();
        }

        $res = $pdo->query("SELECT at_user_name,mail FROM user;");
        while($s = $res->fetch()){
            if($arobase == $s['at_user_name']){
                header("location:inscription.php");
                exit();
            }
            if($mail == $s['mail']){
                header("location:inscription.php");
                exit();
            }
        }

        $salt = "vive le projet tweet_academy";
        $password_salted = $salt . $password;
        $hashed_password = hash('ripemd160', $password_salted);
        $username = substr($arobase,1);
        $pdo->query("INSERT INTO user(username, at_user_name, profile_picture, banner, birthdate, mail, password) VALUES ('$username', '$arobase', 'NON', 'NON', '$date_naissance', '$mail', '$hashed_password')");
        header("location:connexion.php");
        exit();
    }

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./CSS/inscription.css">
        <link rel="stylesheet" href="./CSS/normalize.css">
        <title>Inscription</title>
        
    </head>
    <body>
        <form method="post">
            <div class="container">
                <div class="row">
                    <div class="six columns">
                        <img src="./images/twitter.png" alt="logo twitter" id="logo">
                        <img src="./images/typotwitter.png" alt="typo twitter" id="typo">
                        <br>
                    </div>

                    <div class="six columns" id="right">    
                        <input type="text" id="arobase" name="arobase" placeholder="Arobase" value="@" required><br>
                        <br>

                        <input type="email" id="email" name="email" placeholder="Adresse mail" autocomplete="on" required><br>
                        <br>

                        <input type="password" id="motDePasse" name="motDePasse" placeholder="Mot de passe" required><br>
                        <br>

                        <input type="date" id="dateNaissance" name="dateNaissance" required><br>
                        <br>

                        <button type="submit" name="inscription">S'inscrire</button><br>
                        <br>
                        <p>Vous avez déjà un compte ? <a href="connexion.php">Connectez-vous ici</a></p>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>