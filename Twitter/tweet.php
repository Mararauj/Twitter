<?php

    require 'db.php';

    session_start();

    $id = $_SESSION['id'];

    $idL = isset($_POST['idL']) ? $_POST['idL'] : null;

    if(!empty($idL)){
        $rL = $pdo->query("select * from likes where id_user=$id and id_tweet=$idL");
        if($rL->rowCount() == 0) {
            $pdo->query("INSERT INTO likes (id_user,id_tweet) VALUES ($id,$idL)");
        }
        else{
            $like = $pdo->query("delete from likes where id_user=$id and id_tweet=$idL");
        }
        
    }

    $idR = isset($_POST['idR']) ? $_POST['idR'] : null;

    if(!empty($idR)){

        $rR = $pdo->query("select * from retweet where id_user=$id and id_tweet=$idR");
        if($rR->rowCount() == 0) {
            $pdo->query("INSERT INTO retweet (id_user,id_tweet) VALUES ($id,$idR)");
        }
        else{
            $pdo->query("delete from retweet where id_user=$id and id_tweet=$idR");
        }
        
    }

    $result = array();

    $content = isset($_POST['content']) ? $_POST['content'] : null;
    $rep = isset($_POST['rep']) ? $_POST['rep'] : NULL;
    $photo = isset($_POST['photo']) ? $_POST['photo'] : null;


    if(!empty($content) || !empty($photo)){
        if($photo != null){
            $chemin = './images/' . $photo;
        }
        $sql = $pdo->prepare("INSERT INTO tweet (id_user,id_response,content) VALUES (?,?,?)");
        $sql->execute([$id, $rep, $content.$chemin]);
        
        $hashtags = getHashtags($content);
    
        if ($hashtags !== false) {
            
            foreach ($hashtags as $hashtag => $position) {
                $sql = $pdo->query("select hashtag from hashtag_list where hashtag='$hashtag'");
                if($sql->rowCount() == 0) {
                    $hh = $pdo->prepare("INSERT INTO hashtag_list(hashtag) VALUES (?)");
                    $hh->execute([$hashtag]);
                }

            }
        }
    }

    if(isset($_GET['t'])){

        $all = array();
        
        $sql = $pdo->query("SELECT id_follow from follow join user on follow.id_user=user.id WHERE id_user='$id'");
        while($r = $sql->fetch()){
            $all[] = $r['id_follow'];
        }

        $all[] = $id;


        $imploded_arr = implode(',', $all);
        
        $t = $pdo->query("SELECT DISTINCT username, at_user_name, profile_picture, content, id_response, tweet.id, tweet.time 
                        FROM tweet
                        LEFT JOIN user ON tweet.id_user = user.id
                        LEFT JOIN likes ON tweet.id = likes.id_tweet
                        LEFT JOIN retweet ON tweet.id = retweet.id_tweet
                        WHERE likes.id_user IN ($imploded_arr) OR user.id IN ($imploded_arr) OR retweet.id_user IN ($imploded_arr) OR tweet.id_user IN ($imploded_arr)
                        ORDER BY tweet.time DESC;");

        while($res = $t->fetch(PDO::FETCH_ASSOC)){
            $result['t'][] = $res;
        }
       
    }

    if(isset($_GET['arobase'])){
        $arobase = $_GET['arobase'];
        $aro = $pdo->query("select profile_picture, username, at_user_name from user where at_user_name like '$arobase%' and id!='$id'");
        while($ar = $aro->fetch(PDO::FETCH_ASSOC)){
            $result['ar'][] = $ar;
        }
    }

    if(isset($_GET['hashtag_list'])){
        $hashtag = $_GET['hashtag_list'];
        $hash = $pdo->query("select hashtag from hashtag_list where hashtag like '$hashtag%';");
        while($hs = $hash->fetch(PDO::FETCH_ASSOC)){
            $result['hs'][] = $hs;
        }
    }

    if(isset($_GET['hashtag'])){

        $hashtag = $_GET['hashtag'];
        
        $hatg = $pdo->query("select username,at_user_name,profile_picture,content,id_response,tweet.id from tweet join user on tweet.id_user=user.id    
                            where content like '%$hashtag%' order by tweet.time desc;");

        while($h = $hatg->fetch(PDO::FETCH_ASSOC)){
            $result['h'][] = $h;
        }
    }

    function getHashtags($content) {
        preg_match_all('/#(\w+)/', $content, $matches, PREG_OFFSET_CAPTURE);
        
        $hashtags = array();
        
        if (!empty($matches[0])) {
            foreach ($matches[0] as $match) {
                $hashtags[$match[0]] = $match[1];
            }
            return $hashtags;
        } else {
            return false;
        }
    }

    $repondre = isset($_POST['repondre']);

    if(!empty($repondre) || trim($repondre)!=""){
        $repondre = $_POST['repondre'];
        $id_t = $_POST['id_t'];
        $rep_sql = $pdo->prepare("INSERT INTO tweet (id_user,id_response,content) VALUES (?,?,?)");
        $rep_sql->execute([$id, $id_t, $repondre]);
    }

/*
    select username,at_user_name,profile_picture,content,id_response from tweet 
    left join user on tweet.id_user=user.id 
    left join likes on tweet.id=likes.id_tweet
    left join retweet on tweet.id=retweet.id_tweet 
    where likes.id_user=2 or user.id=2 order by tweet.time asc;


    select username,at_user_name,profile_picture,content,id_response from tweet      
    left join user on tweet.id_user=user.id      
    left join likes on tweet.id=likes.id_tweet     
    left join retweet on tweet.id=retweet.id_tweet      
    where likes.id_user=1 or user.id=1 or retweet.id_user=1 order by tweet.time asc;
*/   

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    echo json_encode($result);

?>