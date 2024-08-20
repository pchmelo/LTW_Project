<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/customer.class.php');

  $db = getDatabaseConnection();

  $user = User::getUserWithPassword($db, $_POST['email'], $_POST['password']);
  echo "<script>console.log('" . $_POST['email'] . "');</script>";
  die();
  if ($user) {
    $session->setId($user->id);
    $session->setName($user->username);
    $session->addMessage('success', 'Login successful!');
  } else {
    $user = User::getUserWithPasswordEmail($db, $_POST['email'], $_POST['password']);
    
    if ($user) {
      $session->setId($user->id);
      $session->setName($user->username);
      $session->addMessage('success', 'Login successful!');
    } else {
      $session->addMessage('error', 'Wrong password!');
    } 
  }

  header('Location: ' . $_SERVER['HTTP_REFERER']);