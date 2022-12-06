<?php
$server = "mysql:host=localhost;dbname=tracalorie;charset=utf8";
$login = "justinD";
$pass = "chajuoli";

try{
    $connexion = new PDO($server,$login,$pass);
    $connexion -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
}
catch(PDOException $e){
    die("Erreur de connexion " . $e->getMessage());
}

?>