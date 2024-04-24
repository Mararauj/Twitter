<?php

    require 'db.php';

    session_start();

    $id = $_SESSION['id'];

    $result = array();
    $message = isset($_POST['message']) ? $_POST['message'] : null;
    $from = isset($_POST['from']) ? $_POST['from'] : null;


    if(!empty($message) && !empty($from)){
        $sql = $pdo->prepare("INSERT INTO messages (id_convo,id_user,content) VALUES (?,?,?)");
        $result['send_status'] = $sql->execute([$id, $from, $message]);
    }


    if(isset($_GET['id_convo'])){
        $idC = $_GET['id_convo'];
        $mess = $pdo->query("SELECT id_convo, id_user, content FROM messages WHERE (id_convo like '$id' or id_user like '$id') and (id_convo like '$idC' or id_user like '$idC') order by time asc");
        while($res = $mess->fetch(PDO::FETCH_ASSOC)){
            $result['mess'][] = $res;
        }
    }

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    echo json_encode($result);

?>