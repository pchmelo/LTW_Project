<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

if ($_POST['csrf'] !== $_SESSION['csrf']) {
    die("CSRF token validation failed");
}

$db = getDatabaseConnection();

$errors = [];

$username = htmlspecialchars($_POST['username']);

if(empty($username) || strlen($username) < 2){
    $errors[] = "Username is required and must be at least 2 characters long";
}

$firstName = htmlspecialchars($_POST['firstName']);
$lastName = htmlspecialchars($_POST['lastName']);

if(empty($firstName) || strlen($firstName) < 2){
    $errors[] = "First Name is required and must be at least 2 characters long";
}

if(empty($lastName) || strlen($lastName) < 2) {
    $errors[] = "Last Name is required and must be at least 2 characters long";
}

$user = User::getUserById($db, $_POST['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($errors)) {
        $user->username = $username;
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->save($db);
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }

    header('Location: ../pages/profileSettings.php?username=' . $user->username);
    exit();
}