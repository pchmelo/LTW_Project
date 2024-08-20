<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/user.class.php');

  require_once(__DIR__ . '/../templates/signUp.tpl.php');
  require_once(__DIR__ . '/../templates/landingPage.tpl.php');

  $db = getDatabaseConnection();

  $isValid = null;
  if (isset($_SESSION['error'])) {
      $isValid = $_SESSION['error'];
      unset($_SESSION['error']);
  }

  drawHeader($db,$session);
  drawSignupForm($session, $isValid);

  //drawFooter();
