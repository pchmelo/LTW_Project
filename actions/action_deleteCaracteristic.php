<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/caracteristicas.class.php');

$db = getDatabaseConnection();

if ($_POST['csrf'] !== $_SESSION['csrf']) {
    die("CSRF token validation failed");
}

$caracteristica_id = preg_replace ("/[^a-zA-Z\s]/", '', $_POST['caracteristicaID']);

$caracteristica = new Caracteristicas($caracteristica_id, null, null, null);
$caracteristica->delete($db);

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