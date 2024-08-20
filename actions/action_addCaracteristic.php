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

$caracteristicas_type = preg_replace ("/[^a-zA-Z\s]/", '', $_POST['carateristicasType']);
$caracteristicas_value = preg_replace ("/[^a-zA-Z\s]/", '', $_POST['carateristicasValue']);
$caracteristicas_img = $_POST['imageUrl'];

$caracteristica = new Caracteristicas(null, $caracteristicas_type, $caracteristicas_value, $caracteristicas_img);
$caracteristica->create($db);

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
