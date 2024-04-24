<?php
$host = 'localhost';  
$dbname = 'twitter';  
$user = 'Marco';  
$pass = 'root';  

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connecté";
    
}
catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>