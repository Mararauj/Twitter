<?php

    require 'db.php';
    session_start();
    
    if(!isset($_SESSION['id'])){
        header("Location: connexion.php");
        exit;
    }

    $id = $_SESSION['id'];
    $sql = $pdo->query("SELECT * FROM user WHERE id = '$id'");

    while($res = $sql->fetch()){
        $username = $res['username'];
        $at_user_name = $res['at_user_name'];
        $birthdate = $res['birthdate'];
        $mail = $res['mail'];
        $bio = $res['bio'];
    }


    if(isset($_POST['valid'])){
        $salt = "vive le projet tweet_academy";
        $password_salted = $salt . $_POST['password'];
        $hp = hash('ripemd160', $password_salted);
        $p = $_POST['pseudo'];
        $m = $_POST['mail'];
        $b = $_POST['bio'];
        
        if($m == $mail){
            $pdo->query("UPDATE user set username='$p', password='$hp', bio='$b' WHERE id='$id'");
        }
        else{
            $pdo->query("UPDATE user set username='$p', mail='$m', password='$hp', bio='$b' WHERE id='$id' ");
        }
        header('Location: profil.php');
        exit();
    }

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./CSS/infos_compte.css">
        <title>Modification</title>
    </head>
    <body>
        <div class="container">
            <form method="post">
                <h2>Mon compte</h2>

                <h3>Modification de données</h3>

                <div class="modification">
                    <input type="file" name="photo" id="photo" accept="image/*">
                </div>

                <div class="modification">
                    <p><?php echo $at_user_name ?></p>
                </div>
                <br>

                <div class="modification">
                    <input type="text" id="pseudo" name="pseudo" value="<?php echo $username ?>" placeholder="Pseudo" required>
                </div>
                <br>

                <div class="modification">
                    <p><?php echo $birthdate ?></p>
                </div>
                <br>

                <div class="modification">
                    <input type="email" id="email" name="mail" value="<?php echo $mail ?>" autocomplete="on" placeholder="Adresse mail" required>
                </div>
                <br>

                <div class="modification">
                    <input type="password" id="motDePasse" name="password" placeholder="Mot de passe" required>
                </div>
                <br>

                <div class="bio">
                    <textarea name="bio" placeholder="Biographie" maxlength="140" rows="5"><?php if($bio==NULL){echo "";}else{echo $bio;}?></textarea>
                </div>
                <br>

                <div class="btn-center">
                    <button type="submit" name="valid">Changer</button><br>
                </div>
                <br>
            </form>
        </div>
    </body>
</html>