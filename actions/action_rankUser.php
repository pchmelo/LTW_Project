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
    $user_name = $_POST['username'];
    $name = preg_replace ("/[^a-zA-Z\s]/", '', $user_name);

    $user = User::getUserByUsername($db, $user_name);

    //var_dump($user);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user->rank = $user->rank + 1;
        $user->save($db);
        header('Location: ../pages/admin.php?selector=1');
        exit();
    }