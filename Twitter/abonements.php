<?php
    $id = $_GET['id'];

    require 'db.php';

    $sql = $pdo->query("SELECT at_user_name FROM user join follow on user.id=follow.id_follow WHERE id_user='$id' ");
        if ($sql->rowCount() > 0){
            while($s = $sql->fetch()){
                echo $s['at_user_name'].'<br>';
            }
        
        }
        else{
            echo 'Aucun abonnement';
        }
    
?>