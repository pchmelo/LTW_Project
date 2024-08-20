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

if(empty($_POST["password"]) || empty($_POST["repeatPassword"])){
    $errors[] = "All fields must be filled";
} else {
    if($_POST["password"] != $_POST["repeatPassword"]){
        $errors[] = "Passwords do not match";
    }
}

$user = User::getUserById($db, $_POST['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];

    if(empty($errors) && !password_verify($password, $user->password)){
        $errors[] = "Password is incorrect";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ../pages/profileSettingsDelete.php?username=' . $user->username);
        exit();
    }
    else{
        $user->delete($db);
        session_destroy();
        header('Location: ../pages/login.php');
        exit();
    }
}