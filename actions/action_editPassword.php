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

if(empty($_POST["oldPassword"]) || empty($_POST["newPassword"]) || empty($_POST["repeatPassword"])){
    $errors[] = "All fields must be filled";
} else {
    if(strlen($_POST["newPassword"]) < 8){
        $errors[] = "New password must be at least 8 characters long";
    }

    if($_POST["newPassword"] != $_POST["repeatPassword"]){
        $errors[] = "Passwords do not match";
    }
}

$user = User::getUserById($db, $_POST['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $repeatPassword = $_POST['repeatPassword'];

    if(!password_verify($oldPassword, $user->password)){
        $errors[] = "Current password is incorrect";
    }
    else if(empty($errors)){
        $user->password = password_hash($newPassword, PASSWORD_DEFAULT);
        $user->save($db);
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }

    header('Location: ../pages/profileSettingsPassword.php?username=' . $user->username);
    exit();
}