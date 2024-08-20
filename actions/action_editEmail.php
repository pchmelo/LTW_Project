<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

$db = getDatabaseConnection();

if ($_POST['csrf'] !== $_SESSION['csrf']) {
    die("CSRF token validation failed");
}

$errors = [];

if(empty($_POST["newEmail"]) || empty($_POST["repeatEmail"]) || empty($_POST["Password"])){
    $errors[] = "All fields must be filled";
} else {
    if(strlen($_POST["newEmail"]) < 2){
        $errors[] = "Email must be at least 2 characters long";
    }

    if($_POST["newEmail"] != $_POST["repeatEmail"]){
        $errors[] = "Emails do not match";
    }
}

$user = User::getUserById($db, $_POST['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newEmail = $_POST['newEmail'];
    $repeatEmail = $_POST['repeatEmail'];
    $password = $_POST['Password'];

    if(!password_verify($password, $user->password)){
        $errors[] = "Password is incorrect";
    }
    else if(empty($errors)){
        $user->email = $newEmail;
        $user->save($db);
    }

    if (!empty($errors)) {
        $_SESSION['newEmail'] = $newEmail;
        $_SESSION['repeatEmail'] = $repeatEmail;
        $_SESSION['errors'] = $errors;
    }

    header('Location: ../pages/profileSettingsEmail.php?username=' . $user->username);
    exit();
}

