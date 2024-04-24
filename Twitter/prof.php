<?php

    require 'db.php';

    session_start();

    $id = $_SESSION['id'];

    $result = array();

    if(isset($_POST['aroA'])){
        $aro = $_POST['aroA'];

        $sql = $pdo->query("SELECT id from user WHERE at_user_name='$aro'");
        while($r = $sql->fetch()){
            $p_id = $r['id'];
        }

        $pdo->query("insert into follow(id_user,id_follow) values($id,$p_id)");

    }

    if(isset($_POST['aroD'])){
        $aro = $_POST['aroD'];

        $sql = $pdo->query("SELECT id from user WHERE at_user_name='$aro'");
        while($r = $sql->fetch()){
            $p_id = $r['id'];
        }

        $pdo->query("delete from follow where id_user=$id and id_follow=$p_id");

    }

    if(isset($_POST['aroMSG'])){
        $aro = $_POST['aroMSG'];

        $sql = $pdo->query("SELECT id from user WHERE at_user_name='$aro'");
        while($r = $sql->fetch()){
            $p_id = $r['id'];
        }
        $sql = $pdo->prepare("INSERT INTO messages (id_convo,id_user,content) VALUES (?,?,?)");
        $result['send_status'] = $sql->execute([$id, $p_id, '']);

    }

    if(isset($_GET['arobase'])){
        
        if($_GET['arobase']==="nul"){
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

        else{
            $all = array();

            $p = $_GET['arobase'];

            $sql = $pdo->query("SELECT id from user WHERE at_user_name='$p'");
            while($r = $sql->fetch()){
                $p_id = $r['id'];
            }

            $p_id = intval($p_id);

            $sql = $pdo->query("SELECT id_user from follow WHERE id_user='$id' and id_follow='$p_id'");
            while($r = $sql->fetch()){
                $result['suivre'][] = $r['id_user'];
            }
            
            $sql = $pdo->query("SELECT id_follow from follow join user on follow.id_user=user.id WHERE id_user='$p_id'");
            while($r = $sql->fetch()){
                $all[] = $r['id_follow'];
            }

            $all[] = $p_id;

            if (!empty($all)) {
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
            else{
                $result['feur'] = "feur";
            }

        }
        
        

        
       
    }


    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    echo json_encode($result);



?>