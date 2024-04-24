<!DOCTYPE html>
<html lang="fr" >
  <head>
    <meta charset="UTF-8">
    <title> Messagerie </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="./CSS/messagerie.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="./JS/messagerie.js" defer> </script>
  </head>
  <body>

  <div class="container">
    <sidebar>
      <a href="accueil.php"> <img src="./images/typotwitter.png" class="logo" /> </a>
      <div class="list-wrap">
        <?php
        require 'db.php';
        
        session_start();

        if(!isset($_SESSION['id'])){
          header("Location: connexion.php");
          exit;
      }

        $id = $_SESSION['id'];

        $sql = $pdo->query("SELECT id_convo,id_user FROM messages WHERE id_convo='$id' or id_user='$id'");
        $all=[];
        while($res = $sql->fetch()){
          if($res["id_convo"] != $id){
            if (!in_array($res["id_convo"], $all)) {;
                array_push($all,$res["id_convo"]);
            }
          }
          else{
            if (!in_array($res["id_user"], $all)) {
              array_push($all,$res["id_user"]);
            }
          }
        }
        $i = 0;
        while($i < count($all)){
          $getUser = $pdo->query("SELECT username,profile_picture FROM user WHERE id='$all[$i]'");
              while($r = $getUser->fetch()){
                echo '
                <div class="list" onclick="message(' . $all[$i] . ', \'' . $r["profile_picture"] . '\', \'' . $r["username"] . '\')">
                  <img src="'. $r["profile_picture"] .'" />
                  <div class="info">
                    <span class="user">'. $r["username"] .'</span>
                  </div>
                </div>
                ';
              }
          $i++;
        }
        ?>

  </div>
    </sidebar>

    <div class="content">
      <header>

        <div id="user" class="info">
          
        </div>

        <div class="open">
          <a href="javascript:;"><i class='bx bx-menu' ></i></a>
        </div>
        
      </header>
      <div id="m" class="message-wrap">

      </div>
      
      <div id="mess" class="message-footer">
        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/1940306/ico_picture.png" class="icon-image" alt="">
        <form>
          <input type="text" id="message" placeholder="Ecrivez un message..." />
          <input id="submit" type="submit" value="Envoyer">
        </form>
      </div>
    </div>
  </div>

  </body>
</html>