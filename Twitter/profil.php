<?php
require 'db.php';

session_start();

if(!isset($_SESSION['id'])){
    header("Location: connexion.php");
    exit;
}

$currentUserId = $_SESSION['id'];

if(!isset($_GET['arobase'])){
    $profileUserId = $currentUserId;
}
else{
    $query = $pdo->prepare("SELECT id FROM user WHERE at_user_name= ?");
    $query->execute([$_GET['arobase']]);
    $profil_aro = $query->fetch();

    $profileUserId = $profil_aro['id'];
}

if(isset($_POST['deco'])){
    session_destroy();
    header("Location: connexion.php");
    exit;
}

$query = $pdo->prepare("SELECT at_user_name, username, bio FROM user WHERE id = ?");
$query->execute([$profileUserId]);
$profil = $query->fetch();

$query = $pdo->prepare("SELECT COUNT(*) FROM follow WHERE id_user = ? AND id_follow = ?");
$query->execute([$currentUserId, $profileUserId]);
$isFollowing = $query->fetchColumn() > 0;

$query = $pdo->prepare("SELECT content, time FROM tweet WHERE id_user = ? ORDER BY time DESC");
$query->execute([$profileUserId]);
$tweets = $query->fetchAll();

$query = $pdo->prepare("SELECT u.id, u.username FROM user u JOIN follow f ON u.id = f.id_follow WHERE f.id_user = ?");
$query->execute([$profileUserId]);
$followings = $query->fetchAll();

$query = $pdo->prepare("SELECT u.id, u.username FROM user u JOIN follow f ON u.id = f.id_user WHERE f.id_follow = ?");
$query->execute([$profileUserId]);
$followers = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/profil.css">
    <link rel="icon" href="./images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="./JS/profil.js" defer></script>
    <script src="./JS/theme_profil.js" defer></script>
    
    <title>Profil</title>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="./images/logo.png" alt="twitter">
    </div>
    <div class="list">
        <ul>
            <div id="home">
                <a href="accueil.php"><li><img src="./images/home.png" alt="home"> Home </li></a>
            </div>
            <div id="recherche">
                <a href="accueil.php"><li><img src="./images/loupe.png" alt="recherche" > Recherche </li></a>
            </div>
            <div id="message">
                <a href="messagerie.php"><li><img src="./images/message.png" alt="message"> Message </li></a>
            </div>
            <div id="profil">
                <a href="profil.php"><li><img src="./images/profil.png" alt="profil"> Profil </li></a>
            </div>
        <div class="switch-box">
            <i class="fas fa-moon"></i>
        
        </div>
        </ul>
    </div>
</div>

<div class="tweet">
  <div class="tweetotal">
  <div class="profil">
<div class="bg-profil">
    <div class="profil-info">
        <img src="./images/profile.png" id="img-profile">
        <h1><?= htmlspecialchars($profil['username']); ?></h1>
        <small id="arobase"><?= htmlspecialchars($profil['at_user_name']); ?></small>
        <p ><?= nl2br(htmlspecialchars($profil['bio'])); ?></p>
      
    <div class="follow-info">
        <a href="abonements.php?id=<?php echo $profileUserId?>"><h2 id="abonnements"> <?= count($followings); ?> Abonnements  </h2></a>
        <a href="abonnes.php?id=<?php echo $profileUserId?>"><h2 id="abonnes"> <?= count($followers); ?> Abonnés </h2></a>
    </div>

    <?php if (isset($_GET['arobase'])): ?>
        <button class="button button-primary" id="ba"></button>
        <a href="messagerie.php"><button class="button" id="msg"> Envoyer un message </button></a>
    <?php endif; ?>
    
    <?php if (!isset($_GET['arobase'])): ?>
        <a class="button button-primary"  href="infos_compte.php">Éditer le profil</a>
        <form method="post">
            <button class="button" name="deco"> Déconnexion </button>
        </form>
    <?php endif; ?>
    
    </div>
    
    </div>

    <div id="post" class="post">
        <h2 id="title-post">Tweets</h2>
            
    </div>




</div>


</div>
</div>
</div>
</div>

</body>