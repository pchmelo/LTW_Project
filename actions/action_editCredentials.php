<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

$db = getDatabaseConnection();

$username = htmlspecialchars($_POST['username']);

if(empty($username) || strlen($username) < 2){
    die("Username is required and must be at least 2 characters long");
}

$firstName = htmlspecialchars($_POST['firstName']);

if(empty($firstName) || strlen($firstName)){
    die("First Name is required and must be at least 2 characters long");
}

$lastName = htmlspecialchars($_POST['lastName']);

if(empty($lastName) || strlen($lastName)) {
    die("Last Name is required and must be at least 2 characters long");
}

  $user = User::getUserById($db, $_POST['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user->username = $username;
    $user->firstName = $firstName;
    $user->lastName = $lastName;
    $user->save($db);

    header('Location: ../pages/profileSettings.php?username=' . $user->username);
    exit();

}
