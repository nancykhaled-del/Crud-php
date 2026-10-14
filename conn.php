<?php 
$username="root";
$password="";
try {
    $conn= new pdo("mysql:host=localhost;dbname=phpblog",$username,$password);
} catch (Throwable $e) {
    echo $e->getMessage();
}



?>