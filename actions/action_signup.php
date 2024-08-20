<?php
    session_start();
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/user.class.php');

    $db = getDatabaseConnection();

    $username = htmlspecialchars($_POST["username"]);

    if(empty($username) || strlen($username) < 2){
        $_SESSION['error'] = "Username is required and must be at least 2 characters long";
        header("Location: /../pages/SignUp.php");
        exit();
    }

    $username = strtolower($username);
    $user = new User(null, $username, null, null, null, null);
    $existingUser = $user->getUserByUsername($db, $username);

    if ($existingUser) {
        $_SESSION['error'] = "Username is already taken";
        header("Location: /../pages/SignUp.php");
        exit();
    }

    if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) || strlen($_POST["email"]) < 11){
        $_SESSION['error'] = "Valid email is required and must be at least 11 characters long";
        header("Location: /../pages/SignUp.php");
        exit();
    }

    if(strlen($_POST["password"]) < 8){
        $_SESSION['error'] = "Password must have at least 8 characters";
        header("Location: /../pages/SignUp.php");
        exit();
    }

    if(!preg_match("/[A-Z]/", $_POST["password"], $matches)){
        $_SESSION['error'] = "Password must have at least one uppercase letter";
        header("Location: /../pages/SignUp.php");
        exit();
    }

    if(!preg_match("/[0-9]/", $_POST["password"], $matches)){
        $_SESSION['error'] = "Password must have at least one number";
        header("Location: /../pages/SignUp.php");
        exit();
    }

    $passwordHash = password_hash($_POST["password"], PASSWORD_DEFAULT);

    try {
        $user = new User(null, $username, $passwordHash, $_POST["email"], $_POST["firstName"], $_POST["lastName"]);
        if($user->create($db)){
            $_SESSION['success'] = "User created successfully. You can now log in.";
            header("Location: /../pages/LogIn.php");
        } else {
            $_SESSION['error'] = "Failed to create user";
            header("Location: /../pages/SignUp.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: /../pages/SignUp.php");
        exit();
    }