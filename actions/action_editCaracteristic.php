<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/caracteristicas.class.php');

if ($_POST['csrf'] !== $_SESSION['csrf']) {
    die("CSRF token validation failed");
}

$db = getDatabaseConnection();

$caracteristica_id = htmlspecialchars($_POST['editcaracteristicaID']);
$caracteristics_value =  htmlspecialchars($_POST['editCaracteristicValue']);
$characteristics_type =  htmlspecialchars($_POST['editCaracteristicType']);
$characteristics_img = $_POST['imageUrl'];

$caracteristica = new Caracteristicas($caracteristica_id, $characteristics_type , $caracteristics_value, $characteristics_img);



$caracteristica->update($db);

//die($_POST['selector']);

if($_POST['selector'] == 3){
    header('Location: ../pages/admin.php?selector=3');
}
else if($_POST['selector'] == 4){
    header('Location: ../pages/admin.php?selector=4');
}
else if($_POST['selector'] == 5){
    header('Location: ../pages/admin.php?selector=5');
}

exit();
