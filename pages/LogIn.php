<?php
    declare(strict_types = 1); 
    ini_set('display_errors', '1');
    ini_set('log_errors', '1');
    error_reporting(E_ALL);
    require_once(__DIR__ . '/../utils/session.php');

    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../templates/LogIn.tpl.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/user.class.php');

    $is_valid = false;

    if($_SERVER['REQUEST_METHOD'] === 'POST'){


        $db = getDatabaseConnection();

        if (!$db) {
            error_log("Database connection failed");
        }

        $users = new User();
        $users = $users->getAll($db);

        $inputUsername = strtolower($_POST["username"]);
        $user = new User(null, $inputUsername, null, null, null, null);
        $user = $user->getUserByUsername($db, $inputUsername);

        if(isset($user)){
          if(password_verify($_POST["password"], $user->password)){
            $session = new Session();
            $session->login($user->id);
          
            header("Location: ../index.php");
            exit;
          } else {
            error_log("Password verification failed for user: " . $inputUsername);
          }
        } else {
          $user = new User(null, null, null, $inputUsername, null, null);
          $user = $user->getUserByEmail($db, $inputUsername);
          if(isset($user)){
            if(password_verify($_POST["password"], $user->password)){
              $session = new Session();
              $session->login($user->id);
              
              header("Location: ../index.php");
              exit;
            } else {
              error_log("Password verification failed for user: " . $inputUsername);
            }
          }
          else{
            error_log("User not found: " . $inputUsername);
          }
            
        }

        $is_valid = true;
      
    } else {
        error_log("Request method is not POST");
    }

    $session = new Session();
    require_once(__DIR__ . '/../templates/landingPage.tpl.php');


    $db = getDatabaseConnection();

    drawHeader($db,$session);
    drawLoginForm($is_valid);
